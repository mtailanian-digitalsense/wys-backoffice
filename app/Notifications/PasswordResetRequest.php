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
            ->line('Hemos recibido la solicitud para restablecer la contraseña de tu cuenta.')
            ->line('Para continuar, haz click en el siguiente botón:')
            ->action('Restablecer contraseña', $url)
            ->line('En el caso de no haber realizado esta solicitud por favor ignora el presente correo electronico.');
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
