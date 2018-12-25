<?php

namespace App\Mail;

use App\Models\Process;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplyForm extends Mailable
{
    use Queueable, SerializesModels;

    public $process;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.form.apply')
            ->subject(config('app.name') . ' - Candidatura de Esterilização')
            ->with([
                'process' => $this->process,
            ]);
    }
}
