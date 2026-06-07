<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DraftReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Collection $drafts,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "📋 You have {$this->drafts->count()} pending draft document(s) — " . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.draft_reminder',
        );
    }
}
