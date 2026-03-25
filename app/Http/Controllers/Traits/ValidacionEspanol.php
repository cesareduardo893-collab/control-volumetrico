<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ValidacionEspanol
{
    /**
     * Validar datos con mensajes personalizados en español
     */
    protected function validar(Request $request, array $reglas, array $mensajesPersonalizados = [])
    {
        $mensajes = array_merge($this->mensajesValidacionEspanol(), $mensajesPersonalizados);
        
        $validator = Validator::make($request->all(), $reglas, $mensajes);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
        
        return null;
    }

    /**
     * Validar datos para API con mensajes personalizados en español
     */
    protected function validarParaApi(Request $request, array $reglas, array $mensajesPersonalizados = [])
    {
        $mensajes = array_merge($this->mensajesValidacionEspanol(), $mensajesPersonalizados);
        
        $validator = Validator::make($request->all(), $reglas, $mensajes);
        
        if ($validator->fails()) {
            return [
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()->toArray()
            ];
        }
        
        return null;
    }

    /**
     * Mensajes de validación en español
     */
    protected function mensajesValidacionEspanol()
    {
        return [
            // Mensajes requeridos
            'required' => 'El campo :attribute es obligatorio.',
            'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
            'required_unless' => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
            'required_with' => 'El campo :attribute es obligatorio cuando :values está presente.',
            'required_with_all' => 'El campo :attribute es obligatorio cuando :values están presentes.',
            'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente.',
            'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values está presente.',
            
            // Mensajes de tipo
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'boolean' => 'El campo :attribute debe ser verdadero o falso.',
            'array' => 'El campo :attribute debe ser un arreglo.',
            'date' => 'El campo :attribute no es una fecha válida.',
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
            'file' => 'El campo :attribute debe ser un archivo.',
            'image' => 'El campo :attribute debe ser una imagen.',
            
            // Mensajes de tamaño
            'min' => [
                'numeric' => 'El campo :attribute debe ser al menos :min.',
                'file' => 'El campo :attribute debe tener al menos :min kilobytes.',
                'string' => 'El campo :attribute debe tener al menos :min caracteres.',
                'array' => 'El campo :attribute debe tener al menos :min elementos.',
            ],
            'max' => [
                'numeric' => 'El campo :attribute no debe ser mayor que :max.',
                'file' => 'El campo :attribute no debe ser mayor que :max kilobytes.',
                'string' => 'El campo :attribute no debe tener más de :max caracteres.',
                'array' => 'El campo :attribute no debe tener más de :max elementos.',
            ],
            'size' => [
                'numeric' => 'El campo :attribute debe ser :size.',
                'file' => 'El campo :attribute debe tener :size kilobytes.',
                'string' => 'El campo :attribute debe tener :size caracteres.',
                'array' => 'El campo :attribute debe contener :size elementos.',
            ],
            'between' => [
                'numeric' => 'El campo :attribute debe estar entre :min y :max.',
                'file' => 'El campo :attribute debe tener entre :min y :max kilobytes.',
                'string' => 'El campo :attribute debe tener entre :min y :max caracteres.',
                'array' => 'El campo :attribute debe tener entre :min y :max elementos.',
            ],
            'digits' => 'El campo :attribute debe tener :digits dígitos.',
            'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos.',
            
            // Mensajes de formato
            'alpha' => 'El campo :attribute solo puede contener letras.',
            'alpha_num' => 'El campo :attribute solo puede contener letras y números.',
            'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
            'url' => 'El campo :attribute debe ser una URL válida.',
            'uuid' => 'El campo :attribute debe ser un UUID válido.',
            
            // Mensajes de comparación
            'confirmed' => 'La confirmación del campo :attribute no coincide.',
            'different' => 'Los campos :attribute y :other deben ser diferentes.',
            'same' => 'Los campos :attribute y :other deben coincidir.',
            
            // Mensajes de valores
            'in' => 'El campo :attribute seleccionado no es válido.',
            'not_in' => 'El campo :attribute seleccionado no es válido.',
            'exists' => 'El campo :attribute seleccionado no es válido.',
            'unique' => 'El campo :attribute ya ha sido tomado.',
            
            // Mensajes de fecha
            'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
            'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
            'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
            'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
            
            // Mensajes de archivo
            'mimes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
            'mimetypes' => 'El campo :attribute debe ser un archivo de tipo: :values.',
            
            // Mensajes personalizados para el sistema
            'rfc_format' => 'El campo :attribute debe tener un formato RFC válido.',
            'codigo_postal_format' => 'El campo :attribute debe ser un código postal válido de 5 dígitos.',
            'telefono_format' => 'El campo :attribute debe ser un número de teléfono válido.',
            'capacidad_positiva' => 'El campo :attribute debe ser un valor positivo.',
            'fecha_futura' => 'El campo :attribute debe ser una fecha futura.',
            'fecha_pasada' => 'El campo :attribute debe ser una fecha pasada.',
            'rango_capacidad' => 'La capacidad útil no puede ser mayor a la capacidad total.',
            'password_segura' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial.',
        ];
    }

    /**
     * Reglas de validación comunes para contribuyentes
     */
    protected function reglasContribuyente($esEdicion = false)
    {
        $reglas = [
            'rfc' => ($esEdicion ? 'sometimes' : 'required') . '|string|size:13',
            'razon_social' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'regimen_fiscal' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'domicilio_fiscal' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'codigo_postal' => ($esEdicion ? 'sometimes' : 'required') . '|string|size:5',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'representante_legal' => 'nullable|string|max:255',
            'representante_rfc' => 'nullable|string|size:13',
            'numero_permiso' => 'nullable|string|max:255',
            'tipo_permiso' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean',
        ];

        if ($esEdicion) {
            $reglas['estatus_verificacion'] = 'nullable|string|max:50';
        }

        return $reglas;
    }

    /**
     * Reglas de validación comunes para instalaciones
     */
    protected function reglasInstalacion($esEdicion = false)
    {
        return [
            'contribuyente_id' => ($esEdicion ? 'sometimes' : 'required') . '|integer',
            'clave_instalacion' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'nombre' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'tipo_instalacion' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'domicilio' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'codigo_postal' => ($esEdicion ? 'sometimes' : 'required') . '|string|size:5',
            'municipio' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'estado' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'estatus' => ($esEdicion ? 'sometimes' : 'required') . '|in:OPERACION,SUSPENDIDA,CANCELADA',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'fecha_apertura' => 'nullable|date',
            'fecha_cierre' => 'nullable|date',
            'activo' => 'sometimes|boolean',
        ];
    }

    /**
     * Reglas de validación comunes para tanques
     */
    protected function reglasTanque($esEdicion = false)
    {
        return [
            'instalacion_id' => ($esEdicion ? 'sometimes' : 'required') . '|integer',
            'identificador' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'material' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:100',
            'capacidad_total' => ($esEdicion ? 'sometimes' : 'required') . '|numeric|min:0',
            'capacidad_util' => ($esEdicion ? 'sometimes' : 'required') . '|numeric|min:0|lte:capacidad_total',
            'capacidad_operativa' => ($esEdicion ? 'sometimes' : 'required') . '|numeric|min:0|lte:capacidad_util',
            'capacidad_minima' => ($esEdicion ? 'sometimes' : 'required') . '|numeric|min:0',
            'temperatura_referencia' => ($esEdicion ? 'sometimes' : 'required') . '|numeric',
            'presion_referencia' => ($esEdicion ? 'sometimes' : 'required') . '|numeric',
            'tipo_medicion' => ($esEdicion ? 'sometimes' : 'required') . '|in:estatica,dinamica',
            'estado' => ($esEdicion ? 'sometimes' : 'required') . '|in:OPERATIVO,MANTENIMIENTO,FUERA_SERVICIO,CALIBRACION',
            'producto_id' => 'nullable|integer',
            'activo' => 'sometimes|boolean',
        ];
    }

    /**
     * Reglas de validación comunes para medidores
     */
    protected function reglasMedidor($esEdicion = false)
    {
        return [
            'tanque_id' => 'nullable|integer',
            'instalacion_id' => ($esEdicion ? 'sometimes' : 'required') . '|integer',
            'numero_serie' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'clave' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'fabricante' => 'nullable|string|max:255',
            'elemento_tipo' => ($esEdicion ? 'sometimes' : 'required') . '|in:primario,secundario,terciario',
            'tipo_medicion' => ($esEdicion ? 'sometimes' : 'required') . '|in:estatica,dinamica',
            'precision' => ($esEdicion ? 'sometimes' : 'required') . '|numeric|min:0',
            'capacidad_maxima' => ($esEdicion ? 'sometimes' : 'required') . '|numeric|min:0',
            'estado' => ($esEdicion ? 'sometimes' : 'required') . '|in:OPERATIVO,CALIBRACION,MANTENIMIENTO,FUERA_SERVICIO,FALLA_COMUNICACION',
            'tecnologia_id' => 'nullable|string|max:255',
            'protocolo_comunicacion' => 'nullable|string|max:255',
            'presion_maxima' => 'nullable|numeric|min:0',
            'temperatura_maxima' => 'nullable|numeric',
            'fecha_instalacion' => 'nullable|date',
            'fecha_ultima_calibracion' => 'nullable|date',
            'fecha_proxima_calibracion' => 'nullable|date',
            'certificado_calibracion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'activo' => 'sometimes|boolean',
        ];
    }

    /**
     * Reglas de validación comunes para productos
     */
    protected function reglasProducto($esEdicion = false)
    {
        return [
            'clave_sat' => ($esEdicion ? 'sometimes' : 'required') . '|string|size:10',
            'codigo' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:20',
            'clave_identificacion' => ($esEdicion ? 'sometimes' : 'required') . '|string|size:10',
            'nombre' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'descripcion' => 'nullable|string',
            'unidad_medida' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:50',
            'tipo_hidrocarburo' => ($esEdicion ? 'sometimes' : 'required') . '|in:petroleo,gas_natural,condensados,gasolina,diesel,turbosina,gas_lp,propano,otro',
            'activo' => 'sometimes|boolean',
            'densidad_referencia' => 'nullable|numeric|min:0',
            'temperatura_referencia' => 'nullable|numeric',
            'factor_conversion' => 'nullable|numeric|min:0',
            'octanaje' => 'nullable|numeric',
            'numero_octano' => 'nullable|numeric',
        ];
    }

    /**
     * Reglas de validación comunes para usuarios
     */
    protected function reglasUsuario($esEdicion = false)
    {
        $reglas = [
            'identificacion' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:18',
            'nombres' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'apellidos' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'email' => ($esEdicion ? 'sometimes' : 'required') . '|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'activo' => 'sometimes|boolean',
        ];

        if (!$esEdicion) {
            $reglas['password'] = 'required|min:8|confirmed';
            $reglas['roles'] = 'required|array|min:1';
            $reglas['roles.*'] = 'integer';
        } else {
            $reglas['roles'] = 'sometimes|array';
            $reglas['roles.*'] = 'integer';
        }

        return $reglas;
    }

    /**
     * Reglas de validación comunes para roles
     */
    protected function reglasRol($esEdicion = false)
    {
        return [
            'nombre' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'nivel_jerarquico' => ($esEdicion ? 'sometimes' : 'required') . '|integer|min:1|max:100',
            'es_administrador' => 'sometimes|boolean',
            'permisos' => 'nullable|array',
            'permisos.*' => 'integer',
            'activo' => 'sometimes|boolean',
        ];
    }

    /**
     * Reglas de validación comunes para alarmas
     */
    protected function reglasAlarma($esEdicion = false)
    {
        return [
            'numero_registro' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'fecha_hora' => ($esEdicion ? 'sometimes' : 'required') . '|date',
            'componente_tipo' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'componente_id' => ($esEdicion ? 'sometimes' : 'required') . '|integer',
            'componente_identificador' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'tipo_alarma_id' => ($esEdicion ? 'sometimes' : 'required') . '|integer',
            'gravedad' => ($esEdicion ? 'sometimes' : 'required') . '|in:BAJA,MEDIA,ALTA,CRITICA',
            'descripcion' => ($esEdicion ? 'sometimes' : 'required') . '|string',
            'estado_atencion' => ($esEdicion ? 'sometimes' : 'required') . '|in:PENDIENTE,EN_PROCESO,RESUELTA,IGNORADA',
            'requiere_atencion_inmediata' => 'nullable|boolean',
        ];
    }

    /**
     * Reglas de validación comunes para certificados de verificación
     */
    protected function reglasCertificadoVerificacion($esEdicion = false)
    {
        $reglas = [
            'folio' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'contribuyente_id' => ($esEdicion ? 'sometimes' : 'required') . '|integer',
            'proveedor_rfc' => ($esEdicion ? 'sometimes' : 'required') . '|string|size:13',
            'proveedor_nombre' => ($esEdicion ? 'sometimes' : 'required') . '|string|max:255',
            'fecha_emision' => ($esEdicion ? 'sometimes' : 'required') . '|date',
            'fecha_inicio_verificacion' => ($esEdicion ? 'sometimes' : 'required') . '|date|after_or_equal:fecha_emision',
            'fecha_fin_verificacion' => ($esEdicion ? 'sometimes' : 'required') . '|date|after_or_equal:fecha_inicio_verificacion',
            'resultado' => ($esEdicion ? 'sometimes' : 'required') . '|in:acreditado,no_acreditado',
            'tabla_cumplimiento' => ($esEdicion ? 'sometimes' : 'required') . '|array',
            'observaciones' => 'nullable|string',
            'vigente' => 'sometimes|boolean',
            'fecha_caducidad' => 'nullable|date',
            'requiere_verificacion_extraordinaria' => 'sometimes|boolean',
        ];

        return $reglas;
    }

    /**
     * Mensajes personalizados para campos específicos
     */
    protected function atributosPersonalizados()
    {
        return [
            'rfc' => 'RFC',
            'razon_social' => 'razón social',
            'nombre_comercial' => 'nombre comercial',
            'regimen_fiscal' => 'régimen fiscal',
            'domicilio_fiscal' => 'domicilio fiscal',
            'codigo_postal' => 'código postal',
            'telefono' => 'teléfono',
            'email' => 'correo electrónico',
            'representante_legal' => 'representante legal',
            'representante_rfc' => 'RFC del representante',
            'numero_permiso' => 'número de permiso',
            'tipo_permiso' => 'tipo de permiso',
            'contribuyente_id' => 'contribuyente',
            'instalacion_id' => 'instalación',
            'clave_instalacion' => 'clave de instalación',
            'tipo_instalacion' => 'tipo de instalación',
            'domicilio' => 'domicilio',
            'municipio' => 'municipio',
            'fecha_apertura' => 'fecha de apertura',
            'fecha_cierre' => 'fecha de cierre',
            'identificador' => 'identificador',
            'capacidad_total' => 'capacidad total',
            'capacidad_util' => 'capacidad útil',
            'capacidad_operativa' => 'capacidad operativa',
            'capacidad_minima' => 'capacidad mínima',
            'temperatura_referencia' => 'temperatura de referencia',
            'presion_referencia' => 'presión de referencia',
            'tipo_medicion' => 'tipo de medición',
            'producto_id' => 'producto',
            'tanque_id' => 'tanque',
            'numero_serie' => 'número de serie',
            'elemento_tipo' => 'tipo de elemento',
            'precision' => 'precisión',
            'capacidad_maxima' => 'capacidad máxima',
            'tecnologia_id' => 'tecnología',
            'protocolo_comunicacion' => 'protocolo de comunicación',
            'presion_maxima' => 'presión máxima',
            'temperatura_maxima' => 'temperatura máxima',
            'fecha_instalacion' => 'fecha de instalación',
            'fecha_ultima_calibracion' => 'fecha de última calibración',
            'fecha_proxima_calibracion' => 'fecha de próxima calibración',
            'certificado_calibracion' => 'certificado de calibración',
            'clave_sat' => 'clave SAT',
            'clave_identificacion' => 'clave de identificación',
            'unidad_medida' => 'unidad de medida',
            'tipo_hidrocarburo' => 'tipo de hidrocarburo',
            'densidad_referencia' => 'densidad de referencia',
            'factor_conversion' => 'factor de conversión',
            'identificacion' => 'identificación',
            'nombres' => 'nombres',
            'apellidos' => 'apellidos',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'password_actual' => 'contraseña actual',
            'roles' => 'roles',
            'nivel_jerarquico' => 'nivel jerárquico',
            'es_administrador' => 'es administrador',
            'permisos' => 'permisos',
            'numero_registro' => 'número de registro',
            'fecha_hora' => 'fecha y hora',
            'componente_tipo' => 'tipo de componente',
            'componente_id' => 'componente',
            'componente_identificador' => 'identificador de componente',
            'tipo_alarma_id' => 'tipo de alarma',
            'gravedad' => 'gravedad',
            'estado_atencion' => 'estado de atención',
            'requiere_atencion_inmediata' => 'requiere atención inmediata',
        ];
    }
}