<?php

namespace App\Mail;

use App\Models\Process;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplyForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Process $process,
    ) {}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->markdown('emails.form.apply')
            ->subject(config('app.name').' - Candidatura de Esterilização')
            ->with([
                'process' => $this->process,
            ]);
    }
}
