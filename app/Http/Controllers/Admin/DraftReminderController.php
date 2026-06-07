<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DraftReminderMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DraftReminderController extends Controller
{
    public function index(): View
    {
        $users = User::active()
            ->whereHas('generatedDocuments', fn ($q) => $q->where('status', 'draft'))
            ->with(['generatedDocuments' => fn ($q) => $q
                ->where('status', 'draft')
                ->with(['template', 'bank'])
                ->latest()
            ])
            ->get();

        return view('admin.draft_reminders.index', compact('users'));
    }

    public function sendAll(): RedirectResponse
    {
        $users = User::active()
            ->whereHas('generatedDocuments', fn ($q) => $q->where('status', 'draft'))
            ->with(['generatedDocuments' => fn ($q) => $q
                ->where('status', 'draft')
                ->with(['template', 'bank'])
                ->latest()
            ])
            ->get();

        if ($users->isEmpty()) {
            return back()->with('info', 'No pending drafts found. No mail was sent.');
        }

        foreach ($users as $user) {
            Mail::to($user->email)->send(new DraftReminderMail($user, $user->generatedDocuments));
        }

        return back()->with('success', "Draft reminder mail sent to {$users->count()} user(s).");
    }

    public function sendOne(User $user): RedirectResponse
    {
        $drafts = $user->generatedDocuments()
            ->where('status', 'draft')
            ->with(['template', 'bank'])
            ->latest()
            ->get();

        if ($drafts->isEmpty()) {
            return back()->with('info', "{$user->name} has no pending drafts.");
        }

        Mail::to($user->email)->send(new DraftReminderMail($user, $drafts));

        return back()->with('success', "Reminder mail sent to {$user->name} ({$user->email}).");
    }
}
