<?php

namespace Tests\Feature;

use App\MailerLite\Error;
use App\MailerLite\Result;
use App\MailerLite\ResultMeta;
use App\MailerLite\Subscriber;
use App\Services\MailerLiteService;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class MailerLiteServiceTest extends TestCase
{
    private $mailerLiteService;

    public function setUp(): void
    {
        parent::setUp();

        $this->mailerLiteService = new MailerLiteService();
    }

    public function test_it_sets_api_key_and_headers()
    {
        Http::fake();

        $this->mailerLiteService->setApiKey('test');
        $this->mailerLiteService->getSubscribers();

        Http::assertSent(function (Request $request) {
            return $request->isJson() &&
                $request->hasHeaders([
                    'Authorization' => 'Bearer test',
                    'Accept' => 'application/json'
                ]);
        });
    }

    public function test_get_subscribers_correctly_builds_request()
    {
        Http::fake();

        $this->mailerLiteService->getSubscribers(25, 'cursor');

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://connect.mailerlite.com/api/subscribers') &&
                data_get($request->data(), 'limit') === 25 &&
                data_get($request->data(), 'cursor') === 'cursor' &&
                $request->method() === 'GET';
        });
    }

    public function test_search_subscriber_correctly_builds_request()
    {
        Http::fake();

        $this->mailerLiteService->searchSubscriber('test@test.com');

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://connect.mailerlite.com/api/subscribers/test@test.com') &&
                $request->method() === 'GET';
        });
    }

    public function test_post_subscriber_correctly_builds_request()
    {
        Http::fake();

        $this->mailerLiteService->createSubscriber('test@test.com', 'Willy', 'Wonka', 'Wonderland');

        Http::assertSent(function (Request $request) {
            $payload = json_decode($request->body(), true);

            return Str::startsWith($request->url(), 'https://connect.mailerlite.com/api/subscribers') &&
                data_get($payload, 'email') === 'test@test.com' &&
                data_get($payload, 'fields.name') === 'Willy' &&
                data_get($payload, 'fields.last_name') === 'Wonka' &&
                data_get($payload, 'fields.country') === 'Wonderland' &&
                $request->method() === 'POST';
        });
    }

    public function test_update_subscriber_correctly_builds_request()
    {
        Http::fake();

        $this->mailerLiteService->updateSubscriber(1, 'Willy', 'Wonka', 'Wonderland');

        Http::assertSent(function (Request $request) {
            $payload = json_decode($request->body(), true);

            return $request->url() === 'https://connect.mailerlite.com/api/subscribers/1' &&
                data_get($payload, 'fields.name') === 'Willy' &&
                data_get($payload, 'fields.last_name') === 'Wonka' &&
                data_get($payload, 'fields.country') === 'Wonderland' &&
                $request->method() === 'PUT';
        });
    }

    public function test_delete_subscriber_correctly_builds_request()
    {
        Http::fake();

        $this->mailerLiteService->deleteSubscriber(1);

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://connect.mailerlite.com/api/subscribers/1' &&
                $request->method() === 'DELETE';
        });
    }

    public function test_returns_result_object()
    {
        Http::fake();

        $response = $this->mailerLiteService->getSubscribers();

        $this->assertInstanceOf(Result::class, $response);
    }

    public function test_get_subscribers_returns_array_of_subscriber_object()
    {
        Http::fake(function ($request) {
            return Http::response([
                'data' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'subscribed_at' => '2021-09-01 14:03:50',
                    'fields' => [
                        'name' => 'Willy',
                        'last_name' => 'Wonka',
                        'country' => 'Wonderland',
                    ]
                ]
            ], 200);
        });

        $response = $this->mailerLiteService->getSubscribers();

        $this->assertIsArray($response->data);
        $this->assertInstanceOf(Subscriber::class, $response->data[0]);
        $this->assertEquals($response->data[0]->id, 1);
        $this->assertEquals($response->data[0]->email, 'test@test.com');
        $this->assertEquals($response->data[0]->subscribeDate, '2021-09-01');
        $this->assertEquals($response->data[0]->subscribeTime, '14:03:50');
        $this->assertEquals($response->data[0]->firstName, 'Willy');
        $this->assertEquals($response->data[0]->lastName, 'Wonka');
        $this->assertEquals($response->data[0]->country, 'Wonderland');
    }

    public function test_create_subscriber_returns_array_of_subscriber_object()
    {
        Http::fake(function ($request) {
            return Http::response([
                'data' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'subscribed_at' => '2021-09-01 14:03:50',
                    'fields' => [
                        'name' => 'Willy',
                        'last_name' => 'Wonka',
                        'country' => 'Wonderland',
                    ]
                ],
                'meta' => [
                    'prev_cursor' => 'prev',
                    'next_cursor' => 'next',
                ]
            ], 200);
        });

        $response = $this->mailerLiteService->createSubscriber('', '', '', '');

        $this->assertIsArray($response->data);
        $this->assertInstanceOf(Subscriber::class, $response->data[0]);
        $this->assertInstanceOf(ResultMeta::class, $response->meta);
        $this->assertEquals($response->meta->prevCursor, 'prev');
        $this->assertEquals($response->meta->nextCursor, 'next');
    }

    public function test_edit_subscriber_returns_array_of_subscriber_object()
    {
        Http::fake(function ($request) {
            return Http::response([
                'data' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'subscribed_at' => '2021-09-01 14:03:50',
                    'fields' => [
                        'name' => 'Willy',
                        'last_name' => 'Wonka',
                        'country' => 'Wonderland',
                    ]
                ]
            ], 200);
        });

        $response = $this->mailerLiteService->updateSubscriber(1, '', '', '');

        $this->assertIsArray($response->data);
        $this->assertInstanceOf(Subscriber::class, $response->data[0]);
    }

    public function test_delete_subscriber_returns_empty_array()
    {
        Http::fake();

        $response = $this->mailerLiteService->deleteSubscriber(1);

        $this->assertIsArray($response->data);
    }

    public function test_returns_error_object()
    {
        Http::fake(function ($request) {
            return Http::response([], 403);
        });

        $response = $this->mailerLiteService->getSubscribers();

        $this->assertInstanceOf(Error::class, $response);
    }

    public function test_handles_errors_list()
    {
        Http::fake(function ($request) {
            return Http::response([
                'errors' => ['test']
            ], 403);
        });

        $response = $this->mailerLiteService->getSubscribers();

        $this->assertEquals($response->errors, ['test']);
    }

    public function test_handles_missing_errors_list()
    {
        Http::fake(function ($request) {
            return Http::response([], 403);
        });

        $response = $this->mailerLiteService->getSubscribers();

        $this->assertIsArray($response->errors);
    }

    public function test_returns_correct_messages()
    {
        Http::fakeSequence()
            ->pushStatus(200)
            ->pushStatus(201)
            ->pushStatus(202)
            ->pushStatus(204)
            ->pushStatus(400)
            ->pushStatus(401)
            ->pushStatus(403)
            ->pushStatus(404)
            ->pushStatus(405)
            ->pushStatus(408)
            ->pushStatus(422)
            ->pushStatus(429)
            ->pushStatus(500)
            ->pushStatus(502)
            ->pushStatus(503)
            ->pushStatus(504);

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.200"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.201"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.202"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.204"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.400"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.401"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.403"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.404"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.405"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.408"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.422"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.429"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.500"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.502"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.503"));

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.504"));
    }

    public function test_handles_timeout()
    {
        Http::fake(function ($request) {
            throw new ConnectionException();
        });

        $response = $this->mailerLiteService->getSubscribers();
        $this->assertEquals($response->message, __("mailerlite.messages.500"));

        $response = $this->mailerLiteService->searchSubscriber('');
        $this->assertEquals($response->message, __("mailerlite.messages.500"));

        $response = $this->mailerLiteService->createSubscriber('', '', '', '');
        $this->assertEquals($response->message, __("mailerlite.messages.500"));

        $response = $this->mailerLiteService->updateSubscriber(1, '', '', '');
        $this->assertEquals($response->message, __("mailerlite.messages.500"));

        $response = $this->mailerLiteService->deleteSubscriber(1);
        $this->assertEquals($response->message, __("mailerlite.messages.500"));
    }
}
