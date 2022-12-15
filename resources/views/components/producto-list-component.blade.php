<div class="d-flex">
    <a href="{{ $ruta }}">
        <div>
            <img src="{{ $foto }}" class="rounded-sm" width="55">
        </div>
        <div class="ps-3">
            <h6 class="font-15">{{ $nombre }}</h6>
    </a>
    <a href="#"><span class="badge bg-red-dark font-700 font-12  carrito" id="{{ $id }}"><i
                class="fa fa-shopping-cart"></i> Añadir</span></a>
    <a href="#"><span class="badge bg-blue-dark font-700 font-12 copiarLink" id="{{ $id }}" ruta="{{$ruta}}"><i
                class="fa fa-link"></i> Compartir</span></a>

</div>
<div class="ms-auto">
    <h1 class="font-15">{{ $precio }} Bs</h1>
</div>
</div>
<div class="divider mt-3 mb-3"></div>
