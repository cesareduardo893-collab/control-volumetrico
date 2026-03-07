<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
        $this->setApiToken(Session::get('api_token'));
    }

    public function edit()
    {
        try {
            $response = $this->apiGet('/api/user');
            
            if (!$this->apiResponseSuccessful($response)) {
                return back()->withErrors(['error' => 'Error al cargar perfil']);
            }
            
            $data = $this->apiResponseData($response);
            
            return view('profile.edit', [
                'user' => $data['user'] ?? $data
            ]);
        } catch (\Exception $e) {
            Log::error('Profile edit error', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al cargar perfil']);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
            ]);
            
            $response = $this->apiPut('/api/user', $request->all());
            
            if (!$this->apiResponseSuccessful($response)) {
                return back()->withErrors($response['errors'] ?? ['error' => $response['message'] ?? 'Error al actualizar']);
            }
            
            // Actualizar usuario en sesión
            $userData = $this->apiResponseData($response);
            Session::put('user', $userData['user'] ?? $userData);
            
            return back()->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Profile update error', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al actualizar perfil']);
        }
    }

    public function showChangePassword()
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);
            
            $response = $this->apiPost('/api/change-password', $request->all());
            
            if (!$this->apiResponseSuccessful($response)) {
                return back()->withErrors($response['errors'] ?? ['error' => $response['message'] ?? 'Error al cambiar contraseña']);
            }
            
            return back()->with('success', 'Contraseña cambiada correctamente');
        } catch (\Exception $e) {
            Log::error('Change password error', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Error al cambiar contraseña']);
        }
    }
}