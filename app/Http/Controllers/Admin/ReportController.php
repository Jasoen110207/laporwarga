<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Parameter filter divalidasi sebelum dipakai
        $filters = Validator::make($request->query(), [
            'status' => ['nullable', Rule::in(array_keys(Report::STATUSES))],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'q' => ['nullable', 'string', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($filters->fails()) {
            return redirect()->route('admin.reports.index')->with('error', 'Filter pencarian tidak valid, tampilan direset.');
        }

        $reports = Report::with(['category', 'user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', (int) $request->query('category')))
            ->search($request->query('q'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'categories' => Category::orderBy('name')->get(),
            'counts' => Report::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Report $report)
    {
        $report->load(['category', 'user', 'responses.user']);

        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        // Laporan final (selesai/ditolak) hanya boleh dibuka kembali oleh admin
        if ($report->isFinal() && ! $request->user()->isAdmin()) {
            return back()->with('error', 'Laporan yang sudah selesai atau ditolak hanya bisa diubah oleh admin.');
        }

        $quick = $request->boolean('quick');

        if ($quick && $request->input('status') === 'ditolak') {
            return redirect()->route('admin.reports.show', $report)
                ->with('error', 'Untuk menolak laporan, tulis alasannya di halaman ini.');
        }

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Report::STATUSES))],
            'message' => [Rule::requiredIf($request->input('status') === 'ditolak'), 'nullable', 'string', 'max:1000'],
        ], [
            'message.required' => 'Tulis alasan jika laporan ditolak.',
        ]);

        $message = $data['message'] ?? null;

        if ($data['status'] === $report->status && blank($message)) {
            return $quick
                ? back()->with('error', 'Status tidak berubah.')
                : back()->withErrors(['message' => 'Ubah status atau tulis tanggapan.']);
        }

        $old = $report->status;

        // Status dan riwayat tanggapan harus tersimpan bersama atau tidak sama sekali
        DB::transaction(function () use ($report, $data, $message, $request) {
            $report->update(['status' => $data['status']]);
            $report->responses()->create([
                'user_id' => $request->user()->id,
                'message' => $message,
                'status_after' => $data['status'],
            ]);
        });

        AuditLog::record('status_laporan', "{$report->ticket}: {$old} -> {$data['status']}");

        return back()->with('success', "Status {$report->ticket} diperbarui.");
    }

    public function destroy(Request $request, Report $report)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $ticket = $report->ticket;

        if ($report->photo) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();
        AuditLog::record('laporan_dihapus_admin', $ticket);

        return redirect()->route('admin.reports.index')->with('success', "Laporan {$ticket} dihapus.");
    }
}
