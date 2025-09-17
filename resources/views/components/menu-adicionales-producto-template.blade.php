<div id="details-menu" class="menu menu-box-bottom rounded-m  pb-5">    
    <div class="menu-title">
        <p class="color-highlight">Delight-Nutrifood</p>
        <h1 class="font-22">Personaliza tu orden</h1>
        <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
    </div>
    <div class="divider mb-0"></div>
    <div class="content mt-3">
        <div class="d-flex mb-3">
            <div class="align-self-center">
                <img src="{{asset($producto->imagen)}}" class="rounded-sm me-3"
                style="width: 5rem; height: 5rem; object-fit: cover;">
            </div>
            <div class="align-self-center">
                <h2 class="font-16 line-height-s mt-1 mb-n1">{{$producto->nombre}}</h2>
                <div class="mb-0 font-11 mt-2 d-flex flex-row align-items-center justify-content-center gap-2">
                    @foreach ($producto->tag as $tag)
                    <i  data-lucide="{{$tag->icono}}" 
                        class="lucide-icon tag-icon"
                        style="width: 1rem; height: 1rem;"></i>
                    @endforeach
                    Info
                    <i class="fa fa-truck color-green-dark pe-1 ps-2"></i>Empacado estandar
                </div>
            </div>
        </div>
        <h5>Adicionales</h5>
        <div class="row">
                @foreach ($adicionales as $adicional)
                <div class="col-6">
                    <div class="form-check icon-check mb-0">
                        <input class="form-check-input" id="check-{{$adicional->id}}" type="checkbox" checked="">
                        <label class="form-check-label" for="check-{{$adicional->id}}">{{ucfirst($adicional->nombre)}}</label>
                        <i class="icon-check-1 fa fa-square color-gray-dark font-16"></i>
                        <i class="icon-check-2 fa fa-check-square font-16 color-highlight"></i>
                    </div>
                </div>
                @endforeach
        </div>
        <div class="divider mb-2"></div>
        <div class="d-flex mb-3 pb-1">
            <div class="align-self-center">
                <h5 class="mb-0">Cantidad</h5>
            </div>
            <div class="ms-auto align-self-center">
                <div class="stepper rounded-s small-switch me-n2">
                    <a href="#" class="stepper-sub"><i class="fa fa-minus color-theme opacity-40"></i></a>
                    <input type="number" min="1" max="99" value="1">
                    <a href="#" class="stepper-add"><i class="fa fa-plus color-theme opacity-40"></i></a>
                </div>
            </div>
        </div>
        <div class="d-flex mb-3">
            <div class="align-self-center">
                <h5 class="mb-0">Costo Total</h5>
            </div>
            <div class="ms-auto align-self-center">
                <h5 class="mb-0">Bs. 25.30</h5>
            </div>
        </div>
        <div class="divider"></div>
        <a href="#" class="btn btn-full btn-m bg-highlight font-700 rounded-sm close-menu">Agregar al carrito</a>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {

        });

        // DEFINIR LA LOGICA PARA EL CONTROL DEL MENU
        // $(".menu-active").removeClass("menu-active"); //para cerrar cualquier modal posiblemente abierto incluido el backdrop
        // $(".menu-hider").addClass("menu-active"); //para activar/mostrar backdrop
        // $("#" + response.modal).addClass("menu-active"); //para activar/mostrar menu
    </script>
@endpush
