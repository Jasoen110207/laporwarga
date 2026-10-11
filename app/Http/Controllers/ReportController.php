<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    private function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:120',
            'description' => 'required|string|min:20|max:3000',
            'location' => 'required|string|max:200',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    /** Hanya pemilik yang boleh mengubah/menghapus, dan hanya selama status masih "baru". */
    private function authorizeOwner(Request $request, Report $report): void
    {
        abort_unless($report->user_id === $request->user()->id, 403);
        abort_unless($report->status === 'baru', 403, 'Laporan yang sudah ditindaklanjuti tidak bisa diubah atau dihapus.');
    }

    public function index(Request $request)
    {
        $reports = $request->user()->reports()->with('category')->latest()->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('reports', 'public');
        }

        $report = $request->user()->reports()->create($data);
        AuditLog::record('laporan_dibuat', $report->ticket);

        return redirect()->route('reports.show', $report)
            ->with('success', "Laporan terkirim. Simpan nomor tiket {$report->ticket} untuk melacak statusnya.");
    }

    public function show(Request $request, Report $report)
    {
        abort_unless($report->user_id === $request->user()->id || $request->user()->isStaff(), 403);

        $report->load(['category', 'user', 'responses.user']);

        return view('reports.show', compact('report'));
    }

    public function edit(Request $request, Report $report)
    {
        $this->authorizeOwner($request, $report);

        return view('reports.edit', [
            'report' => $report,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Report $report)
    {
        $this->authorizeOwner($request, $report);

        $data = $request->validate($this->rules() + ['remove_photo' => 'nullable|boolean']);
        unset($data['remove_photo']);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($report);
            $data['photo'] = $request->file('photo')->store('reports', 'public');
        } elseif ($request->boolean('remove_photo')) {
            $this->deletePhoto($report);
            $data['photo'] = null;
        } else {
            unset($data['photo']);
        }

        $report->update($data);
        AuditLog::record('laporan_diubah', $report->ticket);

        return redirect()->route('reports.show', $report)->with('success', 'Laporan diperbarui.');
    }

    public function destroy(Request $request, Report $report)
    {
        $this->authorizeOwner($request, $report);

        $ticket = $report->ticket;
        $this->deletePhoto($report);
        $report->delete();
        AuditLog::record('laporan_dihapus', $ticket);

        return redirect()->route('reports.index')->with('success', "Laporan {$ticket} dihapus.");
    }

    private function deletePhoto(Report $report): void
    {
        if ($report->photo) {
            Storage::disk('public')->delete($report->photo);
        }
    }
}
