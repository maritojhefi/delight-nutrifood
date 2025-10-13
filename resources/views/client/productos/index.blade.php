@extends('client.master')
@section('content')
    <x-cabecera-pagina-highlight titulo="Eco Tienda" />
    <div class="content mb-0">
        {{-- <x-barra-busqueda-productos /> --}}

        {{-- SLIDER PRODUCTOS MAS VENDIDOS --}}
        <div id="best-selling-container" class="my-4">
            <x-slider-productos :productos="$masVendidos" tag="popular" :title="'Los mas vendidos'" />
        </div>

        {{-- SLIDER PRODUCTOS MAS RECIENTES --}}
        <div id="recent-container" class="my-4">
            <x-slider-productos :productos="$masRecientes" tag="recent" title="Novedades" orientation="right" />
        </div>
        <a  
            {{-- data-bs-toggle="modal" 
            data-bs-target="#subcategoriesModal" --}}
            href="{{ route('listar.subcategorias.productos') }}"
            data-card-height="100" class="card card-style col-12 mx-0 mt-2 px-0 round-medium shadow-huge hover-grow-xs"
            style="height: 100px;background-color: #FF5A5A;">
            <div class="card-center d-flex flex-row align-items-center justify-content-between ps-3 pe-3">
                <div class="d-flex flex-row align-items-center gap-3">
                    {{-- <i data-lucide="apple" class="lucide-icon" style="color: white; width: 3rem; height: 3rem;"></i> --}}
                    <i class="fa fa-apple-alt fa-3x" style="color: white"></i>
                    <div class="text-start">
                        <h2 class="text-white font-16">Todas nuestras categorias</h2>
                        <p class="mb-0 font-12 text-white opacity-75">Explora las categorias disponibles</p>
                    </div>
                </div>
                <i class="fa fa-arrow-circle-right fa-2x" style="color: white"></i>
            </div>
            <div class="card-overlay dark-mode-tint"></div>
        </a>

        {{-- CARD PRODUCTOS PUNTUADOS --}}
        @if ($conMasPuntos->count() > 0)
            <x-seccion-gana-puntos :productos="$conMasPuntos" />
        @endif
    </div>

    {{-- MODAL SUPLEMENTOS STARK --}}
    <div class="modal fade" id="starkSuplementsModal" tabindex="-1" aria-labelledby="starkSuplementsModalLabel" aria-hidden="true" style="z-index: 9999">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mx-2 mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="stark-modal-title" class="mb-0 ms-4 align-self-center text-uppercase">STARK SUPLEMENTS</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 px-3 d-flex flex-column"  id="listado-productos-stark">
                    {{-- <div class="p-0 m-0 justify-content-center align-items-center"> --}}
                        <!-- Contenedor items individuales-->
                        @if($suplementosStark->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <i class="fa fa-question-circle fa-5x mb-3"></i>
                                <p>Parece que no hay suplementos stark en stock ahora mismo, verifica más tarde.</p>
                            </div>
                        @else
                            @foreach ($suplementosStark as $productoStark)
                                <x-producto-card :producto="$productoStark" />
                            @endforeach
                        @endif
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PRODUCTOS EN OFERTA --}}
    <div class="modal fade" id="saleProductsModal" tabindex="-1" aria-labelledby="saleProductsModalLabel" aria-hidden="true" style="z-index: 9999">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header mt-2 border-0 gap-4 d-flex align-items-center">
                    <h4 id="sale-modal-title" class="mb-0 align-self-center text-uppercase">Productos en Oferta</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body pt-0 px-3 mt-0 d-flex flex-column" id="listado-productos-ofertados">
                    {{-- <div class="content justify-content-center align-items-center" > --}}
                        <!-- Contenedor items individuales-->
                        @if($enDescuento->isEmpty())
                            <div class="d-flex flex-column justify-content-center align-items-center text-center py-5">
                                <i class="fa fa-question-circle fa-5x mb-3"></i>
                                <p>Parece que no hay productos en oferta ahora mismo, verifica más tarde.</p>
                            </div>
                        @else
                            @foreach ($enDescuento as $ofertado)
                                <x-producto-card :producto="$ofertado" />
                            @endforeach
                        @endif
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>

    <x-menu-adicionales-producto :isUpdate="false"/>
@endsection

@push('scripts')
<script> 
    // $(document).ready(function() {
    //     $(document).on('click', '.add-to-cart', agregarAlCarritoHandler);
    // });
    $(document).ready(function() {
        $(document).on('click', '.agregar-unidad', agregarAlCarritoHandler);

        $(document).on('click', '.menu-adicionales-btn', function() {
            const productoId = $(this).data('producto-id');
            console.log("Product ID:", productoId); 
            openDetallesMenu(productoId);
        });
    });

    async function agregarAlCarritoHandler() {
        const product_Id = $(this).data('producto-id');
        const product_nombre = $(this).data('producto-nombre')

        try {
            const result = await carritoStorage.agregarAlCarrito(product_Id, 1);
            refrescarContadoresFooter();
            if (result.success) {
                console.log("Producto  agregado con exito al carrito.")
            } else {
                console.log(`Error al agregar el producto ${product_nombre} al carrito.`)
            }
        } catch (error) {
            console.error('Error agregando el producto al carrito:', error);
        }
    }
</script>
{{-- SCRIPT CONTROL DEL MODAL PRODUCTOS CATEGORIZADOS [ECO-TIENDA] --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        saleProductsModal = new bootstrap.Modal(document.getElementById('saleProductsModal'), {
            focus: true
        });

        starkProductsModal = new bootstrap.Modal(document.getElementById('starkSuplementsModal'), {
            focus: true
        });

        const saleModalElement = document.getElementById('saleProductsModal');
        const starkModalElement = document.getElementById('starkSuplementsModal');

        saleModalElement.addEventListener('show.bs.modal', async function (event) {
            const triggerElement = event.relatedTarget;
        });

        starkModalElement.addEventListener('show.bs.modal', async function (event) {
            const triggerElement = event.relatedTarget;
        });
    });
</script>

@endpush