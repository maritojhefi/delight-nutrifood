<div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header row">
                <div class="col">
                    <h4 class="card-title">Productos por expirar</h4>
                </div>
                <div class="col-2"><div  class="d-flex justify-content-center">
                    <div wire:loading class="spinner-border" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                  </div></div>
                <div class="col"> <input type="text" class="form-control form-control-sm" placeholder="Buscar producto"
                        wire:model.debounce.750ms="search"></div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>


                                <th><strong>Nombre</strong></th>
                                <th><strong>Restantes/lote</strong></th>
                                <th><strong>Estado</strong></th>
                                
                                <th><strong>Fecha Vencimiento</strong></th>
                               

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $item)
                                <tr>

                                    <td>
                                        <div class="d-flex align-items-center"><strong>{{ $item->nombre }}</strong>
                                        </div>
                                    </td>

                                    <td>{{ $item->cantidad }}</td>
                                    <td><span class="w-space-no">
                                            @if (Carbon\Carbon::now()->startOfDay()->gte($item->fecha_venc) == true)
                                                <span class="badge badge-danger">expirado (Hace {{Carbon\Carbon::parse($item->fecha_venc)->diffInDays()}} dias)</span>
                                            @else
                                                <span class="badge badge-info">vigente (Quedan {{Carbon\Carbon::parse($item->fecha_venc)->diffInDays()}} dias)</span>
                                            @endif


                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{date_format(date_create($item->fecha_venc),'d-M-y')}}</span>
                                    </td>



                                   
                                </tr>

                                
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row  mx-auto">

            </div>
            <div class="row  mx-auto">

            </div>
        </div>
    </div>
</div>
