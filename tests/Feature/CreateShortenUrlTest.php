<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateShortenUrlTest extends TestCase
{
    use RefreshDatabase; // Handle migratiin & transaction rollback automatically

    public function test_success(): void
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

    public function test_white_list(): void
    {

        $originalUrl = 'https://example.com';
        $shortUrl = 'test';

        $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);

        $char = '@#$%^&*()_+|~=`{}[]:";\'<>?,./';

        $response = $this->get('/api/?short_url=' . $char);

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
            'short_url' => 'LoremipsumdolorsitametconsecteturadipiscingelitseddoeiusmodtemporincididuntutlaboreetdoloremagnaaliquaUtenimadminimveniamquisnostrudexercitationullamcolaborisnisiutaliquipexeacommodoconsequatDuisautereprehenderitinfuturevelitesseillumdoloreeufugiatnullapar'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'short_url'
                ]
            ]);
    }
}
