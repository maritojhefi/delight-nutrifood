<div class="row px-2 mb-0">

    @php
        $labels = [
            'sopa' => 'Sopa',
            'ensalada' => 'Ensalada',
            'jugo' => 'Jugo'
        ];
    @endphp

    @foreach($labels as $key => $label)
        @if ($plan->$key)
            <div class="col-12 px-0 d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex flex-row gap-1 align-items-center">
                    <strong class="">{{ $label }}</strong>
                    <!-- <i data-lucide="check" class="lucide-icon color-teal-dark" style="width: 1.1rem; height: 1.1rem;"></i> -->
                    <i class="fa fa-check color-green-dark"></i>
                </div>
                <p class="text-end font-400 mb-0 color-theme">
                    {{ ucfirst($lista[$key]) }}
                </p>
                <input type="hidden" value="{{ $lista[$key] }}" name="{{ $key }}">
            </div>
        @endif
    @endforeach

    <input type="hidden" value="{{ $lista['dia'] }}" name="dia">
    <input type="hidden" value="{{ $lista['id'] }}" name="id">
    <input type="hidden" value="{{ $plan->id }}" name="plan">

    <div class="d-flex flex-row gap-1 justify-content-evenly px-0 my-2">
        @if ($lista['estado'] == 'pendiente')
            <a href="#" data-menu="permiso-menu-{{ $lista['id'] }}"
                class="py-2 px-3 font-15 rounded-s text-uppercase bg-delight-red color-white font-600 line-height-s permiso-pedido-btn">
                <span class="text-white">Permiso</span>
            </a>
        @endif
        <button type="submit" disabled 
            class="btn py-2 px-3 font-15 rounded-s text-uppercase bg-highlight font-600 line-height-s">
            <span class="d-flex flex-row align-items-center gap-1">Confirmar</span>
        </button>
    </div>
</div>

@push('modals')
<div id="permiso-menu-{{ $lista['id'] }}" class="menu menu-box-modal pb-3 rounded-m overflow-hidden" style="width: 90%; max-width: 320px">
    <div class="menu-title p-3">
        <div class="d-flex flex-row gap-2 align-items-center">
            <i data-lucide="calendar-clock" class="lucide-icon" style="width: 2.5rem; height: 2.5rem;"></i>
            <div>
                <!-- <p class="color-highlight font-10">{{ $plan->nombre }}</p> -->
                <h1 class="font-20 p-0 m-0 line-height-m">Solicitar permiso</h1>
            </div>
        </div>
        <a href="#" class="close-menu"><i data-lucide="x-circle" class="lucide-icon"></i></a>
    </div>
    <div class="content mt-0 mb-3 d-flex flex-column h-100 gap-3">
        <p class="pe-3 mb-0 color-theme">
            Tu pedido para {{ $lista['dia'] }} {{ $lista['fecha'] }}. será pospuesto por un dia.
        </p>
        <div class="d-flex flex-row gap-1 justify-content-evenly mb-0">
            <a href="#" class="close-menu py-2 px-2 font-15 rounded-s text-uppercase bg-delight-red color-white font-600 line-height-s">Cancelar</a>
            <button data-pedido="{{ $lista['id'] }}" href="#" class="confirmar-pedido-permiso py-2 px-2 font-15 rounded-s text-uppercase bg-highlight font-600 line-height-s">
                <span class="d-flex flex-row align-items-center gap-1">Confirmar</span>
            </button>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<!-- <script>
    $(document).ready( function () {
            $('.confirmar-pedido-permiso').on('click', async function (e) {
                e.preventDefault();
                const idPedido = $(this).data('pedido');
                console.log(`click en confirmar permiso para el pedido ${idPedido}`);

                await PlanesService.permisoPedido(idPedido).then(
                    (respuestaPermiso) => {
                        // Cerrar modal
                        location.reload();
                        // Retirar acordeon para el pedido omitido

                        // (Posible caso) En el caso de quedar pocos dias en el plan, se deberan renderizar el acordeon para el nuevo pedido
                        // Empujado un dia al final del plan, (lo mas sencillo es re renderizar toda la pagina)
                    }
                ).catch(error => {
                    if (error.response) {
                        console.log("Aparentemente hay respuesta para el error:", error.response);
                    }
                    console.log(`Ocurrio un error al solicitar el permiso ${idPedido}:`, error);
                })
            });
        }
    );
</script> -->
@endpush