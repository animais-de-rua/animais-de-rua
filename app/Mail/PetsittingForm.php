<?php

namespace App\Mail;

use App\Models\StorePetsittingRequests;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PetsittingForm extends Mailable
{
    use Queueable, SerializesModels;

    public $lastEmailId;
    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request, $lastEmailId)
    {
        $this->lastEmailId = $lastEmailId;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.form.petsitting')
            ->subject('Pedido de Petsitting #' . $this->lastEmailId . ' - ' . $this->request->first_name . ' ' . $this->request->last_name)
            ->with([
                'request' => $this->request,
            ]);
    }
}
