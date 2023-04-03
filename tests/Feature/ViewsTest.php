<?php

namespace Tests\Feature;

use App\Services\ApiKeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    public function test_views()
    {
        ApiKeyService::set('test');

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

        $response = $this->get('/');

        $response->assertSuccessful();
        $response->assertViewIs('home');

        $response = $this->get('/apikeys/create');

        $response->assertSuccessful();
        $response->assertViewIs('apikeys.create');

        $response = $this->get('/subscribers');

        $response->assertSuccessful();
        $response->assertViewIs('subscribers.index');

        $response = $this->get('/subscribers/create');

        $response->assertSuccessful();
        $response->assertViewIs('subscribers.create');

        $response = $this->get('/subscribers/1/edit');

        $response->assertSuccessful();
        $response->assertViewIs('subscribers.edit');
    }
}
