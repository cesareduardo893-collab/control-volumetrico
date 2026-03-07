<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Respuesta exitosa
     */
    public function success($data = null, string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Respuesta de error
     */
    public function error(string $message, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Error de validación
     */
    public function validationError($errors, string $message = 'Error de validación'): JsonResponse
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Error de autenticación
     */
    public function unauthorized(string $message = 'No autorizado'): JsonResponse
    {
        return $this->error($message, 401);
    }

    /**
     * Error de permisos
     */
    public function forbidden(string $message = 'Acceso prohibido'): JsonResponse
    {
        return $this->error($message, 403);
    }

    /**
     * Error de recurso no encontrado
     */
    public function notFound(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->error($message, 404);
    }
}