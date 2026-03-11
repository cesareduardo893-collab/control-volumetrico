<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $this->initApiClient(false); // Sin token aún

            $response = $this->apiPost('/api/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            if (!$this->apiResponseSuccessful($response)) {
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->with('error', $this->apiResponseMessage($response, 'Credenciales incorrectas'));
            }

            $data = $this->apiResponseData($response, []);

            // Guardar datos en sesión
            Session::put('api_token', $data['token'] ?? null);
            Session::put('user_id', $data['user']['id'] ?? null);
            Session::put('user_name', $data['user']['full_name'] ?? '');
            Session::put('user_email', $data['user']['email'] ?? '');
            Session::put('user_roles', $data['user']['roles'] ?? []);

            // Registrar actividad
            $this->logActivity(
                $data['user']['id'] ?? null,
                'seguridad',
                'LOGIN',
                'Autenticación',
                'Inicio de sesión exitoso'
            );

            // Verificar si debe cambiar contraseña
            if ($data['user']['force_password_change'] ?? false) {
                return redirect()->route('password.change')
                    ->with('warning', 'Debes cambiar tu contraseña por razones de seguridad');
            }

            return redirect()->intended('dashboard')
                ->with('success', 'Bienvenido ' . ($data['user']['full_name'] ?? ''));

        } catch (\Exception $e) {
            Log::error('Error en login', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Error al iniciar sesión');
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        try {
            $userId = Session::get('user_id');
            $token = Session::get('api_token');

            if ($token) {
                $this->setApiToken($token);
                $this->apiPost('/api/logout');
            }

            // Registrar actividad
            if ($userId) {
                $this->logActivity(
                    $userId,
                    'seguridad',
                    'LOGOUT',
                    'Autenticación',
                    'Cierre de sesión'
                );
            }

            // Limpiar sesión
            Session::flush();

            return redirect()->route('login')
                ->with('success', 'Sesión cerrada exitosamente');

        } catch (\Exception $e) {
            Log::error('Error en logout', [
                'error' => $e->getMessage()
            ]);

            Session::flush();

            return redirect()->route('login')
                ->with('success', 'Sesión cerrada');
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

        } catch (\Exception $e) {
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
        $request->validate([
            'identificacion' => 'required|string|max:18',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        try {
            $this->initApiClient(false); // Sin token aún

            $response = $this->apiPost('/api/register', $request->all());

            if ($this->apiResponseSuccessful($response)) {
                return redirect()->route('login')
                    ->with('success', 'Registro exitoso. Por favor inicia sesión.');
            }

            // Manejar errores de validación
            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al registrar'));

        } catch (\Exception $e) {
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
        $request->validate([
            'password_actual' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $this->setApiToken(Session::get('api_token'));
            $userId = Session::get('user_id');

            $response = $this->apiPost("/api/users/{$userId}/cambiar-password", [
                'password_actual' => $request->password_actual,
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation
            ]);

            if ($this->apiResponseSuccessful($response)) {
                // Registrar actividad
                $this->logActivity(
                    Session::get('user_id'),
                    'seguridad',
                    'CAMBIO_PASSWORD',
                    'Seguridad',
                    'Contraseña cambiada exitosamente'
                );

                return redirect()->route('dashboard')
                    ->with('success', 'Contraseña cambiada exitosamente');
            }

            // Manejar errores
            if ($response->status === 401) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La contraseña actual es incorrecta');
            }

            if ($response->status === 422) {
                $errors = $this->apiResponseErrors($response, []);
                return redirect()->back()
                    ->withInput()
                    ->withErrors($errors);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $this->apiResponseMessage($response, 'Error al cambiar contraseña'));

        } catch (\Exception $e) {
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