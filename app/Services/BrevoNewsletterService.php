<?php

namespace App\Services;

use Brevo\Client\Api\ContactsApi;
use Brevo\Client\ApiException;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateContact;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BrevoNewsletterService
{
    protected ContactsApi $apiInstance;
    protected int $listId;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.api_key'));
        $this->apiInstance = new ContactsApi(new Client(), $config);
        $this->listId = (int) config('services.brevo.list_id');
    }

    /**
     * @throws ValidationException
     */
    public function subscribe($email, $firstName)
    {
        $contact = new CreateContact();

        $contact->setEmail($email);
        $contact->setListIds([$this->listId]);
        $contact->setAttributes((object)[
            'FIRSTNAME' => $firstName,
        ]);

        try {
            $this->apiInstance->createContact($contact);
        } catch (ApiException $e) {
            Log::error('Exception when calling ContactsApi->createContact: ' . $e->getResponseBody());
            throw ValidationException::withMessages([
                'message' => __('An error occurred while subscribing to the newsletter.'),
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    public function isSubscribed($email): bool
    {
        try {
            $response = $this->apiInstance->getContactInfo($email);
            return $response !== null &&
                $response->getListIds() !== null &&
                in_array($this->listId, $response->getListIds());
        } catch (ApiException $e) {
            if ($e->getCode() === 404) {
                return false;
            }
            Log::error('Exception when calling ContactsApi->getContactInfo: ' . $e->getResponseBody());
            throw ValidationException::withMessages([
                'message' => __('Something went wrong, please try again later.'),
            ]);
        }
    }
}
