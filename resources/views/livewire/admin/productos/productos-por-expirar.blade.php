<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <h4 class="card-title">Almacén y stock de productos</h4>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3">
                        <div class="d-flex justify-content-center">
                            <div wire:loading class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3"> <input type="text" class="form-control form-control-sm"
                            placeholder="Buscar producto" wire:model.debounce.750ms="search"></div>
                    <div class="col-12 mx-auto">

                        @if ($productosSinStock->isNotEmpty())
                            <strong class="text-muted">Resultados de productos sin stock: </strong><br>
                            @foreach ($productosSinStock as $prodSinstock)
                                <span style="cursor: pointer;" onclick="opcionesProducto({
                                id: {{ $prodSinstock->id }},
                                nombre: '{{ $prodSinstock->nombre }}',
                                precio: '{{ $prodSinstock->precio }} Bs',
                                stockTotal: '0',
                                estado: '{{ $prodSinstock->estado ? 'Activo' : 'Inactivo' }}',
                                contable: '{{ $prodSinstock->contable ? 'Sí' : 'No' }}',
                                productoVinculado: '{{ $prodSinstock->productoVinculadoStock ? $prodSinstock->productoVinculadoStock->nombre : 'Ninguno' }}',
                                medicion: '{{ $prodSinstock->medicion }}'
                            })"
                                    class="badge badge-sm  badge-primary">{{ $prodSinstock->nombre }} <i class="fa fa-info-circle"></i></span>
                            @endforeach
                        @endif

                    </div>
                </div>
                <br>


            </div>

            <div class="card-body">
                <div class="col-12">
                    <div class="card">
                        {{-- <div class="card-header border-0 mb-0">
                            <h4 class="fs-20 card-title">Listado de productos</h4>
                        </div> --}}
                        <div class="row mx-auto p-2">
                           <strong>Productos encontrados con stock: {{ $productos->total() }}</strong>
                        </div>
                        <div class="card-body pb-0  pt-0">
                            @foreach ($productos as $item)
                                <div class="media align-items-center">
                                    <div class="media-image me-2 d-none d-md-block">
                                        <img src="{{ asset($item->pathAttachment()) }}" alt=""
                                            class="img-fluid">
                                    </div>
                                    <div class="media-body m-0 mt-2 p-0" style="line-height: 15px">
                                        <h6 class="fs-16 mb-0">
                                                                        <a href="javascript:void(0);" onclick="opcionesProducto({
                                id: {{ $item->id }},
                                nombre: '{{ $item->nombre }}',
                                precio: '{{ $item->precio }} Bs',
                                stockTotal: '{{ $item->stockTotal() }}',
                                estado: '{{ $item->estado ? 'Activo' : 'Inactivo' }}',
                                contable: '{{ $item->contable ? 'Sí' : 'No' }}',
                                productoVinculado: '{{ $item->productoVinculadoStock ? $item->productoVinculadoStock->nombre : 'Ninguno' }}',
                                medicion: '{{ $item->medicion }}'
                            })"
                                class="text-truncate d-inline-block"
                                ><span class="badge badge-primary">{{ $item->nombre }} <i
                                    class="fa fa-info-circle"></i></span></a>
                                        </h6>
                                        <div class="d-flex flex-wrap">
                                            @if ($item->descuento > 0)
                                                <span class="fs-14 mb-1">Precio con descuento: <del
                                                        class="text-muted">{{ $item->precio }}</del> <strong
                                                        class="text-warning">{{ $item->descuento }} Bs</strong>
                                                </span>
                                            @else
                                                <span class="fs-14 mb-1">Precio actual: <strong
                                                        class="text-success">{{ $item->precio }} Bs</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="row">
                                            @foreach ($item->sucursale as $sucursal)
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-2">
                                                    <div class="media align-items-center event-list p-2 bordeado">
                                                        <div class="media-body px-0">
                                                            <div class="float-end">
                                                                <div
                                                                    class="dropdown custom-dropdown mb-0 tbl-orders-style">
                                                                    <div class="btn sharp tp-btn"
                                                                        data-bs-toggle="dropdown">
                                                                        <svg width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z"
                                                                                stroke="#194039" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"></path>
                                                                            <path
                                                                                d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z"
                                                                                stroke="#194039" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"></path>
                                                                            <path
                                                                                d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z"
                                                                                stroke="#194039" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"></path>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        {{-- <a class="dropdown-item" href="javascript:void(0);">Eliminar</a> --}}
                                                                        <a class="dropdown-item text-danger"
                                                                            href="javascript:void(0);"
                                                                            onclick="eliminarStock({{ $sucursal->pivot->id }},'{{ $item->nombre }}')">Eliminar
                                                                            lote</a>
                                                                        <a class="dropdown-item"
                                                                            href="javascript:void(0);"
                                                                            onclick="cambiarFechaExpiracion({{ $sucursal->pivot->id }},'{{ $item->nombre }}','{{ $sucursal->pivot->fecha_venc }}')">Cambiar
                                                                            fecha de expiracion</a>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6 class="mt-0 mb-3 fs-12">Vence :
                                                                {{ App\Helpers\GlobalHelper::fechaFormateada(3, $sucursal->pivot->fecha_venc) }}
                                                                <br>

                                                                <span
                                                                    class="text-{{ $sucursal->pivot->fecha_venc < date('Y-m-d') ? 'danger' : 'success' }}">{{ App\Helpers\GlobalHelper::timeago($sucursal->pivot->fecha_venc) }}</span>

                                                            </h6>

                                                            <ul
                                                                class="fs-14 list-inline mb-2 d-flex flex-column flex-sm-row justify-content-between">
                                                                <li class="mb-1 mb-sm-0">Restantes :
                                                                    {{ $sucursal->pivot->cantidad }}</li>
                                                                <li>Vendidos :
                                                                    {{ $sucursal->pivot->max - $sucursal->pivot->cantidad . '/' . $sucursal->pivot->max }}
                                                                </li>
                                                            </ul>
                                                            @php
                                                                $porcentaje_vendido =
                                                                    (($sucursal->pivot->max -
                                                                        $sucursal->pivot->cantidad) *
                                                                        100) /
                                                                    $sucursal->pivot->max;

                                                                $clase =
                                                                    $porcentaje_vendido <= 25
                                                                        ? 'bg-danger'
                                                                        : ($porcentaje_vendido <= 50
                                                                            ? 'bg-warning'
                                                                            : ($porcentaje_vendido <= 75
                                                                                ? 'bg-info'
                                                                                : 'bg-success'));
                                                            @endphp
                                                            <div class="progress mb-0" style="height:4px; width:100%;">
                                                                <div class="progress-bar {{ $clase }}"
                                                                    style="width:{{ $porcentaje_vendido }}%;"
                                                                    role="progressbar">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>

                                </div>
                                <hr class="p-0 m-0">
                            @endforeach
                            <div class="row mx-auto p-2">
                                <div class="col-12">
                                    {{ $productos->links() }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Variable global para almacenar la información del producto actual
        let productoActual = null;
        function cambiarFechaExpiracion(id, nombreProducto, fechaActual) {
            Swal.fire({
                title: "Cambiar fecha de expiración",
                html: `
                    <div class="swal2-input-container">
                        <label for="nueva-fecha" class="swal2-input-label">Nueva fecha de expiración:</label>
                        <input type="date" id="nueva-fecha" class="swal2-input" value="${fechaActual}" 
                               min="${new Date().toISOString().split('T')[0]}" 
                               style="width: 100%; margin: 10px 0; padding: 10px; border: 1px solid #d3d3d3; border-radius: 5px;">
                        <small class="text-muted">Fecha actual: ${fechaActual}</small>
                    </div>
                `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Sí, cambiar fecha",
                cancelButtonText: "Cancelar",
                focusConfirm: false,
                preConfirm: () => {
                    const nuevaFecha = document.getElementById('nueva-fecha').value;

                    if (!nuevaFecha) {
                        Swal.showValidationMessage('Debes seleccionar una fecha');
                        return false;
                    }

                    const fechaSeleccionada = new Date(nuevaFecha);
                    const fechaActualObj = new Date(fechaActual);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);

                    if (fechaSeleccionada <= hoy) {
                        Swal.showValidationMessage('La nueva fecha debe ser posterior al día de hoy');
                        return false;
                    }

                    if (fechaSeleccionada <= fechaActualObj) {
                        Swal.showValidationMessage(
                            `La nueva fecha debe ser posterior a la fecha actual de expiración (${fechaActual})`
                        );
                        return false;
                    }

                    return nuevaFecha;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Livewire.emit('cambiarFechaExpiracion', id, result.value);
                    Swal.fire({
                        title: "¡Fecha actualizada!",
                        text: `La fecha de expiración de "${nombreProducto}" ha sido cambiada a ${result.value}.`,
                        icon: "success",
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        }

        function eliminarStock(id, nombreProducto) {
            Swal.fire({
                title: "¿Estás seguro?",
                text: `Se eliminará el stock de "${nombreProducto}". Esta acción no se puede deshacer.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminarStock', id);
                    Swal.fire(
                        "Eliminado",
                        `El stock de "${nombreProducto}" ha sido eliminado.`,
                        "success"
                    );
                }
            });
        }

        function opcionesProducto(producto) {
            // Almacenar la información del producto para uso posterior
            productoActual = producto;
            
            Swal.fire({
                title: 'Información del Producto',
                html: `
                    <div style="font-size: 13px; margin: 10px 0; color: white;">
                        <div class="row" style="margin-bottom: 4px;">
                            <div class="col-4" style="color: white;"><strong>Nombre:</strong></div>
                            <div class="col-8" style="color: white; font-weight: 500;">${producto.nombre}</div>
                        </div>
                        <div class="row" style="margin-bottom: 4px;">
                            <div class="col-4" style="color: white;"><strong>Precio:</strong></div>
                            <div class="col-8" style="color: white; font-weight: 600;">${producto.precio}</div>
                        </div>
                        <div class="row" style="margin-bottom: 4px;">
                            <div class="col-4" style="color: white;"><strong>Stock:</strong></div>
                            <div class="col-8" style="color: white; font-weight: 500;">${producto.stockTotal} ${producto.medicion}(s)</div>
                        </div>
                        <div class="row" style="margin-bottom: 4px;">
                            <div class="col-4" style="color: white;"><strong>Estado:</strong></div>
                            <div class="col-8">
                                <span class="badge badge-sm ${producto.estado === 'Activo' ? 'badge-success' : 'badge-danger'}">${producto.estado}</span>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 4px;">
                            <div class="col-4" style="color: white;"><strong>Contable:</strong></div>
                            <div class="col-8">
                                <span class="badge badge-sm ${producto.contable === 'Sí' ? 'badge-info' : 'badge-secondary'}">${producto.contable}</span>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 8px;">
                            <div class="col-4" style="color: white;"><strong>Stock Vinculado:</strong></div>
                            <div class="col-8" style="color: ${producto.productoVinculado === 'Ninguno' ? '#dc3545' : 'white'}; font-weight: 500;">
                                ${producto.productoVinculado}
                                ${producto.productoVinculado !== 'Ninguno' ? '<i class="fa fa-trash text-danger ms-2" style="cursor: pointer;" onclick="eliminarVinculoStock(' + producto.id + ')"></i>' : ''}
                            </div>
                        </div>
                        <hr style="margin: 8px 0; border-color: rgba(255,255,255,0.3);">
                        <div class="text-center">
                            <strong style="font-size: 14px; color: white;">¿Qué quieres hacer?</strong>
                        </div>
                    </div>
                `,
                icon: 'question',
                showCancelButton: false,
                showDenyButton: true,
                confirmButtonColor: '#28a745',
                denyButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-plus"></i> Agregar Stock',
                denyButtonText: '<i class="fa fa-link"></i> Vincular Stock',
                cancelButtonText: 'Cancelar',
                buttonsStyling: true,
                customClass: {
                    actions: 'my-actions',
                    confirmButton: 'btn btn-success',
                    denyButton: 'btn btn-info',
                    cancelButton: 'btn btn-secondary'
                },
                footer: '<button id="editarProductoBtn" class="btn btn-warning" style="margin-right: 10px;"><i class="fa fa-edit"></i> Editar Producto</button>'
            }).then((result) => {
                if (result.isConfirmed) {
                    agregarStock(producto.id, producto.medicion, producto.nombre);
                } else if (result.isDenied) {
                    vincularStock(producto.id, producto.nombre, producto.medicion);
                }
            });

            // Agregar event listener para el botón de editar producto
            setTimeout(() => {
                const editarBtn = document.getElementById('editarProductoBtn');
                if (editarBtn) {
                    editarBtn.addEventListener('click', function() {
                        Swal.close();
                        editarProducto(producto.nombre);
                    });
                }
            }, 100);
        }
        function eliminarVinculoStock(productoId) {
            Livewire.emit('eliminarVinculoStock', productoId);
        }
        function editarProducto(nombreProducto) {
            // Construir la URL usando la ruta comentada en el código
            const url = `{{ route('producto.listar', ['buscar' => '__NOMBRE__']) }}`.replace('__NOMBRE__', encodeURIComponent(nombreProducto));
            
            // Abrir en nueva pestaña
            window.open(url, '_blank');
        }

        function agregarStock(productoId, unidad, nombreProducto) {
            const hoy = new Date().toISOString().split('T')[0];
            
            Swal.fire({
                title: `Agregar Stock - ${nombreProducto}`,
                html: `
                    <div class="swal2-input-container">
                        <label for="fecha-expiracion" class="swal2-input-label">Fecha de Expiración:</label>
                        <input type="date" id="fecha-expiracion" class="swal2-input" 
                               value="${hoy}"
                               min="${hoy}" 
                               style="width: 100%; margin: 10px 0; padding: 10px; border: 1px solid #d3d3d3; border-radius: 5px;">
                        <div id="dias-expiracion" style="font-size: 12px; color: #666; margin-top: 5px; display: block;">
                            <i class="fa fa-exclamation-circle text-warning"></i> <strong class="text-warning">Vence hoy</strong>
                        </div>
                        
                        <label for="cantidad-items" class="swal2-input-label">Cantidad en ${unidad}(s):</label>
                        <input type="number" id="cantidad-items" class="swal2-input" 
                               min="1" placeholder="Ingrese la cantidad"
                               style="width: 100%; margin: 10px 0; padding: 10px; border: 1px solid #d3d3d3; border-radius: 5px;">
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Agregar Stock',
                cancelButtonText: '<i class="fa fa-arrow-left"></i> Atrás',
                focusConfirm: false,
                didOpen: () => {
                    const fechaInput = document.getElementById('fecha-expiracion');
                    const diasDiv = document.getElementById('dias-expiracion');
                    
                    function calcularDias() {
                        const fechaSeleccionada = new Date(fechaInput.value);
                        const hoy = new Date();
                        hoy.setHours(0, 0, 0, 0);
                        fechaSeleccionada.setHours(0, 0, 0, 0);
                        
                        if (fechaInput.value) {
                            const diferenciaTiempo = fechaSeleccionada.getTime() - hoy.getTime();
                            const diferenciaDias = Math.round(diferenciaTiempo / (1000 * 3600 * 24));
                            
                            if ((diferenciaDias + 1) === 0) {
                                diasDiv.innerHTML = '<i class="fa fa-exclamation-circle text-warning"></i> <strong class="text-warning">Vence hoy</strong>';
                                diasDiv.style.color = '#ffc107';
                            } else if ((diferenciaDias + 1) === 1) {
                                diasDiv.innerHTML = '<i class="fa fa-clock text-warning"></i> Expira mañana (1 día)';
                                diasDiv.style.color = '#ffc107';
                            } else if ((diferenciaDias + 1) <= 7) {
                                diasDiv.innerHTML = '<i class="fa fa-exclamation-triangle text-warning"></i> Expira en ' + (diferenciaDias + 1) + ' días';
                                diasDiv.style.color = '#ffc107';
                            } else if ((diferenciaDias + 1) <= 30) {
                                diasDiv.innerHTML = '<i class="fa fa-calendar text-warning"></i> Expira en ' + (diferenciaDias + 1) + ' días';
                                diasDiv.style.color = '#ffc107';
                            } else {
                                diasDiv.innerHTML = '<i class="fa fa-calendar-check text-warning"></i> Expira en ' + (diferenciaDias + 1) + ' días';
                                diasDiv.style.color = '#ffc107';
                            }
                            
                            diasDiv.style.display = 'block';
                        } else {
                            diasDiv.style.display = 'none';
                        }
                    }
                    
                    fechaInput.addEventListener('change', calcularDias);
                    fechaInput.addEventListener('input', calcularDias);
                },
                preConfirm: () => {
                    const fechaExpiracion = document.getElementById('fecha-expiracion').value;
                    const cantidad = document.getElementById('cantidad-items').value;

                    if (!fechaExpiracion) {
                        Swal.showValidationMessage('Debes seleccionar una fecha de expiración');
                        return false;
                    }

                    if (!cantidad || cantidad <= 0) {
                        Swal.showValidationMessage('Debes ingresar una cantidad válida mayor a 0');
                        return false;
                    }

                    const fechaSeleccionada = new Date(fechaExpiracion);
                    const hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);
                    fechaSeleccionada.setHours(0, 0, 0, 0);

                    

                    return {
                        fechaExpiracion,
                        cantidad
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Livewire.emit('agregarStock', productoId, result.value.fechaExpiracion, result.value.cantidad);
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                    // Volver al menú de opciones usando la información almacenada
                    opcionesProducto(productoActual);
                }
            });
        }

        function vincularStock(productoId, nombreProducto, unidad) {
            Swal.fire({
                title: `Vincular Stock - ${nombreProducto}`,
                html: `
                    <div class="swal2-input-container">
                        <label for="buscar-producto" class="swal2-input-label">Buscar Producto:</label>
                        <input type="text" id="buscar-producto" class="swal2-input" 
                               placeholder="Escriba el nombre del producto"
                               style="width: 100%; margin: 10px 0; padding: 10px; border: 1px solid #d3d3d3; border-radius: 5px;">
                        
                        <div id="resultados-busqueda" style="max-height: 200px; overflow-y: auto; margin-top: 10px; border: 1px solid #e9ecef; border-radius: 5px; display: none;">
                        </div>
                        
                        <input type="hidden" id="producto-seleccionado-id" value="">
                        <div id="producto-seleccionado" style="margin-top: 10px; display: none;">
                            <strong>Producto seleccionado:</strong> <span id="nombre-producto-seleccionado"></span>
                        </div>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Vincular Stock',
                cancelButtonText: '<i class="fa fa-arrow-left"></i> Atrás',
                focusConfirm: false,
                didOpen: () => {
                    const buscarInput = document.getElementById('buscar-producto');
                    const resultadosDiv = document.getElementById('resultados-busqueda');
                    const productoSeleccionadoId = document.getElementById('producto-seleccionado-id');
                    const productoSeleccionadoDiv = document.getElementById('producto-seleccionado');
                    const nombreProductoSeleccionado = document.getElementById('nombre-producto-seleccionado');

                    let timeoutId;

                    buscarInput.addEventListener('input', function() {
                        clearTimeout(timeoutId);
                        const busqueda = this.value.trim();

                        if (busqueda.length >= 2) {
                            timeoutId = setTimeout(() => {
                                // Emitir evento para buscar productos
                                Livewire.emit('buscarProductosParaVincular', busqueda);
                            }, 500);
                        } else {
                            resultadosDiv.style.display = 'none';
                            resultadosDiv.innerHTML = '';
                        }
                    });

                    // Escuchar eventos de productos encontrados
                    window.addEventListener('productosEncontrados', function(event) {
                        const productos = event.detail.productos;

                        if (productos.length > 0) {
                            let html = '<ul class="list-group list-group-flush">';
                            productos.forEach(producto => {
                                html += `<li class="list-group-item list-group-item-action" 
                                           style="cursor: pointer; padding: 8px 12px;" 
                                           onclick="seleccionarProducto(${producto.id}, '${producto.nombre}')">
                                           ${producto.nombre}
                                         </li>`;
                            });
                            html += '</ul>';

                            resultadosDiv.innerHTML = html;
                            resultadosDiv.style.display = 'block';
                        } else {
                            resultadosDiv.innerHTML =
                                '<p class="text-muted text-center p-3">No se encontraron productos</p>';
                            resultadosDiv.style.display = 'block';
                        }
                    });

                    // Función global para seleccionar producto
                    window.seleccionarProducto = function(id, nombre) {
                        productoSeleccionadoId.value = id;
                        nombreProductoSeleccionado.textContent = nombre;
                        productoSeleccionadoDiv.style.display = 'block';
                        resultadosDiv.style.display = 'none';
                        buscarInput.value = nombre;
                    };
                },
                preConfirm: () => {
                    const productoSeleccionadoId = document.getElementById('producto-seleccionado-id').value;

                    if (!productoSeleccionadoId) {
                        Swal.showValidationMessage('Debes seleccionar un producto para vincular el stock');
                        return false;
                    }

                    return productoSeleccionadoId;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    Livewire.emit('vincularStock', productoId, result.value);
                } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
                    // Volver al menú de opciones usando la información almacenada
                    opcionesProducto(productoActual);
                }
            });
        }
    </script>
@endpush
