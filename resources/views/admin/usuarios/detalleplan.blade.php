@extends('admin.master')
@section('content')
    
    @livewire('admin.modal-cambiar-usuario', ['usuario' => $usuario,'plan'=>$plan])
    @env('local')
    @php
    $path = env('APP_URL');
    @endphp
    @endenv
    @production
        @php
        $path = 'https://delight-nutrifood.com';
        @endphp
    @endproduction
    @include('admin.usuarios.script')
    <div class="col-xl-12 col-xxl-12">
        <div class="card">
            <div class="card-body">
                <div id="calendar" class="app-fullcalendar"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalcalendar">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirme los dias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-cal">
                        <div class="row">
                            @csrf
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="{{ $usuario->name }}"
                                    id="nombre" readonly>
                            </div>

                            <input type="hidden" class="form-control" name="idplan" value="{{ $plan->id }}"
                                id="idplan">
                            <input type="hidden" class="form-control" name="iduser" value="{{ $usuario->id }}"
                                id="iduser">

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Plan</label>
                                <input type="text" class="form-control" name="plan" placeholder="Plan" id="plan"
                                    value="{{ $plan->nombre }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Inicio</label>
                                <input type="date" class="form-control" name="start" placeholder="Fecha Inicio" id="start"
                                    readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Fin</label>
                                <input type="date" class="form-control" name="end" placeholder="Fecha Final" id="end">
                            </div>

                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardar">Confirmar</button>


                    <button type="button" class="btn btn-warning btn-xs" id="btnFeriado">Marcar Feriado<span
                            class="btn-icon-end"><i class="fa fa-star"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de este evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formBasic">
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3 col-md-6">
                            <label>Evento</label>
                            <input type="text" class="form-control" name="title" id="title" readonly>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label>Segundo</label>
                            <input type="text" class="form-control" id="segundo">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Carbohidrato</label>
                            <input type="text" class="form-control" id="carbo">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Tipo Envio</label>
                            <input type="text" class="form-control" id="'envio'">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Tipo Empaque</label>
                           <input type="text" class="form-control" id="empaque">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnEliminar">Borrar<span class="btn-icon-end"><i
                                class="fa fa-close"></i></span>
                    </button>
                    <button type="button" class="btn btn-info btn-sm" id="btnPermiso">Permiso<span class="btn-icon-end"><i
                                class="fa fa-calendar"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
