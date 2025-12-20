

<li>
    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
    {{$slot}}
        <span class="nav-text">{{$titulo}}</span>
    </a>
    <ul aria-expanded="false" class="mm-collapse">
        @foreach ($lista as $subtitulo=>$ruta)
        <li >
            @if(is_array($ruta))
                <a href="{{route($ruta['ruta'], $ruta['params'] ?? [])}}">{{$subtitulo}}</a>
            @else
                <a href="{{route($ruta)}}">{{$subtitulo}}</a>
            @endif
        </li>
        @endforeach
    </ul>
</li>