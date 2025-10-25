<div class="card card-style">
    <div class="card bg-13" data-card-height="250"
        style="height: 250px;background-image:url('{{ $producto->pathAttachment() }}')">
        <div class="card-bottom pb-4 ps-3">
            <h3 class="font-20 text-white">
                {!! wordwrap($producto->nombre(), 20, "<br />\n") !!}
            </h3>
        </div>
        <div class="card-bottom pb-4 pe-3">
            <h1 class="font-20 text-end mb-3 text-white">
                {{ $producto->descuento ? $producto->descuento : $producto->precio }} Bs <br><sup
                    class="font-400 font-17 opacity-50">({{ $producto->medicion }})</sup></h1>
            <span
                class="badge {{ $producto->descuento ? 'bg-danger' : 'bg-dark' }} color-white px-2 py-1 mt-n1 text-uppercase d-block float-end">{{ $producto->descuento ? 'En descuento' : 'Sin promocion' }}</span>
        </div>
        <div class="card-overlay bg-gradient opacity-70 rounded-0"></div>
    </div>
    <div class="content mt-n2">
        <div class="row">
            <div class="col-6">
                <p class="line-height-m">
                    {{ $producto->detalle() }}
                </p>
            </div>
            <div class="col-6">
                <div>
                    <p class="font-10 mb-n2">Categoria</p>
                    <p class="font-12 color-theme font-700">{{ $producto->subcategoria->nombre }}</p>
                </div>
                <div>
                    <p class="font-10 mb-n2">Stock</p>
                    <p class="font-12 color-theme font-700">
                        @if ($producto->contable)
                            @if ($producto->sucursale->count() != 0)
                                {{-- @foreach ($producto->sucursale->groupBy('nombre') as $nombre => $item)
                                
                                    {{ $nombre }} : {{ $item->sum('pivot.cantidad') }} <br>
                                @endforeach --}}
                                <a href="#" class="chip chip-small bg-gray-light mt-2">
                                    <i class="fa fa-check bg-green-dark"></i>
                                    <strong class="color-black font-400">Disponible</strong>
                                </a>
                            @else
                                <a href="#" class="chip chip-small bg-gray-light mt-2">
                                    <i class="fa fa-times bg-red-dark"></i>
                                    <strong class="color-black font-400">Agotado</strong>
                                </a>
                            @endif
                        @else
                            @if ($producto->estado == 'activo')
                                <a href="#" class="chip chip-small bg-gray-light mt-2">
                                    <i class="fa fa-check bg-green-dark"></i>
                                    <strong class="color-black font-400">Disponible</strong>
                                </a>
                            @else
                                <a href="#" class="chip chip-small bg-gray-light mt-2">
                                    <i class="fa fa-times bg-red-dark"></i>
                                    <strong class="color-black font-400">Agotado</strong>
                                </a>
                            @endif
                        @endif



                    </p>
                </div>
                <div>
                    <p class="font-10 mb-n2">Puntos por la compra</p>
                    <p class="font-12 color-theme font-700">{{ $producto->puntos == null ? '0' : $producto->puntos }}
                        Pts
                    </p>
                </div>

            </div>
        </div>
        <div class="divider mt-4 mb-2"></div>
        {{-- <div class="d-flex">
            <div>
                <p class="mb-n1 font-10">Puntuacion &amp; Comentarios</p>
                <h6 class="float-start">4.9</h6>
                <i class="float-start color-yellow-dark pt-1 ps-2 fa fa-star"></i>
                <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
                <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
                <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
                <i class="float-start color-yellow-dark pt-1 fa fa-star"></i>
            </div>
            <div class="ms-auto">

            </div>
        </div> --}}
        <div class="row mb-0">

            <div class="col-6">
                <a data-menu="menu-tips-2" href="#" id="{{ $producto->id }}"
                    class="btn btn-sm me-3 rounded-s btn-full shadow-l bg-highlight font-900 text-uppercase carrito"><i class="fa fa-shopping-cart"></i> Añadir</a>
            </div>
            <div class="col-6">
                <a href="#" ruta="{{Request::url()}}"
                    class="btn btn-sm me-3 rounded-s btn-full shadow-l bg-blue-dark font-900 text-uppercase copiarLink"><i class="fa fa-link"></i> Compartir</a>
            </div>
        </div>

    </div>
</div>
