
   
<form wire:submit.prevent="submit">
    @foreach ($lista as $titulo=>$variables)
    


      @if ($variables[1]=='text' || $variables[1]=='number' || $variables[1]=='password' || $variables[1]=='email' || $variables[1]=='date')
                                           
        @if ($variables[0]=='precio' || $variables[0]=='Precio' )
        <div class="mb-3 row">
          <label class="col-sm-4 col-form-label">{{$titulo}}</label>
          <div class="col-sm-8">
            <div class=" input-group" >
          <input type="{{$variables[1]}}"  wire:model.defer="{{$variables[0]}}" placeholder="@isset($variables[2]){{$variables[2]}} @endisset" min="0" class=" form-control  @error($variables[0]) is-invalid @enderror">
          <span class="input-group-text">Bs</span>
        </div>
          @error($variables[0]) <span class="error">{{ $message }}</span> @enderror
          </div>
        </div>
     
        @else    
        <div class="mb-3 row">
          <label class="col-sm-4 col-form-label">{{$titulo}}</label>
          <div class="col-sm-8">
          <input type="{{$variables[1]}}" wire:model.defer="{{$variables[0]}}" placeholder="@isset($variables[2]){{$variables[2]}} @endisset" class=" form-control  @error($variables[0]) is-invalid @enderror">
          @error($variables[0]) <span class="error">{{ $message }}</span> @enderror
          </div>
        </div>
        @endif                              
     

      @if ($variables[1]=='password')
      <div class="mb-3 row">
        <label class="col-sm-4 col-form-label">Confirmar</label>
        <div class="col-sm-8">
        <input type="password" wire:model.defer="{{$variables[0]}}_confirmation" class=" form-control @error($variables[0]) is-invalid @enderror" placeholder="Confirmar">
        </div>
      </div>
      
      @endif
      @elseif($variables[1]=='file')
      <div class="row mb-3 row">
        <div class="col-6 col-sm-6">
        
            <label for="{{$titulo}}" class="btn btn-danger btn-sm btn-rounded" >Subir Foto</label>
            <div class="col-sm-8">
            <input id="{{$titulo}}" type="file" wire:model="{{$variables[0]}}"  class=" form-control @error($variables[0]) is-invalid @enderror" style="display:none">
            </div>
        </div>
        <div class="col-6 col-sm-6">
            <div class="avatar avatar-xl position-relative mt-3 border  @error($variables[0]) border-danger @enderror">
            {{$slot}}
            </div>
        </div>
        <div wire:loading wire:target="{{$variables[0]}}"  class="mb-3 row">
        Cargando...
        </div>
        @error($variables[0])<span class="error">{{ $message }}</span> @enderror
      </div>
      
    
   @elseif($variables[1]=='textarea')
   
   

      <div class="mb-3 row">
        <label class="col-sm-4 col-form-label">{{$titulo}}</label>
        <div class="col-sm-8">
        <textarea type="text" wire:model.defer="{{$variables[0]}}" placeholder="@isset($variables[2]){{$variables[2]}} @endisset" class=" form-control @error($variables[0]) is-invalid @enderror"></textarea>
        @error($variables[0]) <span class="error">{{ $message }}</span> @enderror
        </div>
      </div>
      
      @endif
    @endforeach
   
    @isset($otrosinputs)
  
        {{$otrosinputs}}
   
    @endisset
    <div class="mb-3 row">
      <div class="col-sm-10">
          <button wire:click="submit" data-bs-dismiss="modal" class="btn btn-primary">Guardar</button>
      </div>
  </div>
</form>

 
