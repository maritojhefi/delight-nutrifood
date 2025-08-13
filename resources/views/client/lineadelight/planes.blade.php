@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Planes {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') }}" cabecera="appkit" />

    <a href="#" data-menu="menu-tips-1" >
        <div class="card card-style rounded-md mb-3" style="background-color: #5BD6FF;" data-card-height="125"
            style="height: 145px;">
            <div class="card-center ">
                <div class="row mb-0 align-items-center px-1">
                    <div class="col-10">
                        <h1 class="color-white font-700 mb-n1 ms-3">Terminos y condiciones</h1>
                        <p class="color-white opacity-100 mb-0 ms-3">
                            Leelos antes de ingresar a cualquiera de nuestros planes.
                        </p>
                    </div>
                    <div class="col-2 ">
                        <i class="fa fa-exclamation text-white fa-3x fa-beat"></i>
                    </div>
                </div>
            </div>
            <div class="card-overlay dark-mode-tint"></div>
        </div>
    </a>
    
    @foreach ($subcategoria->productos as $producto)
        <div data-card-height="150" class="card card-style plan-card round-medium shadow-huge top-30 mb-3"
            style="background-image: url('{{ asset('imagenes/delight/default-bg-horizontal.jpg') }}'); background-size: cover; background-position: center;">

            <div class="d-flex justify-content-between align-items-center ms-4 me-4 mt-4">
                <div class="plan-title-container" style="flex: 1; margin-right: 15px;">
                    <h2 class="mb-0 plan-title-text"
                        style="z-index: 10; 
                           line-height: 1.2;
                           word-break: break-word;
                           height: 2.4rem;"
                        data-plan-name="{{ $producto->nombre }}">
                        {{ $producto->nombre }}
                    </h2>
                </div>
                <button
                    class="btn add-to-cart confirm-btn text-light rounded-pill fw-bold text-uppercase small flex-shrink-0"
                    style="z-index: 10;" 
                    id="{{ $producto->plane ? $producto->plane->id : $producto->id }}"
                    data-producto-id="{{$producto->id}}"
                    data-producto-nombre="{{$producto->nombre}}"
                    >
                    <span class="text-white">{{ $producto->precioReal() }} Bs </span>
                    <i class="fa fa-heart fa-beat" style="color: deeppink;"> </i>
                </button>
            </div>

            @if ($producto->plane && $producto->plane->editable)
                <div class="d-flex align-items-center ms-4 me-3 mt-3 card-center">
                    <a href="#" class="icon icon-xxs rounded-circle shadow-l ms-0 me-2 bg-green-light">
                        <i class="fa fa-check"></i>
                    </a>
                    <p class="text-white fw-bold font-12 small lh-sm m-0">PLAN PERSONALIZABLE</p>
                </div>
            @endif

            <div class="d-flex align-items-center ms-4 me-3 mb-2 card-bottom">
                <i class="fa fa-apple-alt fs-1 me-3 plan-icon"></i>
                <p class="text-white small lh-sm m-0">{{ $producto->detalle }}</p>
            </div> 

            <div class="plan-overlay card-overlay opacity-60"></div>

        </div>
    @endforeach
    @include('client.lineadelight.include-planes-modal')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Función para ajustar el tamaño de fuente para que el texto quepa exactamente en 2 líneas
            function adjustPlanTitleFontSize() {
                const planTitles = document.querySelectorAll('.plan-title-text');

                planTitles.forEach(function(title) {
                    const planName = title.getAttribute('data-plan-name');

                    // Configuración inicial
                    let maxFontSize = 1.5; // 1.5rem máximo
                    let minFontSize = 0.7; // 0.7rem mínimo
                    let currentFontSize = maxFontSize;
                    const maxHeight = 2.4; // 2.4rem altura máxima (2 líneas)
                    const lineHeight = 1.2;

                    // Función para convertir rem a px (asumiendo 16px = 1rem)
                    function remToPx(rem) {
                        return rem * 16;
                    }

                    // Función para verificar si el texto cabe en el contenedor
                    function textFitsInContainer(fontSize) {
                        title.style.fontSize = fontSize + 'rem';
                        title.style.lineHeight = lineHeight;

                        // Crear un elemento temporal para medir el texto
                        const tempElement = document.createElement('div');
                        tempElement.style.position = 'absolute';
                        tempElement.style.visibility = 'hidden';
                        tempElement.style.fontSize = fontSize + 'rem';
                        tempElement.style.lineHeight = lineHeight;
                        tempElement.style.wordBreak = 'break-word';
                        tempElement.style.width = title.offsetWidth + 'px';
                        tempElement.textContent = planName;

                        document.body.appendChild(tempElement);
                        const actualHeight = tempElement.offsetHeight;
                        document.body.removeChild(tempElement);

                        return actualHeight <= remToPx(maxHeight);
                    }

                    // Algoritmo de búsqueda binaria para encontrar el tamaño óptimo
                    let iterations = 0;
                    const maxIterations = 20;

                    while (maxFontSize - minFontSize > 0.05 && iterations < maxIterations) {
                        currentFontSize = (maxFontSize + minFontSize) / 2;

                        if (textFitsInContainer(currentFontSize)) {
                            minFontSize = currentFontSize;
                        } else {
                            maxFontSize = currentFontSize;
                        }

                        iterations++;
                    }

                    // Aplicar el tamaño final con un pequeño margen de seguridad
                    const finalFontSize = Math.max(minFontSize - 0.02, 0.7);
                    title.style.fontSize = finalFontSize + 'rem';
                    title.style.lineHeight = lineHeight;

                    // Si aún no cabe, usar la estrategia de word-wrap más agresiva
                    if (!textFitsInContainer(finalFontSize)) {
                        title.style.wordBreak = 'break-all';
                        title.style.hyphens = 'auto';
                    }
                });
            }

            // Ejecutar la función al cargar la página y después de un pequeño delay
            adjustPlanTitleFontSize();
            setTimeout(adjustPlanTitleFontSize, 100);
        });
    </script>
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