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
                <div class="card-center px-3 no-click">
                    {{-- CONDICIONANTE DE TEXTO PARA OFERTAS --}}
                    @if ($producto->descuento && $producto->descuento > 0 && $producto->descuento < $producto->precio)
                        {{-- SI El PRODUCTO TIENE DESCUENTO --}}
                        <div class="d-flex flex-row align-items-center gap-4">
                            <h1 class="color-theme mb-n2 font-24">Precio unitario de Bs. {{$producto->descuento}}</h1>
                        </div>
                        <h5 class="color-theme mt-n1 opacity-80 font-14">Precio fuera de oferta: <del>Bs. {{$producto->precio}}</del></h5>
                    @else
                        {{-- SI El PRODUCTO TIENE DESCUENTO --}}
                        <h1 class="color-theme mb-n2 font-24">Precio unitario de Bs. {{$producto->precio}}</h1>
                    @endif
                    <h5 id="order-info-text" class="color-highlight mt-n1 opacity-80 font-14">Unidades en mi carrito: <span class="color-theme" id="details-cart-counter">x</span></h5>
                </div>              
                {{-- CONDICIONANTE HABILITACION BOTON POR STOCK --}}
                <div class="card-center">
                    @if ($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0)
                        <button class="float-end mx-3 gradient-gray btn-s rounded-sm shadow-xl text-uppercase text-white font-800">Sin Stock</button>
                    @else
                        <button
                        data-producto-id="{{$producto->id}}"
                        data-producto-nombre="{{$producto->nombre}}"
                        class="add-to-cart bg-highlight float-end hover-grow-s mx-3 btn-s rounded-sm shadow-xl text-uppercase text-white font-800">
                            <i class="fa fa-shopping-cart"></i>
                            Añadir
                        </button>
                    @endif
                </div>
                {{-- Control tonalidad oscura --}}
                <div class="card-overlay dark-mode-tint opacity-70"></div>
            </div>
            {{-- PUNTOS DEL PRODUCTO --}}
            @if ($producto->puntos && $producto->puntos > 0)
                    <div class="d-flex flex-row align-items-center justify-content-center gap-2 my-3">
                        <i data-lucide="circle-star" class="lucide-icon" style="color: gold"></i>
                        <p class="color-theme font-18 mb-0">Gana <span class="font-700">{{$producto->puntos}}</span> puntos por unidad comprada</p>
                        <i data-lucide="circle-star" class="lucide-icon" style="color: gold"></i>
                    </div>
            @endif
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
@endsection

@push('scripts')
<script> 
    $(document).ready(function() {
        // Llamado al handler al momento de hacer click en el elemento add-to-cart
        $(document).on('click', '.add-to-cart', addToCartHandler);
        // Renderizado condicional de informacion del producto en carrito
        const product_Id = $('#product-info-card').data('producto-id');
        renderOrderInfo(product_Id);
        carritoStorage.updateCartItemDetailCounter(product_Id);
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
    function renderOrderInfo(productId) {
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