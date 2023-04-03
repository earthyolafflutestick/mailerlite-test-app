<?php

namespace App\Services;

use App\MailerLite\Error;
use App\MailerLite\Result;
use App\MailerLite\Subscriber;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;

class MailerLiteService
{
    protected $apiKey;
    protected $timeout = 5000;

    public const BASE_URL = 'https://connect.mailerlite.com';

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function getSubscribers($limit = 10, $cursor = null)
    {
        $parameters = [
            'limit' => $limit,
        ];

        if ($cursor) {
            $parameters['cursor'] = $cursor;
        }

        try {
            $response = $this->request()->get('/api/subscribers', $parameters);

            return $this->mapResponse($response);
        } catch (ConnectionException $e) {
            return $this->handleException($e);
        }
    }

    public function searchSubscriber($email)
    {
        try {
            $response = $this->request()->get("/api/subscribers/{$email}");

            return $this->mapResponse($response);
        } catch (ConnectionException $e) {
            return $this->handleException($e);
        }
    }

    public function createSubscriber($email, $firstName, $lastName, $country)
    {
        try {
            $response = $this->request()->withBody(json_encode([
                'email' => $email,
                'fields' => [
                    'name' => $firstName,
                    'last_name' => $lastName,
                    'country' => $country,
                ]
            ]), 'application/json')->post('/api/subscribers');

            return $this->mapResponse($response);
        } catch (ConnectionException $e) {
            return $this->handleException($e);
        }

    }

    public function updateSubscriber($id, $firstName, $lastName, $country)
    {
        try {
            $response = $this->request()->withBody(json_encode([
                'fields' => [
                    'name' => $firstName,
                    'last_name' => $lastName,
                    'country' => $country,
                ]
            ]), 'application/json')->put("/api/subscribers/{$id}");

            return $this->mapResponse($response);
        } catch (ConnectionException $e) {
            return $this->handleException($e);
        }
    }

    public function deleteSubscriber($id)
    {
        try {
            $response = $this->request()->delete("/api/subscribers/{$id}");

            return $this->mapResponse($response);
        } catch (ConnectionException $e) {
            return $this->handleException($e);
        }
    }

    private function request()
    {
        return Http::baseUrl(self::BASE_URL)
            ->contentType('application/json')
            ->acceptJson()
            ->withToken($this->apiKey)
            ->timeout($this->timeout);
    }

    private function mapResponse(Response $response)
    {
        if ($response->failed()) {
            $messageId = "mailerlite.messages.{$response->status()}";
            $message = Lang::has($messageId) ? __($messageId) : __('mailerlite.messages.500');
            $errors = $response->json('errors') ?? [];

            return new Error($response->status(), $message, $errors);
        }

        $messageId = "mailerlite.messages.{$response->status()}";
        $message = Lang::has($messageId) ? __($messageId) : __('mailerlite.messages.200');

        $data = $response->object();
        $data = isset($data->data) ? $data->data : [];
        $data = is_array($data) ? $data : [$data];
        $data = array_map(function ($entry) {
            return new Subscriber(
                $entry->id,
                $entry->email,
                $entry->fields->name,
                $entry->fields->last_name,
                $entry->fields->country
            );
        }, $data);

        return new Result($message, $data);
    }

    private function handleException(\Exception $e)
    {
        return new Error(500, __('mailerlite.messages.500'));
    }
}
