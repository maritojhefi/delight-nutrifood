

<li>
    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
    {{$slot}}
        <span class="nav-text">{{$titulo}}</span>
    </a>
    <ul aria-expanded="false" class="mm-collapse">
        @foreach ($lista as $subtitulo=>$ruta)
        <li ><a href="{{route($ruta)}}">{{$subtitulo}}</a></li>
        @endforeach
        
    </ul>
</li>