<?php

namespace App\Services;

use Brevo\Client\Api\ContactsApi;
use Brevo\Client\ApiException;
use Brevo\Client\Configuration;
use Brevo\Client\Model\CreateContact;
use GuzzleHttp\Client;

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
     * @throws ApiException
     */
    public function subscribe($email, $firstName)
    {
        $contact = new CreateContact();
        $attributes = new class {
            public string $FIRSTNAME;
        };

        $attributes->FIRSTNAME = $firstName;

        $contact->setEmail($email);
        $contact->setListIds([$this->listId]);
        $contact->setAttributes($attributes);

        $this->apiInstance->createContact($contact);
    }

    public function isSubscribed($email): bool
    {
        try {
            $response = $this->apiInstance->getContactInfo($email);
            return $response !== null &&
                $response->getListIds() !== null &&
                in_array($this->listId, $response->getListIds());
        } catch (ApiException $e) {
            throw $e->getResponseBody();
        }
    }
}
