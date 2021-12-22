<div class="row">
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">Ventas Abiertas</h4>

            <div class="">

                @foreach ($ventas as $item)
                <div
                    class="alert alert-{{$item->productos->count()>0?'success':'dark' }}@isset($cuenta) {{$item->id==$cuenta->id?'solid':''}}@endisset  alert-dismissible alert-alt fade show">
                    @if($item->productos->count()==0)
                    <button type="button" class="btn-close" wire:click="eliminar('{{ $item->id }}')">
                    </button>
                    @endif

                    <a href="#" wire:click="seleccionar('{{ $item->id }}')">
                        #{{$item->id}}@isset($item->cliente)<span
                            class="badge badge-xs light badge-dark">{{Str::limit($item->cliente->name, 10)}}</span>
                        @endisset <strong>{{$item->total}} Bs</strong>
                    </a>
                </div>
                @endforeach

                <!-- checkbox -->



                <div x-data="{ count: 0 }">
                    <div x-data="{ open: false, count:'abrir' }">
                        <button class="btn light btn-xs btn-outline-warning" @click="open = ! open, count='cerrar'">
                            <template x-if="open">
                                <div>CERRAR</div>
                            </template>
                            <template x-if="!open">
                                <div>ABRIR NUEVA CUENTA</div>
                            </template>
                        </button>

                        <div x-show="open">
                            <div class="row">
                                <div class="mb-3 col-md-6 mt-2">
                                    <label class="form-label">Sucursal</label>
                                    <select
                                        class="form-control form-control-sm  form-white @error($sucursal) is-invalid @enderror"
                                        wire:model.lazy="sucursal">
                                        <option>Elija 1</option>
                                        @foreach ($sucursales as $nombre=>$id)
                                        <option value="{{$id}}">{{$nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6 mt-2">
                                    <label class="form-label">Cliente</label>
                                    <input type="text" class="form-control  form-control-sm" placeholder="Opcional"
                                        wire:model.debounce.1000ms='user'>
                                </div>
                                <span class="badge light badge-info" wire:loading wire:target='user'> Cargando...
                                </span>
                                @foreach ($usuarios as $item)

                                <a href="#" class="m-2"
                                    wire:click="seleccionarcliente('{{ $item->id }}','{{$item->name}}')"><span
                                        class="badge light badge-primary"> {{$item->name}} <i
                                            class="fa fa-check"></i></span></a>
                                @endforeach
                            </div>
                            <button type="button" wire:click="crear" class="btn btn-primary btn-sm">Crear
                                Cuenta</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card-col4>
    @isset($cuenta)

    <x-card-col4>
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <small class="m-3 text-muted" wire:loading.remove>Venta #{{$cuenta->id}}</small> <br>
            <small class="m-3 text-muted" wire:loading>Actualizando...</small>
            @if($cuenta->cliente)
            <a href="#" data-bs-toggle="modal" data-bs-target="#planesusuario"><span
                    class="badge light badge-success">{{$cuenta->cliente->name}}</span></a>
            @else
            <span class="badge light badge-danger">Sin usuario</span>

            @endif


            <span class="badge badge-primary badge-pill">{{$itemsCuenta}}</span>
        </h4>



        <ul class="list-group mb-3" @isset($cuenta->cliente) @php $time = strtotime($cuenta->cliente->nacimiento);
            @endphp @if (date("m-d")==date('m-d', $time)) style="background-image:
            url('{{asset('images/cumple.gif')}}')"@endif @endisset>


            @foreach ($listacuenta as $item)
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <div class="row">
                        
                        <div class="col"><a href="#" wire:click="mostraradicionales('{{ $item['id'] }}')">
                                <h6 class="my-0"><small
                                        class="@isset($productoapuntado){{$item['nombre']==$productoapuntado->nombre?'text-success':''}} @endisset">{{$item['nombre']}}</small>
                                </h6>
                            </a></div>
                    </div>

                    <small class="text-muted">
                        <div class="row">
                            <div class="col">
                                <a href="#" wire:click="adicionar('{{ $item['id'] }}')"><span
                                        class="badge badge-xs light badge-success"><i class="fa fa-plus"></i></span></a>
                                <strong>{{$item['cantidad']}}</strong> {{$item['medicion']}}(s)
                                <a href="#" wire:click="eliminaruno('{{ $item['id'] }}')"> <span
                                        class="badge badge-xs light badge-danger"><i class="fa fa-minus"></i></span></a>
                                <a href="#" class="btn btn-danger shadow btn-xs sharp"
                                    wire:click="eliminarproducto('{{ $item['id'] }}')"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>

                    </small>
                    @isset($productoapuntado)
                    @if($productoapuntado->id==$item['id'])
                    @foreach ($array as $lista)
                    <ul>
                        <li> <small class="badge badge-xs badge-warning">{{$loop->iteration}}</small>
                            @foreach ($lista as $posicion=>$adic)

                            @foreach ($adic as $nombre=>$precio)
                            <small class="badge badge-xs light badge-warning">{{$nombre}} <label
                                    class="text-dark">{{$precio}}Bs</label></small>
                            @endforeach



                            @endforeach</li>
                             </ul>



                    @endforeach
                    <button class="btn btn-xxs btn-info light" data-bs-toggle="modal" data-bs-target="#modalObservacion" wire:click="cargarObservacion({{$productoapuntado->id}})">Observacion</button>
                            <button class="btn btn-xxs btn-accent light" data-bs-toggle="modal" data-bs-target="#modalEnviar">Enviar a cocina</button>
                   
                    @endif
                    @endisset

                </div>
                <div>
                    <span class=" row badge badge-secondary light">{{$item['subtotal']}} Bs</span>
                    <div x-data="{ open: false }">
                        <button @click="open = ! open" class="badge badge-xs light badge-info">Añadir</button>

                        <div x-show="open" @click.outside="open = false">
                            <div class="mb-3 col-md-2">
                                <input type="number" class="form-control" wire:model.lazy="cantidadespecifica"
                                    style="padding: 3px;height:30px;width:40px" value="{{$item['cantidad']}}">
                            </div>
                            <button class="btn btn-xxs light btn-warning"
                                wire:click="adicionarvarios('{{ $item['id'] }}')"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>

                </div>

            </li>
            @endforeach


            <li class="list-group-item d-flex justify-content-between">
                <small>Subtotal</small>
                <strong>{{$cuenta->total}} Bs</strong>

            </li>

            <li class="list-group-item d-flex justify-content-between">
                <small>Descuento</small>

                <div x-data="{ open: false }">
                    <button @click="open = ! open" class="badge badge-xs light badge-secondary">Editar</button>

                    <div x-show="open" @click.outside="open = false">
                        <div class="mb-3 col-md-2">
                            <input type="number" class="form-control" wire:model.lazy="descuento"
                                style="padding: 3px;height:30px;width:80px" value="{{$item['cantidad']}}">
                        </div>
                        <button class="btn btn-xxs btn-warning" wire:click="editardescuento"><i
                                class="fa fa-check"></i>Guardar</button>
                    </div>
                </div>
                <strong>{{$cuenta->descuento}} Bs</strong>
            </li>

            <li class="list-group-item d-flex justify-content-between">
                <span>Total a pagar</span>
                <strong>{{$cuenta->total-$cuenta->descuento}} Bs/{{$cuenta->puntos}} pts</strong>

            </li>
        </ul>

        @isset($productoapuntado)

        <label for="">Seleccione un item</label>
        <div class="input-group">
            @foreach ($productoapuntado->ventas->where('id',$cuenta->id) as $agregados)
            @for ($i = 1; $i <= $agregados->pivot->cantidad; $i++)
                <a href="#" wire:click="seleccionaritem('{{ $i }}')" data-bs-toggle="modal" data-bs-target="#modalAdicionales">
                    <i class="badge badge-rounded badge-outline-warning {{$itemseleccionado==$i?'badge-outline-dark':''}} m-2">{{ $i }}</i></a>
            @endfor
            @endforeach



                <div wire:ignore.self class="modal fade" id="modalAdicionales">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><small>Adicionales para</small> <span class="badge badge-primary">{{$productoapuntado->nombre}}({{$adicionales->count()}})</span> <span class="badge badge-secondary">Item #{{$itemseleccionado}}</span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">
                                @isset($itemseleccionado)

                                <div class="row">
                                    @foreach ($adicionales as $item)
                                    <div class="col-6">
                                   <a href="#" wire:click="agregaradicional('{{ $item->id }}', '{{ $itemseleccionado }}')"><i
                                    class="badge badge-rounded badge-outline-warning m-2">{{$item->nombre}} ({{$item->precio}} Bs)</i></a>
                                    </div>
                                    @endforeach
                                </div>
                                    
                                
                                @endisset
                            </div>
                           
                        </div>
                    </div>
                </div>
                <div wire:ignore.self class="modal fade" id="modalObservacion">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                              
                                <h5 class="modal-title"><small>Observaciones para</small> <span class="badge badge-primary">{{$productoapuntado->nombre}}</span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="modal-body">
                               <div class="form-group">
                                   
                                   <textarea id="my-textarea" wire:model.defer="observacion" class="form-control" name="" rows="5">{{$this->observacion}}</textarea>
                               </div> 
                            </div>
                           <div class="modal-footer">
                               <button class="btn btn-success btn-sm" wire:click="guardarObservacion({{$productoapuntado->id}})">Guardar</button>
                           </div>
                        </div>
                    </div>
                </div>
                <div wire:ignore.self class="modal fade" id="modalEnviar">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                              
                                <h5 class="modal-title"><small>Esta seguro de enviar a cocina?</small></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>
                            </div>
                            <div class="card-body">
                                <span class="badge badge-primary">{{$productoapuntado->nombre}}</span>
                                <br>
                                <h4>Detalle:</h4>
                                <ul>
                                    @foreach ($productoapuntado->ventas as $item)
                                    
                                    
                                    <li>{{$item->pivot->adicionales}}</li>
                                   
                                    <li>{{$item->pivot->observacion}}</li>
                                    @endforeach
                                </ul>
                                
                                
                            </div>
                           <div class="modal-footer">
                               <button class="btn btn-success btn-sm" wire:click="enviarCocina({{$productoapuntado->id}})">Enviar</button>
                           </div>
                        </div>
                    </div>
                </div>
        </div>
        @endisset
        @if ($cuenta->total!=0)
        <div class="row m-2">
            <button class="btn btn-xs light btn-warning" data-bs-toggle="modal" data-bs-target="#basicModal">Cobrar
                Cuenta</button>

        </div>
        <div wire:ignore.self class="modal fade" id="basicModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Esta seguro?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Subtotal</h6>

                                </div>
                                <span class="text-muted">{{$cuenta->total}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Descuento</h6>

                                </div>
                                <span class="text-muted">{{$cuenta->descuento}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Puntos</h6>
                                    @isset($cuenta->cliente)
                                    <small class="text-muted">Para :
                                        {{$cuenta->cliente->name}} </small>
                                    @endisset
                                </div>
                                <span class="text-muted">{{$cuenta->puntos}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between active">
                                <span>Total (BS)</span>
                                <strong>{{$cuenta->total-$cuenta->descuento}}</strong>
                            </li>
                        </ul>
                        <span class="badge light badge-warning">Tipo de pago: </span>
                        @isset($tipocobro)
                        <span class="badge light badge-success">{{$tipocobro}}</span>
                        @endisset

                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro"
                                    value="efectivo">
                                <label class="form-check-label">
                                    Efectivo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro"
                                    value="tarjeta">
                                <label class="form-check-label">
                                    Tarjeta
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro"
                                    value="banco-sol">
                                <label class="form-check-label">
                                    Banco Sol
                                </label>
                            </div>
                            <div class="form-check disabled">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro"
                                    value="banco-bisa">
                                <label class="form-check-label">
                                    Banco Bisa
                                </label>
                            </div>
                            <div class="form-check disabled">
                                <input class="form-check-input" type="radio" name="gridRadios" wire:model="tipocobro"
                                    value="banco-mercantil">
                                <label class="form-check-label">
                                    Banco Mercantil
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:loading.remove wire:target='imprimir' type="button" class="btn btn-warning btn-sm"
                            wire:click="imprimir"><span>Imprimir</span></button>
                        <button wire:loading wire:target='imprimir' type="button" disabled
                            class="btn btn-warning btn-sm" wire:click="imprimir"><span>Espere...</span></button>


                        <button type="button" class="btn btn-primary" wire:click="cobrar" {{$tipocobro?'':'disabled'}}
                            data-bs-dismiss="modal">Confirmar y cerrar venta</button>
                    </div>
                </div>
            </div>
        </div>
        @endif


    </x-card-col4>

    <x-card-col4>
        <div class="basic-list-group m-3">
            <ul class="list-group">
                <li class="list-group-item active"><input type="search" wire:model.debounce.750ms="search"
                        class="form-control" placeholder="Busca Productos"></li>
                @foreach ($productos as $item)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{asset($item->pathAttachment())}}" alt="" class="me-3 rounded" width="50">

                        </div>
                        <div class="col">
                            @php
                            $total=0;
                            @endphp
                            {{$item->nombre}}
                            @foreach ($item->sucursale->where('id',$cuenta->sucursale_id) as $relacion)
                            @isset($relacion)
                            @php
                            $total+=$relacion->pivot->cantidad;
                            @endphp
                            @endisset


                            @endforeach
                            <small>
                                @if ($item->contable==true)
                                @if ($total==0)
                                <span class="badge badge-danger mb-2">Agotado</span>
                                @else
                                <span class="badge badge-warning mb-2">Stock actual:{{$total}}</span>
                                @endif
                                @endif


                            </small>
                            @if ($item->puntos!=0 && $item->puntos !=null)
                            <span class="badge light badge-dark">{{$item->puntos}}pts</span>
                            @endif
                        </div>



                    </div>
                    <div class="row">
                        <div class="col">
                            @if ($item->descuento!=0)
                            <span class="badge badge-xs light badge-success">{{$item->descuento}} Bs</span>
                            <del class="badge badge-xs light badge-danger">{{$item->precio}} Bs</del>
                            @else
                            <span class="badge badge-xs light badge-warning">{{$item->precio}} Bs</span>
                            @endif
                            <button type="button" class="btn btn-rounded btn-primary btn-xxs pull-right"
                                {{$total==0 && $item->contable==true?'disabled':''}}
                                wire:click="adicionar('{{ $item->id }}')"><span
                                    class="btn-icon-start text-primary btn-xxs"><i class="fa fa-shopping-cart"></i>
                                </span>Añadir</button>

                        </div>

                    </div>


                </li>

                @endforeach
            </ul>
        </div>

    </x-card-col4>

    @endisset


    <!-- Modal -->
    <div class="modal fade" id="planesusuario">
        <div class="modal-dialog">
            <div class="modal-content">
                @isset($cuenta->cliente->planes)
                <div class="modal-header">
                    <h5 class="modal-title">Planes existentes ({{$cuenta->cliente->planes->count()}})</h5>
                    <a href="{{route('planes')}}" class="btn btn-xs btn-warning">Ir a todos los planes</a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">

                    @foreach ($cuenta->cliente->planes as $item)
                    <div class="card overflow-hidden bg-image-2 bg-secondary">
                        <div class="card-header  border-0">
                            <div>
                                <p class="mb-2 font-w100 text-white">Plan: {{$item->nombre}} - Expira :
                                    {{$item->pivot->dia_limite}}


                                    <h3 class="mb-0 fs-24 font-w600 text-white">Restante: {{$item->pivot->restante}}
                                    </h3> <button data-bs-dismiss="modal"
                                        wire:click="agregardesdeplan({{$item->pivot->user_id}},{{$item->pivot->plane_id}},{{$item->producto_id}})"
                                        class="btn light btn-xxs btn-success">Agregar al carro</button>
                            </div>
                        </div>
                    </div>

                    @endforeach


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Cerrar</button>

                </div>
                @endisset
            </div>
        </div>
    </div>

</div>
