@extends('client.master')
@section('content')
    {{-- CABECERA ESTATICA --}}
    <x-cabecera-detalle-delight :producto="$producto"/>
    {{-- CARD ENVOLVENTE PARA EL CONTENIDO --}}
    <div class="card card-full rounded-m">
        <div class="drag-line"></div>
        <div class="content">
            {{-- CONTENEDOR PRECIO Y BOTON DE AGREGAR --}}
            <div id="product-info-card" data-producto-id="{{$producto->id}}" class="card card-style bg-dtheme-blue mx-0 my-2 mt-3" style="height: 100px;" data-card-height="100">
                <div class="card-center d-flex flex-row justify-content-between gap-3 px-3">
                    <div>
                        <h1 class="color-theme mb-n2 font-20 d-flex">Precio unitario de Bs. {{$producto->precioReal()}}</h1>
                        {{-- CONDICIONANTE DE TEXTO PARA OFERTAS --}}
                        @if ($producto->descuento && $producto->descuento > 0 && $producto->descuento < $producto->precio)
                        <h5 class="color-theme mt-n1 opacity-80 font-14">Originalmente: <del>Bs. {{$producto->precio}}</del></h5> 
                        @endif
                        <h5 id="order-info-text" class="color-highlight mt-n1 opacity-80 font-14">Unidades en mi carrito: <span class="color-theme" id="details-cart-counter">0</span></h5>
                    </div>
                    {{-- CONDICIONANTE HABILITACION BOTON POR STOCK --}}
                    <div class="d-flex align-items-center justify-content-center">
                    @if ($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0)
                        <button class="gradient-gray btn-m rounded-sm text-uppercase text-white font-800" style=" line-height: 1rem;">Sin Stock</button>
                    @else
                        <button
                        id="{{ $adicionales->isNotEmpty() ? "menu-adicionales-btn" : 'agregar-unidad' }}"
                        data-producto-id="{{$producto->id}}"
                        data-producto-nombre="{{$producto->nombre}}"
                        class="bg-highlight hover-grow-s btn-xs rounded-sm text-uppercase text-white font-800">
                            <div class="d-flex flex-row align-items-center gap-1">    
                                <i class="fa fa-shopping-cart"></i>
                                Añadir
                            </div>
                        </button>
                    @endif
                    </div>
                </div>    
                {{-- Control tonalidad oscura --}}
                <div class="card-overlay dark-mode-tint opacity-70"></div>
            </div>
            {{-- MENCION A LOS TAGS DEL PRODUCTO --}}
            <ul class="icon-list row row-cols-2 g-1 ms-5 my-3">
                @if ($producto->tags->isEmpty())
                    {{-- <li class="col">No hay tags asociados a este producto.</li> --}}
                @else
                    @foreach ($producto->tags as $tag)
                        <li class="col d-flex align-items-center">
                            <i data-lucide="{{$tag->icono}}" class="lucide-icon tag-icon me-1"></i>
                            <span class="font-12">{{$tag->nombre}}</span>
                        </li>
                        
                    @endforeach
                @endif
                {{-- LISTADO DE TAGS DE PRUEBA --}}

                {{-- <li class="col d-flex align-items-center">
                    <i data-lucide="wheat-off" class="lucide-icon tag-icon me-1"></i>
                    <span class="font-12">Libre de Gluten</span>
                </li>
                <li class="col d-flex align-items-center">
                    <i data-lucide="candy-off" class="lucide-icon tag-icon me-1"></i>
                    <span class="font-12">Organico</span>
                </li>
                <li class="col d-flex align-items-center">
                    <i data-lucide="candy-off" class="lucide-icon tag-icon me-1"></i>
                    <span class="font-12">Sin azúcar agregada</span>
                </li> --}}
            </ul>
            <x-divider-manzana/>
            {{-- FOOTER CON PRODUCTOS SIMILARES --}}
            <x-footer-productos-similares :producto="$producto" :similares="$similares" />
        </div>
    </div>
    <x-menu-adicionales-producto :identificador="$producto->id" :isUpdate="false" :producto="$producto" :adicionales="$adicionales" />
@endsection

@push('scripts')
<script> 

    document.addEventListener('DOMContentLoaded', function() {
        // Llamado al handler al momento de hacer click en el elemento agregar-unidad
        $('#agregar-unidad').on('click', addToCartHandler);

        // Renderizado condicional de informacion del producto en carrito
        const product_Id = $('#product-info-card').data('producto-id');
        renderOrderInfo(product_Id);
        carritoStorage.updateCartItemDetailCounter(product_Id);

        // ACTIVAR MENU DETALLES 
        // Para un elemento especifico por ID [detalle-producto]
        const activadorMenu = document.getElementById('menu-adicionales-btn');
        if (activadorMenu) {
            const productId = activadorMenu.getAttribute('data-producto-id');
            activadorMenu.addEventListener('click', () => {
                openDetallesMenu(productId);
            });
        }
        
        
        // Para multiples elementos por clase [carrito]
        document.querySelectorAll('.menu-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openDetallesMenu();
            });
        });
    });

    async function addToCartHandler() {
        const product_Id = $(this).data('producto-id'); 
        try {
            // Solicitud para revisar stock y agregar al carrito
            const result = await carritoStorage.addToCart(product_Id, 1);
            if (result.success) {
                // En caso de exito, actualizar el contador de unidades
                carritoStorage.updateCartItemDetailCounter(product_Id);
                renderOrderInfo(product_Id);
            } else {
                console.log('Error: ', result.message);
            }
        } catch (error) {
            console.error('Error agregando el producto al carrito:', error);
        }
    }

    // Renderizado condicional de informacion de la orden existente
    const renderOrderInfo = (productId) => {
        const productData = carritoStorage.getCartItem(productId);
        const orderInfoElement = $('#order-info-text');
        
        if (productData && productData.quantity > 0) {
            // Mostrar la informacion de unidades en el carrito
            orderInfoElement.show();
        } else {
            // Ocultar el elemento si no hay unidades del producto en el carrito
            orderInfoElement.hide();
        }
    }
</script>
@endpush