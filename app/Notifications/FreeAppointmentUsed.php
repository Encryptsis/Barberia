<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FreeAppointmentUsed extends Notification
{
    use Queueable;

    protected $cita;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Cita $cita
     * @return void
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Puedes añadir otros canales como 'database'
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
                    ->greeting("Hola {$notifiable->usr_nombre_completo},")
                    ->line("Has utilizado tu cita gratuita para el servicio: **{$this->cita->servicios->pluck('srv_nombre')->join(', ')}**.")
                    ->line("**Detalles de la Cita:**")
                    ->line("**Profesional:** {$this->cita->profesional->usr_nombre_completo}")
                    ->line("**Fecha:** " . \Carbon\Carbon::parse($this->cita->cta_fecha)->format('d/m/Y'))
                    ->line("**Hora:** " . \Carbon\Carbon::parse($this->cita->cta_hora)->format('H:i'))
                    ->action('Ver Cita', route('my.appointments'))
                    ->line('¡Gracias por usar nuestros servicios!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Has utilizado tu cita gratuita.',
            'cita_id' => $this->cita->cta_id,
        ];
    }
}