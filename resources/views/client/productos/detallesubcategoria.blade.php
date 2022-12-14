@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="{{ $subcategoria->nombre }}" cabecera="bordeado" />
    
    <div class="card card-style">
        <div class="content">
            <h4>Todos los productos de esta categoria!</h4>
            <p>
                Encuentra lo que mas te gusta!
            </p>
            @foreach ($subcategoria->productos->where('estado','activo')->shuffle() as $item)
           <x-producto-list-component 
           :ruta="route('detalleproducto',$item->id)" 
           :foto="asset($item->pathAttachment())" 
           :nombre="$item->nombre()"
           :id="$item->id"
           :precio="$item->precio()" 
           />
            @endforeach
            
           
        </div>
    </div>
@endsection
