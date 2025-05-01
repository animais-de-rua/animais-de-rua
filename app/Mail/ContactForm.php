<?php

namespace App\Mail;

use App\Http\Requests\Form\FormSubmitContactRequest;
use App\Models\Territory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public FormSubmitContactRequest $request
    ) {
        $this->request->merge([
            'territory' => Territory::find($this->request->territory_id),
        ]);
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->markdown('emails.form.contact')
            ->subject(config('app.name')." - Contacto - {$this->request->name}")
            ->replyTo($this->request->email, $this->request->name)
            ->with([
                'request' => $this->request,
            ]);
    }
}
