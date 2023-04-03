<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewsTest extends TestCase
{
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
