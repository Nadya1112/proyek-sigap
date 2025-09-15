<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi Akun SIGAP KOMPLEK')
            ->view('emails.verify-code')
            ->with([
                'name'      => $this->user->name,
                'code'      => $this->user->verification_code,
                'expiresAt' => $this->user->verification_expires_at,
            ]);
    }
}
