<x-card-col tamano="3">
    <div class="mb-2">
        <center class="card-intro-title p-2 " style="font-size:15px">Ventas Pendientes</center>
        <div class="">
            @foreach ($ventas as $item)
                <div class="alert {{ $item->productos->count() > 0 ? 'border-success alert-success' : 'alert-danger border-danger' }}@isset($cuenta) {{ $item->id == $cuenta->id ? 'solid alert-alt bordeado-pulse' : '' }} @endisset p-1 px-2 pb-2"
                    style="line-height: 0px">
                    <a href="#" class="p-0 m-0" style="line-height: 20px"
                        wire:click="seleccionar('{{ $item->id }}')">
                        <div wire:loading wire:target="seleccionar({{ $item->id }})"
                            class="spinner-grow spinner-grow-sm" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <small class="letra10"> #{{ $item->id }}</small>
                        <strong
                            class="@isset($cuenta) {{ $item->id == $cuenta->id ? 'text-white' : '' }} @endisset ">{{ $item->total }}Bs
                        </strong>
                        @if ($item->productos->count() == 0)
                            <a href="#" class="float-end badge badge-danger badge-pill py-1 px-2"
                                wire:click="eliminar('{{ $item->id }}')">
                                <i class="fa fa-trash"></i>
                            </a>
                        @endif
                        @if ($item->pagado)
                            <span class="badge badge-dark badge-sm popover-container p-1">Pagado <span
                                    class="popover-text">Esta venta se encuentra pagada</span></span>
                        @endif
                        @if ($item->cocina && !$item->despachado_cocina)
                            <span class="badge badge-info badge-sm popover-container py-1"><i class="fa fa-send"></i>
                                <span class="popover-text">Enviado a cocina</span></span>
                        @elseif($item->despachado_cocina)
                            <span class="badge badge-success badge-sm popover-container"><i
                                    class="fa fa-thumbs-up"></i>
                                <span class="popover-text">Venta despachada desde cocina</span></span>
                        @endif
                        @isset($item->usuario_manual)
                            <br>
                            <strong class="p-0 m-0"
                                style="font-size:13px;line-height: 10px">{{ Str::limit($item->usuario_manual, 35) }}</strong>
                        @endisset

                        @isset($item->cliente)
                            <br>
                            <strong class="p-0 m-0"
                                style="font-size:13px;line-height: 10px">{{ Str::limit($item->cliente->name, 35) }}</strong>
                        @endisset
                    </a>

                </div>
            @endforeach

            <!-- checkbox -->



            <div x-data="{ count: 0 }">
                <div x-data="{ open: false, count: 'abrir' }">
                    <button class="btn light btn-xs btn-outline-warning p-1 px-2 m-0"
                        @click="open = ! open, count='cerrar'">
                        <template x-if="open">
                            <div>CERRAR</div>
                        </template>
                        <template x-if="!open">
                            <div>ABRIR NUEVA VENTA</div>
                        </template>
                    </button>

                    <div x-show="open" @click.outside="open = false">
                        <div class="row">
                            {{-- <div class="mb-3 col-md-6 mt-2">
                                <label class="form-label">Sucursal</label>
                                <select
                                    class="form-control form-control-sm  form-white @error($sucursal) is-invalid @enderror"
                                    wire:model="sucursal">

                                    @foreach ($sucursales as $nombre => $id)
                                        <option value="{{ $id }}">{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="mb-3 col-md-12 mt-2" style="font-size: 12px">
                                <label class="form-label">Buscar cliente</label><button data-bs-toggle="modal"
                                    data-bs-target="#modalNuevoCliente"
                                    class="badge badge-xs light badge-success float-end">Añadir <i
                                        class="fa fa-plus"></i></button>
                                <input type="text" class="form-control  form-control-sm"
                                    style="border-style: solid;border-color:rgb(14, 178, 79);
                        border-width: 1px;border-radius:15px; display: inline-block;height:20px"
                                    placeholder="Buscar cliente (Opcional)" wire:model.debounce.1000ms='user'>

                                <span class="badge light badge-info float-center" wire:loading
                                    wire:target='user'>Cargando...
                                </span>
                            </div>

                            @foreach ($usuarios as $item)
                                <a href="#" class="m-1 letra12 "
                                    wire:click="seleccionarcliente('{{ $item->id }}','{{ $item->name }}')">
                                    @if ($item->id == $cliente)
                                        <strong class="badge  badge-outline-success py-0">{{ $item->name }} <i
                                                class="fa fa-check"></i>
                                        </strong>
                                    @else
                                        <span class="badge light badge-dark"><small>{{ $item->name . ' ' }}</small>
                                            <i class="fa fa-user-plus"></i>
                                        </span>
                                    @endif

                                </a>
                            @endforeach
                        </div>
                        <button type="button" wire:click="crear" class="btn btn-primary btn-block btn-sm m-0 p-1 mt-2">Abrir
                            Venta</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-card-col>