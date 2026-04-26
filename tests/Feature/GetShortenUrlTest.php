<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetShortenUrlTest extends TestCase
{
    use RefreshDatabase; // Handle migratiin & transaction rollback automatically

    public function test_success(): void
    {

        $originalUrl = 'https://example.com';
        $shortUrl = 'test';

        $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);


        $response = $this->get('/api/?short_url=' . $shortUrl);

        $response->assertStatus(200)
            // check the shape
            ->assertJsonStructure([
                'code',
                'status',
                'message',
                'data' => ['original_url']
            ])
            // check the content
            ->assertJsonPath('data.original_url', $originalUrl)
            ->assertJsonPath('status', 'Success');
    }

    public function test_bad_response(): void
    {

        $originalUrl = 'https://example.com';
        $shortUrl = 'test';

        $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);

        $tooFewShortUrl1 = 'in';

        $response = $this->get('/api/?short_url=' . $tooFewShortUrl1);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'short_url'
                ]
            ]);

        $tooLongShortUrl = 'testLoremipsumdolorsitametconsecteturadipiscingelit'; // 51 chars, exceeding the max length of 50

        $response = $this->get('/api/?short_url=' . $tooLongShortUrl);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'short_url'
                ]
            ]);
    }

    public function test_white_list(): void
    {

        $originalUrl = 'https://example.com';
        $shortUrl = '#$%&*';

        $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);

        $tooFewShortUrl1 = 'in';

        $response = $this->get('/api/?short_url=' . $tooFewShortUrl1);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'short_url'
                ]
            ]);
    }

    public function test_not_found(): void
    {

        $originalUrl = 'https://example.com';
        $shortUrl = 'test';
        $notFoundShortUrl = 'non-existent';


        $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'short_url' => $shortUrl
        ]);


        $response = $this->get('/api/?short_url=' . $notFoundShortUrl);

        $response->assertStatus(404)
            // check the shape
            ->assertJsonStructure([
                'code',
                'status',
                'message',
                'data'
            ])
            // check the content
            ->assertJsonPath('data', null)
            ->assertJsonPath('status', 'Failed');
    }
}
