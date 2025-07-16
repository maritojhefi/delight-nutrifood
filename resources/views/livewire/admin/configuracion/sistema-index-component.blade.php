<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <h3 class="p-2"><strong>Configuraciones del sistema</strong></h3>
                        <div class="col-sm-3">
                            <div class="nav flex-column nav-pills mb-3" role="tablist">
                                <a href="#v-pills-texto" data-bs-toggle="pill" class="nav-link active show"
                                    aria-selected="true" role="tab" wire:ignore>Texto</a>
                                <a href="#v-pills-imagenes" data-bs-toggle="pill" class="nav-link" aria-selected="false"
                                    tabindex="-1" role="tab" wire:ignore>Imágenes</a>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="tab-content">
                                <div id="v-pills-texto" class="tab-pane fade active show" role="tabpanel"
                                    wire:ignore.self>
                                    <div class="email-list mt-3">
                                        @foreach ($configuraciones as $index => $configuracion)
                                            @if (!$configuracion['es_imagen'])
                                                <div class="message">
                                                    <div>
                                                        <div class="d-flex message-single">
                                                            <div class="ps-1 align-self-center col-4">
                                                                <div class="form-check custom-checkbox">
                                                                    <label for="{{ $configuracion['clave'] }}"
                                                                        class="form-label">
                                                                        {{ ucfirst(str_replace('_', ' ', $configuracion['clave'])) }}
                                                                    </label>
                                                                    <div wire:loading class="spinner-border"
                                                                        role="status"
                                                                        style="width: 1rem; height: 1rem; font-size: 10px;">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="ms-2 col-6">
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    id="{{ $configuracion['clave'] }}"
                                                                    wire:model.defer="configuraciones.{{ $index }}.valor">
                                                            </div>
                                                            <div class="col-2 text-center">
                                                                <button type="button"
                                                                    class="btn btn-rounded btn-info btn-sm"
                                                                    wire:click="saveTextSetting({{ $index }})"
                                                                    wire:loading.attr="disabled">
                                                                    <span wire:loading.remove>Guardar</span>
                                                                    <span wire:loading wire:loading.attr="disabled">
                                                                        Cargando...
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div id="v-pills-imagenes" class="tab-pane fade" role="tabpanel" wire:ignore.self>
                                    <div class="row">
                                        @foreach ($configuraciones as $index => $configuracion)
                                            @if ($configuracion['es_imagen'])
                                                <div class="message">
                                                    <div>
                                                        <div class="d-flex message-single">
                                                            <div class="ps-1 align-self-center col-4">
                                                                <div class="form-check custom-checkbox">
                                                                    @if ($configuracion['valor'])
                                                                        <button type="button"
                                                                            class="btn btn-primary btn-xxs btn-rounded"
                                                                            onclick="previewImage('{{ asset($configuracion['valor']) }}')">
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                    @endif
                                                                    <label for="{{ $configuracion['clave'] }}"
                                                                        class="form-label">
                                                                        {{ ucfirst(str_replace('_', ' ', $configuracion['clave'])) }}
                                                                    </label>




                                                                </div>
                                                            </div>
                                                            <div class="ms-2 col-6">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm {{ isset($validationStates[$index]) ? ($validationStates[$index]['is_valid'] ? 'is-valid' : 'is-invalid') : '' }}"
                                                                        id="{{ $configuracion['clave'] }}"
                                                                        wire:model.defer="configuraciones.{{ $index }}.valor">
                                                                    @if (isset($validationStates[$index]))
                                                                        <span class="input-group-text">
                                                                            @if ($validationStates[$index]['is_valid'])
                                                                                <i class="fa fa-check text-success"></i>
                                                                            @else
                                                                                <i class="fa fa-ban text-danger"></i>
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                @if (isset($validationStates[$index]) && !$validationStates[$index]['is_valid'])
                                                                    <small
                                                                        class="text-danger">{{ $validationStates[$index]['message'] }}</small>
                                                                @endif
                                                                @error('configuraciones.' . $index . '.valor')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                            <div class="col-2 text-center">
                                                                <button type="button"
                                                                    class="btn btn-rounded btn-info btn-sm"
                                                                    wire:click="saveImageSetting({{ $index }})"
                                                                    wire:loading.attr="disabled">
                                                                    <span wire:loading.remove>Guardar</span>
                                                                    <span wire:loading wire:loading.attr="disabled">
                                                                        <i class="fa fa-spinner fa-spin"></i>
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Animación para cambios de estado */
            .btn-loading {
                position: relative;
            }

            .btn-loading:disabled {
                opacity: 0.8;
            }

            /* Estilo para el placeholder de imagen */
            .img-placeholder {
                background-color: #f8f9fa;
                border: 1px dashed #dee2e6;
            }

            /* Transición suave para imágenes */
            img {
                transition: opacity 0.3s ease;
            }

            img:hover {
                opacity: 0.9;
            }

            /* Mejor alineación de spinner */
            .fa-spinner {
                margin-right: 0.3rem;
            }
        </style>
    @endpush
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    @push('scripts')
        <script>
            // Inicializar Fancybox
            document.addEventListener('livewire:load', function() {
                Fancybox.bind();

                // Función para previsualizar imagen
                window.previewImage = function(imageUrl) {
                    Fancybox.show([{
                        src: imageUrl,
                        type: 'image'
                    }]);
                };

                Livewire.on('notify', (data) => {
                    if (data.type === 'error') {
                        // Construir lista HTML de errores
                        let errorsList = '';
                        if (data.errors && data.errors.length > 0) {
                            errorsList = '<ul>';
                            data.errors.forEach(error => {
                                errorsList += `<li>* ${error}</li>`;
                            });
                            errorsList += '</ul>';
                        } else {
                            errorsList = `<p>${data.message}</p>`;
                        }
                        Swal.fire({
                            title: 'Error',
                            icon: 'error',
                            html: `
                        <div class="text-start">
                            <p>No se puede guardar la configuración:</p>
                            ${errorsList}
                        </div>
                    `,
                            confirmButtonText: 'Entendido'
                        });
                    } else {
                        Swal.fire({
                            text: data.message,
                            icon: data.type,
                        });
                    }
                });
            });
        </script>
    @endpush
</div>
