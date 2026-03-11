<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class BaseController extends Controller
{
    use Traits\ConsumesApi; // Solo mantenemos el trait para consumir APIs

    protected $apiClient;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Respuesta exitosa JSON
     */
    public function jsonSuccess($data = null, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Respuesta de error JSON
     */
    public function jsonError(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Renderizar vista con datos de API
     */
    protected function renderView($view, $apiResponse, $defaultData = [], $filters = [])
    {
        if (!$this->apiResponseSuccessful($apiResponse)) {
            return redirect()->back()->with('error', $this->apiResponseMessage($apiResponse, 'Error al cargar datos'));
        }

        $data = $this->apiResponseData($apiResponse, []);

        $viewData = array_merge($defaultData, [
            'filters' => $filters
        ]);

        if (isset($apiResponse->meta)) {
            $viewData['meta'] = $apiResponse->meta;
            $viewData['links'] = $apiResponse->links ?? [];
        }

        // Asignar los datos principales según el contexto
        if (isset($defaultData['key'])) {
            $viewData[$defaultData['key']] = $data;
        } else {
            $viewData['data'] = $data;
        }

        return view($view, $viewData);
    }

    /**
     * Obtener catálogo para selects
     */
    protected function getCatalog($endpoint, $params = [])
    {
        try {
            $this->setApiToken(session('api_token'));
            $response = $this->apiGet($endpoint, array_merge(['per_page' => 500], $params));
            
            if ($this->apiResponseSuccessful($response)) {
                return $this->apiResponseData($response, []);
            }
            
            return [];
        } catch (\Exception $e) {
            Log::error('Error al obtener catálogo', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Parsear user agent
     */
    protected function parseUserAgent(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return null;
        }

        if (preg_match('/\((.*?)\)/', $userAgent, $matches)) {
            return substr($matches[1], 0, 100);
        }

        return substr($userAgent, 0, 100);
    }
}