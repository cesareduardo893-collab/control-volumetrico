/**
 * FormEnhancements - Librería para mejorar formularios
 * Facilita el llenado de datos con autocompletado, selects dependientes, etc.
 */

const FormEnhancements = {
    
    // Configuración por defecto
    config: {
        apiBaseUrl: '/api',
        debounceMs: 300,
        minChars: 2,
    },

    /**
     * Inicializar todas las mejoras en un formulario
     */
    init(formSelector = 'form') {
        this.initSelect2();
        this.initAutoComplete();
        this.initDependentSelects();
        this.initAutoFill();
        this.initFormValidation();
        this.initKeyboardShortcuts();
        console.log('FormEnhancements initialized');
    },

    /**
     * Inicializar Select2 en todos los selects
     */
    initSelect2() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap-5',
                language: 'es',
                placeholder: 'Buscar...',
                allowClear: true,
                width: '100%'
            });
        }
    },

    /**
     * Crear campo de autocompletado
     */
    createAutoComplete(inputId, options = {}) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const defaults = {
            url: '',
            minChars: this.config.minChars,
            debounceMs: this.config.debounceMs,
            onSelect: null,
            displayField: 'nombre',
            valueField: 'id',
            searchFields: ['nombre', 'rfc', 'clave'],
            placeholder: 'Escriba para buscar...'
        };

        const config = { ...defaults, ...options };
        
        // Crear contenedor
        const wrapper = document.createElement('div');
        wrapper.className = 'autocomplete-wrapper position-relative';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        // Crear lista de resultados
        const resultsList = document.createElement('div');
        resultsList.className = 'autocomplete-results list-group position-absolute w-100';
        resultsList.style.cssText = 'z-index: 1050; max-height: 250px; overflow-y: auto; display: none;';
        wrapper.appendChild(resultsList);

        let debounceTimer;
        let selectedIndex = -1;

        // Evento de input
        input.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < config.minChars) {
                resultsList.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                this.search(query, config, resultsList, input);
            }, config.debounceMs);
        });

        // Navegación con teclado
        input.addEventListener('keydown', (e) => {
            const items = resultsList.querySelectorAll('.autocomplete-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                this.highlightItem(items, selectedIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, 0);
                this.highlightItem(items, selectedIndex);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                items[selectedIndex].click();
            } else if (e.key === 'Escape') {
                resultsList.style.display = 'none';
                selectedIndex = -1;
            }
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                resultsList.style.display = 'none';
            }
        });
    },

    /**
     * Realizar búsqueda
     */
    async search(query, config, resultsList, input) {
        try {
            const response = await fetch(`${config.url}?search=${encodeURIComponent(query)}`);
            const data = await response.json();

            resultsList.innerHTML = '';
            selectedIndex = -1;

            if (data.data && data.data.length > 0) {
                data.data.forEach((item, index) => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-item list-group-item list-group-item-action';
                    div.style.cursor = 'pointer';
                    
                    // Mostrar múltiples campos
                    let displayText = item[config.displayField] || '';
                    if (config.searchFields.length > 1) {
                        const extras = config.searchFields
                            .slice(1)
                            .map(f => item[f])
                            .filter(Boolean)
                            .join(' | ');
                        if (extras) displayText += ` <small class="text-muted">(${extras})</small>`;
                    }
                    
                    div.innerHTML = displayText;
                    div.dataset.value = item[config.valueField];
                    div.dataset.item = JSON.stringify(item);

                    div.addEventListener('mouseenter', () => {
                        this.highlightItem(resultsList.querySelectorAll('.autocomplete-item'), index);
                        selectedIndex = index;
                    });

                    div.addEventListener('click', () => {
                        input.value = item[config.displayField];
                        input.dataset.selectedId = item[config.valueField];
                        resultsList.style.display = 'none';
                        
                        if (config.onSelect) {
                            config.onSelect(item);
                        }

                        // Disparar evento change
                        input.dispatchEvent(new Event('change', { bubbles: true }));
                    });

                    resultsList.appendChild(div);
                });
                resultsList.style.display = 'block';
            } else {
                resultsList.innerHTML = '<div class="list-group-item text-muted">No se encontraron resultados</div>';
                resultsList.style.display = 'block';
            }
        } catch (error) {
            console.error('Error en autocompletado:', error);
        }
    },

    /**
     * Resaltar elemento seleccionado
     */
    highlightItem(items, index) {
        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });
    },

    /**
     * Inicializar selects dependientes (cascada)
     */
    initDependentSelects() {
        // Ejemplo: Al cambiar contribuyente, filtrar instalaciones
        document.querySelectorAll('[data-depends-on]').forEach(select => {
            const parentField = select.dataset.dependsOn;
            const parentSelect = document.getElementById(parentField) || document.querySelector(`[name="${parentField}"]`);
            
            if (parentSelect) {
                parentSelect.addEventListener('change', (e) => {
                    this.loadDependentOptions(select, e.target.value);
                });
            }
        });
    },

    /**
     * Cargar opciones dependientes
     */
    async loadDependentOptions(select, parentValue) {
        const url = select.dataset.url;
        const parentField = select.dataset.parentField || 'parent_id';
        const displayField = select.dataset.displayField || 'nombre';
        const valueField = select.dataset.valueField || 'id';

        if (!url || !parentValue) {
            select.innerHTML = '<option value="">Seleccione primero el campo anterior</option>';
            return;
        }

        select.innerHTML = '<option value="">Cargando...</option>';
        select.disabled = true;

        try {
            const response = await fetch(`${url}?${parentField}=${parentValue}`);
            const data = await response.json();

            select.innerHTML = '<option value="">Seleccione...</option>';
            
            const items = data.data || data;
            if (Array.isArray(items)) {
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[valueField];
                    option.textContent = item[displayField];
                    select.appendChild(option);
                });
            }

            select.disabled = false;
        } catch (error) {
            console.error('Error cargando opciones dependientes:', error);
            select.innerHTML = '<option value="">Error al cargar</option>';
        }
    },

    /**
     * Inicializar auto-rellenado basado en selecciones
     */
    initAutoFill() {
        // Al seleccionar un contribuyente, auto-rellenar RFC, dirección, etc.
        document.querySelectorAll('[data-autofill-source]').forEach(select => {
            select.addEventListener('change', (e) => {
                const selectedItem = e.target.options[e.target.selectedIndex];
                if (selectedItem && selectedItem.dataset.autofill) {
                    try {
                        const data = JSON.parse(selectedItem.dataset.autofill);
                        this.applyAutoFill(data);
                    } catch (error) {
                        console.error('Error en auto-rellenado:', error);
                    }
                }
            });
        });
    },

    /**
     * Aplicar auto-rellenado
     */
    applyAutoFill(data) {
        Object.keys(data).forEach(field => {
            const input = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
            if (input && !input.value) {
                input.value = data[field];
                // Animación visual
                input.classList.add('bg-success-subtle');
                setTimeout(() => input.classList.remove('bg-success-subtle'), 1000);
            }
        });
    },

    /**
     * Inicializar validación de formulario en tiempo real
     */
    initFormValidation() {
        document.querySelectorAll('form').forEach(form => {
            // Validar campos requeridos
            form.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('is-invalid')) {
                        this.validateField(input);
                    }
                });
            });

            // Validar antes de enviar
            form.addEventListener('submit', (e) => {
                let isValid = true;
                form.querySelectorAll('[required]').forEach(input => {
                    if (!this.validateField(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    // Scroll al primer campo con error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });
        });
    },

    /**
     * Validar campo individual
     */
    validateField(input) {
        const value = input.value.trim();
        let isValid = true;
        let message = '';

        if (input.required && !value) {
            isValid = false;
            message = 'Este campo es requerido';
        } else if (input.type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
            message = 'Email inválido';
        } else if (input.dataset.minLength && value.length < parseInt(input.dataset.minLength)) {
            isValid = false;
            message = `Mínimo ${input.dataset.minLength} caracteres`;
        } else if (input.dataset.maxLength && value.length > parseInt(input.dataset.maxLength)) {
            isValid = false;
            message = `Máximo ${input.dataset.maxLength} caracteres`;
        } else if (input.dataset.pattern && value && !new RegExp(input.dataset.pattern).test(value)) {
            isValid = false;
            message = input.dataset.patternMessage || 'Formato inválido';
        }

        // Actualizar UI
        input.classList.toggle('is-invalid', !isValid);
        input.classList.toggle('is-valid', isValid && value);

        // Mostrar/ocultar mensaje de error
        let feedback = input.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
        feedback.style.display = isValid ? 'none' : 'block';

        return isValid;
    },

    /**
     * Validar email
     */
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    /**
     * Inicializar atajos de teclado
     */
    initKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl + S = Guardar
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                const form = document.querySelector('form');
                if (form) form.submit();
            }

            // Escape = Cancelar/Volver
            if (e.key === 'Escape') {
                const cancelBtn = document.querySelector('a.btn-secondary, a.btn-danger');
                if (cancelBtn) cancelBtn.click();
            }
        });
    },

    /**
     * Duplicar registro existente
     */
    async duplicateRecord(module, id) {
        if (!confirm('¿Desea duplicar este registro?')) return;

        try {
            const response = await fetch(`/api/${module}/${id}/duplicate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = `/${module}/${data.data.id}/edit`;
            } else {
                alert('Error al duplicar: ' + (data.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error duplicando registro:', error);
            alert('Error al duplicar el registro');
        }
    },

    /**
     * Guardar borrador del formulario
     */
    saveDraft(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        localStorage.setItem(`draft_${formId}`, JSON.stringify({
            data,
            savedAt: new Date().toISOString()
        }));

        this.showNotification('Borrador guardado', 'success');
    },

    /**
     * Restaurar borrador
     */
    restoreDraft(formId) {
        const draft = localStorage.getItem(`draft_${formId}`);
        if (!draft) return null;

        const { data, savedAt } = JSON.parse(draft);
        const savedDate = new Date(savedAt);
        const hoursSince = (new Date() - savedDate) / (1000 * 60 * 60);

        // Eliminar borradores de más de 24 horas
        if (hoursSince > 24) {
            localStorage.removeItem(`draft_${formId}`);
            return null;
        }

        return { data, savedAt: savedDate };
    },

    /**
     * Mostrar notificación
     */
    showNotification(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Toast(toast).show();
        }
        
        setTimeout(() => toast.remove(), 3000);
    },

    /**
     * Crear campo de autocompletado para contribuyentes
     */
    initContribuyenteAutocomplete(inputId) {
        this.createAutoComplete(inputId, {
            url: '/api/contribuyentes/search',
            displayField: 'razon_social',
            valueField: 'id',
            searchFields: ['razon_social', 'rfc'],
            onSelect: (item) => {
                // Auto-rellenar campos relacionados
                const rfcInput = document.getElementById('rfc');
                if (rfcInput) rfcInput.value = item.rfc || '';
            }
        });
    },

    /**
     * Crear campo de autocompletado para instalaciones
     */
    initInstalacionAutocomplete(inputId, contribuyenteId = null) {
        this.createAutoComplete(inputId, {
            url: contribuyenteId 
                ? `/api/instalaciones/search?contribuyente_id=${contribuyenteId}`
                : '/api/instalaciones/search',
            displayField: 'nombre',
            valueField: 'id',
            searchFields: ['nombre', 'clave_instalacion'],
            onSelect: (item) => {
                // Auto-rellenar dirección
                const domicilioInput = document.getElementById('domicilio');
                if (domicilioInput && item.domicilio) {
                    domicilioInput.value = item.domicilio;
                }
            }
        });
    },

    /**
     * Crear campo de autocompletado para productos
     */
    initProductoAutocomplete(inputId) {
        this.createAutoComplete(inputId, {
            url: '/api/productos/search',
            displayField: 'nombre',
            valueField: 'id',
            searchFields: ['nombre', 'clave_producto', 'clave_sat'],
            onSelect: (item) => {
                // Auto-rellenar clave SAT
                const claveSatInput = document.getElementById('clave_sat');
                if (claveSatInput) claveSatInput.value = item.clave_sat || '';
            }
        });
    },

    /**
     * Configurar select dependiente: Contribuyente -> Instalaciones
     */
    initContribuyenteInstalacionDependency(contribuyenteSelectId, instalacionSelectId) {
        const contribuyenteSelect = document.getElementById(contribuyenteSelectId);
        const instalacionSelect = document.getElementById(instalacionSelectId);

        if (!contribuyenteSelect || !instalacionSelect) return;

        contribuyenteSelect.addEventListener('change', async (e) => {
            const contribuyenteId = e.target.value;
            
            instalacionSelect.innerHTML = '<option value="">Cargando...</option>';
            instalacionSelect.disabled = true;

            if (!contribuyenteId) {
                instalacionSelect.innerHTML = '<option value="">Seleccione primero un contribuyente</option>';
                return;
            }

            try {
                const response = await fetch(`/api/instalaciones?contribuyente_id=${contribuyenteId}&activo=true`);
                const data = await response.json();

                instalacionSelect.innerHTML = '<option value="">Seleccione...</option>';
                
                (data.data || []).forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.clave_instalacion} - ${item.nombre}`;
                    option.dataset.domicilio = item.domicilio || '';
                    instalacionSelect.appendChild(option);
                });

                instalacionSelect.disabled = false;
            } catch (error) {
                console.error('Error cargando instalaciones:', error);
                instalacionSelect.innerHTML = '<option value="">Error al cargar</option>';
            }
        });

        // Al cambiar instalación, auto-rellenar domicilio
        instalacionSelect.addEventListener('change', (e) => {
            const selectedOption = e.target.options[e.target.selectedIndex];
            if (selectedOption && selectedOption.dataset.domicilio) {
                const domicilioInput = document.getElementById('domicilio');
                if (domicilioInput) {
                    domicilioInput.value = selectedOption.dataset.domicilio;
                }
            }
        });
    },

    /**
     * Configurar select dependiente: Instalación -> Tanques
     */
    initInstalacionTanqueDependency(instalacionSelectId, tanqueSelectId) {
        const instalacionSelect = document.getElementById(instalacionSelectId);
        const tanqueSelect = document.getElementById(tanqueSelectId);

        if (!instalacionSelect || !tanqueSelect) return;

        instalacionSelect.addEventListener('change', async (e) => {
            const instalacionId = e.target.value;
            
            tanqueSelect.innerHTML = '<option value="">Cargando...</option>';
            tanqueSelect.disabled = true;

            if (!instalacionId) {
                tanqueSelect.innerHTML = '<option value="">Seleccione primero una instalación</option>';
                return;
            }

            try {
                const response = await fetch(`/api/tanques?instalacion_id=${instalacionId}&estado=OPERACION`);
                const data = await response.json();

                tanqueSelect.innerHTML = '<option value="">Seleccione...</option>';
                
                (data.data || []).forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.clave_tanque} - ${item.nombre || 'Sin nombre'}`;
                    option.dataset.capacidad = item.capacidad_total || 0;
                    option.dataset.producto = item.producto_id || '';
                    tanqueSelect.appendChild(option);
                });

                tanqueSelect.disabled = false;
            } catch (error) {
                console.error('Error cargando tanques:', error);
                tanqueSelect.innerHTML = '<option value="">Error al cargar</option>';
            }
        });
    },

    /**
     * Calcular automáticamente campos derivados
     */
    initAutoCalculations() {
        // Cálculo de volumen operación = volumen_final - volumen_inicial
        const volumenInicial = document.getElementById('volumen_inicial');
        const volumenFinal = document.getElementById('volumen_final');
        const volumenOperacion = document.getElementById('volumen_operacion');

        if (volumenInicial && volumenFinal && volumenOperacion) {
            const calcular = () => {
                const inicial = parseFloat(volumenInicial.value) || 0;
                const final = parseFloat(volumenFinal.value) || 0;
                volumenOperacion.value = (final - inicial).toFixed(2);
            };

            volumenInicial.addEventListener('input', calcular);
            volumenFinal.addEventListener('input', calcular);
        }

        // Cálculo de masa = volumen * densidad
        const volumen = document.getElementById('volumen_operacion');
        const densidad = document.getElementById('densidad');
        const masa = document.getElementById('masa');

        if (volumen && densidad && masa) {
            const calcularMasa = () => {
                const vol = parseFloat(volumen.value) || 0;
                const den = parseFloat(densidad.value) || 0;
                masa.value = (vol * den).toFixed(4);
            };

            volumen.addEventListener('input', calcularMasa);
            densidad.addEventListener('input', calcularMasa);
        }
    },

    /**
     * Formatear campos automáticamente
     */
    initAutoFormatting() {
        // RFC en mayúsculas
        document.querySelectorAll('[name="rfc"]').forEach(input => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.toUpperCase();
            });
        });

        // Código postal solo números
        document.querySelectorAll('[name="codigo_postal"]').forEach(input => {
            input.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 5);
            });
        });

        // Teléfono con formato
        document.querySelectorAll('[name="telefono"]').forEach(input => {
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 10) value = value.slice(0, 10);
                if (value.length >= 6) {
                    value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6)}`;
                }
                e.target.value = value;
            });
        });
    }
};

// Exportar para uso global
window.FormEnhancements = FormEnhancements;

// Auto-inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    FormEnhancements.init();
    FormEnhancements.initAutoCalculations();
    FormEnhancements.initAutoFormatting();
});
