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
            'custom_alias' => 'test'
        ]);

        $response->assertStatus(201)
            // check the shape
            ->assertJsonStructure([
                'code',
                'status',
                'message',
                'data' => ['custom_alias']
            ])
            // check the content
            ->assertJsonPath('data.custom_alias', 'test')
            ->assertJsonPath('status', 'Success');
    }

    public function test_bad_request_invalid_original_url(): void
    {

        $originalUrl = 'nohttporhttps.com';
        $custom_alias = 'test';

        $response = $this->postJson('/api/shorten', [
            'original_url' => $originalUrl,
            'custom_alias' => $custom_alias
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
        $custom_alias = 'test';

        $response = $this->postJson('/api/shorten', [
            'original_url' => $originalUrl,
            'custom_alias' => $custom_alias
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'original_url'
                ]
            ]);
    }

    public function test_bad_request_invalid_custom_alias(): void
    {

        $originalUrl = 'https://example.com';
        $invalidChar = '@#$%^&*()_+|~=`{}[]:";\'<>?,./';

        $response = $this->postJson('/api/shorten', [
            'original_url' => $originalUrl,
            'custom_alias' => $invalidChar
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'custom_alias'
                ]
            ]);
    }

    public function test_bad_response_too_few_custom_alias(): void
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'example.com',
            'custom_alias' => 'st'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'custom_alias'
                ]
            ]);
    }

    public function test_bad_response_too_long_custom_alias(): void
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'https://example.com',
            'custom_alias' => '' . str_repeat('a', 51) // max 50 chars
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'custom_alias'
                ]
            ]);
    }

    public function test_custom_alias_already_exist(): void
    {

        $originalUrl = 'https://site.com';
        $custom_alias = 'test';

        $response = $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'custom_alias' => $custom_alias
        ]);

        $response = $this->postJson('/api/shorten', [ // testing
            'original_url' => $originalUrl,
            'custom_alias' => $custom_alias
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
