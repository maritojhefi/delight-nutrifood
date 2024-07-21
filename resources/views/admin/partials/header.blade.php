<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between row">
                <div class="col-8 ">
                    <div class="d-none d-sm-none d-md-block">
                        <div class="row ">
                            <div class="col-3">
                                @livewire('admin.egresos-component')
                            </div>
                            <div class="col-3">
                                <a href="{{ route('producto.listar') }}"
                                    class="btn  btn-sm light btn-outline-secondary">Productos <i
                                        class="fa fa-link"></i></a>
                            </div>
                            <div class="col-3">
                                <a href="{{ route('almuerzos.reporte') }}"
                                    class="btn  btn-sm  btn-outline-info">Almuerzos <i class="fa fa-list"></i></a>
                            </div>
                            <div class="col-3">
                                <a href="{{ route('usuario.listar') }}"
                                    class="btn  btn-sm  btn-outline-warning">Usuarios <i class="fa fa-user"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                <ul class="navbar-nav header-right col-4">
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                            <div class="header-info me-3">
                                <span class="fs-16 font-w600 ">{{ auth()->user()->name }}</span>
                                <small class="text-end fs-14 font-w400">{{ auth()->user()->role->nombre }}</small>
                            </div>
                            @if (auth()->user()->foto)
                                <img src="{{ asset('imagenes/perfil/' . auth()->user()->foto) }}" width="20">
                            @else
                                <img src="{{ asset('user.png') }}" width="20">
                            @endif

                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ route('usuario.perfil') }}" class="dropdown-item ai-icon">
                                <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                    width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span class="ms-2">Perfil </span>
                            </a>
                            @if (auth()->user()->role->nombre == 'admin' || auth()->user()->role->nombre == 'cajero')
                                <a href="{{ route('caja.diaria') }}" class="dropdown-item ai-icon">
                                    <i class="la la-dollar"></i> <span class="ms-2">Caja Diaria</span>
                                </a>
                            @endif
                            <a href="{{ route('miperfil') }}" class="dropdown-item ai-icon">
                                <i class="fa fa-user"></i> <span class="ms-2">Ir a la tienda</span>
                            </a>

                            <a href="{{ route('logout') }}" class="dropdown-item ai-icon"
                                onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                    width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span class="ms-2">Cerrar Sesion </span>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
