<?php

namespace Tests\Feature;

use App\Services\ApiKeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ApiKeyService::set('test');
    }

    public function test_views()
    {
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
