<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'total' => Report::count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function track(Request $request)
    {
        $data = $request->validate(['ticket' => 'required|string|max:20']);
        $ticket = strtoupper(trim($data['ticket']));

        $report = Report::with(['category', 'responses.user'])->where('ticket', $ticket)->first();

        return view('track', compact('report', 'ticket'));
    }
}
