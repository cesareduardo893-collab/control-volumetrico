<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use App\Http\Controllers\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    use LogsActivity, ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Mostrar formulario de login (GET)
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesar login (POST)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $this->setApiToken(null);

            Log::info('Intentando login', ['email' => $request->email]);

            $response = $this->apiPost('/api/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            Log::info('Respuesta del API', ['response' => $response]);

            // Verificar si la respuesta fue exitosa
            if ($this->apiResponseSuccessful($response)) {
                $data = $this->apiResponseData($response);

                // El backend devuelve la estructura: ['data' => ['user' => ..., 'token' => ...]]
                $userData = $data['user'] ?? null;
                $token = $data['token'] ?? null;

                if ($token && $userData) {
                    Session::put('api_token', $token);
                    Session::put('user', $userData);
                    Session::put('authenticated', true);

                    Log::info('Login exitoso', ['user_id' => $userData['id'] ?? null]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Login exitoso',
                        'redirect' => '/dashboard'
                    ]);
                }
            }

            // Si llegamos aquí, el login falló
            $message = $this->apiResponseMessage($response, 'Credenciales incorrectas');

            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => ['email' => [$message]]
            ], 401);

        } catch (\Exception $e) {
            Log::error('Error en login', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Log the failed login attempt
            $this->logActivity(
                null,
                'seguridad',
                'seguridad',
                'Autenticación',
                'Intento de inicio de sesión fallido',
                'users',
                null,
                null,
                null,
                [
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'GuzzleHttp/7',
                    'dispositivo' => 'GuzzleHttp/7'
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        try {
            if (Session::has('api_token')) {
                $this->setApiToken(Session::get('api_token'));
                $this->apiPost('/api/logout');
            }
        } catch (\Exception $e) {
            Log::error('Error en logout', ['error' => $e->getMessage()]);
        } finally {
            Session::flush();
        }

        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro
     */
public function register(Request $request)
    {
        $request->validate([
            'identificacion' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $this->setApiToken(null);

            $response = $this->apiPost('/api/register', [
                'identificacion' => $request->identificacion,
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

            if ($this->apiResponseSuccessful($response)) {
                return redirect('/login')->with('success', 'Registro exitoso. Por favor inicie sesión.');
            }

            // Handle API validation errors
            if ($response->status() === 422 && $response->json('errors')) {
                return back()
                    ->withInput()
                    ->withErrors($response->json('errors'));
            }

            // Handle other HTTP errors (e.g., 404, 500)
            if (!$response->successful()) {
                Log::error('Error en registro API', ['status' => $response->status(), 'body' => $response->body()]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Error de comunicación con el servidor (código ' . $response->status() . ')']);
            }

            return back()
                ->withInput()
                ->withErrors(['email' => $this->apiResponseMessage($response, 'Error en el registro')]);

        } catch (\Exception $e) {
            Log::error('Error en registro', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario de recuperación de contraseña
     */
    public function showForgot()
    {
        return view('auth.forgot');
    }

    /**
     * Enviar enlace de recuperación
     */
    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->setApiToken(null);

            $response = $this->apiPost('/api/forgot-password', [
                'email' => $request->email,
            ]);

            if ($this->apiResponseSuccessful($response)) {
                return back()->with('success', 'Se ha enviado un enlace de recuperación a su correo.');
            }

            return back()
                ->withInput()
                ->withErrors(['email' => $this->apiResponseMessage($response, 'Error al enviar el enlace')]);

        } catch (\Exception $e) {
            Log::error('Error en forgot password', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario de reset de contraseña
     */
    public function showReset($token)
    {
        return view('auth.reset', ['token' => $token]);
    }

    /**
     * Procesar reset de contraseña
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $this->setApiToken(null);

            $response = $this->apiPost('/api/reset-password', [
                'token' => $request->token,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

            if ($this->apiResponseSuccessful($response)) {
                return redirect('/login')->with('success', 'Contraseña actualizada. Por favor inicie sesión.');
            }

            return back()
                ->withInput()
                ->withErrors(['email' => $this->apiResponseMessage($response, 'Error al resetear contraseña')]);

        } catch (\Exception $e) {
            Log::error('Error en reset password', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Error en el servidor: ' . $e->getMessage()]);
        }
    }
}