<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BrevoNewsletterService;

class NewsletterController extends Controller
{
    protected $newsletter;

    public function __construct(BrevoNewsletterService $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $response = $this->newsletter->subscribe(
            $request->email,
        );

        return response()->json($response);
    }

    public function unsubscribe(Request $request)
    {
        // TODO: Implement unsubscribe method
    }

    public function checkSubscription(Request $request)
    {
        // TODO: Implement checkSubscription method
    }
}
