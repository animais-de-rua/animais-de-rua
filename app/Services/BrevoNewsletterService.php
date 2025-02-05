<?php

namespace App\Services;

use Brevo\Client\Api\ContactsApi;
use Brevo\Client\ApiException;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateContact;
use GuzzleHttp\Client;

class BrevoNewsletterService
{
    protected $apiInstance;
    protected $listId;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.brevo.api_key'));
        $this->apiInstance = new ContactsApi(new Client(), $config);
        $this->listId = (int) config('services.brevo.list_id');
    }

    public function subscribe($email)
    {
        $contact = new CreateContact();
        $contact->setEmail($email);
        $contact->setListIds([$this->listId]);

        try {
            $this->apiInstance->createContact($contact);
            return ['message' => 'Subscribed successfully!'];
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
