<?php

namespace App\Mail;

use App\Models\Territory;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VolunteerForm extends Mailable
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
        $this->request->territory = Territory::find($this->request->county)->fullname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.form.volunteer')
            ->subject(config('app.name').' - Voluntariado - '.$this->request->name)
            ->replyTo($this->request->email, $this->request->name)
            ->with([
                'request' => $this->request,
            ]);
    }
}
