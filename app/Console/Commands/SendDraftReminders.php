<?php

namespace App\Console\Commands;

use App\Mail\DraftReminderMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDraftReminders extends Command
{
    protected $signature = 'drafts:remind
                            {--email= : Send test mail only to this specific email address}
                            {--dry-run : Show the list without actually sending any mail}';

    protected $description = 'Send reminder emails to users about their pending draft documents';

    public function handle(): void
    {
        $isDryRun = $this->option('dry-run');
        $filterEmail = $this->option('email');

        $query = User::active()
            ->whereHas('generatedDocuments', fn ($q) => $q->where('status', 'draft'))
            ->with(['generatedDocuments' => fn ($q) => $q
                ->where('status', 'draft')
                ->with(['template', 'bank'])
                ->latest()
            ]);

        if ($filterEmail) {
            $query->where('email', $filterEmail);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->warn('No draft documents found. No mail sent.');
            return;
        }

        $this->info("Users with drafts found: {$users->count()}");

        $rows = [];
        foreach ($users as $user) {
            $rows[] = [$user->name, $user->email, $user->generatedDocuments->count()];
        }
        $this->table(['Name', 'Email', 'Drafts'], $rows);

        if ($isDryRun) {
            $this->warn('[Dry Run] No mail was sent.');
            return;
        }

        $sent = 0;
        foreach ($users as $user) {
            $drafts = $user->generatedDocuments;
            Mail::to($user->email)->send(new DraftReminderMail($user, $drafts));
            $sent++;
            $this->line("  ✓ Mail sent: {$user->email} ({$drafts->count()} drafts)");
        }

        $this->info("Reminder mail sent to {$sent} user(s).");
    }
}
