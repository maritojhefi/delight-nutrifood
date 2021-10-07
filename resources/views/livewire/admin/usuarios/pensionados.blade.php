<div class="row">
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">Pensionados</h4>
                    <input type="text" class="form-control  form-control-sm" placeholder="Busque un usuario" wire:model.debounce.1000ms='user'>
                                      
                    <span class="badge light badge-info" wire:loading wire:target='user'> Cargando... </span>
                       @foreach ($usuarios as $item)
                      <a href="#" wire:click="seleccionar('{{ $item['id'] }}')"><span class="badge light badge-primary mt-2" >{{$item->name}}</span></a> 
                       @endforeach               
        </div>
    </x-card-col4>
    @isset($seleccionado)
    <x-card-col4>
        <div class="card-body">
            <h4 class="card-intro-title">{{$seleccionado->name}}</h4>
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-xxl-6 mb-3">
                    <div class="media event-card p-3 rounded align-items-center">	
                       
                        <div class="media-body event-size">
                            <span class="fs-14 d-block mb-1 text-primary">Pensionado</span>
                         
                            <span class="fs-18 font-w500 event-size-1">{{$seleccionado->pensione?'SI':'NO'}}</span>
                        </div>
                    </div>
                </div>
                @isset($seleccionado->pensione)
                <div class="col-lg-4 col-sm-6 col-xxl-6 mb-3">
                    <div class="media event-card p-3 rounded align-items-center">	
                       
                        <div class="media-body event-size">
                            <span class="fs-14 d-block mb-1 text-primary">Dias restantes</span>
                            <span class="fs-18 font-w500 event-size-1 ">{{date_diff(Carbon\Carbon::today(), \Carbon\Carbon::parse($seleccionado->pensione->fecha_venc))->format('%R%a')}}</span>
                     
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 col-xxl-12">
                    <div class="media event-card p-3 rounded align-items-center">	
                       
                        <div class="media-body event-size">
                            <span class="fs-14 d-block mb-1 text-primary">Vencimiento</span>
                            <span class="fs-18 font-w500 event-size-1 ">{{$seleccionado->pensione->fecha_venc}}</span>
                        </div>
                       
                    </div>
                </div> 
                @endisset
                
            </div>
        </div>
    </x-card-col4>
    <x-card-col4>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="agregardias">

                    <div class="row">
                        <div class="mb-3 col-md-8">
                           
                            <input type="number" class="form-control  @error($dias) is-invalid @enderror" placeholder="Ingrese dias" wire:model.lazy="dias">
                        </div> 
                        <div class="mb-3 col-md-4">
                           
                            <button type="submit"  wire:click="agregardias" class="btn btn-primary light btn-sm">Agregar dias</button>
                        </div> 
                    </div>
                </form>
                <form wire:submit.prevent="agregarfecha">
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            
                            <input type="date" class="form-control @error($fecha) is-invalid @enderror"  wire:model.lazy="fecha" >
                        </div>
                        <div class="mb-3 col-md-4">
                           
                            <button wire:click="agregarfecha" type="submit" class="btn btn-info light btn-sm">Agregar fecha</button>
                        </div> 
                    </div>
                    
                </form>
               
            </div>
        </div>
    </x-card-col4>
    @endisset
</div>
