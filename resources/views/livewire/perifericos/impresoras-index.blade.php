<div class="row">
    @empty($sucursalSeleccionada)
    <x-card-col4>
        <div class="card-header">
            <h4 class="card-title">Seleccione Sucursal</h4>
            <span wire:loading wire:target='seleccionarSucursal' class="badge badge-primary">Cargando...</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
               @foreach ($sucursales as $item)
              <a href="#" wire:click="seleccionarSucursal({{$item->id}})"><span class="badge badge-info light">{{$item->nombre}}</span></a> 
               @endforeach
            </div>
        </div>
    </x-card-col4>
    @endempty
    

    @isset($sucursalSeleccionada)
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Listado de impresoras 
                    <span class="badge badge-success light">{{$sucursalSeleccionada->nombre}}<span class="badge badge-danger badge-xs m-1"><a href="#" class="text-white" wire:click="resetSucursal"> X </a></span></span>  
                    
                    <span class="badge badge-info ms-auto">Estado actual: {{$sucursalSeleccionada->id_impresora==null?'Sin impresora':'Con impresora'}}</span>  
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-info">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Impresora</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            @foreach ($impresoras as $item)
                            <tr class="{{$sucursalSeleccionada->id_impresora==$item['idprinter']?'table-success':''}}">
                                <td>{{$item['idprinter']}}</td>
                                <td>{{$item['nombre']}}</td>
                                <td><span class="badge badge-{{$item['status']=='offline'?'danger':'success'}}">{{$item['status']}}</span></td>
                                @if ($sucursalSeleccionada->id_impresora==$item['idprinter'])
                                <td><button class="btn btn-xs btn-success" disabled>Seleccionada</button></td>
                                @else
                                <td><button class="btn btn-xs btn-warning" wire:click="guardarImpresora('{{$item['idprinter']}}')">Activar</button></td>

                                @endif
                            </tr>
                                
                            @endforeach
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endisset
</div>
