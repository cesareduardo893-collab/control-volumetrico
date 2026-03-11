<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->initApiClient();
        $this->setApiToken(Session::get('api_token'));
    }

    /**
     * Muestra el dashboard principal con datos vacíos.
     */
    public function index()
    {
        return view('dashboard.index', [
            'resumen' => [
                'contribuyentes_activos' => 0,
                'instalaciones_activas'   => 0,
                'alarmas_activas'         => 0,
                'volumen_total'           => 0,
                'ultimos_movimientos'     => [],
            ],
            'tiempoReal' => [
                'volumen_actual' => 0,
                'flujo'           => 0,
                'temperatura'     => 0,
                'presion'         => 0,
            ],
        ]);
    }

    // Los demás métodos (tiempoReal, graficaMovimientos, etc.) se pueden eliminar o dejar comentados
}