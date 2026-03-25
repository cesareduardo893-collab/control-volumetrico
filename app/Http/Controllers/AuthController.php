<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ValidacionEspanol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    use ValidacionEspanol;
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $resultadoValidacion = $this->validar($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            Log::info('Paso 1: Validación OK');
            $this->initApiClient();

            $response = $this->apiPost('/api/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            Log::info('Paso 2: API respondió', ['response_type' => gettype($response)]);

            // Verificar si la respuesta es exitosa usando el helper
            if (!$this->apiResponseSuccessful($response)) {
                $message = $this->apiResponseMessage($response, 'Credenciales incorrectas');
                Log::warning('Login fallido: ' . $message);
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', $message);
            }

            $data = $this->apiResponseData($response, []);
            Log::info('Paso 3: Datos recibidos', ['force_password_change' => $data['user']['force_password_change'] ?? false]);

            Session::put('api_token', $data['token'] ?? null);
            Session::put('user_id', $data['user']['id'] ?? null);
            Session::put('user_name', $data['user']['full_name'] ?? '');
            Session::put('user_email', $data['user']['email'] ?? '');
            Session::put('user_roles', $data['user']['roles'] ?? []);

            Log::info('Paso 4: Sesión guardada', session()->all());

            // Redirigir directamente al dashboard, ignorando force_password_change
            Log::info('Paso 5: Redirigir a dashboard');
            return redirect()->intended('dashboard')
                ->with('success', 'Bienvenido ' . ($data['user']['full_name'] ?? ''));

        } catch (\Throwable $e) {
            Log::error('Error en login', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'email' => $request->email
            ]);
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Error al iniciar sesión: ' . $e->getMessage());
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        try {
            $token = Session::get('api_token');

            if ($token) {
                $this->setApiToken($token);
                $this->apiPost('/api/logout');
            }

            Session::flush();

            return redirect()->route('login')
                ->with('success', 'Sesión cerrada exitosamente');

        } catch (\Throwable $e) {
            Log::error('Error en logout', ['error' => $e->getMessage()]);
            Session::flush();
            return redirect()->route('login')->with('success', 'Sesión cerrada');
        }
    }

    /**
     * Obtener usuario autenticado
     */
    public function user()
    {
        try {
            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/user');

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->route('login')
                    ->with('error', 'Sesión expirada');
            }

            $user = $this->apiResponseData($response, []);

            return view('auth.profile', [
                'user' => $user
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al obtener usuario', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('login')
                ->with('error', 'Error al obtener información de usuario');
        }
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $resultadoValidacion = $this->validar($request, [
            'identificacion' => 'required|string|max:18',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->initApiClient(false); // Sin token aún

            $response = $this->apiPost('/api/register', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                return redirect()->route('login')
                    ->with('success', 'Registro exitoso. Por favor inicia sesión.');
            }

            // Manejar errores de validación
            $errors = $this->apiResponseErrors($response, []);
            if (!empty($errors)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al registrar'));

        } catch (\Throwable $e) {
            Log::error('Error en registro', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar usuario');
        }
    }

    /**
     * Mostrar formulario de cambio de contraseña
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(Request $request)
    {
        $resultadoValidacion = $this->validar($request, [
            'password_actual' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);
        if ($resultadoValidacion) {
            return $resultadoValidacion;
        }

        try {
            $this->setApiToken(Session::get('api_token'));
            $userId = Session::get('user_id');

            $response = $this->apiPost("/api/users/{$userId}/cambiar-password", [
                'password_actual' => $request->password_actual,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation
            ]);

            if ($this->apiResponseSuccessful($response)) {
                return redirect()->route('dashboard')
                    ->with('success', 'Contraseña cambiada exitosamente');
            }

            $errors = $this->apiResponseErrors($response, []);
            if (!empty($errors)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            $message = $this->apiResponseMessage($response, 'Error al cambiar contraseña');
            if (strpos($message, 'actual') !== false || strpos($message, 'incorrecta') !== false) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La contraseña actual es incorrecta');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $message);

        } catch (\Throwable $e) {
            Log::error('Error al cambiar contraseña', [
                'error' => $e->getMessage(),
                'user_id' => Session::get('user_id')
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al cambiar contraseña');
        }
    }
}