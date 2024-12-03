<div wire:ignore.self class="modal fade" id="planesusuario">
    <div class="modal-dialog">
        <div class="modal-content">
            @isset($cuenta->cliente)
                <div class="modal-header">
                    <h5 class="modal-title col-4"><span
                            class="badge badge-xxs badge-{{ $cuenta->cliente->saldo > 0 ? 'warning' : 'primary' }}">
                            {{ Str::of($cuenta->cliente->name)->before(' ') }}{{ $cuenta->cliente->saldo > 0 ? ' debe:' : ' tiene a favor:' }}
                            {{ abs((int) $cuenta->cliente->saldo) }} Bs </span></h5>
                    <a href="#" wire:click="verSaldo" class="btn btn-xxs btn-outline-warning col-4"><i
                            class="flaticon-075-reload"></i> Cambiar cliente</a>
                    @if ($verVistaSaldo)
                        <a href="#" wire:click="verSaldo" class="btn btn-xxs btn-outline-info col-4"><i
                                class="fa fa-list"></i>
                            Ver sus planes</a>
                    @else
                        <a href="#" wire:click="verSaldo" class="btn btn-xxs btn-outline-secondary col-4"><i
                                class="flaticon-381-id-card"></i> Ver su billetera</a>
                    @endif
                   
                </div>
                <div class="modal-body pt-0">
                    @if ($verVistaSaldo)
                        <div class="card m-0 bordeado">
                            <center class="letra14">Anticipos/Pagos de deudas</center>
                            <div class="card-body letra12">

                                <div class="row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom01">Monto (Bs)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="number"
                                            class="form-control @error($montoSaldo) is-invalid @enderror bordeado"
                                            style="height: 30px" step="any" wire:model.lazy="montoSaldo"
                                            placeholder="Ingrese un monto" required="">

                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom01">Metodo
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <select name="" id="" wire:model="tipoSaldo"
                                            class="form-control @error($tipoSaldo) is-invalid @enderror bordeado"
                                            style="height: 30px">
                                            <option value="">Seleccione uno</option>
                                            @foreach ($metodosPagos as $metodo)
                                                <option value="{{ $metodo->id }}">{{ $metodo->nombre_metodo_pago }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom02">Detalle <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control @error($detalleSaldo) is-invalid @enderror bordeado" style="height: 30px" cols="30"
                                            rows="10" wire:model.lazy="detalleSaldo"></textarea>

                                    </div>
                                </div>
                                <button class="btn btn-success btn-xxs" wire:click="registrarSaldo">Registrar</button>
                            </div>

                            <center>Registro de saldos:{{ $cuenta->cliente->saldos->count() }}</center>
                            <ul class="list-group" style="overflow-y: auto;max-height:250px;overflow-x: hidden">
                                @foreach ($cuenta->cliente->saldos->sortByDesc('created_at') as $saldo)
                                    <li class="list-group-item d-flex justify-content-between lh-condensed letra12 p-1">

                                        <div class="">
                                            @if ($saldo->anulado)
                                                <del class="my-0 text-danger">
                                                    {{ $saldo->es_deuda ? 'DEUDA POR COMPRA' : 'PAGO A FAVOR DEL CLIENTE' }}
                                                    <i
                                                        class="flaticon-{{ $saldo->es_deuda ? '001-arrow-down text-warning' : '003-arrow-up text-info' }} "></i></del><br>
                                            @else
                                                <h6 class="my-0">
                                                    {{ $saldo->es_deuda ? 'DEUDA POR COMPRA' : 'PAGO A FAVOR DEL CLIENTE' }}
                                                    <i
                                                        class="flaticon-{{ $saldo->es_deuda ? '001-arrow-down text-warning' : '003-arrow-up text-info' }}"></i>
                                                </h6>
                                            @endif

                                            <small
                                                class="letra10 text-muted">{{ App\Helpers\GlobalHelper::fechaFormateada(4, $saldo->created_at->format('d-M')) }},
                                                {{ App\Helpers\WhatsappAPIHelper::timeago($saldo->created_at) }}</small><br>
                                            <small class="letra10">{{ Str::limit($saldo->detalle, 25) }}</small>
                                        </div>

                                        <strong class="letra14">
                                            @if ($saldo->anulado)
                                                <del class="text-danger me-2">{{ $saldo->monto }} Bs</del>
                                                <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                    class="badge badge-danger letra10"><i class="fa fa-ban"></i>
                                                    Anulado</a>
                                            @else
                                                {{ $saldo->monto }} Bs
                                                <a href="#" wire:click="imprimirSaldo({{ $saldo->id }})"
                                                    class="badge badge-outline-dark letra14"><i class="fa fa-print"></i></a>
                                                <a href="#" wire:click="anularSaldo({{ $saldo->id }})"
                                                    class="badge badge-success letra10"><i class="fa fa-check"></i>
                                                    Activo</a>
                                            @endif
                                        </strong>
                                    </li>
                                @endforeach

                            </ul>
                            <span
                                class="badge badge-xxs rounded-md badge-{{ $cuenta->cliente->saldo > 0 ? 'warning' : 'primary' }}">
                                {{ Str::of($cuenta->cliente->name)->before(' ') }}{{ $cuenta->cliente->saldo > 0 ? ' debe:' : ' tiene a favor:' }}
                                {{ abs((int) $cuenta->cliente->saldo) }} Bs </span>



                        </div>
                    @else
                        @if ($cuenta->cliente->planes->groupBy('nombre')->count() == 0)
                            <center>No hay planes activos para {{ Str::of($cuenta->cliente->name)->before(' ') }}</center>
                        @else
                            <center>Planes activos de {{ Str::of($cuenta->cliente->name)->before(' ') }} :
                                <strong>{{ $cuenta->cliente->planes->groupBy('nombre')->count() }}</strong>
                            </center>
                        @endif
                        @foreach ($cuenta->cliente->planes->groupBy('nombre') as $nombre => $item)
                            <div class="card m-0 bordeado">
                                <div class="card-body px-4 py-3 py-lg-2">
                                    <div class="row align-items-center">
                                        <div class="col-xl-3 col-xxl-12 col-lg-12 my-2">
                                            <strong class="mb-0 fs-14">{{ Str::limit($nombre, 30, '') }}</strong>
                                            <a href="{{ route('detalleplan', [$cuenta->cliente->id, $item[0]->id]) }}"
                                                target="_blank" class="badge badge-outline-dark">Ver <i
                                                    class="flaticon-083-share"></i></a>
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
    <div class="modal-dialog modal-sm">
        <div class="modal-content letra12">
            <div class="modal-header">
                Vincular cliente a esta cuenta
                <span class="badge light badge-info" wire:loading wire:target='user'> Cargando...</span>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <input type="text" class="form-control  form-control-sm bordeado" style="height:35px"
                            placeholder="Buscar Usuario" wire:model.debounce.1000ms='user'>
                        @isset($usuarios)
                            <span class="text-muted letra10">Clientes encontrados: {{ $usuarios->count() }}</span><br>
                        @endisset
                        @foreach ($usuarios as $item)
                            <a href="#" class="badge light badge-outline-primary bordeado mt-2"
                                wire:click="cambiarClienteACuenta({{ $item->id }})"><small>{{ $item->name }}
                                </small><span class=""> <i class="fa fa-plus"></i></span></a>
                        @endforeach
                    </div>
                    <center>ó</center>
                    <div class="col-12 mt-2">
                        <input type="text" class="form-control  form-control-sm bordeado" style="height:35px"
                            placeholder="Agrega una referencia" wire:model.lazy="userManual">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success btn-xs" wire:click="addUsuarioManual">Guardar</button>
            </div>
        </div>
    </div>
</div>
