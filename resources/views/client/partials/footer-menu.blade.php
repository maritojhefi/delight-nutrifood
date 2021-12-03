

    <div id="footer-bar" class="footer-bar-5">
        <a href="{{route('miperfil')}}" class="{{(request () -> is ('miperfil'.'*'))? 'active-nav': ''}} ">
            <i data-feather="heart" data-feather-line="1" data-feather-size="21" data-feather-color="red-dark" data-feather-bg="red-fade-light">
            </i><span>Mi Perfil</span>
        </a>
        <a href="{{route('promociones')}}" class="{{(request () -> is ('promociones'.'*'))? 'active-nav': ''}} ">
            <i data-feather="plus" data-feather-line="1" data-feather-size="21" data-feather-color="green-dark" data-feather-bg="green-fade-light"></i>
            <span>Promociones</span>
        </a>
        <a href="{{route('inicio')}}" class="{{(request () -> is ('/'))? 'active-nav': ''}} ">
            <i data-feather="home" data-feather-line="1" data-feather-size="21" data-feather-color="blue-dark" data-feather-bg="blue-fade-light"></i>
            <span>Inicio</span>
        </a>
        <a href="{{route('productos')}}" class="{{(request () -> is ('productos'.'*'))? 'active-nav': ''}} ">
            <i data-feather="file" data-feather-line="1" data-feather-size="21" data-feather-color="brown-dark" data-feather-bg="brown-fade-light"></i>
            <span>Productos</span>
        </a>
      <!--  <a href="{{route('ajustes')}}" class="{{(request () -> is ('ajustes'.'*'))? 'active-nav': ''}} ">
            <i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="dark-dark" data-feather-bg="gray-fade-light"></i>
            <span>Ajustes</span>
        </a> -->
    </div>