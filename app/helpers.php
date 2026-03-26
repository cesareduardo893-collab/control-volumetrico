<?php

use Illuminate\Support\Facades\Session;

if (!function_exists('hasPermission')) {
    /**
     * Verificar si el usuario actual tiene un permiso específico
     */
    function hasPermission(string $permission): bool
    {
        $userRoles = Session::get('user_roles', []);
        
        $rolePermissions = [
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

        foreach ($userRoles as $role) {
            if (isset($rolePermissions[$role])) {
                if (in_array('all', $rolePermissions[$role]) || in_array($permission, $rolePermissions[$role])) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('isAdmin')) {
    /**
     * Verificar si el usuario es administrador
     */
    function isAdmin(): bool
    {
        $userRoles = Session::get('user_roles', []);
        return in_array('Administrador', $userRoles);
    }
}

if (!function_exists('isSupervisorOrHigher')) {
    /**
     * Verificar si el usuario es supervisor o superior
     */
    function isSupervisorOrHigher(): bool
    {
        $userRoles = Session::get('user_roles', []);
        return in_array('Administrador', $userRoles) || in_array('Supervisor', $userRoles);
    }
}

if (!function_exists('currentUserRoles')) {
    /**
     * Obtener los roles del usuario actual
     */
    function currentUserRoles(): array
    {
        return Session::get('user_roles', []);
    }
}

if (!function_exists('currentUserPermissions')) {
    /**
     * Obtener todos los permisos del usuario actual
     */
    function currentUserPermissions(): array
    {
        $userRoles = Session::get('user_roles', []);
        $permissions = [];
        
        $rolePermissions = [
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

        foreach ($userRoles as $role) {
            if (isset($rolePermissions[$role])) {
                $permissions = array_merge($permissions, $rolePermissions[$role]);
            }
        }

        return array_unique($permissions);
    }
}

if (!function_exists('canManageUsers')) {
    /**
     * Verificar si el usuario puede gestionar usuarios
     */
    function canManageUsers(): bool
    {
        return isAdmin();
    }
}

if (!function_exists('canManageInfrastructure')) {
    /**
     * Verificar si el usuario puede gestionar infraestructura
     */
    function canManageInfrastructure(): bool
    {
        return hasPermission('instalaciones.manage');
    }
}
