<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class CheckUserPermission
{
    /**
     * Permisos por rol
     */
    private $rolePermissions = [
        'Administrador' => ['all'],
        'Supervisor' => [
            'instalaciones.manage', 'tanques.manage', 'medidores.manage',
            'dispensarios.manage', 'mangueras.manage', 'infraestructura.view',
            'despliegues.view', 'volumenes.view', 'bitacora.register',
            'alarmas.manage', 'alarmas.view',
            'reportes.generate', 'reportes.view', 'reportes.print', 'reportes.sign',
            'bitacora.view', 'bitacora.export',
            'contribuyentes.manage', 'productos.manage', 'catalogos.view',
            'existencias.view', 'existencias.validate',
            'registros.view', 'registros.manage'
        ],
        'Operador' => [
            'infraestructura.view', 'despliegues.view', 'volumenes.view',
            'bitacora.register', 'alarmas.view',
            'reportes.view',
            'bitacora.view',
            'catalogos.view',
            'existencias.view',
            'registros.view'
        ],
        'Auditor Fiscal' => [
            'infraestructura.view', 'despliegues.view', 'volumenes.view',
            'alarmas.view',
            'reportes.generate', 'reportes.view', 'reportes.print', 'reportes.sign',
            'bitacora.view',
            'catalogos.view',
            'existencias.view',
            'registros.view'
        ]
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  El permiso requerido (slug)
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Verificar si el usuario está autenticado
        if (!Session::has('api_token')) {
            return redirect()->route('login')
                ->with('error', 'Por favor, inicia sesión.');
        }

        // Obtener roles del usuario desde la sesión
        $userRoles = Session::get('user_roles', []);

        if (empty($userRoles)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tiene roles asignados.');
        }

        // Verificar permisos
        foreach ($userRoles as $role) {
            // Si tiene permiso 'all' (Administrador), permitir acceso
            if (isset($this->rolePermissions[$role]) && in_array('all', $this->rolePermissions[$role])) {
                return $next($request);
            }

            // Verificar si tiene el permiso específico
            if (isset($this->rolePermissions[$role]) && in_array($permission, $this->rolePermissions[$role])) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard')
            ->with('error', 'No tiene permisos para acceder a esta sección.');
    }

    /**
     * Verificar si un usuario tiene un permiso específico
     */
    public static function hasPermission(string $permission): bool
    {
        $userRoles = Session::get('user_roles', []);
        $instance = new self();

        foreach ($userRoles as $role) {
            if (isset($instance->rolePermissions[$role])) {
                if (in_array('all', $instance->rolePermissions[$role]) || 
                    in_array($permission, $instance->rolePermissions[$role])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Obtener todos los permisos del usuario actual
     */
    public static function getUserPermissions(): array
    {
        $userRoles = Session::get('user_roles', []);
        $instance = new self();
        $permissions = [];

        foreach ($userRoles as $role) {
            if (isset($instance->rolePermissions[$role])) {
                $permissions = array_merge($permissions, $instance->rolePermissions[$role]);
            }
        }

        return array_unique($permissions);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function isAdmin(): bool
    {
        $userRoles = Session::get('user_roles', []);
        return in_array('Administrador', $userRoles);
    }

    /**
     * Verificar si el usuario es supervisor o superior
     */
    public static function isSupervisorOrHigher(): bool
    {
        $userRoles = Session::get('user_roles', []);
        return in_array('Administrador', $userRoles) || in_array('Supervisor', $userRoles);
    }
}
