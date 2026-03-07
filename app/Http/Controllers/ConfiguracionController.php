<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ConsumesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConfiguracionController extends Controller
{
    use ConsumesApi;

    public function __construct()
    {
        $this->initApiClient();
    }

    /**
     * Mostrar configuración del sistema
     */
    public function index()
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet('/api/configuracion');

        if ($this->apiResponseSuccessful($response)) {
            $configuracion = $this->apiResponseData($response);
            return view('configuracion.index', compact('configuracion'));
        }

        return redirect()->back()->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Actualizar configuración del sistema
     */
    public function update(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'nombre_sistema' => 'required|string|max:255',
            'version_sistema' => 'required|string|max:20',
            'empresa' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:500',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|string',
            'color_principal' => 'nullable|string|max:7',
            'color_secundario' => 'nullable|string|max:7',
            'maximo_registros' => 'required|integer|min:1|max:10000',
            'tiempo_sesion' => 'required|integer|min:1|max:480',
            'auditoria_activa' => 'required|boolean',
            'backup_automatico' => 'required|boolean',
            'ruta_backup' => 'nullable|string|max:500',
            'intervalo_backup' => 'nullable|integer|min:1|max:30',
            'notificaciones_activas' => 'required|boolean',
            'email_notificaciones' => 'nullable|email|max:255',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_user' => 'nullable|string|max:255',
            'smtp_pass' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:none,tls,ssl'
        ]);

        $response = $this->apiPut('/api/configuracion', $data);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('configuracion.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Realizar backup manual
     */
    public function backupManual()
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost('/api/configuracion/backup-manual');

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('configuracion.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('configuracion.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Limpiar caché
     */
    public function limpiarCache()
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiPost('/api/configuracion/limpiar-cache');

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('configuracion.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->route('configuracion.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Ver logs del sistema
     */
    public function logs()
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet('/api/configuracion/logs');

        if ($this->apiResponseSuccessful($response)) {
            $logs = $this->apiResponseData($response);
            return view('configuracion.logs', compact('logs'));
        }

        return redirect()->route('configuracion.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Exportar configuración
     */
    public function exportar()
    {
        $this->setApiToken(session('api_token'));

        $response = $this->apiGet('/api/configuracion/exportar');

        if ($this->apiResponseSuccessful($response)) {
            $configData = $this->apiResponseData($response);
            return response()->json($configData)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="configuracion-' . date('Y-m-d') . '.json"');
        }

        return redirect()->route('configuracion.index')
            ->with('error', $this->apiResponseMessage($response));
    }

    /**
     * Importar configuración
     */
    public function importar(Request $request)
    {
        $this->setApiToken(session('api_token'));

        $data = $request->validate([
            'config_file' => 'required|file|mimes:json|max:10240'
        ]);

        $file = $request->file('config_file');
        $configData = json_decode($file->get(), true);

        $response = $this->apiPost('/api/configuracion/importar', $configData);

        if ($this->apiResponseSuccessful($response)) {
            return redirect()->route('configuracion.index')
                ->with('success', $this->apiResponseMessage($response));
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $this->apiResponseMessage($response));
    }
}