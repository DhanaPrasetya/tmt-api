<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateShortenUrlTest extends TestCase
{
    use RefreshDatabase; // Handle migratiin & transaction rollback automatically

    public function test_success_creating_short_url(): void
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'https://example.com',
            'short_url' => 'test'
        ]);

        $response->assertStatus(201)
            // check the shape
            ->assertJsonStructure([
                'code',
                'status',
                'message',
                'data' => ['short_url']
            ])
            // check the content
            ->assertJsonPath('data.short_url', 'test')
            ->assertJsonPath('status', 'Success');
    }

    public function test_bad_request_invalid_original_url(): void
    {

        $originalUrl = 'nohttporhttps.com';
        $shortUrl = 'test';

        $response = $this->postJson('/api/shorten', [
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'original_url'
                ]
            ]);
    }

    public function test_bad_request_too_long_original_url(): void
    {

        $originalUrl = 'https://' . str_repeat('a', 2049 - 8); // max 2048 chars
        $shortUrl = 'test';

        $response = $this->postJson('/api/shorten', [
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'original_url'
                ]
            ]);
    }

    public function test_bad_request_invalid_short_url(): void
    {

        $originalUrl = 'https://example.com';
        $invalidChar = '@#$%^&*()_+|~=`{}[]:";\'<>?,./';

        $response = $this->postJson('/api/shorten', [
            'original_url' => $originalUrl,
            'short_url' => $invalidChar
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'short_url'
                ]
            ]);
    }

    public function test_bad_response_too_few_short_url(): void
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'example.com',
            'short_url' => 'st'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'original_url',
                    'short_url'
                ]
            ]);
    }

    public function test_bad_response_too_long_short_url(): void
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'https://example.com',
            'short_url' => '' . str_repeat('a', 51) // max 50 chars
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'short_url'
                ]
            ]);
    }

    public function test_short_url_already_exist(): void
    {

        $originalUrl = 'https://site.com';
        $shortUrl = 'test';

        $response = $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);

        $response = $this->postJson('/api/shorten', [ // testing
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);

        $response->assertStatus(409)
            ->assertJsonStructure([
                'code',
                'status',
                'message',
                'data'
            ]);
    }
}
