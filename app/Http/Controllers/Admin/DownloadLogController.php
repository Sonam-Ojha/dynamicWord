<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\DocumentDownload;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DownloadLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = DocumentDownload::query()
            ->with(['user', 'document', 'template', 'bank'])
            ->when($request->input('user_id'), fn ($q, $v) => $q->where('user_id', $v))
            ->when($request->input('template_id'), fn ($q, $v) => $q->where('template_id', $v))
            ->when($request->input('bank_id'), fn ($q, $v) => $q->where('bank_id', $v))
            ->when($request->input('from'), fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->input('to'), fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($request->input('q'), function ($q, $v) {
                $q->whereHas('document', fn ($d) => $d->where('document_number', 'like', "%{$v}%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => DocumentDownload::count(),
            'today' => DocumentDownload::whereDate('created_at', today())->count(),
            'week' => DocumentDownload::where('created_at', '>=', now()->subWeek())->count(),
            'unique_users' => DocumentDownload::distinct('user_id')->count('user_id'),
        ];

        $users = User::orderBy('name')->get(['id', 'name']);
        $templates = Template::orderBy('template_name')->get(['id', 'template_name']);
        $banks = Bank::orderBy('bank_name')->get(['id', 'bank_name']);

        return view('admin.download_logs.index', compact('logs', 'stats', 'users', 'templates', 'banks'));
    }
}
