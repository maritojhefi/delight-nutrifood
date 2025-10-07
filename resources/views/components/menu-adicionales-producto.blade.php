<div id="detalles-menu" class="menu menu-box-bottom rounded-m pb-5" style="z-index: 1056;">    
    <div class="menu-title">
        <p class="color-highlight">Delight-Nutrifood</p>
        <h1 class="font-22">Personaliza tu orden</h1>
        <a href="#" id="btn-cerrar-detalles" class=""><i class="fa fa-times-circle"></i></a>
    </div>
    <div class="divider mb-0"></div>
    <div class="content mt-3">
        <div class="d-flex mb-3 flex-row gap-4 align-items-center">
            <img id="detalles-menu-img" src="{{asset(GlobalHelper::getValorAtributoSetting('bg_default'))}}" class="rounded-sm"
            style="width: 5rem; height: 5rem; object-fit: cover;">

            <div>
                <h2 id="detalles-menu-nombre" class="font-16 line-height-s mt-1 mb-n1">Nombre del producto</h2>
                <div id="contenedor-cantidad-encarrito" class="d-none">
                    <small>Unidades en carrito: <span id="contador-cantidad-encarrito">0</span></small>
                </div>
                <div id="error-producto-container" class="alert bg-red-light p-1 rounded-s mt-2 mb-0" style="display: none;">
                    <p id="error-producto-message" class="text-white text-center">El stock del producto no parece ser suficiente.</p>
                </div>
            </div>
        </div>
        <form id="detalles-menu-adicionales">
            {{-- RENDERIZADO CONDICIONAL ADICIONALES --}}
        </form>
        <div class="divider mb-2"></div>
        @if (!$isUpdate)
        <div id="controladores-cantidad">
            <div class="line-height-xs">
                <small class="color-theme">Los extras seleccionados se veran reflejados en la cantidad del pedido.</small>
            </div>
            <div class="d-flex mb-0 py-1">
                <div class="align-self-center">
                    <h5 class="mb-0">Cantidad</h5>
                    <!-- <small>Disponible(s): <span id="stock-producto-value">x</span></small> -->
                </div>
                <div class="ms-auto align-self-center">
                    <div class="stepper rounded-s small-switch me-n2">
                        <a id="detalles-stepper-down" href="#" class="stepper-sub"><i class="fa fa-minus color-theme opacity-40"></i></a>
                        <input style="font-size: 15px !important;" form="detalles-menu-adicionales" name="cantidad-orden" id="detalles-cantidad" type="number" min="1" max="99" value="1">
                        <a id="detalles-stepper-up" href="#" class="stepper-add"><i class="fa fa-plus color-theme opacity-40"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <div id="error-limitados-container" class="alert bg-orange-light line-height-s text-white p-2 rounded-s" style="display: none;">
            <p id="error-limitados-message" class="text-white">Algunos adicionales disponen de stock bajo, se ajusto la cantidad de su orden.</p>
        </div>
        <div id="error-agotados-container" class="alert bg-orange-light p-2 rounded-s" style="display: none;">
            <p id="error-agotados-message" class="text-white">Algunos adicionales se encuentran agotados.</p>
        </div>
        <div class="d-flex mb-3 mt-1">
            <div class="align-self-center">
                <h5 class="mb-0">Costo Unitario Producto</h5>
            </div>
            <div class="ms-auto align-self-center">
                <h5 id="detalle-costo-unitario" class="mb-0">Bs. 2.53</h5>
            </div>
        </div>
        <div id="display-adicionales" style="display: none;">
            <div class="d-flex mb-3">
                <div class="align-self-center">
                    <h5 class="mb-0">Costo Adicionales</h5>
                </div>
                <div class="ms-auto align-self-center">
                    <h5 id="detalle-costo-adicionales" class="mb-0">Bs. 0</h5>
                </div>
            </div>
        </div>
        <div class="d-flex mb-2">
            <div class="align-self-center">
                <h5 class="mb-0">Costo Total</h5>
            </div>
            <div class="ms-auto align-self-center">
                <h5 id="detalle-costo-total" class="mb-0">Bs. 25.30</h5>
            </div>
        </div>
        <div class="divider mb-2"></div>
        <div id="boton-accion-container">
            <button type="submit" id="btn-verificar-agregado" form="detalles-menu-adicionales" class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm">
                AGREGAR PEDIDO
            </button>
        </div>
    </div>
</div>

<div id="action-menu-agotado" class="menu menu-box-modal bg-red-dark rounded-m" data-menu-height="310" data-menu-width="350" style="display: block; width: 350px; height: 310px; z-index: 1053;">
    <h1 class="text-center mt-4"><i class="fa fa-3x fa-times-circle scale-box color-white shadow-xl rounded-circle"></i></h1>
    <h1 class="text-center mt-3 text-uppercase color-white font-700">Lo sentimos!</h1>
    <p class="boxed-text-l color-white opacity-70">
        El producto que escogiste acaba de agotarse, intenta con otras opciones.
    </p>
    <a href="#" class="close-menu btn btn-m btn-center-l button-s shadow-l rounded-s text-uppercase font-600 bg-white color-black">Entiendo</a>
</div>

