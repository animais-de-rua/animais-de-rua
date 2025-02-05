<?php

namespace App\Http\Controllers;

use Brevo\Client\ApiException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\BrevoNewsletterService;
use Illuminate\Support\Facades\Log;
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
            $validator->errors()->add('email', __('Your email is already subscribed.'));
            throw new ValidationException($validator);
        }

        try {
            $this->newsletter->subscribe(
                $request->email,
                strtok($request->email, '@'),
            );
        } catch (ApiException $e) {
            Log::info($e->getResponseBody());

            $validator->errors()->add('email', __('Something went wrong, please try again later.'));
            throw new ValidationException($validator);
        }

        return response()->json([
            'errors' => false,
            'message' => __('Thank you for subscribing to our newsletter.'),
        ]);
    }
}
