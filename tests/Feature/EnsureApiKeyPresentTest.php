<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnsureApiKeyPresentTest extends TestCase
{
    public function test_it_redirects()
    {
        $response = $this->get('/');

        $response->assertRedirect('/apikeys/create');

        $response = $this->get('/subscribers');

        $response->assertRedirect('/apikeys/create');

        $response = $this->get('/subscribers/create');

        $response->assertRedirect('/apikeys/create');

        $response = $this->post('/subscribers');

        $response->assertRedirect('/apikeys/create');

        $response = $this->get('/subscribers/1/edit');

        $response->assertRedirect('/apikeys/create');

        $response = $this->put('/subscribers/1/');

        $response->assertRedirect('/apikeys/create');

        $response = $this->delete('/subscribers/1');

        $response->assertRedirect('/apikeys/create');
    }
}
