<div class="card card-style rounded-md mx-0 preload-img mt-2 entered loaded" data-src="images/pictures/20s.jpg" data-ll-status="loaded"
    style="background-image: url({{ asset('imagenes/delight/default-bg-vertical.jpg') }});">
    <div class="card-body">
        <div class="mx-4 mb-0">
            <h4 class="color-white pt-3 font-24">Gana Puntos!</h4>
            <p class="color-white pt-1 mb-2 text-justify">
                Los productos seleccionados atribuyen puntos por cada compra realizada.
                Mientras mas puntos, mas premios!
            </p>
        </div>
        <div class="bg-transparent m-0 shadow-0">
            <div class="row mb-0">
                @foreach ($productos as $producto)
                    <div class="col-6 mb-2">
                        <a href="{{ route('delight.detalleproducto', $producto->id) }}"
                            class="card card-style bg-dtheme-blue m-0 p-3 d-flex align-items-center hover-grow"
                            style="min-height: 13rem"
                            data-menu="menu-product">
                            <img
                            src="{{ $producto->pathAttachment() }}"
                            onerror='this.src="{{ GlobalHelper::getValorAtributoSetting("bg_default") }}"'
                            class="rounded-m w-100 shdaow-xl"
                            style="height:7rem;object-fit: cover;"
                            />
                            <div class="my-1" style="height: 3rem;overflow: hidden;">
                                <p id="nombre-producto-puntuado" class="mb-0 font-600 text-center color-theme">{{ Str::limit($producto->nombre(), 40) }}</p>
                            </div>
                            <div class="divider mb-0"></div>
                            <div class="d-flex flex-row justify-content-between flex-grow-0 w-100 mb-0">
                                <p class="font-600 mb-0 color-theme">Bs. {{ $producto->descuento ? $producto->descuento : $producto->precio }}</p>
                                <p class="bg-blue-dark font-11 px-2 font-600 rounded-xs shadow-xxl mb-0">{{ $producto->puntos }} Pts</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-overlay bg-highlight opacity-90"></div>
    <div class="card-overlay dark-mode-tint"></div>
</div>