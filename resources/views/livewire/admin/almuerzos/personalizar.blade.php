<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-block">
                <h4 class="card-title">Dias de la semana</h4>
                <p class="m-0 subtitle">Seleccione un dia y personalize los productos dentro de ella</p>
            </div>
            <div class="card-body">
                
               
                  @foreach ($almuerzos as $item)
                  <a href="#" wire:click="editar('{{$item->id}}')">
                    <div class="alert alert-primary @isset($seleccionado){{$item->id==$seleccionado->id?'solid':''}}@endisset alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                        <strong>{{$item->dia}}</strong> 
                       
                        </button>
                    </div>
                </a>
                  
                  @endforeach
                  
                  
                
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        @isset($seleccionado)
                      <div class="card">
                        <div class="card-header d-block">
                            <h4 class="card-title">Dia {{$seleccionado->dia}} seleccionado</h4>
                           
                        </div>
                          <div class="card-body">
                           
                                <div class="mb-3">
                                    <label class="form-label">Sopa</label>
                                    <input type="text" class="form-control input-default  @error('sopa') is-invalid @enderror" wire:model.lazy="sopa"  >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ensalada</label>
                                    <input type="text" class="form-control input-default  @error('ensalada') is-invalid @enderror" wire:model.lazy="ensalada">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ejecutivo</label>
                                    <input type="text" class="form-control input-default  @error('ejecutivo') is-invalid @enderror" wire:model.lazy="ejecutivo">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dieta</label>
                                    <input type="text" class="form-control input-default  @error('dieta') is-invalid @enderror"  wire:model.lazy="dieta"  >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Vegetariano</label>
                                    <input type="text" class="form-control input-default  @error('vegetariano') is-invalid @enderror"  wire:model.lazy="vegetariano" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Carbohidrato 1</label>
                                    <input type="text" class="form-control input-default  @error('carbohidrato_1') is-invalid @enderror"  wire:model.lazy="carbohidrato_1"  >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Carbohidrato 2</label>
                                    <input type="text" class="form-control input-default  @error('carbohidrato_2') is-invalid @enderror"  wire:model.lazy="carbohidrato_2" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Carbohidrato 3</label>
                                    <input type="text" class="form-control input-default  @error('carbohidrato_3') is-invalid @enderror"  wire:model.lazy="carbohidrato_3" >
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jugo/Mate</label>
                                    <input type="text" class="form-control input-default @error('jugo') is-invalid @enderror"  wire:model.lazy="jugo" placeholder="Jugo/Mate" >
                                </div>
                                <button wire:click="actualizar" class="btn btn-primary">Guardar</button>
                            
                          </div>
                       
                      </div>
                  @endisset
    </div>
</div>
