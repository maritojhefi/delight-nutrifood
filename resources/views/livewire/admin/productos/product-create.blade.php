
    <div class="col-xl-6 col-lg-12 col-xxl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Creando nuevo producto</h4>
            </div>
            <div class="card-body">
                <div class="">
                    <x-input-create  :lista="([
                        'Nombre'=>['nombre','text'],
                        'Precio'=>['precio','number'],
                        'Detalle'=>['detalle','textarea'],
                        'Imagen'=>['imagen','file'],
                        'Descuento'=>['descuento','number'],
                          ])" >
                                   
                          @if ($imagen)
                          <img src="{{ $imagen->temporaryUrl() }}" class="w-100 border-radius-lg shadow-sm">
                         @endif
                        <x-slot name="otrosinputs">
                            
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Categoria</label>
                                <div class="col-sm-9">
                                    <select  wire:model="cat"  class="form-control @error($cat)is-invalid @enderror">
                                        <option  class="dropdown-item" aria-labelledby="dropdownMenuButton" >Seleccione una opcion</option>
                                        @foreach ($subcategorias as $cat)
                                        <option value="{{$cat->id}}" class="dropdown-item" aria-labelledby="dropdownMenuButton" >{{$cat->nombre}}</option>
                                        
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                        </x-slot>
                    </x-create-form>
                   
                </div>
            </div>
        </div>
    </div>
