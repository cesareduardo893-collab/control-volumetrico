<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Session;

class ApiService
{
    protected Client $client;
    protected string $baseUrl;
    protected string $tokenKey;

    public function __construct()
    {
        // DEBUG: Mostrar qué URL está usando
        \Log::info('=== INICIALIZANDO ApiService ===');
        
        // Obtener la URL de diferentes formas para debug
        $envUrl = env('API_URL');
        $configUrl = config('services.api.url');
        
        \Log::info('env("API_URL"): ' . ($envUrl ?? 'NO DEFINIDO'));
        \Log::info('config("services.api.url"): ' . ($configUrl ?? 'NO CONFIGURADO'));
        
        $this->baseUrl = config('services.api.url', env('API_URL', 'http://localhost:8000/api'));
        $this->tokenKey = config('services.api.token_key', env('SESSION_TOKEN_KEY', 'api_auth_token'));
        
        \Log::info('URL FINAL ApiService: ' . $this->baseUrl);
        \Log::info('Token Key: ' . $this->tokenKey);
        
        // Para evitar problemas de resolución de paths por Guzzle, usamos
        // como `base_uri` solo la parte del host (sin el path `/api`).
        $baseUriHost = $this->baseUrl;
        if (strpos($baseUriHost, '/api') !== false) {
            $baseUriHost = rtrim(substr($baseUriHost, 0, strpos($baseUriHost, '/api')), '/');
        }

        $this->client = new Client([
            'base_uri' => rtrim($baseUriHost, '/') . '/',
            'timeout' => config('services.api.timeout', env('API_TIMEOUT', 30)),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function setToken(string $token): void
    {
        Session::put($this->tokenKey, $token);
        \Log::info('Token guardado en sesión: ' . substr($token, 0, 20) . '...');
    }

    public function getToken(): ?string
    {
        $token = Session::get($this->tokenKey);
        \Log::info('Token obtenido de sesión: ' . ($token ? substr($token, 0, 20) . '...' : 'NULL'));
        return $token;
    }

    public function removeToken(): void
    {
        Session::forget($this->tokenKey);
        \Log::info('Token removido de sesión');
    }

    public function isAuthenticated(): bool
    {
        $isAuth = !is_null($this->getToken());
        \Log::info('Usuario autenticado: ' . ($isAuth ? 'SI' : 'NO'));
        return $isAuth;
    }

    private function getHeaders(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $token = $this->getToken();
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
            \Log::info('Headers con token de autorización');
        } else {
            \Log::info('Headers SIN token de autorización');
        }

        return $headers;
    }

    public function request(string $method, string $endpoint, array $data = [])
    {
        // Normalizar endpoint: siempre enviamos endpoints relativos con
        // prefijo 'api/'. De esta forma la URL final será
        // {base_host}/api/{endpoint} y evitamos reemplazos indeseados.
        $endpoint = ltrim($endpoint, '/');
        if ($endpoint === '') {
            $endpoint = 'api';
        } elseif (strpos($endpoint, 'api/') !== 0 && $endpoint !== 'api') {
            $endpoint = 'api/' . $endpoint;
        }

        try {
            \Log::info("=== API REQUEST ===");
            \Log::info("Método: {$method}");
            \Log::info("Endpoint: {$this->baseUrl}/{$endpoint}");
            \Log::info("Datos: " . json_encode($data));
            
            $options = [
                'headers' => $this->getHeaders(),
                // No seguir redirecciones automáticamente (evitar caer en /login web)
                'allow_redirects' => false,
                // No lanzar excepciones por códigos 4xx/5xx, manejamos la respuesta manualmente
                'http_errors' => false,
            ];

            if (!empty($data)) {
                if (in_array(strtoupper($method), ['GET', 'HEAD'])) {
                    $options['query'] = $data;
                } else {
                    $options['json'] = $data;
                }
            }

            \Log::info("Opciones de request: " . json_encode($options));

            $response = $this->client->request($method, $endpoint, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            \Log::info("=== API RESPONSE ===");
            \Log::info("Status: " . $response->getStatusCode());
            \Log::info("Body: " . json_encode($body));

            return [
                'success' => $body['success'] ?? false,
                'data' => $body['data'] ?? null,
                'message' => $body['message'] ?? '',
                'errors' => $body['errors'] ?? null,
                'status' => $response->getStatusCode(),
            ];

        } catch (ClientException $e) {
            \Log::error('ClientException: ' . $e->getMessage());
            \Log::error('Request URL: ' . $e->getRequest()->getUri());
            return $this->handleException($e);
        } catch (ServerException $e) {
            \Log::error('ServerException: ' . $e->getMessage());
            return $this->handleException($e);
        } catch (RequestException $e) {
            \Log::error('RequestException: ' . $e->getMessage());
            return $this->handleException($e);
        } catch (ConnectException $e) {
            \Log::error('ConnectException: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'No se pudo conectar con el servidor. Verifica tu conexión a internet.',
                'data' => null,
                'errors' => null,
                'status' => 0,
            ];
        } catch (Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error inesperado: ' . $e->getMessage(),
                'data' => null,
                'errors' => null,
                'status' => 500,
            ];
        }
    }

    private function handleException($e): array
    {
        $status = $e->getCode();
        $response = $e->getResponse();
        
        \Log::error('=== HANDLE EXCEPTION ===');
        \Log::error('Código: ' . $status);
        \Log::error('Mensaje: ' . $e->getMessage());
        
        if ($response) {
            try {
                $body = json_decode($response->getBody()->getContents(), true);
                \Log::error('Response Body: ' . json_encode($body));
                return [
                    'success' => $body['success'] ?? false,
                    'message' => $body['message'] ?? $e->getMessage(),
                    'data' => $body['data'] ?? null,
                    'errors' => $body['errors'] ?? null,
                    'status' => $response->getStatusCode(),
                ];
            } catch (\Exception $ex) {
                \Log::error('Error parseando response: ' . $ex->getMessage());
                return [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data' => null,
                    'errors' => null,
                    'status' => $response->getStatusCode(),
                ];
            }
        }

        return [
            'success' => false,
            'message' => $e->getMessage(),
            'data' => null,
            'errors' => null,
            'status' => $status ?: 500,
        ];
    }

    // Métodos específicos
    public function login(array $credentials)
    {
        \Log::info('=== API LOGIN CALL ===');
        \Log::info('Credentials: ' . json_encode($credentials));
        return $this->request('POST', '/login', $credentials);
    }

    public function logout()
    {
        $response = $this->request('POST', '/logout');
        if ($response['success']) {
            $this->removeToken();
        }
        return $response;
    }

    public function getUser()
    {
        return $this->request('GET', '/user');
    }
}