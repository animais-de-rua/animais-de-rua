<?php

namespace App\Services;

use Brevo\Client\Api\ContactsApi;
use Brevo\Client\ApiException;
use Brevo\Client\Configuration;
use GuzzleHttp\Client;

class BrevoNewsletterService
{
    protected $apiInstance;
    protected $listId;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.api_key'));
        $this->apiInstance = new ContactsApi(new Client(), $config);
        $this->listId = config('services.brevo.list_id');
    }

    public function subscribe($email, $firstName = null, $lastName = null)
    {
        $contact = [
            'email' => $email,
            'listIds' => [$this->listId],
            'attributes' => [
                'FIRSTNAME' => $firstName,
                'LASTNAME' => $lastName
            ]
        ];

        try {
            return $this->apiInstance->createContact($contact);
        } catch (ApiException $e) {
            return $e->getResponseBody();
        }
    }

    public function unsubscribe($email)
    {
        return $this->apiInstance->removeContactFromList($this->listId, $email);
    }

    public function isSubscribed($email)
    {
        try {
            $response = $this->apiInstance->getContactInfo($email);
            return $response !== null;
        } catch (\Exception $e) {
            return false;
        }
    }
}
