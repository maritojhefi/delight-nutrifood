<div id="detalles-menu" class="menu menu-box-bottom rounded-m pb-5">    
    <div class="menu-title">
        <p class="color-highlight">Delight-Nutrifood</p>
        <h1 class="font-22">Personaliza tu orden</h1>
        <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
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
        <div class="d-flex mb-3 pb-1">
            <div class="align-self-center">
                <h5 class="mb-0">Cantidad</h5>
            </div>
            <div class="ms-auto align-self-center">
                <div class="stepper rounded-s small-switch me-n2">
                    <a id="detalles-stepper-down" href="#" class="stepper-sub"><i class="fa fa-minus color-theme opacity-40"></i></a>
                    <input id="detalles-cantidad" type="number" min="1" max="99" value="1">
                    <a id="detalles-stepper-up" href="#" class="stepper-add"><i class="fa fa-plus color-theme opacity-40"></i></a>
                </div>
            </div>
        </div>
        <div class="d-flex mb-3">
            <div class="align-self-center">
                <h5 class="mb-0">Costo Unitario</h5>
            </div>
            <div class="ms-auto align-self-center">
                <h5 id="detalle-costo-unitario" class="mb-0">Bs. 2.53</h5>
            </div>
        </div>
        <div class="d-flex mb-3">
            <div class="align-self-center">
                <h5 class="mb-0">Costo Total</h5>
            </div>
            <div class="ms-auto align-self-center">
                <h5 id="detalle-costo-total" class="mb-0">Bs. 25.30</h5>
            </div>
        </div>
        <div class="divider"></div>
        @if ($isUpdate)
            <!-- <button type="submit" form="detalles-menu-adicionales">Submit check</button> -->
            <button class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm close-menu">Actualizar pedido</buttno>
        @else
            <!-- <button type="submit" form="detalles-menu-adicionales">Submit check</button> -->
            <button type="submit" form="detalles-menu-adicionales" class="btn btn-full btn-m bg-highlight font-700 w-100 text-uppercase rounded-sm">Agregar al carrito</button>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        
        // Abrir menu de detalles-producto
        window.openDetallesMenu = async function(productId) {
            await prepararMenuProducto(productId);

            // Cerrar otros menu abiertos
            $(".menu-active").removeClass("menu-active");

            // Revelar el backdrop
            $(".menu-hider").addClass("menu-active");

            // Revelar el menu
            $("#detalles-menu").addClass("menu-active");
        };

        // Preparar la informacion del Menu para el producto seleccionado.
        const prepararMenuProducto = async (productoId) => {
            // Llamado axios solicitando la informacion del producto
            const response = await ProductoService.getProductoDetalle(productoId);

            const infoProducto = response.data;
            const nombreProductoMenu = document.getElementById('detalles-menu-nombre');
            const adicionalesContainer = document.getElementById('detalles-menu-adicionales');
            const elementoCostoUnitario = document.getElementById('detalle-costo-unitario')
            const elementoCostoTotal = document.getElementById('detalle-costo-total');
            const incrementarProductoBtn = document.getElementById('detalles-stepper-up');
            const reducirProductoBtn = document.getElementById('detalles-stepper-down');

            incrementarProductoBtn.addEventListener('click', () => {
                actualizarCostoTotal(infoProducto.precio);
            });

            reducirProductoBtn.addEventListener('click', () => {
                actualizarCostoTotal(infoProducto.precio);
            });

            actualizarCostoTotal(infoProducto.precio);
            nombreProductoMenu.innerText = infoProducto.nombre; 
            elementoCostoUnitario.innerText = `Bs. ${(infoProducto.precio).toFixed(2)}`;
            adicionalesContainer.innerHTML = renderAdicionales(infoProducto.adicionales);

            $('.input-single').on('change', function() {
                $('input[name="' + this.name + '"]').not(this).prop('checked', false);
            });

            setTimeout(() => {
                $('.input-multiple').on('change', function() {
                    const grupoDiv = $(this).closest('[data-grupo]');
                    const max = parseInt(grupoDiv.data('max'), 10);
                    const checked = grupoDiv.find('.input-multiple:checked');
                    if (checked.length > max) {
                        // Uncheck the last one checked
                        this.checked = false;
                    }
                });
            }, 0);

            const formAdicionales = document.getElementById("detalles-menu-adicionales");
            
            formAdicionales.addEventListener('submit',(e) => {
                e.preventDefault();
                console.log("clic en submit adicionale")
                const formData = new FormData(formAdicionales);
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
            });
        }

        const renderAdicionales = (adicionales) => {
            if (adicionales && adicionales.length > 0) {

                const adicionalesOpcional1 = adicionales.filter(item => item.grupo !== null && item.grupo.maximo_seleccionable == 1 && item.grupo.es_obligatorio == false);

                const adicionalesOpcionalX = adicionales.filter(item => item.grupo !== null && item.grupo.maximo_seleccionable > 1 && item.grupo.es_obligatorio == false);

                const adicionalesObligatorios = adicionales.filter(item => item.grupo !== null && item.grupo.es_obligatorio == true);

                const adicionalesSinGrupo = adicionales.filter(item => item.grupo === null);

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

                console.log("Grupo check 1: ", adicionalesCheck1)

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
                    const nombreGrupo = item.grupo.nombre;
                    if (!grupos[nombreGrupo]) {
                        grupos[nombreGrupo] = {
                            items: [],
                            maximo: item.grupo.maximo_seleccionable
                        };
                    }
                    grupos[nombreGrupo].items.push(item);
                    return grupos;
                }, {});
                
                
                return `
                    ${Object.entries(adicionalesRadio).map(([nombreGrupo, grupo]) => `
                    <div>
                        <h6>${nombreGrupo} <span class="font-300">(obligatorio)</span></h6>
                        <div class="row mb-2">
                            ${grupo.map(ad_obligatorio => `
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="${nombreGrupo}" value="${ad_obligatorio.nombre}">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            ${ad_obligatorio.nombre}
                                        </label>
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
                                        <input class="form-check-input input-single" id="check-${adicionalUnico.id}" type="checkbox" name="${nombreGrupo}" value="${adicionalUnico.nombre}">
                                        <label class="form-check-label" for="check-${adicionalUnico.id}">${adicionalUnico.nombre} </label>
                                        <i class="icon-check-1 fa fa-square color-gray-dark font-16"></i>
                                        <i class="icon-check-2 fa fa-check-square font-16 color-highlight"></i>
                                    </div>
                                </div>
                            `).join('')}
                        </div>  
                    </div>  
                    `).join('')}
                    ${Object.entries(adicionalesCheckX).map(([nombreGrupo, grupoObj]) => `
                        <div>
                            <h6>${nombreGrupo} <span class="font-300">(máx. ${grupoObj.maximo})</span></h6>
                            <div class="row mb-2" data-grupo="${nombreGrupo}" data-max="${grupoObj.maximo}">
                                ${grupoObj.items.map(adicional => `
                                    <div class="col-6">
                                        <div class="form-check icon-check mb-0">
                                            <input class="form-check-input input-multiple" id="check-${adicional.id}" type="checkbox" name="${nombreGrupo}" value="${adicional.nombre}">
                                            <label class="form-check-label" for="check-${adicional.id}">${adicional.nombre}</label>
                                            <i class="icon-check-1 fa fa-square color-gray-dark font-16"></i>
                                            <i class="icon-check-2 fa fa-check-square font-16 color-highlight"></i>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `).join('')}
                    <div>
                        <h6>Adicionales</h6>
                        <div class="row">
                            ${adicionalesSinGrupo.map(adicional => `
                                <div class="col-6">
                                    <div class="form-check icon-check mb-0">
                                        <input class="form-check-input" id="check-${adicional.id}" type="checkbox" name="Adicionales" value="${adicional.nombre}" ${adicional.cantidad == 0 && adicional.contable == true ? 'disabled': '' }>
                                        <label class="form-check-label" for="check-${adicional.id}">${adicional.nombre}</label>
                                        <i class="icon-check-1 fa fa-square color-gray-dark font-16"></i>
                                        <i class="icon-check-2 fa fa-check-square font-16 color-highlight"></i>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `
            }
            return '';
        };

        const actualizarCostoTotal = (precio) => {
            // Obtener el valor actual del input
            const cantidadInput = document.getElementById('detalles-cantidad');
            const cantidad = parseInt(cantidadInput.value, 10);
            if (cantidad < 1) {
                cantidadInput.value = 1
                return;
            }
            // Actualizar el costo total:
            const elementoCostoTotal = document.getElementById('detalle-costo-total')
            elementoCostoTotal.innerText = `Bs. ${(precio * cantidad).toFixed(2)}`; 
        }

        // const handleAgregarProductoConAdicionales = () => {

        // }
    });
</script>
@endpush
