<div class="row">
   <div class="col-xl-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Seleccione Sucursal</h4>
        </div>
        <div class="card-body">
           <select name="" wire:model="sucursal" class="form-control" id="">
            <option value="">Elija una opcion</option>

               @foreach ($sucursales as $item)
                   <option value="{{$item->id}}">{{$item->nombre}}</option>
               @endforeach
           </select>
        </div>
    </div>
   </div>
   @isset($sucursal)
   <div class="col-xl-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Seleccione Producto</h4>
        </div>
        <div class="card-body">
            <div class="basic-list-group">
                <ul class="list-group">
                    <li class="list-group-item active"><input type="search" wire:model.debounce.750ms="search" class="form-control" placeholder="Busca Productos"></li>
                    @foreach ($productos as $item)
                    <li class="list-group-item">{{$item->nombre}}<span class="badge light badge-success">{{$item->precio}} Bs</span><a href="#" wire:click="seleccionar('{{ $item->id }}')"><span class="badge badge-success"><span class="ms-1 fa fa-check"></span></span></a></li>
                    @endforeach
                   
                   
                </ul>
            </div>
        </div>
    </div>
</div> 
   @endisset
   @isset($prodlisto)
   <div class="col-xl-4">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Llene los campos</h4>
        </div>
        <div class="card-body">
            <div class="basic-list-group">
               <x-input-create  :lista="([
                'Cantidad'=>['cantidad','number'],
                'Fecha de vencimiento'=>['fecha_venc','date'],
                  ])">
               </x-input-create>
            </div>
        </div>
    </div>
</div> 
   @endisset
</div>
