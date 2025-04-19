<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GodfatherForm extends Mailable
{
    use Queueable, SerializesModels;

    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $request->value = $request->other ?: $request->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.form.godfather')
            ->subject(config('app.name').' - Apadrinhamento - '.$this->request->name)
            ->replyTo($this->request->email, $this->request->name)
            ->with([
                'request' => $this->request,
            ]);
    }
}
