<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetRequest extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = env('RESET_URL') . $this->token;
        return (new MailMessage)
            ->greeting('¡Hola!')
            ->subject('Recuperación de contraseña')
            ->line('Hemos recibido tu solicitud para reestablecer la contraseña de tu cuenta.
            Para continuar haz click en el siguiente botón:')
            ->action('Restablecer contraseña', $url)
            ->line('Si tienes problemas al hacer click en el botón "Reestablecer contraseña",
            copia y pega la siguiente URL en tu navegador web');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
