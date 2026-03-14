<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ConsumesApi
{
    protected $apiClient;
    protected $apiToken;
    protected $baseUrl;

    public function initApiClient()
    {
        $this->baseUrl = config('services.api.url', env('API_URL', 'http://localhost:8000'));
        $this->apiClient = Http::baseUrl($this->baseUrl)
            ->withOptions([
                'timeout' => 30,
                'verify' => false, // Solo para desarrollo
            ]);
    }

    public function setApiToken($token)
    {
        $this->apiToken = $token;
    }

    protected function withToken()
    {
        if ($this->apiToken) {
            return $this->apiClient->withToken($this->apiToken);
        }
        return $this->apiClient;
    }

    public function apiGet($endpoint, $params = [])
    {
        try {
            $response = $this->withToken()->get($endpoint, $params);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API GET Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function apiGetRaw($endpoint, $params = [])
    {
        try {
            return $this->withToken()->get($endpoint, $params);
        } catch (\Exception $e) {
            Log::error('API GET Raw Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    public function apiPost($endpoint, $data = [])
    {
        try {
            $response = $this->withToken()->post($endpoint, $data);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API POST Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function apiPut($endpoint, $data = [])
    {
        try {
            $response = $this->withToken()->put($endpoint, $data);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API PUT Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function apiDelete($endpoint)
    {
        try {
            $response = $this->withToken()->delete($endpoint);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API DELETE Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function handleResponse($response)
    {
        $body = $response->body();
        $json = $response->json();
        
        Log::debug('API Response', [
            'status' => $response->status(),
            'body' => $body
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $json['data'] ?? $json,
                'message' => $json['message'] ?? 'Operación exitosa',
                'status' => $response->status()
            ];
        }

        return [
            'success' => false,
            'message' => $json['message'] ?? 'Error en la solicitud',
            'errors' => $json['errors'] ?? [],
            'status' => $response->status()
        ];
    }

    public function apiResponseSuccessful($response)
    {
        return isset($response['success']) && $response['success'] === true;
    }

    public function apiResponseData($response, $default = [])
    {
        return $response['data'] ?? $default;
    }

    public function apiResponseMessage($response, $default = 'Error')
    {
        return $response['message'] ?? $default;
    }

    public function apiResponseErrors($response, $default = [])
    {
        return $response['errors'] ?? $default;
    }
}
