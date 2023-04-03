<?php

namespace Tests\Feature;

use App\Services\MailerLiteService;
use Illuminate\Http\Client\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class MailerLiteServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Http::fake();
    }

    public function test_get_subscribers_correctly_builds_request()
    {
        $mailerLiteService = new MailerLiteService();
        $mailerLiteService->setApiKey('test');

        $mailerLiteService->getSubscribers(25, 'cursor');

        Http::assertSent(function (Request $request) {
            return $request->isJson() &&
                $request->hasHeaders([
                    'Authorization' => 'Bearer test',
                    'Accept' => 'application/json'
                ]) &&
                Str::startsWith($request->url(), 'https://connect.mailerlite.com/api/subscribers') &&
                data_get($request->data(), 'limit') === 25 &&
                data_get($request->data(), 'cursor') === 'cursor' &&
                $request->method() === 'GET';
        });
    }

    public function test_search_subscriber_correctly_builds_request()
    {
        $mailerLiteService = new MailerLiteService();
        $mailerLiteService->setApiKey('test');

        $mailerLiteService->searchSubscriber('test@test.com');

        Http::assertSent(function (Request $request) {
            return $request->isJson() &&
                $request->hasHeaders([
                    'Authorization' => 'Bearer test',
                    'Accept' => 'application/json'
                ]) &&
                Str::startsWith($request->url(), 'https://connect.mailerlite.com/api/subscribers/test@test.com') &&
                $request->method() === 'GET';
        });
    }

    public function test_post_subscriber_correctly_builds_request()
    {
        $mailerLiteService = new MailerLiteService();
        $mailerLiteService->setApiKey('test');

        $mailerLiteService->createSubscriber('test@test.com', 'Willy', 'Wonka', 'Wonderland');

        Http::assertSent(function (Request $request) {
            $payload = json_decode($request->body(), true);

            return $request->isJson() &&
                $request->hasHeaders([
                    'Authorization' => 'Bearer test',
                    'Accept' => 'application/json'
                ]) &&
                Str::startsWith($request->url(), 'https://connect.mailerlite.com/api/subscribers') &&
                data_get($payload, 'email') === 'test@test.com' &&
                data_get($payload, 'fields.name') === 'Willy' &&
                data_get($payload, 'fields.last_name') === 'Wonka' &&
                data_get($payload, 'fields.country') === 'Wonderland' &&
                $request->method() === 'POST';
        });
    }

    public function test_update_subscriber_correctly_builds_request()
    {
        $mailerLiteService = new MailerLiteService();
        $mailerLiteService->setApiKey('test');

        $mailerLiteService->updateSubscriber(1, 'Willy', 'Wonka', 'Wonderland');

        Http::assertSent(function (Request $request) {
            $payload = json_decode($request->body(), true);

            return $request->isJson() &&
                $request->hasHeaders([
                    'Authorization' => 'Bearer test',
                    'Accept' => 'application/json'
                ]) &&
                $request->url() === 'https://connect.mailerlite.com/api/subscribers/1' &&
                data_get($payload, 'fields.name') === 'Willy' &&
                data_get($payload, 'fields.last_name') === 'Wonka' &&
                data_get($payload, 'fields.country') === 'Wonderland' &&
                $request->method() === 'PUT';
        });
    }

    public function test_delete_subscriber_correctly_builds_request()
    {
        $mailerLiteService = new MailerLiteService();
        $mailerLiteService->setApiKey('test');

        $mailerLiteService->deleteSubscriber(1);

        Http::assertSent(function (Request $request) {
            return $request->isJson() &&
                $request->hasHeaders([
                    'Authorization' => 'Bearer test',
                    'Accept' => 'application/json'
                ]) &&
                $request->url() === 'https://connect.mailerlite.com/api/subscribers/1' &&
                $request->method() === 'DELETE';
        });
    }
}
