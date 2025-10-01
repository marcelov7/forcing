<?php

namespace App\Mail;

use App\Models\LogicChange;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LogicChangeImplemented extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $logicChange;

    /**
     * Create a new message instance.
     */
    public function __construct(LogicChange $logicChange)
    {
        $this->logicChange = $logicChange;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "✅ Alteração de Lógica Implementada - #{$this->logicChange->id}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.logic-change-implemented',
            with: [
                'logicChange' => $this->logicChange,
                'solicitante' => $this->logicChange->user,
                'implementador' => $this->logicChange->implementadoPor,
                'dataImplementacao' => $this->logicChange->data_implementacao,
                'observacoes' => $this->logicChange->observacoes_implementacao,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}