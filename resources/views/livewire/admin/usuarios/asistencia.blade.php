<div>
    <div class="col-xl-4 col-lg-12 col-xxl-7 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Listado de Roles</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Descripcion</strong></th>
                                  
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistencias as $item)
                                <tr>
                                    <td><span class="badge light badge-success">{{$item->user->name}}</span></td>
                                    <td>{{$item->entrada}}</td>
                                   
                                    
                                   
                                </tr>
                                
                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">{{$roles->links()}}</div>
                </div>
                <div class="row  mx-auto">
                    <div class="col">Mostrando {{$roles->count()}} de {{$roles->total()}} registros</div>
                </div>
            </div>
        
        </div>
    </div>
</div>
