<div>
    <div class="col-xl-8 col-lg-12 col-xxl-8 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Asistencia de Hoy</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Nombre</strong></th>
                                    <th><strong>Hora Entrada</strong></th>
                                  
                                    <th><strong>Hora Salida</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistencias as $item)
                                <tr>
                                    <td><span class="badge light badge-success">{{$item->user->name}}</span></td>
                                    <td>@isset($item->entrada)
                                    <span class="badge badge-warning ">{{date_format(date_create($item->entrada),"H:i")}}</span> 
                                    @endisset</td>
                                    @isset($item->salida)<td>
                                    <span class="badge badge-warning ">{{date_format(date_create($item->salida),"H:i")}}</span>
                                    @endisset</td>
                                    
                                   
                                </tr>
                                
                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
               
            </div>
        
        </div>
    </div>
</div>

