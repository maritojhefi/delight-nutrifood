@extends('client.master')
{{-- @section('content-comentado')
    <x-cabecera-pagina titulo="Detalles del Producto" cabecera="appkit" />
    <x-producto-detalle-component :producto="$producto"/>

    <div class="card card-style">
        <div class="content">
            <h4>Productos relacionados</h4>

            <div class="divider mt-3 mb-3"></div>
            @foreach ($producto->subcategoria->productos->shuffle()->take(5) as $item)
                <div class="d-flex">
                    <div>
                        <a href="{{ route('delight.detalleproducto', $item->id) }}">
                            <img src="{{ asset($item->pathAttachment()) }}" class="rounded-sm" width="55">
                    </div>
                    <div class="ps-3">
                        <h4>{{ Str::limit($item->nombre(), 25) }}</h4>
                        </a>
                        <a href="#"><span class="badge bg-magenta-dark font-700 font-11 text-uppercase carrito" id="{{$item->id}}">Añadir <i
                                    class="fa fa-shopping-cart"></i></span></a>
                    </div>
                    <div class="ms-auto">
                        <h1 class="font-20">{{ $item->descuento ? $item->descuento : $item->precio }} Bs</h1>
                    </div>
                </div>
                <div class="divider mt-3 mb-3"></div>
            @endforeach
        </div>
    </div>
@endsection --}}
@section('content')
    {{-- CABECERA ESTATICA --}}
    <x-cabecera-detalle-delight :producto="$producto"/>
    {{-- CARD ENVOLVENTE PARA EL CONTENIDO --}}
    <div class="card card-full rounded-m">
        <div class="drag-line"></div>
        <div class="content">
            {{-- CONTENEDOR PRECIO Y BOTON DE AGREGAR --}}
            <div class="card card-style bg-6 mx-0 mt-3" style="background-image: url({{asset('imagenes/delight/fork_picture.jpg')}}); height: 100px;" data-card-height="100">
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
                    <h5 class="color-white mt-n1 opacity-80 font-14">Unidades en mi carrito: X</h5>
                </div>
                {{-- CONDICIONANTE HABILITACION BOTON POR STOCK --}}
                <div class="card-center">
                    @if ($producto->unfilteredSucursale->isNotEmpty() && $producto->stock_actual == 0)
                        <button class="float-end mx-3 gradient-gray btn-s rounded-sm shadow-xl text-uppercase font-800">Sin Stock</button>
                    @else
                        <button
                        data-producto-id="{{$producto->id}}"
                        data-producto-nombre="{{$producto->nombre}}"
                        class="add-to-cart float-end hover-grow-s mx-3 gradient-highlight btn-s rounded-sm shadow-xl text-uppercase font-800">Agregar</button>
                    @endif
                </div>
                <div class="card-overlay bg-black opacity-40"></div>
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

    <script src="{{ asset('js/carrito/index.js') }}"></script>
    <script src="{{ asset(path: 'js/producto/producto-service.js') }}"></script>


    <script> 
        $(document).ready(function() {
            $(document).on('click', '.add-to-cart', addToCartHandler);
        });

        async function addToCartHandler() {
            const product_Id = $(this).data('producto-id');
            const product_nombre = $(this).data('producto-nombre')

            // console.log("ID producto a agregar: ", product_Id);
            // console.log("Nombre del producto a agregar: ", product_nombre);
            try {
                const result = await addToCart(product_Id, 1);
                if (result.success) {
                    showMessage('success', 'Item agregado al carrito!');
                } else {
                    showMessage('error', result.message);
                }
            } catch (error) {
                console.error('Error agregando el producto al carrito:', error);
                showMessage('error', 'Error al agregar el producto al carrito');
            }
        }

        function showMessage(type, text) {
            $('#message-container').html(`<div class="alert alert-${type}">${text}</div>`);
            setTimeout(() => $('#message-container').empty(), 3000);
        }
    </script>
@endsection
