<div class="modal fade" id="modalDetalleVenta" tabindex="-1" role="dialog" wire:ignore.self>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content letra14">
            @isset($ventaSeleccionada)
                @livewire('admin.reutilizables.modal-detalle-venta-component', ['ventaSeleccionada' => $ventaSeleccionada], key('venta-' . $ventaSeleccionada->id))
            @else
                <div class="alert alert-warning">
                    <i class="fa fa-exclamation-triangle"></i>
                    No se pudo cargar la información de la venta.
                </div>
            @endisset
        </div>
    </div>
</div>
