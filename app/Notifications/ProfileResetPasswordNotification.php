<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProfileResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Token untuk reset password.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Buat URL reset password yang mengarah ke halaman profil
        $url = route('profil.index', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        // Log URL untuk debugging
        \Illuminate\Support\Facades\Log::info('Profile Reset Password URL Generated: ' . $url);

        return (new MailMessage)
            ->subject('Reset Password Akun - SIGAP KOMPLEK')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Anda menerima email ini karena Anda meminta untuk mengubah password akun Anda.')
            ->action('Reset Password', $url)
            ->line('Link reset password ini akan kedaluwarsa dalam ' . config('auth.passwords.users.expire', 60) . ' menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
            ->salutation('Salam, Tim SIGAP KOMPLEK');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
