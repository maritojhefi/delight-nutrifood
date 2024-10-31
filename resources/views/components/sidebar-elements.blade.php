

<li class="p-0 m-0 ps-2">
    <a class="has-arrow ai-icon py-3" href="javascript:void()" aria-expanded="false">
    {{$slot}}
        <span class="nav-text">{{$titulo}}</span>
    </a>
    <ul aria-expanded="false" class="mm-collapse">
        @foreach ($lista as $subtitulo=>$ruta)
        <li class="p-0 m-0 ps-2"><a class="py-2" href="{{route($ruta)}}">{{$subtitulo}}</a></li>
        @endforeach
        
    </ul>
</li>