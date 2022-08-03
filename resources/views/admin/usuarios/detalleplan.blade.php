@extends('admin.master')
@section('content')
    @livewire('admin.modal-cambiar-usuario', ['usuario' => $usuario, 'plan' => $plan])
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
                                <input type="text" class="form-control form-control-sm" name="nombre"
                                    value="{{ $usuario->name }}" id="nombre" readonly>
                            </div>

                            <input type="hidden" class="form-control form-control-sm" name="idplan"
                                value="{{ $plan->id }}" id="idplan">
                            <input type="hidden" class="form-control form-control-sm" name="iduser"
                                value="{{ $usuario->id }}" id="iduser">

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Plan</label>
                                <input type="text" class="form-control form-control-sm" name="plan" placeholder="Plan"
                                    id="plan" value="{{ $plan->nombre }}" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Inicio</label>
                                <input type="date" class="form-control form-control-sm" name="start"
                                    placeholder="Fecha Inicio" id="start" readonly>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Fin</label>
                                <input type="date" class="form-control form-control-sm" name="end"
                                    placeholder="Fecha Final" id="end">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label>Personalizado</label>
                                <input type="number" class="form-control form-control-sm" name="dias"
                                    placeholder="Agregar mas dias" id="dias">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label></label>
                                <div class="form-check custom-checkbox mb-3 checkbox-success">
                                    <input type="checkbox" class="form-check-input" id="sabados" name="sabados">
                                    <label class="form-check-label" for="sabados">Excluir Sabados</label>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnGuardar">Confirmar</button>


                    <button type="button" class="btn btn-warning btn-xs" id="btnFeriadoNuevo">Marcar Feriado<span
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
                            <input type="text" class="form-control form-control-sm" name="title" id="title"
                                readonly>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label>Segundo</label>
                            <input type="text" class="form-control form-control-sm" id="segundo" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Carbohidrato</label>
                            <input type="text" class="form-control form-control-sm" id="carbo" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Tipo Envio</label>
                            <input type="text" class="form-control form-control-sm" id="envio" readonly>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label>Tipo Empaque</label>
                            <input type="text" class="form-control form-control-sm" id="empaque" readonly>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnEliminar">Borrar<span class="btn-icon-end"><i
                                class="fa fa-close"></i></span>
                    </button>
                    <button type="button" class="btn btn-info btn-sm" id="btnPermiso">Permiso<span
                            class="btn-icon-end"><i class="fa fa-calendar"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
