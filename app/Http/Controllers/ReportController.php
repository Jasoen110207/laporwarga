<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
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
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:120',
            'description' => 'required|string|min:20|max:3000',
            'location' => 'required|string|max:200',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('reports', 'public');
        }

        $report = $request->user()->reports()->create($data);

        return redirect()->route('reports.show', $report)
            ->with('success', "Laporan terkirim. Simpan nomor tiket {$report->ticket} untuk melacak statusnya.");
    }

    public function show(Request $request, Report $report)
    {
        abort_unless($report->user_id === $request->user()->id || $request->user()->isStaff(), 403);

        $report->load(['category', 'user', 'responses.user']);

        return view('reports.show', compact('report'));
    }
}
