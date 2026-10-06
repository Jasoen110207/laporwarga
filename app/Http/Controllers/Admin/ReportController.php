<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::with(['category', 'user'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%' . $request->q . '%';
                $q->where(fn ($w) => $w->where('title', 'like', $term)->orWhere('ticket', 'like', $term)->orWhere('location', 'like', $term));
            })
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
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Report::STATUSES))],
            'message' => [Rule::requiredIf($request->status === 'ditolak'), 'nullable', 'string', 'max:1000'],
        ], [
            'message.required' => 'Tulis alasan jika laporan ditolak.',
        ]);

        if ($data['status'] === $report->status && blank($data['message'] ?? null)) {
            return back()->withErrors(['message' => 'Ubah status atau tulis tanggapan.']);
        }

        $report->update(['status' => $data['status']]);
        $report->responses()->create([
            'user_id' => $request->user()->id,
            'message' => $data['message'] ?? null,
            'status_after' => $data['status'],
        ]);

        return back()->with('success', 'Tanggapan tersimpan dan warga bisa melihatnya.');
    }
}
