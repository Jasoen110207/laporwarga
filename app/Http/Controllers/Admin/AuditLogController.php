<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'action' => 'nullable|string|max:50',
        ]);

        $logs = AuditLog::with('user')
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->query('action')))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.audit.index', [
            'logs' => $logs,
            'actions' => AuditLog::select('action')->distinct()->orderBy('action')->pluck('action'),
        ]);
    }
}
