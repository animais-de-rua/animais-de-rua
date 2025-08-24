<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\BrevoNewsletterService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    protected $newsletter;

    public function __construct(BrevoNewsletterService $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    /**
     * @throws ValidationException
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        $validator->validate();

        if ($this->newsletter->isSubscribed($request->email)) {
            throw ValidationException::withMessages([
                'email' => __('Your email is already subscribed.'),
            ]);
        }

        $this->newsletter->subscribe(
            $request->email,
            strtok($request->email, '@'),
        );

        return response()->json([
            'errors' => false,
            'message' => __('Thank you for subscribing to our newsletter!'),
        ]);
    }
}
