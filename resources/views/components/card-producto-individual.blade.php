<!-- @props(['producto']) -->

<a href="{{ route('delight.detalleproducto', $producto->id) }}"
    class="card card-style rounded-md w-100 mx-0"
    style="height: 10rem; background-image: url('{{ $producto->pathAttachment() }}'); background-size: cover; background-position: center;">
    <div class="position-absolute end-0 px-2 py-1 color-theme text-center" 
        style="
            border-radius: 0.375rem 0 0 0.375rem; 
            margin-top: 1rem; 
            background-color: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(8px);                  
            -webkit-backdrop-filter: blur(8px);
            min-width: 4rem;   
            z-index: 2;         
        ">
        <strong class="color-black font-16">Bs. {{ $producto->precioReal() }}</strong>
    </div>
    @if ($producto->puntos > 0)
    <div class="position-absolute end-0 px-1 py-0 font-12 color-theme gradient-blue"
        style="
            margin-top: 3.5rem;
            border-radius: 0.375rem 0 0 0.375rem;
            z-index: 2;
        ">
        <span class="color-white">{{ $producto->puntos }} Puntos</span>
    </div>
    @endif
    <div class="card-bottom text-center">
        <h3 class="color-white font-16 font-600 mb-3 mx-1">{{ $producto->nombre }}</h3>
    </div>
    <div class="card-overlay bg-gradient opacity-80"></div>
</a>