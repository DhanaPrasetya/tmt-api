<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Utils\Response\StandarizedResponse;

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

        $test = StandarizedResponse::standarized_response($responseObject);

        $this->assertEquals(200, $test['code']);
        $this->assertEquals('Operation successful', $test['message']);
        $this->assertIsArray($test['data']);
        $this->assertEquals(123, $test['data']['id']);
    }

    public function test_failed_response(): void
    {
        $responseObject = [
            'code' => 400,
            'message' => 'Operation failed',
            'data' => null
        ];

        $test = StandarizedResponse::standarized_response($responseObject);

        // $this->assertEquals(400, $responseObject['code']);
        // $this->assertEquals('Operation failed', $responseObject['message']);
        // $this->assertNull($responseObject['data']);
    }
}
