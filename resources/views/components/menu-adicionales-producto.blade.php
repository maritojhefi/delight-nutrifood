<div id="detalles-menu" class="menu menu-box-bottom rounded-m pb-5" style="z-index: 1053;">    
    <div class="menu-title">
        <p class="color-highlight">Delight-Nutrifood</p>
        <h1 class="font-22">Personaliza tu orden</h1>
        <a href="#" id="btn-cerrar-detalles" class=""><i class="fa fa-times-circle"></i></a>
    </div>
    <div class="divider mb-0"></div>
    <div class="content mt-3">
        <div class="d-flex mb-3">
            <div class="align-self-center">
                <img id="detalles-menu-img" src="{{asset(GlobalHelper::getValorAtributoSetting('bg_default'))}}" class="rounded-sm me-3"
                style="width: 5rem; height: 5rem; object-fit: cover;">
            </div>
            <div class="align-self-center">
                <h2 id="detalles-menu-nombre" class="font-16 line-height-s mt-1 mb-n1">Nombre del producto</h2>
                <div class="mb-0 font-11 mt-2 d-flex flex-row align-items-center justify-content-start gap-2">
                    {{-- @foreach ($producto->tag as $tag)
                    <i  data-lucide="{{$tag->icono}}" 
                        class="lucide-icon tag-icon"
                        style="width: 1rem; height: 1rem;"></i>
                    @endforeach
                    Info --}}
                    <i class="fa fa-truck color-green-dark pe-1 ps-2"></i>Empacado estandar
                </div>
            </div>
        </div>
        <form id="detalles-menu-adicionales">
            {{-- RENDERIZADO CONDICIONAL ADICIONALES --}}
        </form>
        <div class="divider mb-2"></div>
        <div class="line-height-xs">
            <small class=" mb-0 color-theme">Los extras seleccionados se veran reflejados en la cantidad de unidades que pidas.</small>
        </div>
        <div class="d-flex mb-0 pb-1">
            <div class="align-self-center">
                <h5 class="mb-0">Cantidad</h5>
            </div>
            <div class="ms-auto align-self-center">
                <div class="stepper rounded-s small-switch me-n2">
                    <a id="detalles-stepper-down" href="#" class="stepper-sub"><i class="fa fa-minus color-theme opacity-40"></i></a>
                    <input style="font-size: 15px !important;" form="detalles-menu-adicionales" name="cantidad-orden" id="detalles-cantidad" type="number" min="1" max="99" value="1">
                    <a id="detalles-stepper-up" href="#" class="stepper-add"><i class="fa fa-plus color-theme opacity-40"></i></a>
                </div>
            </div>
        </div>
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
            @if ($isUpdate)
                <!-- <button type="submit" form="detalles-menu-adicionales">Submit check</button> -->
                <button class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm close-menu">Actualizar pedido</buttno>
            @else
                <!-- <button type="submit" form="detalles-menu-adicionales">Submit check</button> -->
                <button type="submit" id="btn-verificar-agregado" form="detalles-menu-adicionales" class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm">Agregar al carrito</button>
            @endif
        </div>
        
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        // const [estaVerficiando, setEstaVerificando] = (false);
        
        // Abrir menu de detalles-producto
        window.openDetallesMenu = async function(productoId) {
            // Hacer el llamado al producto
            const response = await ProductoService.getProductoDetalle(productoId);

            await prepararMenuProducto(response.data);

            // // Cerrar otros menu abiertos
            // $(".menu-active").removeClass("menu-active");

            // Revelar el backdrop
            $(".menu-hider").css("z-index", "1052");
            $(".menu-hider").addClass("menu-active");

            // Revelar el menu
            $("#detalles-menu").addClass("menu-active");

            // Cerrar el menu
            $("#btn-cerrar-detalles").off('click').on('click', function() {
                closeDetallesMenu();
            });
        };

        const closeDetallesMenu = () => {
            $("#detalles-menu").removeClass("menu-active");
            reiniciarCantidadSeleccionada();

            // Revisar si otros menus se encuentran ya activos para evitar ocultar el menu-hider
            // Excluir explícitamente el menu-hider y el detalles-menu
            const otherActiveMenus = $(".menu.menu-active:not(#detalles-menu):not(.menu-hider)").length;
            if (otherActiveMenus === 0) {
                $(".menu-hider").removeClass("menu-active");
            }
        }

        // Preparar la informacion del Menu para el producto seleccionado.
        const prepararMenuProducto = async (infoProducto) => {
            const nombreProductoMenu = document.getElementById('detalles-menu-nombre');
            const adicionalesContainer = document.getElementById('detalles-menu-adicionales');
            const elementoCostoUnitario = document.getElementById('detalle-costo-unitario')
            const elementoCostoTotal = document.getElementById('detalle-costo-total');
            const incrementarProductoBtn = document.getElementById('detalles-stepper-up');
            const reducirProductoBtn = document.getElementById('detalles-stepper-down');

            incrementarProductoBtn.addEventListener('click', () => {
                actualizarCostoTotal(infoProducto);
            });

            reducirProductoBtn.addEventListener('click', () => {
                actualizarCostoTotal(infoProducto);
            });

            actualizarCostoTotal(infoProducto);
            nombreProductoMenu.innerText = infoProducto.nombre; 
            elementoCostoUnitario.innerText = `Bs. ${(infoProducto.precio).toFixed(2)}`;
            adicionalesContainer.innerHTML = renderAdicionales(infoProducto.adicionales);

            $('.input-radio').on('change', function() {
                // Actualizar el costo al cambiar los adicionales obligatorios
                actualizarCostoTotal(infoProducto);
            });

            $('.input-single').on('change', function() {
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

            $('.input-radio').on('change', function() {
                // Actualizar el costo al cambiar los adicionales obligatorios
                actualizarCostoTotal(infoProducto);
            });

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

            const formAdicionales = document.getElementById("detalles-menu-adicionales");
            formAdicionales.addEventListener('submit', async (e) => {
                e.preventDefault();
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
                let cantidad = 1;

                console.log("formData: ",formData);
                
                const IdsAdicionalesSeleccionados = [];
                for (let [key, value] of formData.entries()) {
                    if (key !== 'cantidad-orden') {
                        IdsAdicionalesSeleccionados.push(parseInt(value));  
                    } else {
                        cantidad = parseInt(value);
                    }
                };
                
                console.log("IDs Seleccionados:", IdsAdicionalesSeleccionados);

                try {
                    estaVerificando(true);
                    const respuestaValidacionAdicionales = await ProductoService.validarProductoConAdicionales(infoProducto.id, IdsAdicionalesSeleccionados, cantidad);
                    const AddAttempt = await carritoStorage.agregarAlCarrito(infoProducto.id, cantidad, false, IdsAdicionalesSeleccionados);
                    estaVerificando(false);
                    closeDetallesMenu();
                } catch (error) {
                    estaVerificando(false);
                    if (error.response && error.response.status === 422) {
                        // Informacion recibida de validacion inexitosa en backend
                        const { idsAdicionalesAgotados, idsAdicionalesLimitados, cantidadMaxima,
                        messageLimitados, messageAgotados } = error.response.data;
                        // De existir adicionales agotados:
                        if (idsAdicionalesAgotados.length > 0) {
                            idsAdicionalesAgotados.forEach(adicionalId => {
                                // Deseleccion de checks agotados
                                const invalidInput = document.getElementById(`adicional-${adicionalId}`);
                                if (invalidInput) {
                                    invalidInput.disabled = true;
                                    invalidInput.checked = false;
                                    const label = document.getElementById(`nombre-adicional-${adicionalId}`);
                                    if (label) {
                                        if (!label.textContent.includes('(agotado)')) {
                                            label.textContent += ' (agotado)';
                                        }
                                    }
                                }
                            });
                            // Renderizar mensaje agotados
                            const containerAdvertencia = document.getElementById('error-agotados-container');
                            containerAdvertencia.style.display = 'block';
                            const textoAdvertencia = document.getElementById('error-agotados-message');
                        }
                        // De existir adicionales limitados:
                        if (idsAdicionalesLimitados.length > 0) {
                            // Renderizar mensaje limitados
                            const containerAdvertencia = document.getElementById('error-limitados-container');
                            containerAdvertencia.style.display = 'block';
                            const textoAdvertencia = document.getElementById('error-limitados-message');
                            textoAdvertencia.textContent = messageLimitados;
                            
                            // Transformar boton para actualizar la orden
                            renderBotonActualizarAdicionales(infoProducto,cantidadMaxima);
                        }
                    } else {
                        console.error("Ocurrió un error inesperado:", error.message);
                    }
                }
            });
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
                                        <input class="form-check-input input-radio" id="radio-${ad_obligatorio.id}" type="radio"
                                        name="${nombreGrupo}" value="${ad_obligatorio.id}">
                                        <label class="form-check-label" for="radio-${ad_obligatorio.id}">
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

        const renderBotonActualizarAdicionales = (producto,cantidadMaxima) => {
            const containerBotonAccion = document.getElementById('boton-accion-container');
            containerBotonAccion.innerHTML =  `
                <button id="btn-actualizar-pedido" class="btn btn-full btn-m bg-red-dark font-700 w-100 text-uppercase rounded-sm">Actualizar mi pedido</button>
            `;

            $('#btn-actualizar-pedido').on('click', function() {
                // Actualizar cantidad seleccionada
                const inputCantidad = document.getElementById('detalles-cantidad');
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
                <button type="submit" form="detalles-menu-adicionales" class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm">Agregar al carrito</button>
            `;
        }

        const actualizarCostoTotal = (producto) => {
            // Obtener el valor actual del input
            const cantidadInput = document.getElementById('detalles-cantidad');
            const cantidad = parseInt(cantidadInput.value, 10);
            const totalAdicionalesDisplay = document.getElementById("display-adicionales");

            if (cantidad < 1) {
                cantidadInput.value = 1
                return;
            }

            // Obtener el costo de los adicionales seleccionados
            const costoAdicionales = calcularCostoAdicionales(producto.adicionales);

            // Si el costo de los adicionales es 0, ocultar la informacion del costo adicionales
            if (costoAdicionales > 0) {
                    totalAdicionalesDisplay.style.display = 'block';
                } else {
                    totalAdicionalesDisplay.style.display = 'none';
                }            
            
            // Determinar el costo total de la orden
            const costoTotalOrden = (producto.precio + costoAdicionales) * cantidad;
            // Actualizar el costo total de adicionales:
            const elementoTotalAdicionales = document.getElementById('detalle-costo-adicionales');
            elementoTotalAdicionales.innerText = `Bs. ${(costoAdicionales).toFixed(2)}`; 
            // Actualizar el costo total de la orden:
            const elementoTotalFinal = document.getElementById('detalle-costo-total')
            elementoTotalFinal.innerText = `Bs. ${(costoTotalOrden).toFixed(2)}`;
        }

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

        const ocultarMensajeLimitados = () => {
            const containerAdvertencia = document.getElementById('error-limitados-container');
            containerAdvertencia.style.display = 'none';
        }
        const ocultarMensajeAgotados = () => {
            const containerAdvertencia = document.getElementById('error-agotados-container');
            containerAdvertencia.style.display = 'none';
        }
        const estaVerificando = (booleano) => {
            $('#btn-verificar-agregado')
                .text(booleano ? 'VERIFICANDO...' : 'AGREGAR AL CARRITO')
                .prop('disabled', booleano);
        };
        const reiniciarCantidadSeleccionada = () => {
            const inputCantidad = document.getElementById('detalles-cantidad');
            inputCantidad.value = 1;
        }
    });
</script>
@endpush
