<?php

namespace App\Mail;

use App\Http\Requests\Form\FormSubmitGodfatherRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GodfatherForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public FormSubmitGodfatherRequest $request
    ) {
        $this->request->merge([
            'value' => $request->other ?: $request->value,
        ]);
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->markdown('emails.form.godfather')
            ->subject(config('app.name').' - Apadrinhamento - '.$this->request->name)
            ->replyTo($this->request->email, $this->request->name)
            ->with([
                'request' => $this->request,
            ]);
    }
}