@push('scripts')
<script>
    const esActualizacion = @json($isUpdate ?? null);

    document.addEventListener('DOMContentLoaded', async function() {
        // Control agregar unidad individual
        $(document).on('click', '.agregar-unidad', async function () {
            const ProductoID = $(this).data('producto-id');
            
            try {
                const agregarVentaProducto = await VentaService.agregarProductoVenta(ProductoID, 1);
                await carritoStorage.mostrarToastAgregado();
            } catch (error) {
                if (error.response && error.response.status === 409) {
                    // Si el usuario no dispone de una venta activa (o no ha iniciado sesion) se agrega el producto al carrito
                    const AddAttempt = await carritoStorage.agregarAlCarrito(ProductoID, 1);
                    estaVerificando(false);
                    closeDetallesMenu();
                } else if (error.response && error.response.status === 422) {
                    const { stockProducto, cantidadSolicitada } = error.response.data;
                    if (stockProducto <= 0)
                    {
                        actualizarBotonAgregado(ProductoID);
                        estaVerificando(false);
                        mostrarAvisoAgotado();
                    } else if (stockProducto < cantidadSolicitada) {
                        mostrarToastError("No hay stock suficiente.");
                        // console.log("No hay suficiente stock disponible para completar la solicitud")
                    }
                } else {
                    console.error('Error interno del servidor:', error);
                }
            }
        });

        // Control abrir menu
        $(document).on('click', '.menu-adicionales-btn', function() {
            const productoId = $(this).data('producto-id');
            openDetallesMenu(productoId);
        });
        
        $(document).off('click', '.actualizar-orden-venta').on('click', '.actualizar-orden-venta', async function() {
            const informacionOrden = {
                productoId: $(this).data('producto-id'),
                pventaId: $(this).data('pventa-id'),
                indice: $(this).data('orden-index')
            }
            await openActualizarOrdenMenu(informacionOrden);
        });

        // // $(document).off('click', '.actualizar-orden-carrito').on('click', '.actualizar-orden-carrito', async function() {
        // //     console.log("Tratando de abrir el ActualizarOdenMenu con carrito");
        // // });

        // Abrir menu de detalles-producto
        window.openDetallesMenu = async function(productoId, esActualizacion = false) {
            // Hacer el llamado al producto
            const response = await ProductoService.getProductoDetalle(productoId);
            if (!esActualizacion) {
                await prepararMenuAdicionarProducto(response.data);
            } else {
                await prepararMenuActualizarOrdenVenta(response.data);
            }
            $(".menu-hider").css("z-index", "1055");
            // // Cerrar otros menu abiertos
            // $(".menu-active").removeClass("menu-active");

            // Revelar el backdrop
            
            $(".menu-hider").addClass("menu-active");

            // Revelar el menu
            $("#detalles-menu").addClass("menu-active");

            // Cerrar el menu
            $("#btn-cerrar-detalles").off('click').on('click', function() {
                closeDetallesMenu();
            });
        };

        const openActualizarOrdenMenu = async (informacionOrden) => {
            const informacionProducto = await ProductoService.getProductoDetalle(informacionOrden.productoId);
            
            await prepararMenuActualizarOrdenVenta(informacionProducto.data, informacionOrden);
            $(".menu-hider").css("z-index", "1055");
            // Revelar el hider
            $(".menu-hider").addClass("menu-active");
            // Revelar el menu
            $("#detalles-menu").addClass("menu-active");
            // Cerrar el menu
            $("#btn-cerrar-detalles").off('click').on('click', function() {
                closeDetallesMenu();
            });
        }

        const mostrarAvisoAgotado = () => {
            $("#action-menu-agotado").addClass("menu-active");
            $(".menu-hider").addClass("menu-active");
            $("#action-menu-agotado .close-menu").off('click').on('click', () => {
                $('#action-menu-agotado').removeClass("menu-active")
                $(".menu-hider").remove("menu-active");
            });
        } 

        window.deshabilitarBoton = async (productoID) => {
            const botonActual = $(`.add-disabler[data-producto-id="${productoID}"]`);
            
            if (botonActual.length) {
                const nuevoContenido = `
                    <div class="d-flex flex-row align-items-center gap-1">
                        <i class="fa fa-ban"></i>
                        AGOTADO
                    </div>
                `;
                
                botonActual
                    .html(nuevoContenido)
                    .prop('disabled', true)
                    .removeClass('bg-highlight hover-grow-s')
                    .addClass('bg-gray-dark');
            }
        };


        // Preparar la informacion del Menu para el producto seleccionado.
        const prepararMenuAdicionarProducto = async (infoProducto) => {
            console.log("InfoProducto MenuAdicionarProducto:", infoProducto);
            const nombreProductoMenu = document.getElementById('detalles-menu-nombre');
            const adicionalesContainer = document.getElementById('detalles-menu-adicionales');
            const elementoCostoUnitario = document.getElementById('detalle-costo-unitario')
            const elementoCostoTotal = document.getElementById('detalle-costo-total');
            const contenedorCantidadesCarrito = $('#contenedor-cantidad-encarrito');
            contenedorCantidadesCarrito.addClass('d-none');
            const $incrementarProductoBtn = $('#detalles-stepper-up');
            const $reducirProductoBtn = $('#detalles-stepper-down');
            // // console.log("Ocultando mensaje error producto");
            const containerAdvertencia = $('#error-producto-container');
            containerAdvertencia.removeClass('d-block');
            containerAdvertencia.addClass('d-none');

            if(!esActualizacion)
            {
                const productoCarrito = carritoStorage.obtenerItemCarrito(infoProducto.id);
                const totalEnCarrito = productoCarrito ? Object.keys(productoCarrito.adicionales).length : 0;
                if (totalEnCarrito > 0) {
                    console.log("Total en carrito: ", totalEnCarrito);
                    console.log("Se revelara la cuenta de unidades en carrito");
                    $('#contador-cantidad-encarrito').text(totalEnCarrito);
                    contenedorCantidadesCarrito.removeClass('d-none');
                }
            }

            nombreProductoMenu.innerText = infoProducto.nombre; 
            elementoCostoUnitario.innerText = `Bs. ${(infoProducto.precio).toFixed(2)}`;
            adicionalesContainer.innerHTML = renderAdicionales(infoProducto.adicionales);

            console.log("$incrementarProductoBtn", $incrementarProductoBtn);
            $incrementarProductoBtn.on('click', () => {
                actualizarCostoTotal(infoProducto); 
            });

            console.log("$reducirProductoBtn", $reducirProductoBtn);
            $reducirProductoBtn.on('click', () => {
                actualizarCostoTotal(infoProducto); 
            });

            // actualizarCostoTotal(infoProducto);

            $('.input-radio').off('change').on('change', function() {
                // Actualizar el costo al cambiar los adicionales obligatorios
                actualizarCostoTotal(infoProducto);
            });

            $('.input-single').off('change').on('change', function() {
                $('input[name="' + this.name + '"]').not(this).prop('checked', false);
                // Actualizar el costo al cambiar los adicionales opcionales (MAX 1)
                actualizarCostoTotal(infoProducto);
            });



            // AUTO-SELECCIONAR EL PRIMER RADIO DE CADA GRUPO OBLIGATORIO DE ADICIONALES
            setTimeout(() => {
                // Encontrar todos los radiobuttons de un grupo en particular
                const gruposRadio = {};
                document.querySelectorAll('.input-radio').forEach(radio => {
                    const nombreGrupoRadios = radio.name;
                    if (!gruposRadio[nombreGrupoRadios]) {
                        gruposRadio[nombreGrupoRadios] = [];
                    }
                    gruposRadio[nombreGrupoRadios].push(radio);
                });

                // Seleccionar el primer radio
                Object.values(gruposRadio).forEach(grupo => {
                    if (grupo.length > 0) {
                        grupo[0].checked = true;
                    }
                });

                // Actualizar el costo tras seleccion
                actualizarCostoTotal(infoProducto);
            }, 0);

            // $('.input-radio').on('change', function() {
            //     // Actualizar el costo al cambiar los adicionales obligatorios
            //     actualizarCostoTotal(infoProducto);
            // });

            setTimeout(() => {
                $('.input-multiple').on('change', function() {
                    const grupoDiv = $(this).closest('[data-grupo]');
                    const max = parseInt(grupoDiv.data('max'), 10);
                    const checked = grupoDiv.find('.input-multiple:checked');
                    
                    // Establecer logica para incrementar o reducir el precio final
                    if (checked.length > max) {
                        // Deseleccionar el ultimo check
                        this.checked = false;
                    }
                    // Actualizar el costo al cambiar los adicionales opcionales (MAX n)
                    actualizarCostoTotal(infoProducto);
                });
            }, 0);

            renderBotonAgregarProducto();

            // Formulario de adicionales
            const formAdicionales = document.getElementById("detalles-menu-adicionales");
            
            // Retirar listeners existentes para evitar envios multiples
            $(formAdicionales).off('submit');
            
            // Agregar el handler para el submit del formulario
            $(formAdicionales).on('submit', (e) => handleAdicionarProductoSubmit(infoProducto, e));
        
        }

        const handleAdicionarProductoSubmit = async (infoProducto, e) => {
            e.preventDefault();
            const formAdicionales = document.getElementById("detalles-menu-adicionales");
            
            // VALIDACION: Revisar que todos los radios se encuentren seleccionados 
            const gruposRadio = {};
            document.querySelectorAll('.input-radio').forEach(radio => {
                const nombreGrupoRadios = radio.name;
                if (!gruposRadio[nombreGrupoRadios]) { 
                    gruposRadio[nombreGrupoRadios] = { radios: [], tieneSeleccion: false };
                }
                gruposRadio[nombreGrupoRadios].radios.push(radio);
                if (radio.checked) {
                    gruposRadio[nombreGrupoRadios].tieneSeleccion = true;
                }
            });

            // Revisar si falta seleccionar radios en grupos obligatorios
            const gruposSinSeleccionar = [];
            Object.entries(gruposRadio).forEach(([nombreGrupoRadios, datosGrupo]) => {
                if (!datosGrupo.tieneSeleccion) {
                    gruposSinSeleccionar.push(nombreGrupoRadios);
                }
            });

            if (gruposSinSeleccionar.length > 0) {
                // Mostrar alerta de error en caso de radio faltante
                alert(`Por favor selecciona una opción en: ${gruposSinSeleccionar.join(', ')}`);
                return; // Prevenir envio del formulario
            }

            ocultarMensajeLimitados();
            ocultarMensajeAgotados();
            const formData = new FormData(formAdicionales);
            let cantidadSolicitada = 1;
            
            const IdsAdicionalesSeleccionados = [];
            for (let [key, value] of formData.entries()) {
                if (key !== 'cantidad-orden') {
                    IdsAdicionalesSeleccionados.push(parseInt(value));  
                } else {
                    console.log("Valor de cantidad-orden: ", value);
                    cantidadSolicitada = parseInt(value);
                }
            };

            

            const enCarrito = carritoStorage.obtenerItemCarrito(infoProducto.id);
            const cantidadCarrito = enCarrito ? Object.keys(enCarrito.adicionales).length : 0;
            console.log("Cantidad Solicitada entrando al formulario sin contar carrito: ", cantidadSolicitada);
            try {
                // // SOLICITUD DE AGREGAR PRODUCTO
                // if (enCarrito) {
                //     cantidadSolicitada += cantidadCarrito;
                //     console.log("Cantidad Solicitada entrando al formulario con carrito: ", cantidadSolicitada);
                // }
                estaVerificando(true);
                const agregarVentaProducto = await VentaService.agregarProductoVenta(infoProducto.id, cantidadSolicitada, IdsAdicionalesSeleccionados);
                if (esActualizacion) {
                    renderizarListadoOrdenesVenta(agregarVentaProducto.data);
                    reemplazarCardProductoVenta(agregarVentaProducto.data);
                }

                
                closeDetallesMenu();
                estaVerificando(false);
                mostrarToastSuccess("Se agregó la orden a su pedido.");
            } catch (error) {
                // estaVerificando(false);
                // CONTROL DE VENTA-CARRITO
                if (error.response && error.response.status === 409) {
                    // Si el usuario no dispone de una venta activa (o no ha iniciado sesion) se agrega el producto al carrito
                    try {
                        const AddAttempt = await carritoStorage.agregarAlCarrito(infoProducto.id, cantidadSolicitada, false, IdsAdicionalesSeleccionados);
                        if (AddAttempt.success == false) {
                            if (AddAttempt.stockDisponible <= 0)
                            {
                                deshabilitarBoton(infoProducto.id);
                                mostrarAvisoAgotado();
                                closeDetallesMenu();
                            } else if (AddAttempt.stockDisponible < AddAttempt.totalSolicitado) 
                            {
                                if (AddAttempt.stockDisponible == cantidadCarrito) {
                                    console.log("Se deberia mostrar el toast");
                                    carritoStorage.mostrarToastLimite();
                                    closeDetallesMenu();
                                }
                                const containerAdvertencia = $('#error-producto-container');
                                containerAdvertencia.removeClass('d-none');
                                containerAdvertencia.addClass('d-block');
                                const textoAdvertencia = document.getElementById('error-producto-message');
                                textoAdvertencia.textContent = `Total solicitado: ${AddAttempt.totalSolicitado}    Disponible: ${AddAttempt.stockDisponible}`;
                                $('#stock-producto-value').text(`${AddAttempt.stockDisponible}`);
                                if (!esActualizacion) {
                                    renderBotonActualizarAdicionales(infoProducto, AddAttempt.stockDisponible - cantidadCarrito);
                                } else {
                                    deshabilitarBotonAgregadoOrden();
                                }
                            }
                        } else {
                            if (esActualizacion) {
                                const itemCarrito = carritoStorage.obtenerItemCarrito(infoProducto.id);
                                const infoActualizada = await CarritoService.obtenerInfoItemCarrito(itemCarrito, 1);
                                renderizarListadoOrdenesVenta(infoActualizada.item);
                            }
                            closeDetallesMenu();
                            estaVerificando(false);
                        }
                    } catch (error) {
                        // MostrarToastError
                        console.log("Error al agregar producto a carrito: ", error);
                    }   


                    
                    // const cantidadCarrito = carritoStorage.cantidadOrdenesProducto(infoProducto.id);
                    // if (cantidadCarrito >= ) {
                        
                    // }
                    
                    // mostrarToastSuccess("Se agregó la orden a su carrito.");
                }
                // CONTROL DE STOCK INSUFICIENTE
                else if (error.response && error.response.status === 422) {

                    // Informacion recibida de validacion inexitosa en backend
                    const { idsAdicionalesAgotados, idsAdicionalesLimitados, cantidadMaximaPosible,
                    messageLimitados, messageAgotados, messageProducto, stockProducto } = error.response.data;



                    // PRODUCTO AGOTADO
                    if (stockProducto <= 0)
                    {
                        deshabilitarBoton(infoProducto.id);
                        mostrarAvisoAgotado();
                        closeDetallesMenu();

                        // Mostrar modal disculpa
                    }
                    // STOCK INSUFICIENTE PRODUCTO
                    else if (stockProducto < cantidadSolicitada) {
                        if (stockProducto == cantidadCarrito) {
                            console.log("Se deberia mostrar el toast");
                            carritoStorage.mostrarToastLimite();
                            closeDetallesMenu();
                        }
                        // Renderizar advertencia stock disponible
                        const containerAdvertencia = $('#error-producto-container');
                        containerAdvertencia.removeClass('d-none');
                        containerAdvertencia.addClass('d-block');

                        // const containerAdvertencia = document.getElementById('error-producto-container');
                        // containerAdvertencia.style.display = 'block';
                        const textoAdvertencia = document.getElementById('error-producto-message');
                        textoAdvertencia.textContent = messageProducto;
                        // Actualizar el texto indicando el stock disponible del producto
                        $('#stock-producto-value').text(`${stockProducto}`);
                        // Transformar boton para actualizar la orden
                        if (!esActualizacion) {
                            renderBotonActualizarAdicionales(infoProducto,cantidadMaximaPosible - cantidadCarrito);
                        } else {
                            // dehabilitar boton agregar ordenes
                            deshabilitarBotonAgregadoOrden();
                        }
                    }
                    // STOCK AGOTADO ADICIONALELS
                    if (idsAdicionalesAgotados.length > 0) {
                        idsAdicionalesAgotados.forEach(adicionalId => {
                            // Deseleccionar checks agotados
                            const invalidInput = document.getElementById(`adicional-${adicionalId}`);
                            if (invalidInput) {
                                invalidInput.disabled = true;
                                invalidInput.checked = false;
                                const label = document.getElementById(`nombre-adicional-${adicionalId}`);
                                // Agregar (agotado) a los labels
                                if (label) {
                                    if (!label.textContent.includes('(agotado)')) {
                                        label.textContent += ' (agotado)';
                                    }
                                }
                            }
                        });
                        // Renderizar advertencia adicionales agotados
                        const containerAdvertencia = document.getElementById('error-agotados-container');
                        containerAdvertencia.style.display = 'block';
                        const textoAdvertencia = document.getElementById('error-agotados-message');
                    }
                    // STOCK INSUFICIENTE ADICIONALES
                    if (idsAdicionalesLimitados.length > 0) {
                        // Renderizar advertencia adicionales limitados
                        const containerAdvertencia = document.getElementById('error-limitados-container');
                        containerAdvertencia.style.display = 'block';
                        const textoAdvertencia = document.getElementById('error-limitados-message');
                        textoAdvertencia.textContent = messageLimitados;
                        
                        // Transformar boton para actualizar la orden
                        if (!esActualizacion) {
                            renderBotonActualizarAdicionales(infoProducto,cantidadMaximaPosible - cantidadCarrito);
                        }
                    }
                }  else {
                    // Error interno del servidor
                    console.error("Ocurrió un error inesperado al procesar su solicitud.", error)
                    // Toast error general
                    // Mostrar un dialog sencillo de error
                    estaVerificando(false);
                    // closeDetallesMenu();
                }
                estaVerificando(false);
            }
        }

        const renderBotonActualizarAdicionales = (producto,cantidadMaxima) => {
            const containerBotonAccion = document.getElementById('boton-accion-container');
            containerBotonAccion.innerHTML =  `
                <button id="btn-actualizar-pedido" class="btn btn-full btn-m bg-red-dark font-700 w-100 text-uppercase rounded-sm">Actualizar mi pedido</button>
            `;

            $('#btn-actualizar-pedido').on('click', function() {
                // Actualizar cantidad seleccionada
                const inputCantidad = document.getElementById('detalles-cantidad');
                // // console.log("Debug cantidad maxima: ", cantidadMaxima);
                inputCantidad.value = parseInt(cantidadMaxima, 10);
                actualizarCostoTotal(producto);
                // Ocultar mensaje de limitados
                ocultarMensajeLimitados();
                renderBotonAgregarProducto();
            });
        }

        const renderBotonAgregarProducto = () => {
            const containerBotonAccion = document.getElementById('boton-accion-container');
            containerBotonAccion.innerHTML =  `
                <button type="submit" id="btn-verificar-agregado" form="detalles-menu-adicionales" class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm">Agregar pedido</button>
            `;
        }

        const estaVerificando = (booleano) => {
            $('#btn-verificar-agregado')
                .text(booleano ? 'VERIFICANDO...' : 'AGREGAR PEDIDO')
                .prop('disabled', booleano);
        };


    });


    const prepararMenuActualizarOrdenVenta = async (infoProducto, infoOrden) => {
        $(`#detalles-menu-nombre`).text(infoProducto.nombre);
        reemplazarBotonConActualizar();
        const formAdicionales = $(`#detalles-menu-adicionales`);
        $(`#detalle-costo-unitario`).text(`Bs. ${(infoProducto.precio).toFixed(2)}`);
        formAdicionales.html(renderAdicionales(infoProducto.adicionales));
        // LLamado al backend para obtener los adicionales para el indice de la orden
        $('.input-radio').off('change').on('change', function() {
            // Actualizar el costo al cambiar los adicionales obligatorios
            actualizarCostoTotal(infoProducto);
        });
        const containerAdvertencia = $('#error-producto-container');
        containerAdvertencia.removeClass('d-block');
        containerAdvertencia.addClass('d-none');

        $('.input-single').off('change').on('change', function() {
            $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            // Actualizar el costo al cambiar los adicionales opcionales (MAX 1)
            actualizarCostoTotal(infoProducto);
        });

        setTimeout(() => {
            $('.input-multiple').off('change').on('change', function() {
                const grupoDiv = $(this).closest('[data-grupo]');
                const max = parseInt(grupoDiv.data('max'), 10);
                const checked = grupoDiv.find('.input-multiple:checked');
                
                // Establecer logica para incrementar o reducir el precio final
                if (checked.length > max) {
                    // Deseleccionar el ultimo check
                    this.checked = false;
                }
                // Actualizar el costo al cambiar los adicionales opcionales (MAX n)
                actualizarCostoTotal(infoProducto);
            });
        }, 0);

        try {
            // En caso de no encontrar venta activa, retornar un error especifico
            if (!infoOrden.pventaId || infoOrden.pventaId == "undefined") {
                // // console.log("infoOrden:", infoOrden);
                const adicionalesOrden = carritoStorage.adicionalesOrdenIndice(infoOrden.productoId,infoOrden.indice);
                preseleccionarDefaultsCarrito(adicionalesOrden);
                actualizarCostoTotal(infoProducto);
            } else {
                const respuestaProdVentaInfo = await VentaService.ordenVentaIndex(infoOrden.pventaId,infoOrden.indice);
                const adicionalesOrden = respuestaProdVentaInfo.data;
                preseleccionarDefaultsVenta(adicionalesOrden);
                actualizarCostoTotal(infoProducto);
            }
        } catch (error) {
            // Capturar el error especifico y realizar la preseleccion de adicionales correspondientes al item en localStorage
            console.log("Error al solicitar informacion sobre adicionales", error);
        }
        
        // Retirar listeners existentes para evitar envios multiples
        $(formAdicionales).off('submit');
        
        // Agregar el handler para el submit del formulario
        $(formAdicionales).on('submit', (e) => handleActualizarOrdenVentaSubmit(infoProducto, infoOrden, e));
    }

    const renderAdicionales = (adicionales) => {
        if (adicionales && adicionales.length > 0) {

            const adicionalesOpcional1 = adicionales.filter(item => item.grupo !== null && item.grupo.maximo_seleccionable == 1 && item.grupo.es_obligatorio == false);

            const adicionalesOpcionalX = adicionales.filter(item => (!item.grupo || item.grupo.maximo_seleccionable > 1 && item.grupo.es_obligatorio == false));

            const adicionalesObligatorios = adicionales.filter(item => item.grupo !== null && item.grupo.es_obligatorio == true);

            // Agrupar adicionales por su nombre
            const adicionalesCheck1 = adicionalesOpcional1.reduce((grupos, item) => {
                const nombreGrupo = item.grupo.nombre;
                
                // Si el grupo no existe, crearlo
                if (!grupos[nombreGrupo]) {
                    grupos[nombreGrupo] = [];
                }
                
                // Agregar el item al grupo apropiado
                grupos[nombreGrupo].push(item);
                
                return grupos;
            }, {});

            const adicionalesRadio = adicionalesObligatorios.reduce((grupos, item) => {
                const nombreGrupo = item.grupo.nombre;
                
                if (!grupos[nombreGrupo]) {
                    grupos[nombreGrupo] = [];
                }
                
                grupos[nombreGrupo].push(item);
                
                return grupos;
            }, {});

            // Agrupar por grupo
            const adicionalesCheckX = adicionalesOpcionalX.reduce((grupos, item) => {
                const nombreGrupo = item.grupo?.nombre ?? "Adicionales";
                if (!grupos[nombreGrupo]) {
                    grupos[nombreGrupo] = {
                        items: [],
                        maximo: item.grupo?.maximo_seleccionable ?? Infinity
                    };
                }
                grupos[nombreGrupo].items.push(item);
                return grupos;
            }, {});
            
            // Organizar listado de adicionales para renderizar ilimitados al ultimo
            const { Adicionales, ...otherGroups } = adicionalesCheckX;
            const finalAdicionalesCheckX = Adicionales 
                ? { ...otherGroups, Adicionales } 
                : adicionalesCheckX;
            
            return `
                ${Object.entries(adicionalesRadio).map(([nombreGrupo, grupo]) => `
                <div>
                    <h6>${nombreGrupo} <span class="font-300">(obligatorio)</span></h6>
                    <div class="row mb-2">
                        ${grupo.map(ad_obligatorio => `
                            <div class="col-6">
                                <div class="form-check icon-check mb-0">
                                    <input class="form-check-input input-radio" id="adicional-${ad_obligatorio.id}" type="radio"
                                    name="${nombreGrupo}" value="${ad_obligatorio.id}">
                                    <label class="form-check-label" for="adicional-${ad_obligatorio.id}">
                                        <span id="nombre-adicional-${ad_obligatorio.id}">${ad_obligatorio.nombre}</span> ${ad_obligatorio.precio > 0 ?  `<span class="badge bg-highlight">Bs. ${ad_obligatorio.precio}</span>` : '' }
                                    </label>
                                    <i class="icon-check-1 fa fa-circle color-gray-dark font-16"></i>
                                    <i class="icon-check-2 fa fa-check-circle font-16 color-highlight"></i>
                                </div>
                            </div>
                        `).join('')}
                    </div>    
                </div>
                `).join('')}
                ${Object.entries(adicionalesCheck1).map(([nombreGrupo, grupo]) => `
                <div>
                    <h6>${nombreGrupo} <span class="font-300">(máx. 1)</span></h6>
                    <div class="row mb-2">
                        ${grupo.map(adicionalUnico => `
                            <div class="col-6">
                                <div class="form-check icon-check mb-0">
                                    <input class="form-check-input input-single" id="adicional-${adicionalUnico.id}" type="checkbox" name="${nombreGrupo}[]" value="${adicionalUnico.id}" 
                                    ${adicionalUnico.cantidad == 0 && adicionalUnico.contable == true ? 'disabled': '' }>
                                    <label class="form-check-label" for="adicional-${adicionalUnico.id}">
                                        <span id="nombre-adicional-${adicionalUnico.id}">${adicionalUnico.nombre}</span> ${adicionalUnico.precio > 0 ? `<span class="badge bg-highlight">Bs. ${adicionalUnico.precio}</span>` : '' }
                                    </label>
                                    <i class="icon-check-1 fa fa-square color-gray-dark font-16"></i>
                                    <i class="icon-check-2 fa fa-check-square font-16 color-highlight"></i>
                                </div>
                            </div>
                        `).join('')}
                    </div>  
                </div>  
                `).join('')}
                ${Object.entries(finalAdicionalesCheckX).map(([nombreGrupo, grupoObj]) => `
                    <div>
                        <h6>${nombreGrupo} ${grupoObj.maximo != Infinity ? `<span class="font-300">(máx. ${grupoObj.maximo})</span>` : '' }</h6>
                        <div class="row mb-2" data-grupo="${nombreGrupo}" data-max="${grupoObj.maximo}">
                            ${grupoObj.items.map(adicional => `
                                <div class="col-6">
                                    <div class="form-check icon-check mb-0">
                                        <input class="form-check-input input-multiple" id="adicional-${adicional.id}" type="checkbox" name="${nombreGrupo}[]" value="${adicional.id}" 
                                        ${adicional.cantidad == 0 && adicional.contable == true ? '': '' }>
                                        <label class="form-check-label" for="adicional-${adicional.id}">
                                            <span id="nombre-adicional-${adicional.id}">${adicional.nombre}</span> ${adicional.precio > 0 ?  `<small class="badge bg-highlight">Bs. ${adicional.precio}</small>` : '' }
                                        </label>
                                        <i class="icon-check-1 fa fa-square color-gray-dark font-16"></i>
                                        <i class="icon-check-2 fa fa-check-square font-16 color-highlight"></i>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `).join('')}
            `
        }
        return '';
    };

    const calcularCostoAdicionales = (adicionales) => {
        const form = document.getElementById("detalles-menu-adicionales");
        let costoTotal = 0;
        // Obtener todos los adicionales seleccionados
        const inputsSeleccionados = form.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');

        inputsSeleccionados.forEach(input => {
            // Extraer el id del input correspondiente
            const adicionalId = input.id.split('-')[1]; // Asumiendo id's como "radio-123" or "check-123"
            // Encontrar el precio original desde el listado de adicionales
            const adicional = adicionales.find(item => item.id == adicionalId)

            // Determinar el precio del adicional y sumarlo al total
            const precio = parseFloat(adicional.precio) ?? 0;
            if (precio > 0) {
                costoTotal += precio;
            }
        });

        return costoTotal;
    }

    const actualizarCostoTotal = (producto) => {
        // Obtener el valor actual del input
        const cantidadInput = document.getElementById('detalles-cantidad');
        const totalAdicionalesDisplay = document.getElementById("display-adicionales");
        let cantidad = 1;
        if (cantidadInput) {
            cantidad = parseInt(cantidadInput.value, 10);
            

            if (cantidad < 1) {
                cantidadInput.value = 1
                return;
            }
        }
        
        // Obtener el costo de los adicionales seleccionados
        const costoAdicionales = calcularCostoAdicionales(producto.adicionales);

        // Si el costo de los adicionales es 0, ocultar la informacion del costo adicionales
        if (costoAdicionales > 0) {
                totalAdicionalesDisplay.style.display = 'block';
            } else {
                totalAdicionalesDisplay.style.display = 'none';
            }            
        
        let costoTotalOrden = 0;
        // Determinar el costo total de la orden
        if (cantidadInput) {
            costoTotalOrden = (producto.precio + costoAdicionales) * cantidad;
        } else {
            costoTotalOrden = (producto.precio + costoAdicionales);
        }
        // Actualizar el costo total de adicionales:
        const elementoTotalAdicionales = document.getElementById('detalle-costo-adicionales');
        elementoTotalAdicionales.innerText = `Bs. ${(costoAdicionales).toFixed(2)}`; 
        // Actualizar el costo total de la orden:
        const elementoTotalFinal = document.getElementById('detalle-costo-total')
        elementoTotalFinal.innerText = `Bs. ${(costoTotalOrden).toFixed(2)}`;
    }

    const preseleccionarDefaultsVenta = (adicionales) => {
        adicionales.forEach(adicional => {
            const input = $(`#adicional-${adicional.id}`);
            if (input) {
                input.prop('checked', true);
            }
        });
    }

    const preseleccionarDefaultsCarrito = (adicionales) => {
        adicionales.forEach(adicional => {
            const input = $(`#adicional-${adicional}`);
            if (input) {
                input.prop('checked', true);
            }
        });
    }


    const reemplazarBotonConActualizar = () => {
        const botonAdicionar = $(`#btn-verificar-agregado`);
        const botonActualizar = `                
                <button type="submit" id="btn-verificar-actualizacion" form="detalles-menu-adicionales" class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm">
                    Actualizar pedido
                </button>`;

        botonAdicionar.replaceWith(botonActualizar);
    }


    const estaActualizando = (booleano) => {
        $('#btn-verificar-actualizacion')
            .text(booleano ? 'ACTUALIZANDO...' : 'ACTUALIZAR PEDIDO')
            .prop('disabled', booleano);
    };

    const handleActualizarOrdenVentaSubmit = async (infoProducto, infoOrden, e) => {
        e.preventDefault();
        // // console.log("LLamado al submit handler");
        // // console.log("infoProductoHandler:", infoProducto);
        // // console.log("infoOrdenHandler:", infoOrden);
        const formAdicionales = $(`#detalles-menu-adicionales`);

        ocultarMensajeLimitados();
        ocultarMensajeAgotados();

        const formData = new FormData(formAdicionales[0]);
        let cantidadSolicitada = 1;
        
        const IdsAdicionalesSeleccionados = [];

        // Revisar inputs del form
        for (let [key, value] of formData.entries()) {
            if (key !== 'cantidad-orden') {
                // Incluir IDs adicionales seleccionados
                IdsAdicionalesSeleccionados.push(parseInt(value));  
            } 
            else {
                // ActualizarCantidadSolicitada de ser necesario
                // // cantidadSolicitada = parseInt(value);
            }
        };

        try {
            // SOLICITUD DE ACTUALIZAR ORDEN
            estaActualizando(true);
            // if (!infoOrden.pventaId) {
            //     await carritoStorage.actualizarOrdenPedido(infoProducto.id, infoOrden.indice, nuevosAdicionales);
            // }
            // const agregarVentaProducto = await VentaService.agregarProductoVenta(infoProducto.id, cantidadSolicitada, IdsAdicionalesSeleccionados);
            
            // Reemplazar pventaID con el identificador para producto
            const responseActualizarOdenVenta = await VentaService.actualizarOrdenVentaIndex(infoProducto.id, infoOrden.indice, IdsAdicionalesSeleccionados, 1);
            // En caso de error controlado, actualizar orden en el carrito
            
            reemplazarCardOrdenIndice(responseActualizarOdenVenta.data, infoOrden.indice);
            mostrarToastSuccess("Su pedido fue actualizado");
            estaActualizando(false);
            closeDetallesMenu();
        } catch (error) {
            if (error.response && error.response.status === 409) {
                    // Si el usuario no dispone de una venta activa (o no ha iniciado sesion) se actualiza la orden del producto en carrito
                    carritoStorage.actualizarOrdenCarrito(infoProducto.id, infoOrden.indice, IdsAdicionalesSeleccionados);
                    const itemCarrito = carritoStorage.obtenerItemCarrito(infoProducto.id);
                    const infoProductoActualizado = await CarritoService.obtenerInfoItemCarrito(itemCarrito, 1);
                    reemplazarCardOrdenIndice(infoProductoActualizado.item, infoOrden.indice);
                    closeDetallesMenu();
                    estaActualizando(false);
            } else if (error.response && error.response.status == 422) {
                // Informacion recibida de validacion inexitosa en backend
                const { idsAdicionalesAgotados, idsAdicionalesLimitados, cantidadMaximaPosible,
                messageLimitados, messageAgotados, messageProducto, stockProducto } = error.response.data;
                // PRODUCTO AGOTADO
                if (stockProducto <= 0)
                {
                    console.error("El producto se ha agotado: ", error);
                    // mostrar iun mo

                    // // console.log("El producto se encuentra agotado, cerrando menu")
                    // deshabilitarBoton(infoProducto.id);
                    // mostrarAvisoAgotado();
                    closeDetallesMenu();
                }
                // STOCK AGOTADO ADICIONALELS
                if (idsAdicionalesAgotados.length > 0) {
                    idsAdicionalesAgotados.forEach(adicionalId => {
                        // Deseleccionar checks agotados
                        const invalidInput = document.getElementById(`adicional-${adicionalId}`);
                        if (invalidInput) {
                            invalidInput.disabled = true;
                            invalidInput.checked = false;
                            const label = document.getElementById(`nombre-adicional-${adicionalId}`);
                            // Agregar (agotado) a los labels
                            if (label) {
                                if (!label.textContent.includes('(agotado)')) {
                                    label.textContent += ' (agotado)';
                                }
                            }
                        }
                    });
                    // Renderizar advertencia adicionales agotados
                    const containerAdvertencia = document.getElementById('error-agotados-container');
                    containerAdvertencia.style.display = 'block';
                    const textoAdvertencia = document.getElementById('error-agotados-message');
                    estaActualizando(false);
                }
                // STOCK INSUFICIENTE ADICIONALES
                // if (idsAdicionalesLimitados.length > 0) {
                //     // Renderizar advertencia adicionales limitados
                //     const containerAdvertencia = document.getElementById('error-limitados-container');
                //     containerAdvertencia.style.display = 'block';
                //     const textoAdvertencia = document.getElementById('error-limitados-message');
                //     textoAdvertencia.textContent = messageLimitados;
                    
                //     // Transformar boton para actualizar la orden
                //     renderBotonActualizarAdicionales(infoProducto,cantidadMaximaPosible);
                // }
            }  else {
                // Error interno del servidor
                console.error("Ocurrió un error inesperado al procesar su solicitud.", error)
                // Toast error general
                // Mostrar un dialog sencillo de error
                estaActualizando(false);
                // closeDetallesMenu();
            }
        }
    }

    const ocultarMensajeLimitados = () => {
        const containerAdvertencia = document.getElementById('error-limitados-container');
        containerAdvertencia.style.display = 'none';
    }

    const ocultarMensajeAgotados = () => {
        const containerAdvertencia = document.getElementById('error-agotados-container');
        containerAdvertencia.style.display = 'none';
    }

    const reiniciarCantidadSeleccionada = () => {
        const inputCantidad = document.getElementById('detalles-cantidad');
        if (inputCantidad) {
            inputCantidad.value = 1;
        }
        return;
    }

    const closeDetallesMenu = () => {
        $("#detalles-menu").removeClass("menu-active");
        $("#error-producto-container").css("display", "none");
        $("#error-agotados-container").css("display", "none");
        $("#error-limitados-container").css("display", "none");

        reiniciarCantidadSeleccionada();

        // Revisar si otros menus se encuentran ya activos para evitar ocultar el menu-hider
        // Excluir explícitamente el menu-hider y el detalles-menu
        const otherActiveMenus = $(".menu.menu-active:not(#detalles-menu):not(.menu-hider)").length;
        if (otherActiveMenus === 0) {
            $(".menu-hider").removeClass("menu-active");
        }
    }
</script>
@endpush
