<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $destination;
    public $customMessage;
    public $sentAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($destination, $customMessage = null)
    {
        $this->destination = $destination;
        $this->customMessage = $customMessage ?: 'Hola, este es un correo de prueba enviado exitosamente desde la plataforma de cobranza de DYD Soluciones.';
        $this->sentAt = now()->format('d/m/Y H:i:s');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Prueba de Conexión SMTP - DYD Soluciones Integrales',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.test_notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
