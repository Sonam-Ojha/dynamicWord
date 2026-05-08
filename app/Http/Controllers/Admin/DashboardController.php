<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\GeneratedDocument;
use App\Models\Template;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'banks' => Bank::count(),
            'templates' => Template::count(),
            'documents' => GeneratedDocument::count(),
            'users' => User::count(),
        ];

        $recentDocuments = GeneratedDocument::with(['user', 'bank', 'template'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentDocuments'));
    }
}
