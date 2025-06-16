<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="profile card card-body px-3 pt-3 pb-0">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo"></div>
                    </div>
                    <div class="profile-info">
                        <div class="profile-photo">
                            <img src="{{asset('user.png')}}" class="img-fluid rounded-circle" alt="">
                        </div>
                        <div class="profile-details">
                            <div class="profile-name px-3 pt-2">
                                <h4 class="text-primary mb-0">{{auth()->user()->name}}</h4>
                                <p>{{auth()->user()->role->nombre}}</p>
                            </div>
                            <div class="profile-email px-2 pt-2">
                                <h4 class="text-muted mb-0">{{auth()->user()->email}}</h4>
                                <p>Email</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-statistics">
                                <div class="text-center">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="m-b-0">150</h3><span>Productos comprados</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0">140</h3><span>Puntos</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0">45</h3><span>Me encanta</span>
                                        </div>
                                    </div>
                                    
                                </div>
                               
                              
                            </div>
                        </div>
                    </div>
                </div>
                
             
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="#my-posts" data-bs-toggle="tab" class="nav-link active show">Comprados</a>
                                </li>
                                <li class="nav-item"><a href="#about-me" data-bs-toggle="tab" class="nav-link">Informacion Personal</a>
                                </li>
                                <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link">Me encanta</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="my-posts" class="tab-pane fade active show">
                                    <div class="profile-interest mt-3">
                                        <h5 class="text-primary d-inline">Ultimos registros</h5>
                                        <div class="row mt-4 sp4" id="lightgallery">
                                            <a href="{{asset(GlobalHelper::getValorAtributoSetting('logo'))}}" data-exthumbimage="{{asset(GlobalHelper::getValorAtributoSetting('logo'))}}" data-src="images/profile/2.jpg" class="mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                                <img src="{{asset(GlobalHelper::getValorAtributoSetting('logo'))}}" alt="" class="img-fluid">
                                            </a>
                                           
                                        </div>
                                    </div>
                                </div>
                                <div id="about-me" class="tab-pane fade">
                                    
                                    
                                    <div class="profile-personal-info mt-3">
                                        <h4 class="text-primary mb-4">Informacion Personal</h4>
                                        <div class="row mb-2">
                                            <div class="col-sm-3 col-5">
                                                <h5 class="f-w-500">Nombre <span class="pull-end">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9 col-7"><span>{{auth()->user()->name}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-3 col-5">
                                                <h5 class="f-w-500">Email <span class="pull-end">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9 col-7"><span>{{auth()->user()->email}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-3 col-5">
                                                <h5 class="f-w-500">Rol <span class="pull-end">:</span></h5>
                                            </div>
                                            <div class="col-sm-9 col-7"><span>{{auth()->user()->role->nombre}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-3 col-5">
                                                <h5 class="f-w-500">Fecha Nacimiento <span class="pull-end">:</span>
                                                </h5>
                                            </div>
                                            <div class="col-sm-9 col-7"><span>{{auth()->user()->nacimiento}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-3 col-5">
                                                <h5 class="f-w-500">Telefono <span class="pull-end">:</span></h5>
                                            </div>
                                            <div class="col-sm-9 col-7"><span>{{auth()->user()->telefono}}</span>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-3 col-5">
                                                <h5 class="f-w-500">Direccion<span class="pull-end">:</span></h5>
                                            </div>
                                            <div class="col-sm-9 col-7"><span>{{auth()->user()->direccion}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="profile-settings" class="tab-pane fade">
                                   
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
