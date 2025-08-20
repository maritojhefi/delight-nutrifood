@extends('client.master')
@section('content')
    {{-- CABECERA ESTATICA --}}
    <x-cabecera-detalle-delight :producto="$producto"/>
    {{-- CARD ENVOLVENTE PARA EL CONTENIDO --}}
    <div class="card card-full rounded-m">
        <div class="drag-line"></div>
        <div class="content">
            {{-- CONTENEDOR PRECIO Y BOTON DE AGREGAR --}}
            <div id="product-info-card" data-producto-id="{{$producto->id}}" class="card card-style bg-6 mx-0 mt-3 bg-highlight" style="height: 100px;" data-card-height="100">
                <div class="card-center px-3 no-click">
                    {{-- CONDICIONANTE DE TEXTO PARA OFERTAS --}}
                    @if ($producto->descuento && $producto->descuento > 0 && $producto->descuento < $producto->precio)
                        <div class="d-flex flex-row align-items-center gap-4">
                            <h1 class="color-white mb-n2 font-24">Precio unitario de Bs. {{$producto->descuento}}</h1>
                        </div>
                        <h5 class="color-white mt-n1 opacity-80 font-14">Precio fuera de oferta: <del>Bs. {{$producto->precio}}</del></h5>
                    @else
                        <h1 class="color-white mb-n2 font-24">Precio unitario de Bs. {{$producto->precio}}</h1>
                    @endif
                    <h5 id="order-info-text" class="color-white mt-n1 opacity-80 font-14">Unidades en mi carrito: <span id="details-cart-counter">x</span></h5>
                </div>              
                {{-- CONDICIONANTE HABILITACION BOTON POR STOCK --}}
                <div class="card-center">
                    @if ($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0)
                        <button class="float-end mx-3 gradient-gray btn-s rounded-sm shadow-xl text-uppercase font-800">Sin Stock</button>
                    @else
                        <button
                        data-producto-id="{{$producto->id}}"
                        data-producto-nombre="{{$producto->nombre}}"
                        style="background-color: #FF5A5A;"
                        class="add-to-cart float-end hover-grow-s mx-3 btn-s rounded-sm shadow-xl text-uppercase font-800">Agregar</button>
                    @endif
                </div>
                {{-- Control tonalidad oscura --}}
                <div class="card-overlay dark-mode-tint opacity-70"></div>
            </div>
            {{-- MENCION A LOS TAGS DEL PRODUCTO --}}
            {{-- <p class="color-highlight font-600 mb-n1">Delight Nutrifood</p>
            <h1>Caracteristicas del producto</h1> --}}
            <div class="row">
                <div class="col-2"></div>
                <div class="col-4">
                    <ul class="icon-list">
                        <li><i class="fa fa-check color-green-dark"></i> Libre de Gluten</li>
                        <li><i class="fa fa-check color-green-dark"></i> Organico</li>
                        <li><i class="fa fa-minus color-red-dark"></i> Integral</li>
                    </ul>
                </div>
                <div class="col-4">
                    <ul class="icon-list">
                        <li><i class="fa fa-check color-green-dark"></i> Sin azucares</li>
                        <li><i class="fa fa-minus color-red-dark"></i> Stevia</li>
                    </ul>
                </div>
                <div class="col-2"></div>
            </div>
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