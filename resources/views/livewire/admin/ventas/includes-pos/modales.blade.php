<div wire:ignore.self class="modal fade" id="planesusuario">
    <div class="modal-dialog">
        <div class="modal-content">
            @isset($cuenta->cliente)
                <div class="modal-header">
                    <h5 class="modal-title col-5">Saldo : {{ $cuenta->cliente->saldo }} Bs </h5>
                    <a href="#" wire:click="verSaldo"
                        class="btn btn-xxs btn-warning col-5">{{ $verVistaSaldo ? 'Ver Planes' : 'Descontar Saldo' }}</a>
                    <button type="button" class="btn-close col-2" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    @if ($verVistaSaldo)
                        <div class="card m-0 ">
                            <div class="card-body">
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom01">Monto (Bs)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="number" class="form-control @error($montoSaldo) is-invalid @enderror"
                                            step="any" wire:model.lazy="montoSaldo"
                                            placeholder="Ingrese el monto que paga el cliente" required="">

                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom01">Metodo
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <select name="" id="" wire:model="tipoSaldo"
                                            class="form-control @error($tipoSaldo) is-invalid @enderror">
                                            <option value="">Seleccione uno</option>
                                            <option value="efectivo">Efectivo</option>
                                            <option value="tarjeta">Tarjeta</option>
                                            {{-- <option value="banco-sol">Banco Sol</option> --}}
                                            <option value="banco-bnb">Banco BNB</option>
                                            {{-- <option value="banco-mercantil">Banco Mercantil</option> --}}
                                        </select>

                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom02">Detalle <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control @error($detalleSaldo) is-invalid @enderror" cols="30" rows="10"
                                            wire:model.lazy="detalleSaldo"></textarea>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-success" wire:click="registrarSaldo">Registrar</button>
                                </div>
                            </div>
                            <h4>Registro de saldos:{{ $cuenta->cliente->saldos->count() }}</h4>
                            <ul class="list-group mt-3 " style="overflow-y: auto;max-height:300px;overflow-x: hidden">
                                @foreach ($cuenta->cliente->saldos->sortByDesc('created_at') as $saldo)
                                    @if ($saldo->es_deuda)
                                        <li class="list-group-item d-flex justify-content-between active">
                                            <div class="text-white">
                                                <h6 class="my-0 text-white">DEUDA POR COMPRA</h6>
                                                <small>{{ $saldo->created_at->format('d-M') }}<a href="#"
                                                        class="badge badge-warning badge-xs">{{ App\Helpers\WhatsappAPIHelper::timeago($saldo->created_at) }}</a></small>
                                            </div>
                                            <span class="text-white">{{ $saldo->monto }} Bs <a href="#"
                                                    wire:click="imprimirSaldo({{ $saldo->id }})"
                                                    class="badge badge-warning"><i class="fa fa-print"></i></a>
                                                @if ($saldo->anulado)
                                                    <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                        class="badge badge-danger"><i class="fa fa-close"></i>
                                                        Anulado</a>
                                                @else
                                                    <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                        class="badge badge-warning"><i class="fa fa-check"></i>
                                                        Activo</a>
                                                @endif
                                            </span>

                                        </li>
                                    @else
                                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                                            <div>
                                                <h6 class="my-0">PAGO A FAVOR DEL CLIENTE</h6>
                                                <small class="text-muted">{{ $saldo->created_at->format('d-M') }}
                                                    <a href="#"
                                                        class="badge badge-warning badge-xs">{{ App\Helpers\WhatsappAPIHelper::timeago($saldo->created_at) }}</a></small><br>
                                                <small class="text-muted">{{ Str::limit($saldo->detalle, 25) }}</small>
                                            </div>
                                            <span class="text-muted">{{ $saldo->monto }} Bs <a href="#"
                                                    wire:click="imprimirSaldo({{ $saldo->id }})"
                                                    class="badge badge-primary"><i class="fa fa-print"></i></a>
                                                @if ($saldo->anulado)
                                                    <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                        class="badge badge-danger"><i class="fa fa-close"></i>
                                                        Anulado</a>
                                                @else
                                                    <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                        class="badge badge-warning"><i class="fa fa-check"></i>
                                                        Activo</a>
                                                @endif
                                            </span>
                                        </li>
                                    @endif
                                @endforeach

                            </ul>
                            <ul class="list-group mt-3 ">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Saldo </span>
                                    <strong>{{ $cuenta->cliente->saldo }} Bs</strong>
                                </li>
                            </ul>



                        </div>
                    @else
                        @foreach ($cuenta->cliente->planes->groupBy('nombre') as $nombre => $item)
                            <div class="card m-0 ">
                                <div class="card-body px-4 py-3 py-lg-2">
                                    <div class="row align-items-center">
                                        <div class="col-xl-3 col-xxl-12 col-lg-12 my-2">
                                            <strong class="mb-0 fs-14">{{ Str::limit($nombre, 20, '') }}</strong>
                                            <a href="{{ route('detalleplan', [$cuenta->cliente->id, $item[0]->id]) }}"
                                                class="badge badge-info">Ir <i class="fa fa-arrow-right"></i></a>
                                        </div>
                                        <div class="col-xl-7 col-xxl-12 col-lg-12">
                                            <div class="row align-items-center">
                                                <div class="col-xl-4 col-md-4 col-sm-4 my-2">
                                                    <div class="media align-items-center style-2">

                                                        <div class="media-body ml-1">
                                                            <p class="mb-0 fs-12">Ultima fecha</p>
                                                            @php
                                                                $ultimaFecha = $item->sortBy('pivot.start')->last();
                                                            @endphp
                                                            <h5 class="mb-0   fs-22">
                                                                @if ($ultimaFecha)
                                                                    {{ date_format(date_create($ultimaFecha->pivot->start), 'd-M') }}
                                                                @else
                                                                    N/A
                                                                @endif

                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-4 col-sm-4 my-2">
                                                    <div class="media align-items-center style-2">
                                                        <span class="me-3 fa fa-shield text-warning">

                                                        </span>
                                                        <div class="media-body ml-1">
                                                            <p class="mb-0 fs-12">Permisos</p>
                                                            <h4 class="mb-0 font-w600  fs-22">
                                                                {{ $item->where('pivot.estado', 'permiso')->count() }}
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-md-4 col-sm-4 my-2">
                                                    <div class="media align-items-center style-2">
                                                        <span class="me-3 fa fa-check text-success">

                                                        </span>
                                                        <div class="media-body ml-1">
                                                            <p class="mb-0 fs-12">Restantes</p>
                                                            <h4 class="mb-0 font-w600 fs-22">
                                                                {{ $item->where('pivot.start', '>', date('Y-m-d'))->where('pivot.estado', 'pendiente')->count() }}
                                                                <svg class="ml-2" width="12" height="6"
                                                                    viewBox="0 0 12 6" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M0 6L6 2.62268e-07L12 6" fill="#13B497">
                                                                    </path>
                                                                </svg>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif



                </div>


            @endisset
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modalNuevoCliente">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Creando nuevo cliente <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <x-input-create-custom-function funcion="crearCliente" boton="Crear Cliente" :lista="[
                    'Nombre' => ['name', 'text'],
                    'Correo' => ['email', 'email'],
                    'Nacimiento' => ['cumpleano', 'date', '(opcional)'],
                    'Direccion' => ['direccion', 'text', '(opcional)'],
                    'Contraseña' => ['password', 'password'],
                ]">

                </x-input-create-custom-function>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self class="modal fade" id="modalClientes">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                Enlazar usuario a esta cuenta
                <span class="badge light badge-info" wire:loading wire:target='user'> Cargando...</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-3 col-md-6 mt-2">
                        <label for="">Buscar usuario</label>
                        <input type="text" class="form-control  form-control-sm" placeholder="Buscar Usuario"
                            wire:model.debounce.1000ms='user'>
                        <br>
                        @foreach ($usuarios as $item)
                            <a href="#" class=""
                                wire:click="cambiarClienteACuenta({{ $item->id }})"><small>{{ $item->name }}
                                </small><span class="badge light badge-primary"> <i class="fa fa-plus"></i></span></a>
                            <hr>
                        @endforeach
                    </div>

                    <div class="mb-3 col-md-6 mt-2">
                        <label for=""> Agregar Referencia</label>
                        <input type="text" class="form-control  form-control-sm"
                            placeholder="Agregar una referencia auxiliar" wire:model.lazy="userManual">
                        <button class="btn btn-success btn-xs" wire:click="addUsuarioManual">Confirmar</button>
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>
