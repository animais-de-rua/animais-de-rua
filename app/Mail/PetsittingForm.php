<?php

namespace App\Mail;

use App\Http\Requests\Form\FormSubmitPetsittingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PetsittingForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public FormSubmitPetsittingRequest $request,
        public int $lastEmailId,
    ) {}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->markdown('emails.form.petsitting')
            ->subject("Pedido de Petsitting #{$this->lastEmailId} - {$this->request->first_name} {$this->request->last_name}")
            ->replyTo($this->request->email, $this->request->name)
            ->with([
                'request' => $this->request,
            ]);
    }
}
