<?php

namespace Tests\Unit;

use App\Utils\Response\StandarizedResponse;
use Tests\TestCase;

class StandarizedResponseTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_success_response(): void // need prefix 'test_' to be recognized as a test method
    {
        $responseObject = [
            'code' => 200,
            'message' => 'Operation successful',
            'data' => ['id' => 123]
        ];

        $jsonResponse = StandarizedResponse::standarized_response($responseObject);
        $payload = $jsonResponse->getData(true);

        $this->assertEquals(200, $payload['code']);
        $this->assertEquals('Operation successful', $payload['message']);
        $this->assertIsArray($payload['data']);
        $this->assertEquals(123, $payload['data']['id']);
    }

    public function test_failed_response(): void
    {
        $responseObject = [
            'code' => 400,
            'message' => 'Operation failed',
            'data' => null
        ];

        $jsonResponse = StandarizedResponse::standarized_response($responseObject);
        $payload = $jsonResponse->getData(true);

        $this->assertEquals(400, $payload['code']);
        $this->assertEquals('Operation failed', $payload['message']);
        $this->assertNull($payload['data']);
    }
}
