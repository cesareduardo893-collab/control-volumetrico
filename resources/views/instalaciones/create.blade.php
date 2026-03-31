@extends('layouts.app')

@section('title', 'Nueva Estación de Servicio')
@section('header', 'Registrar Nueva Estación de Servicio')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #006847 0%, #004E98 100%);">
                <h5 class="card-title mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Información de la Estación</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('instalaciones.store') }}">
                    @csrf
                    <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud') }}">
                    <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud') }}">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contribuyente_id" class="form-label">ID Contribuyente *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="contribuyente_id" name="contribuyente_id" 
                                       value="{{ old('contribuyente_id') }}" required min="1"
                                       placeholder="Ingrese el ID del contribuyente">
                                <a href="{{ route('contribuyentes.create') }}" class="btn btn-outline-primary" target="_blank" title="Crear nuevo contribuyente">
                                    <i class="bi bi-plus-circle"></i>
                                </a>
                            </div>
                            <small class="text-muted">Ingrese el ID del contribuyente o <a href="{{ route('contribuyentes.index') }}" target="_blank">busque aquí</a></small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="clave_instalacion" class="form-label">Clave de Instalación *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="clave_instalacion" name="clave_instalacion" 
                                       value="{{ old('clave_instalacion') }}" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="generarClaveAleatoria()" title="Generar clave">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre de la Instalación *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre') }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tipo_instalacion" class="form-label">Tipo de Instalación *</label>
                            <select class="form-select" id="tipo_instalacion" name="tipo_instalacion" required>
                                <option value="">Seleccione...</option>
                                <option value="estacion_servicio" {{ old('tipo_instalacion') == 'estacion_servicio' ? 'selected' : '' }}>Estación de Servicio</option>
                                <option value="terminal" {{ old('tipo_instalacion') == 'terminal' ? 'selected' : '' }}>Terminal</option>
                                <option value="planta" {{ old('tipo_instalacion') == 'planta' ? 'selected' : '' }}>Planta</option>
                                <option value="almacen" {{ old('tipo_instalacion') == 'almacen' ? 'selected' : '' }}>Almacén</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="domicilio" class="form-label">Domicilio *</label>
                        <input type="text" class="form-control" id="domicilio" name="domicilio"
                               value="{{ old('domicilio') }}" required
                               placeholder="Escriba la calle, número, colonia...">
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="bi bi-geo-alt me-2"></i>Ubicación
                    </h6>
                    
                    <!-- Mapa de Google -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <i class="bi bi-map me-2"></i>Seleccionar ubicación en el mapa
                            <small class="text-muted ms-2">(Haga clic en el mapa)</small>
                        </div>
                        <div class="card-body p-0">
                            <div id="map" style="height: 300px; width: 100%;"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal *</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                   value="{{ old('codigo_postal') }}" maxlength="5" required 
                                   placeholder="00000" pattern="[0-9]{5}">
                            <small class="text-muted">Ingrese 5 dígitos</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="municipio" class="form-label">Municipio/Delegación *</label>
                            <select class="form-select" id="municipio" name="municipio" required>
                                <option value="">Seleccione...</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select select2" id="estado" name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="AGUASCALIENTES" {{ old('estado') == 'AGUASCALIENTES' ? 'selected' : '' }}>Aguascalientes</option>
                                <option value="BAJA CALIFORNIA" {{ old('estado') == 'BAJA CALIFORNIA' ? 'selected' : '' }}>Baja California</option>
                                <option value="BAJA CALIFORNIA SUR" {{ old('estado') == 'BAJA CALIFORNIA SUR' ? 'selected' : '' }}>Baja California Sur</option>
                                <option value="CAMPECHE" {{ old('estado') == 'CAMPECHE' ? 'selected' : '' }}>Campeche</option>
                                <option value="CHIAPAS" {{ old('estado') == 'CHIAPAS' ? 'selected' : '' }}>Chiapas</option>
                                <option value="CHIHUAHUA" {{ old('estado') == 'CHIHUAHUA' ? 'selected' : '' }}>Chihuahua</option>
                                <option value="CIUDAD DE MÉXICO" {{ old('estado') == 'CIUDAD DE MÉXICO' ? 'selected' : '' }}>Ciudad de México</option>
                                <option value="COAHUILA" {{ old('estado') == 'COAHUILA' ? 'selected' : '' }}>Coahuila</option>
                                <option value="COLIMA" {{ old('estado') == 'COLIMA' ? 'selected' : '' }}>Colima</option>
                                <option value="DURANGO" {{ old('estado') == 'DURANGO' ? 'selected' : '' }}>Durango</option>
                                <option value="GUANAJUATO" {{ old('estado') == 'GUANAJUATO' ? 'selected' : '' }}>Guanajuato</option>
                                <option value="GUERRERO" {{ old('estado') == 'GUERRERO' ? 'selected' : '' }}>Guerrero</option>
                                <option value="HIDALGO" {{ old('estado') == 'HIDALGO' ? 'selected' : '' }}>Hidalgo</option>
                                <option value="JALISCO" {{ old('estado') == 'JALISCO' ? 'selected' : '' }}>Jalisco</option>
                                <option value="MÉXICO" {{ old('estado', 'MÉXICO') == 'MÉXICO' ? 'selected' : '' }}>Estado de México</option>
                                <option value="MICHOACÁN" {{ old('estado') == 'MICHOACÁN' ? 'selected' : '' }}>Michoacán</option>
                                <option value="MORELOS" {{ old('estado') == 'MORELOS' ? 'selected' : '' }}>Morelos</option>
                                <option value="NAYARIT" {{ old('estado') == 'NAYARIT' ? 'selected' : '' }}>Nayarit</option>
                                <option value="NUEVO LEÓN" {{ old('estado') == 'NUEVO LEÓN' ? 'selected' : '' }}>Nuevo León</option>
                                <option value="OAXACA" {{ old('estado') == 'OAXACA' ? 'selected' : '' }}>Oaxaca</option>
                                <option value="PUEBLA" {{ old('estado') == 'PUEBLA' ? 'selected' : '' }}>Puebla</option>
                                <option value="QUERÉTARO" {{ old('estado') == 'QUERÉTARO' ? 'selected' : '' }}>Querétaro</option>
                                <option value="QUINTANA ROO" {{ old('estado') == 'QUINTANA ROO' ? 'selected' : '' }}>Quintana Roo</option>
                                <option value="SAN LUIS POTOSÍ" {{ old('estado') == 'SAN LUIS POTOSÍ' ? 'selected' : '' }}>San Luis Potosí</option>
                                <option value="SINALOA" {{ old('estado') == 'SINALOA' ? 'selected' : '' }}>Sinaloa</option>
                                <option value="SONORA" {{ old('estado') == 'SONORA' ? 'selected' : '' }}>Sonora</option>
                                <option value="TABASCO" {{ old('estado') == 'TABASCO' ? 'selected' : '' }}>Tabasco</option>
                                <option value="TAMAULIPAS" {{ old('estado') == 'TAMAULIPAS' ? 'selected' : '' }}>Tamaulipas</option>
                                <option value="TLAXCALA" {{ old('estado') == 'TLAXCALA' ? 'selected' : '' }}>Tlaxcala</option>
                                <option value="VERACRUZ" {{ old('estado') == 'VERACRUZ' ? 'selected' : '' }}>Veracruz</option>
                                <option value="YUCATÁN" {{ old('estado') == 'YUCATÁN' ? 'selected' : '' }}>Yucatán</option>
                                <option value="ZACATECAS" {{ old('estado') == 'ZACATECAS' ? 'selected' : '' }}>Zacatecas</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pais" class="form-label">País *</label>
                            <select class="form-select" id="pais" name="pais" required>
                                <option value="MÉXICO" selected>MÉXICO</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="{{ old('telefono') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="estatus" class="form-label">Estatus *</label>
                            <select class="form-select" id="estatus" name="estatus" required>
                                <option value="">Seleccione...</option>
                                <option value="OPERACION" {{ old('estatus', 'OPERACION') == 'OPERACION' ? 'selected' : '' }}>Operación</option>
                                <option value="SUSPENDIDA" {{ old('estatus') == 'SUSPENDIDA' ? 'selected' : '' }}>Suspendida</option>
                                <option value="CANCELADA" {{ old('estatus') == 'CANCELADA' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_apertura" class="form-label">Fecha de Apertura</label>
                            <input type="date" class="form-control datepicker" id="fecha_apertura" 
                                   name="fecha_apertura" value="{{ old('fecha_apertura') }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="fecha_cierre" class="form-label">Fecha de Cierre</label>
                            <input type="date" class="form-control datepicker" id="fecha_cierre" 
                                   name="fecha_cierre" value="{{ old('fecha_cierre') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1"
                                   {{ old('activo', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">Instalación Activa</label>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('instalaciones.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Instalación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Mapa con OpenStreetMap (Leaflet) - No requiere API key
let map;
let marker;
let mapInitialized = false;

function initMap() {
    if (mapInitialized) return;
    
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('Elemento del mapa no encontrado');
        return;
    }
    
    console.log('Inicializando mapa con OpenStreetMap...');
    
    // Coordenadas iniciales (Centro de México)
    const initialPosition = [23.6345, -102.5528];
    
    try {
        // Crear el mapa con Leaflet
        map = L.map('map').setView(initialPosition, 5);
        
        // Agregar capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Crear marcador
        marker = L.marker(initialPosition, { draggable: true }).addTo(map);
        
        // Evento al hacer clic en el mapa
        map.on('click', function(e) {
            console.log('Clic en mapa detectado en:', e.latlng.lat, e.latlng.lng);
            updateMarkerPosition(e.latlng);
        });
        
        // Evento al arrastrar el marcador
        marker.on('dragend', function(e) {
            console.log('Marcador arrastrado a:', e.target.getLatLng().lat, e.target.getLatLng().lng);
            updateMarkerPosition(e.target.getLatLng());
        });
        
        mapInitialized = true;
        console.log('Mapa inicializado correctamente');
        
    } catch (error) {
        console.error('Error al inicializar mapa:', error);
        mapElement.innerHTML = '<div class="alert alert-danger m-3">Error al inicializar el mapa: ' + error.message + '</div>';
    }
}

function updateMarkerPosition(latlng) {
    // Actualizar posición del marcador
    marker.setLatLng(latlng);
    
    // Actualizar campos ocultos de coordenadas
    document.getElementById('latitud').value = latlng.lat;
    document.getElementById('longitud').value = latlng.lng;
    
    console.log('Coordenadas actualizadas:', latlng.lat, latlng.lng);
    
    // Realizar geocodificación inversa con Nominatim
    reverseGeocode(latlng);
}

function reverseGeocode(latlng) {
    console.log('Iniciando geocodificación inversa con Nominatim...');
    
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}&addressdetails=1&accept-language=es`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log('Resultado de geocodificación:', data);
            
            if (data && data.address) {
                fillAddressFromNominatim(data);
            } else {
                console.error('No se encontró dirección');
                alert('No se pudo obtener la dirección para esta ubicación.');
            }
        })
        .catch(error => {
            console.error('Error en geocodificación:', error);
            alert('Error al obtener la dirección. Puede llenar los campos manualmente.');
        });
}

function fillAddressFromNominatim(data) {
    console.log('=== PROCESANDO DIRECCIÓN DE NOMINATIM ===');
    console.log('Data completa:', data);
    
    const address = data.address || {};
    console.log('Address object:', address);
    
    // 1. Llenar domicilio con dirección formateada
    if (data.display_name) {
        const domicilioField = document.getElementById('domicilio');
        domicilioField.value = data.display_name;
        console.log('✓ Domicilio establecido:', data.display_name);
    }
    
    // 2. Llenar código postal
    if (address.postcode) {
        const cpField = document.getElementById('codigo_postal');
        cpField.value = address.postcode;
        console.log('✓ Código postal establecido:', address.postcode);
    } else {
        console.log('⚠ No se encontró código postal');
    }
    
    // 3. Llenar país
    if (address.country) {
        const paisSelect = document.getElementById('pais');
        const paisNombre = address.country.toUpperCase();
        console.log('País del geocoder:', paisNombre);
        
        for (let i = 0; i < paisSelect.options.length; i++) {
            if (paisSelect.options[i].text.toUpperCase() === paisNombre) {
                paisSelect.value = paisSelect.options[i].value;
                console.log('✓ País seleccionado:', paisSelect.value);
                break;
            }
        }
    }
    
    // 4. Llenar estado y cargar municipios
    const estadoGeocoder = address.state || address.region || address.state_district || '';
    console.log('=== ESTADO DEL GEOCODER ===');
    console.log('Estado raw:', estadoGeocoder);
    console.log('Todos los campos de address:', Object.keys(address));
    
    if (estadoGeocoder) {
        const estadoSelect = document.getElementById('estado');
        const estadoGeocoderUpper = estadoGeocoder.toUpperCase().trim();
        
        console.log('Buscando estado:', estadoGeocoderUpper);
        console.log('Opciones disponibles:');
        
        let estadoEncontrado = false;
        let mejorCoincidencia = null;
        let mejorPuntuacion = 0;
        
        for (let i = 0; i < estadoSelect.options.length; i++) {
            const optionText = estadoSelect.options[i].text.toUpperCase().trim();
            const optionValue = estadoSelect.options[i].value.toUpperCase().trim();
            
            console.log(`  Opción ${i}: "${optionText}" (valor: "${optionValue}")`);
            
            // Calcular puntuación de coincidencia
            let puntuacion = 0;
            
            // Coincidencia exacta
            if (optionText === estadoGeocoderUpper || optionValue === estadoGeocoderUpper) {
                puntuacion = 100;
            }
            // El texto del geocoder contiene la opción
            else if (estadoGeocoderUpper.includes(optionText)) {
                puntuacion = 80;
            }
            // La opción contiene el texto del geocoder
            else if (optionText.includes(estadoGeocoderUpper)) {
                puntuacion = 70;
            }
            // Coincidencia parcial de palabras
            else {
                const palabrasGeocoder = estadoGeocoderUpper.split(/\s+/);
                const palabrasOption = optionText.split(/\s+/);
                
                palabrasGeocoder.forEach(palabra => {
                    if (palabrasOption.some(p => p.includes(palabra) || palabra.includes(p))) {
                        puntuacion += 10;
                    }
                });
            }
            
            console.log(`    Puntuación: ${puntuacion}`);
            
            if (puntuacion > mejorPuntuacion) {
                mejorPuntuacion = puntuacion;
                mejorCoincidencia = i;
            }
        }
        
        if (mejorPuntuacion >= 50 && mejorCoincidencia !== null) {
            estadoSelect.value = estadoSelect.options[mejorCoincidencia].value;
            console.log('✓ Estado seleccionado:', estadoSelect.value, '(puntuación:', mejorPuntuacion, ')');
            estadoEncontrado = true;
            
            // Cargar municipios
            const municipio = address.city || address.town || address.village || address.municipality || address.city_district || '';
            console.log('Municipio del geocoder:', municipio);
            cargarMunicipios(estadoSelect.value, municipio);
        }
        
        if (!estadoEncontrado) {
            console.log('⚠ Estado no encontrado. Mejor puntuación:', mejorPuntuacion);
            console.log('⚠ Estado del geocoder:', estadoGeocoderUpper);
        }
    } else {
        console.log('⚠ No se encontró estado en address');
    }
}

// Base de datos de municipios por estado
const municipiosPorEstado = {
    'AGUASCALIENTES': ['Aguascalientes', 'Asientos', 'Calvillo', 'Cosío', 'Jesús María', 'Pabellón de Arteaga', 'Rincón de Romos', 'San José de Gracia', 'Tepezalá', 'San Francisco de los Romo', 'El Llano'],
    'BAJA CALIFORNIA': ['Ensenada', 'Mexicali', 'Tecate', 'Tijuana', 'Playas de Rosarito'],
    'BAJA CALIFORNIA SUR': ['Comondú', 'Mulegé', 'La Paz', 'Los Cabos', 'Loreto'],
    'CAMPECHE': ['Calkiní', 'Campeche', 'Carmen', 'Champotón', 'Hecelchakán', 'Hopelchén', 'Palizada', 'Tenabo', 'Escárcega', 'Calakmul', 'Candelaria'],
    'CHIAPAS': ['Acacoyagua', 'Acala', 'Acapetahua', 'Altamirano', 'Amatán', 'Amatenango de la Frontera', 'Amatenango del Valle', 'Angel Albino Corzo', 'Arriaga', 'Bejucal de Ocampo', 'Bella Vista', 'Benemérito de las Américas', 'Berriozábal', 'Bochil', 'Cacahoatán', 'Catazajá', 'Cintalapa', 'Coapilla', 'Comitán de Domínguez', 'La Concordia', 'Copainalá', 'Chalchihuitán', 'Chamula', 'Chanal', 'Chapultenango', 'Chenalhó', 'Chiapa de Corzo', 'Chiapilla', 'Chicoasén', 'Chicomuselo', 'Chilón', 'Escuintla', 'Francisco León', 'Frontera Comalapa', 'Frontera Hidalgo', 'Huehuetán', 'Huixtán', 'Huitiupán', 'Huixtla', 'La Independencia', 'Ixhuatán', 'Ixtacomitán', 'Ixtapa', 'Ixtapangajoya', 'Jiquipilas', 'Jitotol', 'Juárez', 'Larráinzar', 'La Libertad', 'Mapastepec', 'Maravilla Tenejapa', 'Marqués de Comillas', 'Mazapa de Madero', 'Mazatán', 'Metapa', 'Mitontic', 'Motozintla', 'Nicolás Ruíz', 'Ocosingo', 'Ocotepec', 'Ocozocoautla de Espinosa', 'Ostuacán', 'Osumacinta', 'Oxchuc', 'Palenque', 'Pantelhó', 'Pantepec', 'Pichucalco', 'Pijijiapan', 'El Porvenir', 'Villa Comaltitlán', 'Pueblo Nuevo Solistahuacán', 'Rayón', 'Reforma', 'Las Rosas', 'Sabanilla', 'Salto de Agua', 'San Cristóbal de las Casas', 'San Fernando', 'Siltepec', 'Simojovel', 'Sitalá', 'Socoltenango', 'Solosuchiapa', 'Soyaló', 'Suchiapa', 'Suchiate', 'Sunuapa', 'Tapachula', 'Tapalapa', 'Tecpatán', 'Tenejapa', 'Teopisca', 'Tila', 'Tonalá', 'Totolapa', 'La Trinitaria', 'Tumbalá', 'Tuxtla Gutiérrez', 'Tuxtla Chico', 'Tuzantán', 'Tzimol', 'Unión Juárez', 'Venustiano Carranza', 'Villa Corzo', 'Villaflores', 'Yajalón', 'Zinacantán'],
    'CHIHUAHUA': ['Ahumada', 'Aldama', 'Allende', 'Aquiles Serdán', 'Ascensión', 'Bachíniva', 'Balleza', 'Batopilas', 'Bocoyna', 'Buenaventura', 'Camargo', 'Carichí', 'Casas Grandes', 'Coronado', 'Coyame del Sotol', 'La Cruz', 'Cuauhtémoc', 'Cusihuiriachi', 'Chihuahua', 'Chínipas', 'Delicias', 'Dr. Belisario Domínguez', 'Galeana', 'Santa Isabel', 'Gómez Farías', 'Gran Morelos', 'Guachochi', 'Guadalupe', 'Guadalupe y Calvo', 'Guazapares', 'Guerrero', 'Hidalgo del Parral', 'Huejotitán', 'Ignacio Zaragoza', 'Janos', 'Jiménez', 'Juárez', 'Julimes', 'López', 'Madera', 'Maguarichi', 'Manuel Benavides', 'Matachí', 'Matamoros', 'Meoqui', 'Morelos', 'Moris', 'Namiquipa', 'Nonoava', 'Nuevo Casas Grandes', 'Ocampo', 'Ojinaga', 'Praxedis G. Guerrero', 'Riva Palacio', 'Rosales', 'Rosario', 'San Francisco de Borja', 'San Francisco de Conchos', 'San Francisco del Oro', 'Santa Bárbara', 'Satevó', 'Saucillo', 'Temósachic', 'El Tule', 'Urique', 'Uruachi', 'Valle de Zaragoza'],
    'CIUDAD DE MÉXICO': ['Álvaro Obregón', 'Azcapotzalco', 'Benito Juárez', 'Coyoacán', 'Cuajimalpa de Morelos', 'Cuauhtémoc', 'Gustavo A. Madero', 'Iztacalco', 'Iztapalapa', 'La Magdalena Contreras', 'Miguel Hidalgo', 'Milpa Alta', 'Tláhuac', 'Tlalpan', 'Venustiano Carranza', 'Xochimilco'],
    'COAHUILA': ['Abasolo', 'Acuña', 'Allende', 'Arteaga', 'Candela', 'Castaños', 'Cuatro Ciénegas', 'Escobedo', 'Francisco I. Madero', 'Frontera', 'General Cepeda', 'Guerrero', 'Hidalgo', 'Jiménez', 'Juárez', 'Lamadrid', 'Matamoros', 'Monclova', 'Morelos', 'Múzquiz', 'Nadadores', 'Nava', 'Ocampo', 'Parras', 'Piedras Negras', 'Progreso', 'Ramos Arizpe', 'Sabinas', 'Sacramento', 'Saltillo', 'San Buenaventura', 'San Juan de Sabinas', 'San Pedro', 'Sierra Mojada', 'Torreón', 'Viesca', 'Villa Unión', 'Zaragoza'],
    'COLIMA': ['Armería', 'Colima', 'Comala', 'Coquimatlán', 'Cuauhtémoc', 'Ixtlahuacán', 'Manzanillo', 'Minatitlán', 'Tecomán', 'Villa de Álvarez'],
    'DURANGO': ['Canatlán', 'Canelas', 'Coneto de Comonfort', 'Cuencamé', 'Durango', 'General Simón Bolívar', 'Gómez Palacio', 'Guadalupe Victoria', 'Guanaceví', 'Hidalgo', 'Indé', 'Lerdo', 'Mapimí', 'Mezquital', 'Nazas', 'Nombre de Dios', 'Ocampo', 'El Oro', 'Otáez', 'Pánuco de Coronado', 'Peñón Blanco', 'Poanas', 'Pueblo Nuevo', 'Rodeo', 'San Bernardo', 'San Dimas', 'San Juan de Guadalupe', 'San Juan del Río', 'San Luis del Cordero', 'San Pedro del Gallo', 'Santa Clara', 'Santiago Papasquiaro', 'Súchil', 'Tamazula', 'Tepehuanes', 'Tlahualilo', 'Topia', 'Vicente Guerrero'],
    'GUANAJUATO': ['Abasolo', 'Acámbaro', 'Allende', 'Apaseo el Alto', 'Apaseo el Grande', 'Atarjea', 'Celaya', 'Manuel Doblado', 'Comonfort', 'Coroneo', 'Cortazar', 'Cuerámaro', 'Doctor Mora', 'Dolores Hidalgo Cuna de la Independencia Nacional', 'Guanajuato', 'Huanímaro', 'Irapuato', 'Jaral del Progreso', 'Jerécuaro', 'León', 'Moroleón', 'Ocampo', 'Pénjamo', 'Pueblo Nuevo', 'Purísima del Rincón', 'Romita', 'Salamanca', 'Salvatierra', 'San Diego de la Unión', 'San Felipe', 'San Francisco del Rincón', 'San José Iturbide', 'San Luis de la Paz', 'Santa Catarina', 'Santa Cruz de Juventino Rosas', 'Santiago Maravatío', 'Silao', 'Tarandacuao', 'Tarimoro', 'Tierra Blanca', 'Uriangato', 'Valle de Santiago', 'Victoria', 'Villagrán', 'Xichú', 'Yuriria'],
    'GUERRERO': ['Acapulco de Juárez', 'Ahuacuotzingo', 'Ajuchitlán del Progreso', 'Alcozauca de Guerrero', 'Alpoyeca', 'Apaxtla', 'Arcelia', 'Atenango del Río', 'Atlamajalcingo del Monte', 'Atlixtac', 'Atoyac de Álvarez', 'Ayutla de los Libres', 'Azoyú', 'Benito Juárez', 'Buenavista de Cuéllar', 'Coahuayutla de José María Izazaga', 'Cocula', 'Copala', 'Copalillo', 'Copanatoyac', 'Coyuca de Benítez', 'Coyuca de Catalán', 'Cuajinicuilapa', 'Cualác', 'Cuautepec', 'Cuetzala del Progreso', 'Cutzamala de Pinzón', 'Chilapa de Álvarez', 'Chilpancingo de los Bravo', 'Florencio Villarreal', 'General Canuto A. Neri', 'General Heliodoro Castillo', 'Huamuxtitlán', 'Huitzuco de los Figueroa', 'Iguala de la Independencia', 'Igualapa', 'Ixcateopan de Cuauhtémoc', 'Zihuatanejo de Azueta', 'Juan R. Escudero', 'Leonardo Bravo', 'Malinaltepec', 'Mártir de Cuilapan', 'Metlatónoc', 'Mochitlán', 'Olinalá', 'Ometepec', 'Pedro Ascencio Alquisiras', 'Petatlán', 'Pilcaya', 'Pungarabato', 'Quechultenango', 'San Luis Acatlán', 'San Marcos', 'San Miguel Totolapan', 'Taxco de Alarcón', 'Tecoanapa', 'Técpan de Galeana', 'Teloloapan', 'Tepecoacuilco de Trujano', 'Tetipac', 'Tixtla de Guerrero', 'Tlacoachistlahuaca', 'Tlacoapa', 'Tlalchapa', 'Tlalixtaquilla de Maldonado', 'Tlapa de Comonfort', 'Tlapehuala', 'La Unión de Isidoro Montes de Oca', 'Xalpatláhuac', 'Xochihuehuetlán', 'Xochistlahuaca', 'Zapotitlán Tablas', 'Zirándaro', 'Zitlala'],
    'HIDALGO': ['Acatlán', 'Acaxochitlán', 'Actopan', 'Agua Blanca de Iturbide', 'Ajacuba', 'Alfajayucan', 'Almoloya', 'Apan', 'Atitalaquia', 'Atlapexco', 'Atotonilco de Tula', 'Atotonilco el Grande', 'Calnali', 'Cardonal', 'Cuautepec de Hidalgo', 'Chapantongo', 'Chapulhuacán', 'Chilcuautla', 'Eloxochitlán', 'Emiliano Zapata', 'Epazoyucan', 'Francisco I. Madero', 'Huasca de Ocampo', 'Huautla', 'Huazalingo', 'Huehuetla', 'Huejutla de Reyes', 'Huichapan', 'Ixmiquilpan', 'Jacala de Ledezma', 'Jaltocán', 'Juárez Hidalgo', 'Lolotla', 'Metepec', 'San Agustín Metzquititlán', 'Metztitlán', 'Mineral del Chico', 'Mineral del Monte', 'La Misión', 'Mixquiahuala de Juárez', 'Molango de Escamilla', 'Nicolás Flores', 'Nopala de Villagrán', 'Omitlán de Juárez', 'San Felipe Orizatlán', 'Pacula', 'Pachuca de Soto', 'Pisaflores', 'Progreso de Obregón', 'Mineral de la Reforma', 'San Agustín Tlaxiaca', 'San Bartolo Tutotepec', 'San Salvador', 'Santiago de Anaya', 'Santiago Tulantepec de Lugo Guerrero', 'Singuilucan', 'Tasquillo', 'Tecozautla', 'Tenango de Doria', 'Tepeapulco', 'Tepehuacán de Guerrero', 'Tepeji del Río de Ocampo', 'Tepetitlán', 'Tetepango', 'Villa de Tezontepec', 'Tezontepec de Aldama', 'Tianguistengo', 'Tizayuca', 'Tlahuelilpan', 'Tlaxcoapan', 'Tolcayuca', 'Tula de Allende', 'Tulancingo de Bravo', 'Xochiatipan', 'Xochicoatlán', 'Yahualica', 'Zacualtipán de Ángeles', 'Zapotlán de Juárez', 'Zempoala', 'Zimapán'],
    'JALISCO': ['Acatic', 'Acatlán de Juárez', 'Ahualulco de Mercado', 'Amacueca', 'Amatitán', 'Ameca', 'Arandas', 'Atemajac de Brizuela', 'Atengo', 'Atenguillo', 'Atotonilco el Alto', 'Atoyac', 'Autlán de Navarro', 'Ayotlán', 'Ayutla', 'Barca de la Esperanza', 'Bolaños', 'Cabo Corrientes', 'Casimiro Castillo', 'Cihuatlán', 'Zapotlán el Grande', 'Cocula', 'Colotlán', 'Concepción de Buenos Aires', 'Cuautitlán de García Barragán', 'Cuautla', 'Cuquío', 'Chapala', 'Chimaltitán', 'Chiquilistlán', 'Degollado', 'Ejutla', 'Encarnación de Díaz', 'Etzatlán', 'El Grullo', 'Guachinango', 'Guadalajara', 'Hostotipaquillo', 'Huejúcar', 'Huejuquilla el Alto', 'La Huerta', 'Ixtlahuacán de los Membrillos', 'Ixtlahuacán del Río', 'Jalostotitlán', 'Jamay', 'Jesús María', 'Jilotlán de los Dolores', 'Jocotepec', 'Juanacatlán', 'Juchitlán', 'Lagos de Moreno', 'El Limón', 'Magdalena', 'Santa María del Oro', 'La Manzanilla de la Paz', 'Mascota', 'Mazamitla', 'Mexticacán', 'Mezquitic', 'Mixtlán', 'Ocotlán', 'Ojuelos de Jalisco', 'Pihuamo', 'Poncitlán', 'Puerto Vallarta', 'Villa Purificación', 'Quitupan', 'El Salto', 'San Cristóbal de la Barranca', 'San Diego de Alejandría', 'San Juan de los Lagos', 'San Julián', 'San Marcos', 'San Martín de Bolaños', 'San Martín Hidalgo', 'San Miguel el Alto', 'Gómez Farías', 'San Sebastián del Oeste', 'Santa María de los Ángeles', 'Sayula', 'Tala', 'Talpa de Allende', 'Tamazula de Gordiano', 'Tapalpa', 'Tecalitlán', 'Tecolotlán', 'Techaluta de Montenegro', 'Tenamaxtlán', 'Teocaltiche', 'Teocuitatlán de Corona', 'Tepatitlán de Morelos', 'Tequila', 'Teuchitlán', 'Tizapán el Alto', 'Tlajomulco de Zúñiga', 'San Pedro Tlaquepaque', 'Tolimán', 'Tomatlán', 'Tonalá', 'Tonaya', 'Tonila', 'Totatiche', 'Tototlán', 'Tuxcacuesco', 'Tuxcueca', 'Tuxpan', 'Unión de San Antonio', 'Unión de Tula', 'Valle de Guadalupe', 'Valle de Juárez', 'San Gabriel', 'Villa Corona', 'Villa Guerrero', 'Villa Hidalgo', 'Cañadas de Obregón', 'Yahualica de González Gallo', 'Zacoalco de Torres', 'Zapopan', 'Zapotiltic', 'Zapotitlán de Vadillo', 'Zapotlán del Rey', 'Zapotlanejo'],
    'MÉXICO': ['Acambay', 'Acolman', 'Aculco', 'Almoloya de Alquisiras', 'Almoloya de Juárez', 'Almoloya del Río', 'Amanalco', 'Amatepec', 'Amecameca', 'Apaxco', 'Atenco', 'Atizapán', 'Atizapán de Zaragoza', 'Atlacomulco', 'Atlautla', 'Axapusco', 'Ayapango', 'Calimaya', 'Capulhuac', 'Coacalco de Berriozábal', 'Coatepec Harinas', 'Cocotitlán', 'Coyotepec', 'Cuautitlán', 'Cuautitlán Izcalli', 'Donato Guerra', 'Ecatepec de Morelos', 'Ecatzingo', 'Huehuetoca', 'Hueypoxtla', 'Huixquilucan', 'Isidro Fabela', 'Ixtapaluca', 'Ixtapan de la Sal', 'Ixtapan del Oro', 'Ixtlahuaca', 'Xalatlaco', 'Jaltenco', 'Jilotepec', 'Jilotzingo', 'Jiquipilco', 'Jocotitlán', 'Joquicingo', 'Juchitepec', 'Lerma', 'Malinalco', 'Melchor Ocampo', 'Metepec', 'Mexicaltzingo', 'Morelos', 'Naucalpan de Juárez', 'Nextlalpan', 'Nezahualcóyotl', 'Nicolás Romero', 'Nopaltepec', 'Ocoyoacac', 'Ocuilan', 'El Oro', 'Otumba', 'Otzoloapan', 'Otzolotepec', 'Ozumba', 'Papalotla', 'La Paz', 'Polotitlán', 'Rayón', 'San Antonio la Isla', 'San Felipe del Progreso', 'San Martín de las Pirámides', 'San Mateo Atenco', 'San Simón de Guerrero', 'Santo Tomás', 'Soyaniquilpan de Juárez', 'Sultepec', 'Tecámac', 'Tejupilco', 'Temamatla', 'Temascalapa', 'Temascalcingo', 'Temascaltepec', 'Temoaya', 'Tenancingo', 'Tenango del Aire', 'Tenango del Valle', 'Teoloyucan', 'Teotihuacán', 'Tepetlaoxtoc', 'Tepetlixpa', 'Tepotzotlán', 'Tequixquiac', 'Texcaltitlán', 'Texcalyacac', 'Texcoco', 'Tezoyuca', 'Tianguistenco', 'Timilpan', 'Tlalmanalco', 'Tlalnepantla de Baz', 'Tlatlaya', 'Toluca', 'Tonatico', 'Tultepec', 'Tultitlán', 'Valle de Bravo', 'Valle de Chalco Solidaridad', 'Villa de Allende', 'Villa del Carbón', 'Villa Guerrero', 'Villa Victoria', 'Xonacatlán', 'Zacazonapan', 'Zacualpan', 'Zinacantepec', 'Zumpahuacán', 'Zumpango'],
    'MICHOACÁN': ['Acuitzio', 'Aguililla', 'Álvaro Obregón', 'Angamacutiro', 'Angangueo', 'Apatzingán', 'Aporo', 'Aquila', 'Ario', 'Arteaga', 'Briseñas', 'Buenavista', 'Carácuaro', 'Coahuayana', 'Coalcomán de Vázquez Pallares', 'Coeneo', 'Contepec', 'Copándaro', 'Cotija', 'Cuitzeo', 'Charapan', 'Charo', 'Chavinda', 'Cherán', 'Chilchota', 'Chinicuila', 'Chucándiro', 'Churintzio', 'Churumuco', 'Ecuandureo', 'Epitacio Huerta', 'Erongarícuaro', 'Gabriel Zamora', 'Hidalgo', 'La Huacana', 'Huandacareo', 'Huaniqueo', 'Huetamo', 'Huiramba', 'Indaparapeo', 'Irimbo', 'Ixtlán', 'Jacona', 'Jiménez', 'Jiquilpan', 'Juárez', 'Jungapeo', 'Lagunillas', 'Madero', 'Maravatío', 'Marcos Castellanos', 'Lázaro Cárdenas', 'Morelia', 'Morelos', 'Múgica', 'Nahuatzen', 'Nocupétaro', 'Nuevo Parangaricutiro', 'Nuevo Urecho', 'Numarán', 'Ocampo', 'Pajacuarán', 'Panindícuaro', 'Parácuaro', 'Paracho', 'Pátzcuaro', 'Penjamillo', 'Peribán', 'La Piedad', 'Purépero', 'Puruándiro', 'Queréndaro', 'Quiroga', 'Cojumatlán de Régules', 'Los Reyes', 'Sahuayo', 'San Lucas', 'Santa Ana Maya', 'Salvador Escalante', 'Senguio', 'Susupuato', 'Tacámbaro', 'Tancítaro', 'Tangamandapio', 'Tangancícuaro', 'Tanhuato', 'Taretan', 'Tarímbaro', 'Tepalcatepec', 'Tingambato', 'Tingüindín', 'Tiquicheo de Nicolás Romero', 'Tlalpujahua', 'Tlazazalca', 'Tocumbo', 'Tumbiscatío', 'Turicato', 'Tuxpan', 'Tuzantla', 'Tzintzuntzan', 'Tzitzio', 'Uruapan', 'Venustiano Carranza', 'Villamar', 'Vista Hermosa', 'Yurécuaro', 'Zacapu', 'Zamora', 'Zináparo', 'Zinapécuaro', 'Ziracuaretiro', 'Zitácuaro', 'José Sixto Verduzco'],
    'MORELOS': ['Amacuzac', 'Atlatlahucan', 'Axochiapan', 'Ayala', 'Coatlán del Río', 'Cuautla', 'Cuernavaca', 'Emiliano Zapata', 'Huitzilac', 'Jantetelco', 'Jiutepec', 'Jojutla', 'Jonacatepec', 'Mazatepec', 'Miacatlán', 'Ocuituco', 'Puente de Ixtla', 'Temixco', 'Tepalcingo', 'Tepoztlán', 'Tetecala', 'Tetela del Volcán', 'Tlalnepantla', 'Tlaltizapán', 'Tlaquiltenango', 'Tlayacapan', 'Totolapan', 'Xochitepec', 'Yautepec', 'Yecapixtla', 'Zacatepec', 'Zacualpan de Amilpas'],
    'NAYARIT': ['Acaponeta', 'Ahuacatlán', 'Amatlán de Cañas', 'Bahía de Banderas', 'Compostela', 'Huajicori', 'Ixtlán del Río', 'Jala', 'Xalisco', 'Del Nayar', 'Rosamorada', 'Ruiz', 'San Blas', 'San Pedro Lagunillas', 'Santa María del Oro', 'Santiago Ixcuintla', 'Tecuala', 'Tepic', 'Tuxpan', 'La Yesca'],
    'NUEVO LEÓN': ['Abasolo', 'Agualeguas', 'Los Aldamas', 'Allende', 'Anáhuac', 'Apodaca', 'Aramberri', 'Bustamante', 'Cadereyta Jiménez', 'El Carmen', 'Cerralvo', 'Ciénega de Flores', 'China', 'Doctor Arroyo', 'Doctor Coss', 'Doctor González', 'Galeana', 'García', 'San Pedro Garza García', 'General Bravo', 'General Escobedo', 'General Terán', 'General Treviño', 'Guadalupe', 'Los Herreras', 'Higueras', 'Hualahuises', 'Iturbide', 'Juárez', 'Lampazos de Naranjo', 'Linares', 'Marín', 'Melchor Ocampo', 'Mier y Noriega', 'Mina', 'Montemorelos', 'Monterrey', 'Parás', 'Pesquería', 'Los Ramones', 'Rayones', 'Sabinas Hidalgo', 'Salinas Victoria', 'San Nicolás de los Garza', 'Hidalgo', 'Santa Catarina', 'Santiago', 'Vallecillo', 'Villaldama'],
    'OAXACA': ['Abejones', 'Acatlán de Pérez Figueroa', 'Asunción Cacalotepec', 'Asunción Cuyotepeji', 'Asunción Ixtaltepec', 'Asunción Nochixtlán', 'Asunción Ocotlán', 'Asunción Tlacolulita', 'Ayotzintepec', 'El Barrio de la Soledad', 'Calihualá', 'Candelaria Loxicha', 'Ciénega de Zimatlán', 'Ciudad Ixtepec', 'Coatecas Altas', 'Coicoyán de las Flores', 'La Compañía', 'Concepción Buenavista', 'Concepción Pápalo', 'Constancia del Rosario', 'Cosolapa', 'Cosoltepec', 'Cuilápam de Guerrero', 'Cuyamecalco Villa de Zaragoza', 'Chahuites', 'Chalcatongo de Hidalgo', 'Chiquihuitlán de Benito Juárez', 'Heroica Ciudad de Ejutla de Crespo', 'Eloxochitlán de Flores Magón', 'El Espinal', 'Tamazulápam del Espíritu Santo', 'Fresnillo de Trujullo', 'Guadalupe Etla', 'Guadalupe de Ramírez', 'Guelatao de Juárez', 'Guevea de Humboldt', 'Mesones Hidalgo', 'Huatulco', 'Huautla de Jiménez', 'Ixpantepec Nieves', 'Silacayoápam', 'Ixtlán de Juárez', 'Heroica Ciudad de Juchitán de Zaragoza', 'Loma Bonita', 'Magdalena Apasco', 'Magdalena Jaltepec', 'Magdalena Mixtepec', 'Magdalena Ocotlán', 'Magdalena Peñasco', 'Magdalena Teitipac', 'Magdalena Tequisistlán', 'Magdalena Tlacotepec', 'Magdalena Yodocono de Porfirio Díaz', 'Magdalena Zahuatlán', 'Mariscala de Juárez', 'Mártires de Tacubaya', 'Matías Romero', 'Mazatlán Villa de Flores', 'Mesones Hidalgo', 'Miahuatlán de Porfirio Díaz', 'Mixistlán de la Reforma', 'Monjas', 'Natividad', 'Nazareno Etla', 'Nejapa de Madero', 'Nuevo Zoquiápam', 'Oaxaca de Juárez', 'Ocotlán de Morelos', 'La Pe', 'Pinotepa de Don Luis', 'Pluma Hidalgo', 'San José del Progreso', 'Putla Villa de Guerrero', 'Reforma de Pineda', 'La Reforma', 'Reyes Etla', 'Rojas de Cuauhtémoc', 'Salina Cruz', 'San Agustín Amatengo', 'San Agustín Atenango', 'San Chilcuautla', 'San Dionisio del Mar', 'San Dionisio Ocotepec', 'San Dionisio Ocotlán', 'San Felipe Tejalápam', 'San Felipe Usila', 'San Francisco Cahuacuá', 'San Francisco Cajonos', 'San Francisco Chapulapa', 'San Francisco Chindúa', 'San Francisco del Mar', 'San Francisco Huehuetlán', 'San Francisco Ixhuatán', 'San Francisco Jaltepetongo', 'San Francisco Lachigoló', 'San Francisco Logueche', 'San Francisco Nuxaño', 'San Francisco Ozolotepec', 'San Francisco Sola', 'San Francisco Telixtlahuaca', 'San Francisco Teopan', 'San Francisco Tlapancingo', 'San Gabriel Mixtepec', 'San Ildefonso Amatlán', 'San Ildefonso Sola', 'San Ildefonso Villa Alta', 'San Jacinto Amilpas', 'San Jacinto Tlacotepec', 'San Jerónimo Coatlán', 'San Jerónimo Silacayoápam', 'San Jerónimo Sosola', 'San Jerónimo Taviche', 'San Jerónimo Tecóatl', 'San Jorge Nuchita', 'San José Ayuquila', 'San José Chiltepec', 'San José del Peñasco', 'San José Estancia Grande', 'San José Independencia', 'San José Lachiguiri', 'San José Tenango', 'San Juan Achiutla', 'San Juan Atepec', 'Ánimas Trujano', 'San Juan Bautista Atatlahuca', 'San Juan Bautista Coixtlahuaca', 'San Juan Bautista Cuicatlán', 'San Juan Bautista Guelache', 'San Juan Bautista Jayacatlán', 'San Juan Bautista Lo de Soto', 'San Juan Bautista Suchitepec', 'San Juan Bautista Tlacoatzintepec', 'San Juan Bautista Tlachichilco', 'San Juan Bautista Tuxtepec', 'San Juan Cacahuatepec', 'San Juan Cieneguilla', 'San Juan Coatzóspam', 'San Juan Colorado', 'San Juan Comaltepec', 'San Juan Cotzocón', 'San Juan Chicomezúchil', 'San Juan Chilateca', 'San Juan del Estado', 'San Juan del Río', 'San Juan Diuxi', 'San Juan Evangelista Analco', 'San Juan Guelavía', 'San Juan Guichicovi', 'San Juan Ihualtepec', 'San Juan Juquila Mixes', 'San Juan Juquila Vijanos', 'San Juan Lachao', 'San Juan Lachigalla', 'San Juan Lajarcia', 'San Juan Lalana', 'San Juan de los Cués', 'San Juan Mazatlán', 'San Juan Mixtepec', 'San Juan Mixtepec', 'San Juan Ñumí', 'San Juan Ozolotepec', 'San Juan Petlapa', 'San Juan Quiahije', 'San Juan Quiotepec', 'San Juan Sayultepec', 'San Juan Tabaá', 'San Juan Tamazola', 'San Juan Teita', 'San Juan Teitipac', 'San Juan Tepeuxila', 'San Juan Teposcolula', 'San Juan Yaeé', 'San Juan Yatzona', 'San Juan Yucuita', 'San Lorenzo', 'San Lorenzo Albarradas', 'San Lorenzo Cacaotepec', 'San Lorenzo Cuaunecuiltitla', 'San Lorenzo Texmelúcan', 'San Lorenzo Victoria', 'San Lucas Camotlán', 'San Lucas Ojitlán', 'San Lucas Quiaviní', 'San Lucas Zoquiápam', 'San Luis Amatlán', 'San Marcial Ozolotepec', 'San Marcos Arteaga', 'San Martín de los Cansecos', 'San Martín Huamelúlpam', 'San Martín Itunyoso', 'San Martín Lachilá', 'San Martín Peras', 'San Martín Tilcajete', 'San Martín Toxpalan', 'San Martín Zacatepec', 'San Mateo Cajonos', 'San Mateo del Mar', 'San Mateo Yoloxochitlán', 'San Mateo Etlatongo', 'San Mateo Nejápam', 'San Mateo Peñasco', 'San Mateo Piñas', 'San Mateo Río Hondo', 'San Mateo Sindihui', 'San Mateo Tlapiltepec', 'San Melchor Betaza', 'San Miguel Achiutla', 'San Miguel Ahuehuetitlán', 'San Miguel Aloápam', 'San Miguel Amatitlán', 'San Miguel Amatlán', 'San Miguel Coatlán', 'San Miguel Chicahua', 'San Miguel Chimalapa', 'San Miguel del Puerto', 'San Miguel del Río', 'San Miguel Ejutla', 'San Miguel el Grande', 'San Miguel Huautla', 'San Miguel Mixtepec', 'San Miguel Panixtlahuaca', 'San Miguel Peras', 'San Miguel Piedras', 'San Miguel Quetzaltepec', 'San Miguel Santa Flor', 'San Miguel Soyaltepec', 'San Miguel Suchixtepec', 'San Miguel Tecomatlán', 'San Miguel Tenango', 'San Miguel Tequixtepec', 'San Miguel Tilquiápam', 'San Miguel Tlacamama', 'San Miguel Tlacotepec', 'San Miguel Tulancingo', 'San Miguel Yotao', 'San Nicolás', 'San Nicolás Hidalgo', 'San Pablo Coatlán', 'San Pablo Cuatro Venados', 'San Pablo Etla', 'San Pablo Huitzo', 'San Pablo Huixtepec', 'San Pablo Macuiltianguis', 'San Pablo Tijaltepec', 'San Pablo Villa de Mitla', 'San Pablo Yaganiza', 'San Pedro Amuzgos', 'San Pedro Apóstol', 'San Pedro Atoyac', 'San Pedro Cajonos', 'San Pedro Comitancillo', 'San Pedro Coxcaltepec Cántaros', 'San Pedro el Alto', 'San Pedro Huamelula', 'San Pedro Huilotepec', 'San Pedro Ixcatlán', 'San Pedro Ixtlahuaca', 'San Pedro Jaltepetongo', 'San Pedro Jicayán', 'San Pedro Jocotipac', 'San Pedro Juchatengo', 'San Pedro Mártir', 'San Pedro Mártir Quiechapa', 'San Pedro Mártir Yucuxaco', 'San Pedro Mixtepec', 'San Pedro Mixtepec', 'San Pedro Molinos', 'San Pedro Nopala', 'San Pedro Ocopetatillo', 'San Pedro Ocotepec', 'San Pedro Pochutla', 'San Pedro Quiatoni', 'San Pedro Sochiápam', 'San Pedro Tapanatepec', 'San Pedro Taviche', 'San Pedro Teozacoalco', 'San Pedro Teutila', 'San Pedro Tidaá', 'San Pedro Topiltepec', 'San Pedro Totolápam', 'San Pedro y San Pablo Ayutla', 'San Pedro y San Pablo Teposcolula', 'San Pedro y San Pablo Tequixtepec', 'San Pedro Yaneri', 'San Pedro Yólox', 'San Pedro Yucunama', 'San Raymundo Jalpan', 'San Sebastián Abasolo', 'San Sebastián Coatlán', 'San Sebastián Ixcapa', 'San Sebastián Nicananduta', 'San Sebastián Río Hondo', 'San Sebastián Tecomaxtlahuaca', 'San Sebastián Teitipac', 'San Sebastián Tutla', 'San Simón Almolongas', 'San Simón Zahuatlán', 'Santa Ana', 'Santa Ana Ateixtlahuaca', 'Santa Ana Cuauhtémoc', 'Santa Ana del Valle', 'Santa Ana Tavela', 'Santa Ana Tlapacoyan', 'Santa Ana Yareni', 'Santa Ana Zegache', 'Santa Catalina Quierí', 'Santa Catarina Cuixtla', 'Santa Catarina Ixtepeji', 'Santa Catarina Juquila', 'Santa Catarina Lachatao', 'Santa Catarina Loxicha', 'Santa Catarina Mechoacán', 'Santa Catarina Minas', 'Santa Catarina Quiané', 'Santa Catarina Tayata', 'Santa Catarina Ticuá', 'Santa Catarina Yosonotú', 'Santa Catarina Zapoquila', 'Santa Cruz Acatepec', 'Santa Cruz Amilpas', 'Santa Cruz de Bravo', 'Santa Cruz Itundujia', 'Santa Cruz Mixtepec', 'Santa Cruz Nundaco', 'Santa Cruz Papalutla', 'Santa Cruz Tacache de Mina', 'Santa Cruz Tacahua', 'Santa Cruz Tayata', 'Santa Cruz Xitla', 'Santa Cruz Xoxocotlán', 'Santa Cruz Zenzontepec', 'Santa Gertrudis', 'Santa Inés del Monte', 'Santa Inés Yatzeche', 'Santa Lucía del Camino', 'Santa Lucía Miahuatlán', 'Santa Lucía Monteverde', 'Santa Lucía Ocotlán', 'Santa Magdalena Jicotlán', 'Santa María Alotepec', 'Santa María Apazco', 'Santa María Atzompa', 'Santa María Camotlán', 'Santa María Chachoápam', 'Santa María Chilchotla', 'Santa María Chimalapa', 'Santa María del Rosario', 'Santa María del Tule', 'Santa María Ecatepec', 'Santa María Guelacé', 'Santa María Guienagati', 'Santa María Huatulco', 'Santa María Huazolotitlán', 'Santa María Ipalapa', 'Santa María Ixcatlán', 'Santa María Jacatepec', 'Santa María Jalapa del Marqués', 'Santa María Jaltianguis', 'Santa María Lachixío', 'Santa María Mixtequilla', 'Santa María Nativitas', 'Santa María Nduayaco', 'Santa María Ozolotepec', 'Santa María Pápalo', 'Santa María Peñoles', 'Santa María Petapa', 'Santa María Quiegolani', 'Santa María Sola', 'Santa María Tataltepec', 'Santa María Tecomavaca', 'Santa María Temaxcalapa', 'Santa María Temaxcaltepec', 'Santa María Teopoxco', 'Santa María Tepantlali', 'Santa María Texcatitlán', 'Santa María Tlahuitoltepec', 'Santa María Tlalixtac', 'Santa María Tonameca', 'Santa María Totolapilla', 'Santa María Xadani', 'Santa María Yalina', 'Santa María Yavesía', 'Santa María Yolotepec', 'Santa María Yosoyúa', 'Santa María Yucuhiti', 'Santa María Zacatepec', 'Santa María Zaniza', 'Santa María Zoquitlán', 'Santiago Amoltepec', 'Santiago Apoala', 'Santiago Apóstol', 'Santiago Astata', 'Santiago Atitlán', 'Santiago Ayuquililla', 'Santiago Cacaloxtepec', 'Santiago Camotlán', 'Santiago Comaltepec', 'Santiago Chazumba', 'Santiago Choápam', 'Santiago del Río', 'Santiago Huajolotitlán', 'Santiago Huauclilla', 'Santiago Ihuitlán Plumas', 'Santiago Ixcuintepec', 'Santiago Ixtayutla', 'Santiago Jamiltepec', 'Santiago Jocotepec', 'Santiago Juxtlahuaca', 'Santiago Lachiguiri', 'Santiago Lalopa', 'Santiago Laollaga', 'Santiago Laxopa', 'Santiago Llano Grande', 'Santiago Matatlán', 'Santiago Miltepec', 'Santiago Minas', 'Santiago Nacaltepec', 'Santiago Nejapilla', 'Santiago Nundiche', 'Santiago Nuyoó', 'Santiago Pinotepa Nacional', 'Santiago Suchilquitongo', 'Santiago Tamazola', 'Santiago Tapextla', 'Santiago Tenango', 'Santiago Tepetlapa', 'Santiago Tetepec', 'Santiago Texcalcingo', 'Santiago Textitlán', 'Santiago Tilantongo', 'Santiago Tillo', 'Santiago Tlazoyaltepec', 'Santiago Xanica', 'Santiago Xiacuí', 'Santiago Yaitepec', 'Santiago Yaveo', 'Santiago Yolomécatl', 'Santiago Yosondúa', 'Santiago Yucuyachi', 'Santiago Zacatepec', 'Santiago Zoochila', 'Santo Domingo Albarradas', 'Santo Domingo Armenta', 'Santo Domingo Chihuitán', 'Santo Domingo de Morelos', 'Santo Domingo Ingenio', 'Santo Domingo Ixcatlán', 'Santo Domingo Nuxaá', 'Santo Domingo Ozolotepec', 'Santo Domingo Petapa', 'Santo Domingo Roayaga', 'Santo Domingo Tehuantepec', 'Santo Domingo Teojomulco', 'Santo Domingo Tepuxtepec', 'Santo Domingo Tlatayápam', 'Santo Domingo Tomaltepec', 'Santo Domingo Tonalá', 'Santo Domingo Tonaltepec', 'Santo Domingo Xagacía', 'Santo Domingo Yanhuitlán', 'Santo Domingo Yodohino', 'Santo Domingo Zanatepec', 'Santo Tomás Jalieza', 'Santo Tomás Mazaltepec', 'Santo Tomás Ocotepec', 'Santo Tomás Tamazulapan', 'Santos Reyes Nopala', 'Santos Reyes Pápalo', 'Santos Reyes Tepejillo', 'Santos Reyes Yucuná', 'Silacayoápam', 'Sitio de Xitlapehua', 'Soledad Etla', 'Villa de Tamazulápam del Progreso', 'Tanetze de Zaragoza', 'Taniche', 'Tataltepec de Valdés', 'Teococuilco de Marcos Pérez', 'Teotitlán de Flores Magón', 'Teotitlán del Valle', 'Teotongo', 'Tepelmeme Villa de Morelos', 'Tezoatlán de Segura y Luna', 'Tlacolula de Matamoros', 'Tlacotepec Plumas', 'Tlalixtac de Cabrera', 'Totontepec Villa de Morelos', 'Trinidad Zaachila', 'La Trinidad Vista Hermosa', 'Unión Hidalgo', 'Valerio Trujano', 'Villa de Chilapa de Díaz', 'Villa de Etla', 'Villa de Tamazulápam del Progreso', 'Villa de Tututepec de Melchor Ocampo', 'Villa de Zaachila', 'Villa Díaz Ordaz', 'Villa Hidalgo', 'Villa Sola de Vega', 'Villa Talea de Castro', 'Villa Tejúpam de la Unión', 'Yaxe', 'Magdalena Yodocono de Porfirio Díaz', 'Yogana', 'Yutanduchi de Guerrero', 'Villa de Zaachila', 'Zapotitlán del Río', 'Zapotitlán Lagunas', 'Zapotitlán Palmas', 'Santa Inés de Zaragoza', 'Zimatlán de Álvarez'],
    'PUEBLA': ['Acajete', 'Acateno', 'Acatlán', 'Acatzingo', 'Acteopan', 'Ahuacatlán', 'Ahuatlán', 'Ahuazotepec', 'Ahuehuetitla', 'Ajalpan', 'Albino Zertuche', 'Aljojuca', 'Altepexi', 'Amixtlán', 'Amozoc', 'Aquixtla', 'Atempan', 'Atexcal', 'Atlequizayan', 'Atlixco', 'Atoyatempan', 'Atzala', 'Atzitzihuacán', 'Atzitzintla', 'Axutla', 'Ayotoxco de Guerrero', 'Calpan', 'Caltepec', 'Camocuautla', 'Caxhuacan', 'Coatepec', 'Coatzingo', 'Cohetzala', 'Cohuecan', 'Coronango', 'Coxcatlán', 'Coyomeapan', 'Coyotepec', 'Cuapiaxtla de Madero', 'Cuautempan', 'Cuautinchán', 'Cuautlancingo', 'Cuayuca de Andrade', 'Cuetzalan del Progreso', 'Cuyoaco', 'Chalchicomula de Sesma', 'Chapulco', 'Chiautla', 'Chiautzingo', 'Chiconcuautla', 'Chichiquila', 'Chietla', 'Chigmecatitlán', 'Chignahuapan', 'Chignautla', 'Chila', 'Chila de la Sal', 'Chilchotla', 'Chinantla', 'Domingo Arenas', 'Eloxochitlán', 'Epatlán', 'Esperanza', 'Francisco Z. Mena', 'General Felipe Ángeles', 'Guadalupe', 'Guadalupe Victoria', 'Hermenegildo Galeana', 'Honey', 'Huaquechula', 'Huatlatlauca', 'Huauchinango', 'Huehuetla', 'Huehuetlán el Chico', 'Huehuetlán el Grande', 'Huejotzingo', 'Hueyapan', 'Hueytamalco', 'Hueytlalpan', 'Huitzilan de Serdán', 'Huitziltepec', 'Ixcamilpa de Guerrero', 'Ixcaquixtla', 'Ixtacamaxtitlán', 'Ixtepec', 'Izúcar de Matamoros', 'Jalpan', 'Jolalpan', 'Jonotla', 'Jopala', 'Juan C. Bonilla', 'Juan Galindo', 'Juan N. Méndez', 'Lafragua', 'Libres', 'La Magdalena Tlatlauquitepec', 'Mazapiltepec de Juárez', 'Mixtla', 'Molcaxac', 'Naupan', 'Nauzontla', 'Nealtican', 'Nicolás Bravo', 'Nopalucan', 'Ocotepec', 'Ocoyucan', 'Olintla', 'Oriental', 'Pahuatlán', 'Palmar de Bravo', 'Pantepec', 'Petlalcingo', 'Piaxtla', 'Puebla', 'Quecholac', 'Quimixtlán', 'Rafael Lara Grajales', 'Los Reyes de Juárez', 'San Andrés Cholula', 'San Antonio Cañada', 'San Diego la Mesa Tochimiltzingo', 'San Felipe Teotlalcingo', 'San Felipe Tepatlán', 'San Gabriel Chilac', 'San Gregorio Atzompa', 'San Jerónimo Tecuanipan', 'San Jerónimo Xayacatlán', 'San José Chiapa', 'San José Miahuatlán', 'San Juan Atenco', 'San Juan Atzompa', 'San Martín Texmelucan', 'San Martín Totoltepec', 'San Matías Tlalancaleca', 'San Miguel Ixitlán', 'San Miguel Xoxtla', 'San Nicolás Buenos Aires', 'San Nicolás de los Ranchos', 'San Pablo Anicano', 'San Pedro Cholula', 'San Pedro Yeloixtlahuaca', 'San Salvador el Seco', 'San Salvador el Verde', 'San Salvador Huixcolotla', 'San Sebastián Tlacotepec', 'Santa Catarina Tlaltempan', 'Santa Inés Ahuatempan', 'Santa Isabel Cholula', 'Santiago Miahuatlán', 'Santo Tomás Hueyotlipan', 'Soltepec', 'Tecali de Herrera', 'Tecamachalco', 'Tecomatlán', 'Tehuacán', 'Tehuitzingo', 'Tenampulco', 'Teopantlán', 'Teotlalco', 'Tepanco de López', 'Tepango de Rodríguez', 'Tepatlaxco de Hidalgo', 'Tepeaca', 'Tepemaxalco', 'Tepeojuma', 'Tepetzintla', 'Tepexco', 'Tepexi de Rodríguez', 'Tepeyahualco', 'Tepeyahualco de Cuauhtémoc', 'Tetela de Ocampo', 'Teteles de Ávila Castillo', 'Teziutlán', 'Tianguismanalco', 'Tilapa', 'Tlacotepec de Benito Juárez', 'Tlacuilotepec', 'Tlahuapan', 'Tlaltenango', 'Tlanepantla', 'Tlaola', 'Tlapacoya', 'Tlapanalá', 'Tlatlauquitepec', 'Tlaxco', 'Tochimilco', 'Tochtepec', 'Totoltepec de Guerrero', 'Tulcingo', 'Tuzamapan de Galeana', 'Tzicatlacoyan', 'Venustiano Carranza', 'Vicente Guerrero', 'Xayacatlán de Bravo', 'Xicotepec', 'Xicotlán', 'Xiutetelco', 'Xochiapulco', 'Xochiltepec', 'Xochitlán de Vicente Suárez', 'Xochitlán Todos Santos', 'Yaonáhuac', 'Yehualtepec', 'Zacapala', 'Zacapoaxtla', 'Zacatlán', 'Zapotitlán', 'Zapotitlán de Méndez', 'Zaragoza', 'Zautla', 'Zihuateutla', 'Zinacatepec', 'Zongozotla', 'Zoquiapan', 'Zoquitlán'],
    'QUERÉTARO': ['Amealco de Bonfil', 'Arroyo Seco', 'Cadereyta de Montes', 'Colón', 'Corregidora', 'Ezequiel Montes', 'Huimilpan', 'Jalpan de Serra', 'Landa de Matamoros', 'El Marqués', 'Pedro Escobedo', 'Peñamiller', 'Pinal de Amoles', 'Querétaro', 'San Joaquín', 'San Juan del Río', 'Tequisquiapan', 'Tolimán'],
    'QUINTANA ROO': ['Bacalar', 'Benito Juárez', 'Cozumel', 'Felipe Carrillo Puerto', 'Isla Mujeres', 'José María Morelos', 'Lázaro Cárdenas', 'Othón P. Blanco', 'Puerto Morelos', 'Solidaridad', 'Tulum'],
    'SAN LUIS POTOSÍ': ['Ahualulco', 'Alaquines', 'Aquismón', 'Armadillo de los Infante', 'Cárdenas', 'Catorce', 'Cedral', 'Cerritos', 'Cerro de San Pedro', 'Ciudad del Maíz', 'Ciudad Fernández', 'Ciudad Valles', 'Coxcatlán', 'Charcas', 'Ébano', 'Guadalcázar', 'Huehuetlán', 'Lagunillas', 'Matehuala', 'Mexquitic de Carmona', 'Moctezuma', 'Rayón', 'Rioverde', 'Salinas', 'San Antonio', 'San Ciro de Acosta', 'San Luis Potosí', 'San Martín Chalchicuautla', 'San Nicolás Tolentino', 'Santa Catarina', 'Santa María del Río', 'Santo Domingo', 'Soledad de Graciano Sánchez', 'Tamasopo', 'Tamazunchale', 'Tampacán', 'Tampamolón Corona', 'Tamuín', 'Tancanhuitz', 'Tancuayalab', 'Tanlajás', 'Tanquián de Escobedo', 'Tierra Nueva', 'Vanegas', 'Venado', 'Villa de Arriaga', 'Villa de Guadalupe', 'Villa de la Paz', 'Villa de Ramos', 'Villa de Reyes', 'Villa Hidalgo', 'Villa Juárez', 'Xilitla', 'Zaragoza'],
    'SINALOA': ['Ahome', 'Angostura', 'Badiraguato', 'Concordia', 'Cosalá', 'Culiacán', 'Choix', 'Elota', 'Escuinapa', 'El Fuerte', 'Guasave', 'Mazatlán', 'Mocorito', 'Navolato', 'Rosario', 'Salvador Alvarado', 'San Ignacio', 'Sinaloa'],
    'SONORA': ['Aconchi', 'Agua Prieta', 'Álamos', 'Altar', 'Arivechi', 'Arizpe', 'Atil', 'Bacadéhuachi', 'Bacanora', 'Bacerac', 'Bacoachi', 'Bácum', 'Banámichi', 'Baviácora', 'Bavispe', 'Benito Juárez', 'Caborca', 'Cajeme', 'Cananea', 'Carbó', 'La Colorada', 'Cucurpe', 'Cumpas', 'Divisaderos', 'Empalme', 'Etchojoa', 'Fronteras', 'Granados', 'Guaymas', 'Hermosillo', 'Huachinera', 'Huásabas', 'Huatabampo', 'Huépac', 'Imuris', 'Magdalena', 'Mazatán', 'Moctezuma', 'Naco', 'Nácori Chico', 'Nacozari de García', 'Navojoa', 'Nogales', 'Onavas', 'Opodepe', 'Oquitoa', 'Pitiquito', 'Puerto Peñasco', 'Quiriego', 'Rayón', 'Rosario', 'Sahuaripa', 'San Felipe de Jesús', 'San Ignacio Río Muerto', 'San Javier', 'San Luis Río Colorado', 'San Miguel de Horcasitas', 'San Pedro de la Cueva', 'Santa Ana', 'Santa Cruz', 'Sáric', 'Soyopa', 'Suaqui Grande', 'Tepache', 'Trincheras', 'Tubutama', 'Ures', 'Villa Hidalgo', 'Villa Pesqueira', 'Yécora'],
    'TABASCO': ['Balancán', 'Cárdenas', 'Centla', 'Centro', 'Comalcalco', 'Cunduacán', 'Emiliano Zapata', 'Huimanguillo', 'Jalapa', 'Jalpa de Méndez', 'Jonuta', 'Macuspana', 'Nacajuca', 'Paraíso', 'Tacotalpa', 'Teapa', 'Tenosique'],
    'TAMAULIPAS': ['Abasolo', 'Aldama', 'Altamira', 'Antiguo Morelos', 'Burgos', 'Bustamante', 'Camargo', 'Casas', 'Ciudad Madero', 'Cruillas', 'Gómez Farías', 'González', 'Güémez', 'Guerrero', 'Gustavo Díaz Ordaz', 'Hidalgo', 'Jaumave', 'Jiménez', 'Llera', 'Mainero', 'El Mante', 'Matamoros', 'Méndez', 'Mier', 'Miguel Alemán', 'Miquihuana', 'Nuevo Laredo', 'Nuevo Morelos', 'Ocampo', 'Padilla', 'Palmillas', 'Reynosa', 'Río Bravo', 'San Carlos', 'San Fernando', 'San Nicolás', 'Soto la Marina', 'Tampico', 'Tula', 'Valle Hermoso', 'Victoria', 'Villagrán', 'Xicoténcatl'],
    'TLAXCALA': ['Acuamanala de Miguel Hidalgo', 'Altzayanca', 'Amaxac de Guerrero', 'Apetatitlán de Antonio Carvajal', 'Apizaco', 'Atlangatepec', 'Benito Juárez', 'Calpulalpan', 'Chiautempan', 'Contla de Juan Cuamatzi', 'Cuapiaxtla', 'Cuaxomulco', 'El Carmen Tequexquitla', 'Emiliano Zapata', 'Españita', 'Huamantla', 'Hueyotlipan', 'Ixtacuixtla', 'Ixtenco', 'Mazatecochco de José María Morelos', 'Muñoz de Domingo Arenas', 'Nanacamilpa de Mariano Arista', 'Natívitas', 'Panotla', 'San Pablo del Monte', 'Sanctórum de Lázaro Cárdenas', 'Santa Ana Nopalucan', 'Santa Apolonia Teacalco', 'Santa Catarina Ayometla', 'Santa Cruz Quilehtla', 'Santa Cruz Tlaxcala', 'Santa Isabel Xiloxoxtla', 'Tenancingo', 'Teolocholco', 'Tepetitla de Lardizábal', 'Tepeyanco', 'Terrenate', 'Tetla de la Solidaridad', 'Tetlatlahuca', 'Tlaxcala', 'Tlaxco', 'Tocatlán', 'Totolac', 'Tzompantepec', 'Xaloztoc', 'Xaltocan', 'Xicohtzinco', 'Yauhquemehcan', 'Zacatelco', 'Ziltlaltépec de Trinidad Sánchez Santos'],
    'VERACRUZ': ['Acajete', 'Acatlán', 'Acayucan', 'Actopan', 'Acula', 'Acultzingo', 'Camarón de Tejeda', 'Alpatláhuac', 'Alto Lucero de Gutiérrez Barrios', 'Altotonga', 'Alvarado', 'Amatitlán', 'Amatlán de los Reyes', 'Angel R. Cabada', 'La Antigua', 'Apazapan', 'Aquila', 'Astacinga', 'Atlahuilco', 'Atoyac', 'Atzacan', 'Atzalan', 'Tlaltetela', 'Ayahualulco', 'Banderilla', 'Benito Juárez', 'Boca del Río', 'Calcahualco', 'Camarones', 'Camerino Z. Mendoza', 'Carlos A. Carrillo', 'Carrillo Puerto', 'Catemaco', 'Cazones de Herrera', 'Cerro Azul', 'Chacaltianguis', 'Chalma', 'Chiconamel', 'Chiconquiaco', 'Chicontepec', 'Chinameca', 'Chinampa de Gorostiza', 'Las Choapas', 'Chocamán', 'Chontla', 'Chumatlán', 'Citlaltépetl', 'Coacoatzintla', 'Coahuitlán', 'Coatepec', 'Coatzacoalcos', 'Coatzintla', 'Coetzala', 'Colipa', 'Comapa', 'Córdoba', 'Cosamaloapan de Carpio', 'Cosautlán de Carvajal', 'Coscomatepec', 'Cosoleacaque', 'Cotaxtla', 'Coxquihui', 'Coyutla', 'Cuichapa', 'Cuitláhuac', 'Cutián', 'Chacaltianguis', 'Chalma', 'Chiconamel', 'Chiconquiaco', 'Chicontepec', 'Chinameca', 'Chinampa de Gorostiza', 'Las Choapas', 'Chocamán', 'Chontla', 'Chumatlán', 'Emiliano Zapata', 'Espinal', 'Filomeno Mata', 'Fortín', 'Gutiérrez Zamora', 'Hidalgotitlán', 'Huatusco', 'Huayacocotla', 'Hueyapan de Ocampo', 'Huiloapan de Cuauhtémoc', 'Ignacio de la Llave', 'Ilamatlán', 'Isla', 'Ixcatepec', 'Ixhuacán de los Reyes', 'Ixhuatlán de Madero', 'Ixhuatlán del Café', 'Ixhuatlán del Sureste', 'Ixhuatlancillo', 'Ixmatlahuacan', 'Ixtaczoquitlán', 'Jalacingo', 'Jalcomulco', 'Jáltipan', 'Jamapa', 'Jesús Carranza', 'Xico', 'Jilotepec', 'Juan Rodríguez Clara', 'Juchique de Ferrer', 'Landero y Coss', 'Lerdo de Tejada', 'Magdalena', 'Maltrata', 'Manlio Fabio Altamirano', 'Mariano Escobedo', 'Martínez de la Torre', 'Mecatlán', 'Mecayapan', 'Medellín de Bravo', 'Miahuatlán', 'Las Minas', 'Minatitlán', 'Misantla', 'Mixtla de Altamirano', 'Moloacán', 'Nanchital de Lázaro Cárdenas del Río', 'Naolinco', 'Naranjal', 'Naranjos Amatlán', 'Nautla', 'Nogales', 'Oluta', 'Omealca', 'Orizaba', 'Otatitlán', 'Oteapan', 'Ozuluama de Mascareñas', 'Pajapan', 'Pánuco', 'Papantla', 'Paso de Ovejas', 'Paso del Macho', 'Perote', 'Platón Sánchez', 'Playa Vicente', 'Poza Rica de Hidalgo', 'Las Vigas de Ramírez', 'Pueblo Viejo', 'Puente Nacional', 'Rafael Delgado', 'Rafael Lucio', 'Los Reyes', 'Río Blanco', 'Saltabarranca', 'San Andrés Tenejapan', 'San Andrés Tuxtla', 'San Juan Evangelista', 'San Rafael', 'Santiago Sochiapan', 'Santiago Tuxtla', 'Sayula de Alemán', 'Sochiapa', 'Soconusco', 'Soledad Atzompa', 'Soledad de Doblado', 'Soteapan', 'Tamalín', 'Tamiahua', 'Tampico Alto', 'Tancoco', 'Tantima', 'Tantoyuca', 'Tatatila', 'Castillo de Teayo', 'Tecolutla', 'Tehuipango', 'Temapache', 'Tempoal', 'Tenampa', 'Tenochtitlán', 'Teocelo', 'Tepatlaxco', 'Tepetlán', 'Tepetzintla', 'Tequila', 'Texcatepec', 'Texhuacán', 'Texistepec', 'Tezonapa', 'Tierra Blanca', 'Tihuatlán', 'Tlacojalpan', 'Tlacolulan', 'Tlacotalpan', 'Tlalixcoyan', 'Tlalnelhuayocan', 'Tlapacoyan', 'Tlaquilpa', 'Tlilapan', 'Tomatlán', 'Tonayán', 'Totutla', 'Tuxpan', 'Tuxtilla', 'Ursulo Galván', 'Vega de Alatorre', 'Veracruz', 'Villa Aldama', 'Xalapa', 'Xico', 'Xoxocotla', 'Yanga', 'Yecuatla', 'Zacualpan', 'Zaragoza', 'Zentla', 'Zongolica', 'Zontecomatlán de López y Fuentes', 'Zozocolco de Hidalgo'],
    'YUCATÁN': ['Abalá', 'Acanceh', 'Akil', 'Baca', 'Bokobá', 'Buctzotz', 'Cacalchén', 'Calotmul', 'Cansahcab', 'Cantamayec', 'Celestún', 'Cenotillo', 'Conkal', 'Cuncunul', 'Cuzamá', 'Chacsinkín', 'Chankom', 'Chapab', 'Chemax', 'Chicxulub Pueblo', 'Chichimilá', 'Chikindzonot', 'Chocholá', 'Chumayel', 'Dzan', 'Dzemul', 'Dzidzantún', 'Dzilam de Bravo', 'Dzilam González', 'Dzitás', 'Dzoncauich', 'Espita', 'Halachó', 'Hocabá', 'Hoctún', 'Homún', 'Huhí', 'Hunucmá', 'Ixil', 'Itzincab', 'Kanasín', 'Kantunil', 'Kaua', 'Kinchil', 'Kopomá', 'Mama', 'Maní', 'Maxcanú', 'Mayapán', 'Mérida', 'Mocochá', 'Motul', 'Muna', 'Muxupip', 'Opichén', 'Oxkutzcab', 'Panabá', 'Peto', 'Progreso', 'Quintana Roo', 'Río Lagartos', 'Sacalum', 'Samahil', 'Sanahcat', 'San Felipe', 'Santa Elena', 'Seyé', 'Sinanché', 'Sotuta', 'Sucilá', 'Sudzal', 'Suma', 'Tahdziú', 'Tahmek', 'Teabo', 'Tecoh', 'Tekal de Venegas', 'Tekantó', 'Tekax', 'Tekit', 'Tekom', 'Telchac Pueblo', 'Telchac Puerto', 'Temax', 'Temozón', 'Tepakán', 'Tetiz', 'Teya', 'Ticul', 'Timucuy', 'Tinum', 'Tixcacalcupul', 'Tixkokob', 'Tixmehuac', 'Tixpéhual', 'Tizimín', 'Tunkás', 'Tzucacab', 'Uayma', 'Ucú', 'Umán', 'Valladolid', 'Xocchel', 'Yaxcabá', 'Yaxkukul', 'Yobaín'],
    'ZACATECAS': ['Apozol', 'Apulco', 'Atolinga', 'Benito Juárez', 'Calera', 'Cañitas de Felipe Pescador', 'Concepción del Oro', 'Cuauhtémoc', 'Chalchihuites', 'Fresnillo', 'Trinidad García de la Cadena', 'Genaro Codina', 'General Enrique Estrada', 'General Francisco R. Murguía', 'El Plateado de Joaquín Amaro', 'General Pánfilo Natera', 'Guadalupe', 'Huanusco', 'Jalpa', 'Jerez', 'Jiménez del Teul', 'Juan Aldama', 'Juchipila', 'Loreto', 'Luis Moya', 'Mazapil', 'Melchor Ocampo', 'Mezquital del Oro', 'Miguel Auza', 'Momax', 'Monte Escobedo', 'Morelos', 'Moyahua de Estrada', 'Nochistlán de Mejía', 'Noria de Ángeles', 'Ojocaliente', 'Pánuco', 'Pinos', 'Río Grande', 'Sain Alto', 'Salvador', 'Sombrerete', 'Susticacán', 'Tabasco', 'Tepechitlán', 'Tepetongo', 'Teúl de González Ortega', 'Tlaltenango de Sánchez Román', 'Valparaíso', 'Vetagrande', 'Villa de Cos', 'Villa García', 'Villa González Ortega', 'Villa Hidalgo', 'Villanueva', 'Zacatecas', 'Trancoso', 'Santa María de la Paz']
};

function cargarMunicipios(estado, municipioGeocoder) {
    console.log('=== CARGANDO MUNICIPIOS (LOCAL) ===');
    console.log('Estado:', estado);
    console.log('Municipio del geocoder:', municipioGeocoder);
    
    if (!estado) {
        console.log('No se proporcionó estado');
        return;
    }
    
    const municipioSelect = document.getElementById('municipio');
    municipioSelect.innerHTML = '<option value="">Cargando...</option>';
    municipioSelect.disabled = true;
    
    // Normalizar nombre del estado
    const estadoKey = estado.toUpperCase().trim();
    console.log('Buscando estado en base de datos:', estadoKey);
    
    // Buscar estado en la base de datos
    let municipios = null;
    for (const key in municipiosPorEstado) {
        if (key.toUpperCase() === estadoKey || estadoKey.includes(key.toUpperCase()) || key.toUpperCase().includes(estadoKey)) {
            municipios = municipiosPorEstado[key];
            console.log('✓ Estado encontrado:', key);
            break;
        }
    }
    
    if (municipios && municipios.length > 0) {
        municipioSelect.innerHTML = '<option value="">Seleccione...</option>';
        municipioSelect.disabled = false;
        
        // Agregar municipios
        municipios.forEach(function(municipio, index) {
            const option = document.createElement('option');
            option.value = municipio;
            option.textContent = municipio;
            municipioSelect.appendChild(option);
            
            if (index < 5) {
                console.log(`Municipio ${index}:`, municipio);
            }
        });
        
        console.log('✓ Total municipios cargados:', municipios.length);
        
        // Seleccionar municipio del geocoder si existe
        if (municipioGeocoder) {
            const municipioUpper = municipioGeocoder.toUpperCase();
            console.log('Buscando municipio:', municipioUpper);
            
            let municipioEncontrado = false;
            for (let i = 0; i < municipioSelect.options.length; i++) {
                const optionText = municipioSelect.options[i].text.toUpperCase();
                
                if (optionText.includes(municipioUpper) || 
                    municipioUpper.includes(optionText)) {
                    
                    municipioSelect.value = municipioSelect.options[i].value;
                    console.log('✓ Municipio seleccionado:', municipioSelect.value);
                    municipioEncontrado = true;
                    break;
                }
            }
            
            if (!municipioEncontrado) {
                console.log('⚠ Municipio no encontrado en la lista');
            }
        }
    } else {
        console.log('⚠ Estado no encontrado en la base de datos');
        municipioSelect.innerHTML = '<option value="">No hay municipios disponibles</option>';
        municipioSelect.disabled = false;
    }
}

// Función para generar clave aleatoria
function generarClaveAleatoria() {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let clave = 'INS-';
    for (let i = 0; i < 8; i++) {
        clave += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }
    document.getElementById('clave_instalacion').value = clave;
}

// Búsqueda de contribuyentes
let searchTimeout = null;
const contribuyenteSearch = document.getElementById('contribuyente_search');
const contribuyenteId = document.getElementById('contribuyente_id');

if (contribuyenteSearch) {
    // Crear contenedor de resultados
    const searchContainer = document.createElement('div');
    searchContainer.id = 'search-results';
    searchContainer.style.cssText = 'position: absolute; background: white; border: 1px solid #ddd; border-radius: 4px; max-height: 200px; overflow-y: auto; width: 100%; z-index: 1000; display: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
    contribuyenteSearch.parentNode.style.position = 'relative';
    contribuyenteSearch.parentNode.appendChild(searchContainer);
    
    contribuyenteSearch.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Limpiar timeout anterior
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        // Limpiar selección si se modifica el texto
        contribuyenteId.value = '';
        
        if (query.length < 2) {
            searchContainer.style.display = 'none';
            return;
        }
        
        // Esperar 300ms después de que el usuario deje de escribir
        searchTimeout = setTimeout(function() {
            buscarContribuyentes(query);
        }, 300);
    });
    
    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!contribuyenteSearch.contains(e.target) && !searchContainer.contains(e.target)) {
            searchContainer.style.display = 'none';
        }
    });
}

function buscarContribuyentes(query) {
    const searchContainer = document.getElementById('search-results');
    
    fetch('/contribuyentes/catalogo/list?search=' + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            searchContainer.innerHTML = '';
            
            if (data.success && data.data && data.data.length > 0) {
                data.data.forEach(function(contribuyente) {
                    const item = document.createElement('div');
                    item.style.cssText = 'padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;';
                    item.innerHTML = '<strong>' + (contribuyente.razon_social || contribuyente.nombre || '') + '</strong><br>' +
                                    '<small class="text-muted">RFC: ' + (contribuyente.rfc || '') + '</small>';
                    
                    item.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f0f0f0';
                    });
                    
                    item.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = 'white';
                    });
                    
                    item.addEventListener('click', function() {
                        seleccionarContribuyente(contribuyente);
                    });
                    
                    searchContainer.appendChild(item);
                });
                
                searchContainer.style.display = 'block';
            } else {
                searchContainer.innerHTML = '<div style="padding: 10px; color: #999;">No se encontraron contribuyentes</div>';
                searchContainer.style.display = 'block';
            }
        })
        .catch(function(error) {
            console.error('Error al buscar contribuyentes:', error);
            searchContainer.innerHTML = '<div style="padding: 10px; color: #dc3545;">Error al buscar</div>';
            searchContainer.style.display = 'block';
        });
}

function seleccionarContribuyente(contribuyente) {
    const contribuyenteSearch = document.getElementById('contribuyente_search');
    const contribuyenteId = document.getElementById('contribuyente_id');
    const searchContainer = document.getElementById('search-results');
    
    contribuyenteSearch.value = contribuyente.razon_social || contribuyente.nombre || '';
    contribuyenteId.value = contribuyente.id;
    searchContainer.style.display = 'none';
    
    console.log('Contribuyente seleccionado:', contribuyente.id, contribuyente.razon_social);
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== INICIANDO SISTEMA DE MAPA ===');
    initMap();
    
    // Generar clave aleatoria por defecto
    if (!document.getElementById('clave_instalacion').value) {
        generarClaveAleatoria();
    }
});
</script>
@endpush
