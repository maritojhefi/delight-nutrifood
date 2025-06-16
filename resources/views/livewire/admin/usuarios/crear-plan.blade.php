<div class="row">
    <div class="col-xxl-4 col-xl-4 col-lg-12  col-sm-12">
        <div class="row">
            <div class="card bordeado">

                <div class="card-body">
                    @empty($productoseleccionado)
                        <label for="" class="">Busca un producto</label>
                        <input type="text" wire:model.debounce.750ms="producto"
                            class="form-control form-control-sm p-1 bordeado mb-2" style="height: 50px">
                        @foreach ($productos as $item)
                            <a href="#" wire:click="seleccionarproducto('{{ $item->id }}')"><span
                                    class="badge  badge-outline-info m-2">{{ $item->nombre }} <i
                                        class="flaticon-013-checkmark"></i></span></a>
                        @endforeach
                    @endempty

                    @isset($productoseleccionado)
                        <div class="">
                            <span
                                class="badge light badge-lg m-3 badge-danger">{{ $productoseleccionado->nombre }}({{ $productoseleccionado->precio }}
                                Bs)<button type="button" class="btn-close" wire:click="resetproducto()"></button></span>
                            <div class="card-header">
                                <h4 class="card-title">Nuevo Plan</h4>
                            </div>
                            <x-input-create :lista="[
                                'Nombre' => ['nombre', 'text'],
                            
                                'Detalle' => ['detalle', 'text'],
                            ]">
                            </x-input-create>

                        </div>
                    @endisset


                </div>
            </div>

        </div>
    </div>

    <div class=" col-xxl-8 col-xl-8 col-lg-12 col-sm-12">
        <div class="card overflow-hidden bordeado">
            <div class="card p-0">
                <div class="card-header  m-0">
                    <h4 class="card-title">Planes Registrados</h4>
                </div>
                <div class="card-body letra12 m-0 p-0">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Añadidos</strong></th>
                                    <th><strong>Editable</strong></th>
                                    <th><strong>Asignacion Auto</strong></th>
                                    <th><strong>Detalle</strong></th>


                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($planes as $item)
                                    <tr>
                                        <td><small class="text-info fw-bold">{{ $item->nombre }}</small></td>
                                        <td><a href="#" wire:click="seleccionarPlan({{ $item->id }})"
                                                data-bs-toggle="modal" data-bs-target="#modalAnadidos"
                                                class=" badge badge-sm badge-primary"><span class="fa fa-eye"></span>
                                                Ver</a></td>
                                        <td><a href="#" wire:click="cambiarEditable({{ $item->id }})"><span
                                                    class="badge badge-pill badge-{{ $item->editable == true ? 'success' : 'danger' }} light">{{ $item->editable == true ? 'SI' : 'NO' }}</span></a>
                                        </td>
                                        <td><a href="#" wire:click="cambiarAsignacion({{ $item->id }})"><span
                                                    class="badge badge-pill badge-{{ $item->asignado_automatico == true ? 'success' : 'danger' }} light">{{ $item->asignado_automatico == true ? 'SI' : 'NO' }}</span></a>
                                        </td>
                                        <td>{{ $item->detalle }}</td>
                                        {{-- <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-danger light sharp"
                                                    data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24"
                                                        version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" cx="5" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="12" cy="12" r="2">
                                                            </circle>
                                                            <circle fill="#000000" cx="19" cy="12" r="2">
                                                            </circle>
                                                        </g>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item">Editar</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#modaldelete{{ $item->id }}">Eliminar</a>
                                                </div>
                                            </div>
                                        </td> --}}
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">{{ $planes->links() }}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{ $planes->count() }} de {{ $planes->total() }} registros</div>
                </div>
            </div>

        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalAnadidos" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">

            @isset($planSeleccionado)
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Habilita/deshabilita caracteristicas para: {{ $planSeleccionado->nombre }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <div wire:loading class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="basic-list-group" wire:loading.remove>
                            <ul class="list-group">
                                <a href="#" wire:click="cambiarSopa()">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Sopa <span
                                            class="badge badge-{{ $planSeleccionado->sopa ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->sopa ? 'SI' : 'NO' }}</span>
                                    </li>
                                </a>
                                <a href="#" wire:click="cambiarSegundo()">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Segundo <span
                                            class="badge badge-{{ $planSeleccionado->segundo ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->segundo ? 'SI' : 'NO' }}</span>
                                    </li>
                                </a>
                                <a href="#" wire:click="cambiarCarbohidrato()">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Carbohidrato <span
                                            class="badge badge-{{ $planSeleccionado->carbohidrato ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->carbohidrato ? 'SI' : 'NO' }}</span>
                                    </li>
                                </a>
                                <a href="#" wire:click="cambiarEnsalada()">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Ensalada <span
                                            class="badge badge-{{ $planSeleccionado->ensalada ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->ensalada ? 'SI' : 'NO' }}</span>
                                    </li>
                                </a>
                                <a href="#" wire:click="cambiarJugo()">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Jugo <span
                                            class="badge badge-{{ $planSeleccionado->jugo ? 'primary' : 'danger' }} badge-pill">{{ $planSeleccionado->jugo ? 'SI' : 'NO' }}</span>
                                    </li>
                                </a>




                            </ul>
                        </div>
                    </div>

                </div>
            @endisset

        </div>
    </div>
</div>
