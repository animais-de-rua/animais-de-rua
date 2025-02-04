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
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
        ]);

        $this->newsletter->subscribe(
            $request->email,
            $request->first_name,
            $request->last_name
        );

        return response()->json(['message' => 'Subscribed successfully!']);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $this->newsletter->unsubscribe($request->email);

        return response()->json(['message' => 'Unsubscribed successfully!']);
    }

    public function checkSubscription(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $subscribed = $this->newsletter->isSubscribed($request->email);

        return response()->json(['subscribed' => $subscribed]);
    }
}
