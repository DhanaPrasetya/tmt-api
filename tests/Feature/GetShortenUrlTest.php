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
        $custom_alias = 'test';

        $this->postJson('/api/shorten', [ // seeding
            'original_url' => $originalUrl,
            'custom_alias' => $custom_alias
        ]);


        $response = $this->get('/api/?custom_alias=' . $custom_alias);

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

    public function test_too_few_custom_alias(): void
    {

        $tooFewCustomAlias = 'in';

        $response = $this->get('/api/?custom_alias=' . $tooFewCustomAlias);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'custom_alias'
                ]
            ]);
    }

    public function test_too_long_custom_alias(): void
    {

        $tooLongCustomAlias = str_repeat('a', 51); // 51 chars, exceeding the max length of 50

        $response = $this->get('/api/?custom_alias=' . $tooLongCustomAlias);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'custom_alias'
                ]
            ]);
    }

    public function test_invalid_custom_alias(): void
    {

        $invalidCustomAlias = '#$%&*';

        $response = $this->get('/api/?custom_alias=' . $invalidCustomAlias);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'custom_alias'
                ]
            ]);
    }

    public function test_not_found(): void
    {

        $notFoundCustomAlias = 'non-existent';

        $response = $this->get('/api/?custom_alias=' . $notFoundCustomAlias);

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
