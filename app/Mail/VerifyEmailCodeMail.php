<?php

namespace App\Mail;

use App\Models\User; 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user; 

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Akun SIGAP-KOMPLEK',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Kirim properti yang dibutuhkan ke view
        return new Content(
            view: 'emails.verify-code',
            with: [
                'name' => $this->user->name,
                'code' => $this->user->verification_code,
                'expiresAt' => $this->user->verification_expires_at,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}