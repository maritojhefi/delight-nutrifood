<div class="card-fixed mb-n5 mx-auto" data-card-height="400" style="height: 400px; width:460px">
    <div class="card rounded-0" style="background-image: url({{asset('imagenes/productos/' . $producto->imagen)}}), url('{{asset('imagenes/delight/default-bg-1.png')}}'); height: 400px;" data-card-height="400">
        <div class="card-bottom px-3 pb-5">
            <h1 class="color-white font-30 mb-0">
                {{$producto->nombre()}} 
                @if ($producto->descuento && $producto->descuento > 0 && $producto->descuento < $producto->precio)
                <span class="badge font-15 bg-red-dark color-white text-uppercase d-block float-end my-1">
                    EN DESCUENTO
                </span>
                @endif
            </h1>
            <p class="color-white font-13 opacity-80">
                {{$producto->detalle()}}
            </p>
        </div>
        <div class="card-top mt-3 pb-5 ps-3">
            <a href="#" data-back-button="" class="icon icon-s bg-theme rounded-xl float-start me-3"><i class="fa color-theme fa-arrow-left"></i></a>
            <button ruta="{{route('delight.detalleproducto', $producto->id)}}" class="copiarLink icon icon-s bg-theme rounded-xl float-end me-3 ps-3"><span class="text-secondary">Compartir</span><i class="fa color-theme fa-share-alt"></i></button>
            <button class="cambiarColor page-title-icon shadow-xl bg-theme rounded-xl float-end me-2"><x-theme-icon /></button>
        </div>
        <div class="card-overlay bg-gradient opacity-80"></div>
    </div>
</div>
<div class="card card-clear" data-card-height="400" style="height: 400px;"></div>