<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

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

        $responseData = $this->apiResponseData($apiResponse);
        $meta = [];
        $links = [];

        if (Arr::accessible($responseData) && Arr::exists($responseData, 'data') && Arr::accessible($responseData['data'])) {
            $metaKeys = [
                'current_page', 'from', 'to', 'per_page',
                'last_page', 'total', 'path', 'next_page_url', 'prev_page_url'
            ];

            foreach ($metaKeys as $key) {
                if (Arr::exists($responseData, $key)) {
                    $meta[$key] = $responseData[$key];
                }
            }

            $links = $responseData['links'] ?? [];
            $responseData = $responseData['data'];
        } else {
            $meta = Arr::get($apiResponse, 'meta', []);
            $links = Arr::get($apiResponse, 'links', []);
        }

        $viewData = array_merge($defaultData, [
            'filters' => $filters
        ]);

        if (!empty($meta)) {
            $viewData['meta'] = $meta;
        }

        if (!empty($links)) {
            $viewData['links'] = $links;
        }

        if (isset($defaultData['key'])) {
            $viewData[$defaultData['key']] = $responseData;
        } else {
            $viewData['data'] = $responseData;
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
                $data = $this->apiResponseData($response, []);
                
                // Si la respuesta tiene estructura de paginación (data es array con 'data' dentro)
                if (is_array($data) && isset($data['data'])) {
                    return $data['data'];
                }
                
                return $data;
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
