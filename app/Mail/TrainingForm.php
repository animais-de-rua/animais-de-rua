<?php

namespace App\Mail;

use App\Http\Requests\Form\FormSubmitTrainingRequest;
use App\Models\Territory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public FormSubmitTrainingRequest $request,
    ) {
        $this->request->merge([
            'territory' => Territory::find($this->request->county)->fullname,
        ]);
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->markdown('emails.form.training')
            ->subject(config('app.name').' - Formação - '.$this->request->name)
            ->replyTo($this->request->email, $this->request->name)
            ->with([
                'request' => $this->request,
            ]);
    }
}
