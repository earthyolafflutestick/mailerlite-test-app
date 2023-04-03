<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MailerLiteService
{
    protected $apiKey;

    public const BASE_URL = 'https://connect.mailerlite.com/';

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getSubscribers($limit = 10, $cursor = null)
    {
        $parameters = [
            'limit' => $limit,
        ];

        if ($cursor) {
            $parameters['cursor'] = $cursor;
        }

        return $this->request()->get('/api/subscribers', $parameters)->json();
    }

    public function searchSubscriber($email)
    {
        $this->request()->get("/api/subscribers/{$email}");
    }

    public function createSubscriber($email, $firstName, $lastName, $country)
    {
        $this->request()->withBody(json_encode([
            'email' => $email,
            'fields' => [
                'name' => $firstName,
                'last_name' => $lastName,
                'country' => $country,
            ]
        ]), 'application/json')->post('/api/subscribers');
    }

    public function updateSubscriber($id, $firstName, $lastName, $country)
    {
        $this->request()->withBody(json_encode([
            'fields' => [
                'name' => $firstName,
                'last_name' => $lastName,
                'country' => $country,
            ]
        ]), 'application/json')->put("/api/subscribers/{$id}");
    }

    public function deleteSubscriber($id)
    {
        $this->request()->delete("/api/subscribers/{$id}");
    }

    public function request()
    {
        return Http::baseUrl(self::BASE_URL)
            ->contentType('application/json')
            ->acceptJson()
            ->withToken($this->apiKey);
    }
}
