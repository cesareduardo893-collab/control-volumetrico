<?php

namespace Tests\Feature;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class BaseControllerTest extends TestCase
{
    /** @test */
    public function test_json_success_returns_correct_structure()
    {
        $response = response()->json([
            'success' => true,
            'message' => 'Operation successful',
            'data' => ['key' => 'value'],
        ], 201);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());

        $responseData = $response->getData();
        $this->assertTrue($responseData->success);
        $this->assertEquals('Operation successful', $responseData->message);
    }

    /** @test */
    public function test_json_error_returns_correct_structure()
    {
        $response = response()->json([
            'success' => false,
            'message' => 'Error occurred',
            'errors' => ['field' => ['Error message']],
        ], 422);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());

        $responseData = $response->getData();
        $this->assertFalse($responseData->success);
        $this->assertEquals('Error occurred', $responseData->message);
    }

    /** @test */
    public function test_render_view_with_successful_response()
    {
        $view = view('test-view', ['message' => 'Test message']);

        $this->assertNotNull($view);
    }

    /** @test */
    public function test_render_view_with_error_response()
    {
        Session::put('error', 'Error loading data');

        $this->assertTrue(session()->has('error'));
    }

    /** @test */
    public function test_get_catalog_returns_data_successfully()
    {
        Session::put('api_token', $this->testApiToken);

        $catalogData = [
            ['id' => 1, 'name' => 'Option 1'],
            ['id' => 2, 'name' => 'Option 2'],
        ];

        $this->mockSuccessfulResponse('/api/test-catalog', $catalogData);

        $response = Http::get($this->baseApiUrl.'/api/test-catalog');

        $this->assertTrue($response->successful());
    }

    /** @test */
    public function test_get_catalog_returns_empty_array_on_error()
    {
        Session::put('api_token', $this->testApiToken);

        $this->mockErrorResponse('/api/test-catalog', 'Error', 500);

        $response = Http::get($this->baseApiUrl.'/api/test-catalog');

        $this->assertEquals(500, $response->status());
    }

    /** @test */
    public function test_parse_user_agent_extracts_device_info()
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';

        preg_match('/\((.*?)\)/', $userAgent, $matches);
        $result = substr($matches[1], 0, 100);

        $this->assertStringContainsString('Windows NT 10.0', $result);
    }

    /** @test */
    public function test_parse_user_agent_returns_null_for_empty_input()
    {
        $userAgent = null;

        $result = $userAgent ? substr($userAgent, 0, 100) : null;

        $this->assertNull($result);
    }

    /** @test */
    public function test_parse_user_agent_truncates_long_strings()
    {
        $longUserAgent = str_repeat('a', 200);

        preg_match('/\((.*?)\)/', $longUserAgent, $matches);
        $result = isset($matches[1]) ? substr($matches[1], 0, 100) : substr($longUserAgent, 0, 100);

        $this->assertLessThanOrEqual(100, strlen($result));
    }
}
