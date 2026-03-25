<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'El campo :attribute debe ser aceptado.',
    'accepted_if'          => 'El campo :attribute debe ser aceptado cuando :other es :value.',
    'active_url'           => 'El campo :attribute no es una URL válida.',
    'after'                => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal'       => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha'                => 'El campo :attribute solo puede contener letras.',
    'alpha_dash'           => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num'            => 'El campo :attribute solo puede contener letras y números.',
    'array'                => 'El campo :attribute debe ser un arreglo.',
    'ascii'                => 'El campo :attribute solo puede contener caracteres alfanuméricos y símbolos de un solo byte.',
    'before'               => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal'      => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between'              => [
        'array'   => 'El campo :attribute debe tener entre :min y :max elementos.',
        'file'    => 'El campo :attribute debe tener entre :min y :max kilobytes.',
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'string'  => 'El campo :attribute debe tener entre :min y :max caracteres.',
    ],
    'boolean'              => 'El campo :attribute debe ser verdadero o falso.',
    'can'                  => 'El campo :attribute contiene un valor no autorizado.',
    'confirmed'            => 'La confirmación del campo :attribute no coincide.',
    'current_password'     => 'La contraseña es incorrecta.',
    'date'                 => 'El campo :attribute no es una fecha válida.',
    'date_equals'          => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format'          => 'El campo :attribute no coincide con el formato :format.',
    'decimal'              => 'El campo :attribute debe tener :decimal decimales.',
    'declined'             => 'El campo :attribute debe ser rechazado.',
    'declined_if'          => 'El campo :attribute debe ser rechazado cuando :other es :value.',
    'different'            => 'Los campos :attribute y :other deben ser diferentes.',
    'digits'               => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between'       => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions'           => 'El campo :attribute tiene dimensiones de imagen inválidas.',
    'distinct'             => 'El campo :attribute tiene un valor duplicado.',
    'doesnt_end_with'      => 'El campo :attribute no puede terminar con: :values.',
    'doesnt_start_with'    => 'El campo :attribute no puede comenzar con: :values.',
    'email'                => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'ends_with'            => 'El campo :attribute debe terminar con uno de los siguientes valores: :values.',
    'enum'                 => 'El campo :attribute seleccionado no es válido.',
    'exists'               => 'El campo :attribute seleccionado no es válido.',
    'file'                 => 'El campo :attribute debe ser un archivo.',
    'filled'               => 'El campo :attribute debe tener un valor.',
    'gt'                   => [
        'array'   => 'El campo :attribute debe tener más de :value elementos.',
        'file'    => 'El campo :attribute debe ser mayor que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'string'  => 'El campo :attribute debe tener más de :value caracteres.',
    ],
    'gte'                  => [
        'array'   => 'El campo :attribute debe tener :value elementos o más.',
        'file'    => 'El campo :attribute debe ser mayor o igual que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser mayor o igual que :value.',
        'string'  => 'El campo :attribute debe ser mayor o igual que :value caracteres.',
    ],
    'hex_color'            => 'El campo :attribute debe ser un color hexadecimal válido.',
    'image'                => 'El campo :attribute debe ser una imagen.',
    'in'                   => 'El campo :attribute seleccionado no es válido.',
    'in_array'             => 'El campo :attribute no existe en :other.',
    'integer'              => 'El campo :attribute debe ser un número entero.',
    'ip'                   => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4'                 => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6'                 => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json'                 => 'El campo :attribute debe ser una cadena JSON válida.',
    'lowercase'            => 'El campo :attribute debe estar en minúsculas.',
    'lt'                   => [
        'array'   => 'El campo :attribute debe tener menos de :value elementos.',
        'file'    => 'El campo :attribute debe ser menor que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'string'  => 'El campo :attribute debe tener menos de :value caracteres.',
    ],
    'lte'                  => [
        'array'   => 'El campo :attribute no debe tener más de :value elementos.',
        'file'    => 'El campo :attribute debe ser menor o igual que :value kilobytes.',
        'numeric' => 'El campo :attribute debe ser menor o igual que :value.',
        'string'  => 'El campo :attribute debe ser menor o igual que :value caracteres.',
    ],
    'mac_address'          => 'El campo :attribute debe ser una dirección MAC válida.',
    'max'                  => [
        'array'   => 'El campo :attribute no debe tener más de :max elementos.',
        'file'    => 'El campo :attribute no debe ser mayor que :max kilobytes.',
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string'  => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'max_digits'           => 'El campo :attribute no debe tener más de :max dígitos.',
    'mimes'                => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'mimetypes'            => 'El campo :attribute debe ser un archivo de tipo: :values.',
    'min'                  => [
        'array'   => 'El campo :attribute debe tener al menos :min elementos.',
        'file'    => 'El campo :attribute debe tener al menos :min kilobytes.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'min_digits'           => 'El campo :attribute debe tener al menos :min dígitos.',
    'missing'              => 'El campo :attribute debe estar ausente.',
    'missing_if'           => 'El campo :attribute debe estar ausente cuando :other es :value.',
    'missing_unless'       => 'El campo :attribute debe estar ausente a menos que :other sea :value.',
    'missing_with'         => 'El campo :attribute debe estar ausente cuando :values está presente.',
    'missing_with_all'     => 'El campo :attribute debe estar ausente cuando :values están presentes.',
    'multiple_of'          => 'El campo :attribute debe ser un múltiplo de :value.',
    'not_in'               => 'El campo :attribute seleccionado no es válido.',
    'not_regex'            => 'El formato del campo :attribute no es válido.',
    'numeric'              => 'El campo :attribute debe ser un número.',
    'password'             => [
        'letters'       => 'El campo :attribute debe contener al menos una letra.',
        'mixed'         => 'El campo :attribute debe contener al menos una letra mayúscula y una minúscula.',
        'numbers'       => 'El campo :attribute debe contener al menos un número.',
        'symbols'       => 'El campo :attribute debe contener al menos un símbolo.',
        'uncompromised' => 'El :attribute proporcionado ha aparecido en una filtración de datos. Por favor, elija un :attribute diferente.',
    ],
    'present'              => 'El campo :attribute debe estar presente.',
    'present_if'           => 'El campo :attribute debe estar presente cuando :other es :value.',
    'present_unless'       => 'El campo :attribute debe estar presente a menos que :other sea :value.',
    'present_with'         => 'El campo :attribute debe estar presente cuando :values está presente.',
    'present_with_all'     => 'El campo :attribute debe estar presente cuando :values están presentes.',
    'prohibited'           => 'El campo :attribute está prohibido.',
    'prohibited_if'        => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_unless'    => 'El campo :attribute está prohibido a menos que :other esté en :values.',
    'prohibits'            => 'El campo :attribute prohíbe que :other esté presente.',
    'regex'                => 'El formato del campo :attribute no es válido.',
    'required'             => 'El campo :attribute es obligatorio.',
    'required_array_keys'  => 'El campo :attribute debe contener entradas para: :values.',
    'required_if'          => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_if_accepted' => 'El campo :attribute es obligatorio cuando :other es aceptado.',
    'required_unless'      => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with'        => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all'    => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without'     => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de :values está presente.',
    'same'                 => 'Los campos :attribute y :other deben coincidir.',
    'size'                 => [
        'array'   => 'El campo :attribute debe contener :size elementos.',
        'file'    => 'El campo :attribute debe tener :size kilobytes.',
        'numeric' => 'El campo :attribute debe ser :size.',
        'string'  => 'El campo :attribute debe tener :size caracteres.',
    ],
    'starts_with'          => 'El campo :attribute debe comenzar con uno de los siguientes valores: :values.',
    'string'               => 'El campo :attribute debe ser una cadena de texto.',
    'timezone'             => 'El campo :attribute debe ser una zona horaria válida.',
    'unique'               => 'El campo :attribute ya ha sido tomado.',
    'uploaded'             => 'El campo :attribute no se pudo subir.',
    'uppercase'            => 'El campo :attribute debe estar en mayúsculas.',
    'url'                  => 'El campo :attribute debe ser una URL válida.',
    'ulid'                 => 'El campo :attribute debe ser un ULID válido.',
    'uuid'                 => 'El campo :attribute debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // Contribuyente
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
        'activo' => 'activo',
        'estatus_verificacion' => 'estatus de verificación',

        // Instalación
        'nombre' => 'nombre',
        'direccion' => 'dirección',
        'latitud' => 'latitud',
        'longitud' => 'longitud',
        'tipo' => 'tipo',
        'capacidad_total' => 'capacidad total',
        'fecha_inicio_operaciones' => 'fecha de inicio de operaciones',
        'numero_registro_ambiental' => 'número de registro ambiental',
        'contribuyente_id' => 'contribuyente',

        // Tanque
        'instalacion_id' => 'instalación',
        'producto_id' => 'producto',
        'capacidad' => 'capacidad',
        'capacidad_util' => 'capacidad útil',
        'numero_serie' => 'número de serie',
        'fecha_fabricacion' => 'fecha de fabricación',
        'fecha_ultima_prueba' => 'fecha de última prueba',
        'estatus' => 'estatus',

        // Medidor
        'tanque_id' => 'tanque',
        'marca' => 'marca',
        'modelo' => 'modelo',
        'numero_medidor' => 'número de medidor',
        'fecha_instalacion' => 'fecha de instalación',
        'fecha_calibracion' => 'fecha de calibración',
        'precision' => 'precisión',

        // Producto
        'descripcion' => 'descripción',
        'clave_producto' => 'clave de producto',
        'unidad_medida' => 'unidad de medida',
        'densidad' => 'densidad',
        'precio_referencia' => 'precio de referencia',

        // Usuario
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'nombre_completo' => 'nombre completo',
        'rol' => 'rol',

        // Alarma
        'tipo_alarma' => 'tipo de alarma',
        'nivel_prioridad' => 'nivel de prioridad',
        'mensaje' => 'mensaje',
        'fecha_alarma' => 'fecha de alarma',

        // Registro Volumétrico
        'volumen_inicial' => 'volumen inicial',
        'volumen_final' => 'volumen final',
        'volumen_disponible' => 'volumen disponible',
        'temperatura' => 'temperatura',
        'presion' => 'presión',
        'fecha_medicion' => 'fecha de medición',

        // CFDI
        'folio' => 'folio',
        'serie' => 'serie',
        'fecha_emision' => 'fecha de emisión',
        'subtotal' => 'subtotal',
        'iva' => 'IVA',
        'total' => 'total',
        'uuid' => 'UUID',

        // Dictamen
        'numero_dictamen' => 'número de dictamen',
        'fecha_dictamen' => 'fecha de dictamen',
        'resultado' => 'resultado',
        'observaciones' => 'observaciones',

        // Bitácora
        'evento' => 'evento',
        'modulo' => 'módulo',
        'descripcion_evento' => 'descripción del evento',
        'fecha_evento' => 'fecha del evento',

        // Permisos y Roles
        'permiso' => 'permiso',
        'role_name' => 'nombre del rol',
        'permission_name' => 'nombre del permiso',

        // Certificado de Verificación
        'folio' => 'folio',
        'contribuyente_id' => 'contribuyente',
        'proveedor_rfc' => 'RFC del proveedor',
        'proveedor_nombre' => 'nombre del proveedor',
        'fecha_emision' => 'fecha de emisión',
        'fecha_inicio_verificacion' => 'fecha de inicio de verificación',
        'fecha_fin_verificacion' => 'fecha de fin de verificación',
        'resultado' => 'resultado',
        'tabla_cumplimiento' => 'tabla de cumplimiento',
        'vigente' => 'vigente',
        'fecha_caducidad' => 'fecha de caducidad',
        'requiere_verificacion_extraordinaria' => 'requiere verificación extraordinaria',
        
        // Genéricos
        'id' => 'ID',
        'created_at' => 'fecha de creación',
        'updated_at' => 'fecha de actualización',
        'deleted_at' => 'fecha de eliminación',
    ],

];