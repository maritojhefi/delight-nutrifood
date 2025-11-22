@push('content-comentado')
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
@endpush


<div class="card card-style">
    <div class="content">
        <p class="mb-n1 color-highlight font-600">
            {{ GlobalHelper::getValorAtributoSetting('nombre_sistema') . ' - ' . GlobalHelper::getValorAtributoSetting('slogan') }}
        </p>
        <h2 class="font-24 font-800">
            Productos puntuados
        </h2>
        <p class="my-3">
            ¡Adquiere puntos comprando los siguientes productos!
        </p>
        <div class="row mb-0">
            @foreach ($productos as $producto)
            <div class="col-6 ">
                <!-- <div class="card rounded-m shadow-l"
                    data-card-height="200"
                    style="background-image: url('{{ $producto->pathAttachment() }}');">
                    >
                    <div class="card-bottom text-center">
                        <h2 class="color-white font-700 font-18 mb-3 px-1">{{ $producto->nombre }}</h2>
                        <a class="btn btn-xs rounded-m btn-full font-13 font-600 gradient-blue rounded-s me-2 ms-2 mb-2"
                            href="{{ route('delight.detalleproducto', $producto->id) }}">
                            {{ $producto->puntos }} Puntos
                        </a>
                    </div>
                    <div class="card-overlay bg-gradient dark-mode-tint"></div>
                </div> -->
                <!-- <div class="card card-style mx-0 bg-dtheme-blue">
                    <div class="d-flex text-center align-items-end"
                        style="
                            background-image: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8)), url('{{ $producto->pathAttachment() }}');
                            height: 8rem;
                            background-size: cover;
                            background-position: center;
                        ">
                        <h2 class="color-white font-700 font-18 mb-3 px-1">{{ $producto->nombre }}</h2>
                    </div>
                    <div class="px-2">
                        <div class="d-flex flex-row align-items-center justify-content-between my-2">
                            <h5 class="font-14 mb-0">Bs. {{ $producto->precioReal() }}</h5>
                            <span class=" badge badge-xl gradient-blue">
                                {{ $producto->puntos }} Puntos</span>
                        </div>
                        <a href="#" class="btn btn-xxs btn-full border-highlight rounded-s color-highlight mb-3">Order</a>
                    </div>
                </div> -->
                <x-card-producto-individual :producto="$producto" />
            </div>
            @endforeach
        </div>
    </div>
    
</div>