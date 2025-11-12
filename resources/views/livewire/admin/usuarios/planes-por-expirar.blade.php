<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Reporte de Planes expirados/por expirar</h4>
            </div>
            
            <!-- Buscador -->
            <div class="card-body pb-0">
                <div class="row mb-3">
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar por nombre..." wire:model.debounce.500ms="search">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <div wire:loading class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped table-responsive-sm table-compact">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Restantes</th>
                                <th>Ultima Fecha</th>
                                <th>Plan</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coleccion as $usuario)
                            <tr>
                                <td class="py-2" style="border-bottom: 1px solid #dee2e6;">
                                    <a href="{{route('detalleplan',[$usuario['user_id'],$usuario['plan_id']])}}" target="_blank" style="cursor: pointer; text-decoration: underline;">{{Str::limit($usuario['nombre'],30,'')}}</a>
                                </td>
a                                <td class="py-2" style="border-bottom: 1px solid #dee2e6;">
                                    <span class="badge badge-{{$usuario['cantidadRestante']>0?'success':'danger'}}">
                                        {{$usuario['cantidadRestante']>0?'Vigente':'Expirado'}}
                                    </span>
                                </td>
                                
                                <td class="py-2 color-primary fw-bold">{{$usuario['cantidadRestante']}}</td>
                                <td class="py-2">{{$usuario['ultimoDia']}} ({{ App\Helpers\GlobalHelper::timeago($usuario['ultimoDia'], 'dia') }})</td>
                                <td class="py-2">{{Str::limit($usuario['plan'],30,'')}}</td>
                                <td class="py-2 text-center">
                                    @if($usuario['cantidadRestante'] > 0)
                                        <button type="button" class="btn btn-secondary btn-sm" 
                                                disabled
                                                title="No se puede eliminar. Tiene {{$usuario['cantidadRestante']}} plan(es) pendiente(s)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="confirmarEliminarPlan({{$usuario['user_id']}}, {{$usuario['plan_id']}}, '{{addslashes($usuario['nombre'])}}', '{{addslashes($usuario['plan'])}}')"
                                                title="Eliminar plan">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted">No se encontraron resultados</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Mostrando {{ $coleccion->firstItem() ?? 0 }} - {{ $coleccion->lastItem() ?? 0 }} de {{ $coleccion->total() }} registros
                    </div>
                    <div>
                        {{ $coleccion->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Reducir padding de las filas de la tabla */
    .table-compact tbody tr td,
    .table-compact thead tr th {
        padding: 0.5rem 0.75rem !important;
        vertical-align: middle;
    }
    
    .table-compact tbody tr {
        height: auto;
    }
    
    /* Estilos para el botón de eliminar */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Estilo para botones deshabilitados */
    .btn-secondary:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>

<script>
    function confirmarEliminarPlan(userId, planId, nombreUsuario, nombrePlan) {
        Swal.fire({
            customClass: {
                popup: 'swal-fondo-blanco'
            },
            title: '¿Estás seguro?',
            html: `
                <p>Vas a eliminar el plan de:</p>
                <p><strong>${nombreUsuario}</strong></p>
                <p>Plan: <strong>${nombrePlan}</strong></p>
                <p class="text-danger mt-2">Esta acción no se puede deshacer.</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Llamar al método de Livewire para eliminar
                @this.call('eliminarPlan', userId, planId);
            }
        });
    }
</script>
