<?php


declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrchidResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param string $token
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @return array|string
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail()
    {
        return (new MailMessage())
            ->subject('Restablecer contraseña Back Office')
            ->greeting('Restablecer la contraseña')
            ->line('Recibió este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.')
            ->action('Restablecer la contraseña', url(config('app.url') . route('platform.password.reset', $this->token, false)))
            ->line('Si no solicitó un restablecimiento de contraseña, no se requiere ninguna otra acción.');
    }
}
