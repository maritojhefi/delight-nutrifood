<div>
    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalId">Egresos <i
            class="fa fa-money"></i></a>
</div>

@push('footer')
    <!-- Modal -->
    <div class="modal fade" id="modalId" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">Egresos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form>
                            <div class="mb-3">
                                <input type="number" class="form-control input-default " placeholder="Ingrese Monto">
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control input-rounded" placeholder="Ingrese Detalle">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success">Registrar</button>
                    <a href="#" class="btn btn-sm btn-warning">Ver todo</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        var modalId = document.getElementById('modalId');

        modalId.addEventListener('show.bs.modal', function(event) {
            // Button that triggered the modal
            let button = event.relatedTarget;
            // Extract info from data-bs-* attributes
            let recipient = button.getAttribute('data-bs-whatever');

            // Use above variables to manipulate the DOM
        });
    </script>
@endpush
