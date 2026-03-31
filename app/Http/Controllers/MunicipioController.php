<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MunicipioController extends BaseController
{
    /**
     * Obtener municipios por estado
     */
    public function porEstado(Request $request)
    {
        try {
            $estado = $request->query('estado');

            if (empty($estado)) {
                return $this->jsonError('El parámetro estado es requerido', 400);
            }

            $this->setApiToken(Session::get('api_token'));

            $response = $this->apiGet('/api/municipios/por-estado', ['estado' => $estado]);

            if ($this->apiResponseSuccessful($response)) {
                $data = $this->apiResponseData($response, []);

                return response()->json([
                    'success' => true,
                    'data' => $data,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $this->apiResponseMessage($response, 'Error al obtener municipios'),
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error al obtener municipios por estado', [
                'estado' => $request->query('estado'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener municipios',
            ], 500);
        }
    }
}
