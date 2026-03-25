<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BaseControllerTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new class extends BaseController {
            public function testJsonSuccess($data = null, string $message = '', int $code = 200): JsonResponse
            {
                return $this->jsonSuccess($data, $message, $code);
            }

            public function testJsonError(string $message, int $code = 400, $errors = null): JsonResponse
            {
                return $this->jsonError($message, $code, $errors);
            }

            public function testRenderView($view, $apiResponse, $defaultData = [], $filters = [])
            {
                return $this->renderView($view, $apiResponse, $defaultData, $filters);
            }

            public function testGetCatalog($endpoint, $params = [])
            {
                return $this->getCatalog($endpoint, $params);
            }

            public function testParseUserAgent($userAgent)
            {
                return $this->parseUserAgent($userAgent);
            }
        };
    }

    /** @test */
    public function test_json_success_returns_correct_structure()
    {
        $data = ['key' => 'value'];
        $message = 'Operation successful';

        $response = $this->controller->testJsonSuccess($data, $message, 201);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());

        $responseData = $response->getData();
        $this->assertTrue($responseData->success);
        $this->assertEquals($message, $responseData->message);
        $this->assertEquals($data, (array)$responseData->data);
    }

    /** @test */
    public function test_json_error_returns_correct_structure()
    {
        $message = 'Error occurred';
        $errors = ['field' => ['Error message']];

        $response = $this->controller->testJsonError($message, 422, $errors);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());

        $responseData = $response->getData();
        $this->assertFalse($responseData->success);
        $this->assertEquals($message, $responseData->message);
        $this->assertEquals($errors, (array)$responseData->errors);
    }

    /** @test */
    public function test_render_view_with_successful_response()
    {
        Session::put('api_token', $this->testApiToken);

        $apiResponse = [
            'success' => true,
            'data' => [
                'items' => [['id' => 1, 'name' => 'Test']],
                'current_page' => 1,
                'total' => 1,
                'per_page' => 10,
                'last_page' => 1
            ]
        ];

        $response = $this->controller->testRenderView(
            'test-view',
            $apiResponse,
            ['key' => 'testKey'],
            ['filter' => 'value']
        );

        // Verificar que retorna una vista
        $this->assertNotNull($response);
    }

    /** @test */
    public function test_render_view_with_error_response()
    {
        $apiResponse = [
            'success' => false,
            'message' => 'Error loading data'
        ];

        $response = $this->controller->testRenderView(
            'test-view',
            $apiResponse,
            ['key' => 'testKey'],
            []
        );

        // Debería redirigir con error
        $this->assertNotNull($response);
        $this->assertTrue(session()->has('error'));
    }

    /** @test */
    public function test_get_catalog_returns_data_successfully()
    {
        Session::put('api_token', $this->testApiToken);

        $catalogData = [
            ['id' => 1, 'name' => 'Option 1'],
            ['id' => 2, 'name' => 'Option 2']
        ];

        $this->mockSuccessfulResponse('/api/test-catalog', $catalogData);

        $result = $this->controller->testGetCatalog('/api/test-catalog');

        $this->assertEquals($catalogData, $result);
    }

    /** @test */
    public function test_get_catalog_returns_empty_array_on_error()
    {
        Session::put('api_token', $this->testApiToken);

        $this->mockErrorResponse('/api/test-catalog', 'Error', 500);

        $result = $this->controller->testGetCatalog('/api/test-catalog');

        $this->assertEquals([], $result);
    }

    /** @test */
    public function test_parse_user_agent_extracts_device_info()
    {
        $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
        $result = $this->controller->testParseUserAgent($userAgent);
        
        $this->assertStringContainsString('Windows NT 10.0', $result);
    }

    /** @test */
    public function test_parse_user_agent_returns_null_for_empty_input()
    {
        $result = $this->controller->testParseUserAgent(null);
        $this->assertNull($result);

        $result = $this->controller->testParseUserAgent('');
        $this->assertNull($result);
    }

    /** @test */
    public function test_parse_user_agent_truncates_long_strings()
    {
        $longUserAgent = str_repeat('a', 200);
        $result = $this->controller->testParseUserAgent($longUserAgent);
        
        $this->assertLessThanOrEqual(100, strlen($result));
    }
}