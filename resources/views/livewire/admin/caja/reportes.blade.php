<div class="row">
    @empty($cajaactiva)
    <div class="col-xl-4 col-lg-12 col-xxl-6 col-sm-12">
        <div class="row" style="margin: 0px">
            <div class="card">
                <div class="card">
                    <div class="card-header">
                        Listado de cajas
                    </div>
                    <div class="card-body">
                        @foreach ($cajas as $item)
                        <div class="media pb-3 border-bottom mb-3 align-items-center">
                            <div class="media-image me-2">
                                <img src="{{asset('images/delight_logo.jpg')}}" alt="">
                            </div>
                            <div class="media-body">
                                <h6 class="fs-16 mb-0">{{$item->created_at->format('d-M-Y')}} <span class="badge badge-primary badge-xxs">{{$item->sucursale->nombre}}</span></h6>
                                <div class="d-flex">
                                   <a href="#"  class="fs-14 me-auto text-secondary" wire:click='buscarCaja({{$item->id}})'><span class="fs-14 me-auto text-secondary"><i class="fa fa-ticket me-1"></i>Ver Detalle</span></a> 
                                    <span class="fs-14 text-nowrap ">{{$item->acumulado}} Bs</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                {{$cajas->links()}}
            </div>
        </div>
    </div>
        
  
    @endempty
   
    @isset($cajaactiva)
    <div class="col-xl-8 col-lg-12 col-xxl-5 col-sm-12">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Caja <span class="badge badge-primary">{{$cajaactiva->created_at->format('d-M-Y')}}</span></h4>
                    
                        
                        <a href="#" wire:click="resetCaja"><span class="badge light badge-pill badge-danger">Cambiar de caja</span></a>
                </div>
                <div class="card-body">
                    <div class="">
                      
                       <div class="widget-stat card bg-danger">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalVentas">
                        <div class="card-body  p-4">
                            <div class="media">
                                <span class="me-3">
                                    <i class="flaticon-381-calendar-1"></i>
                                </span>
                                <div class="media-body text-white text-end">
                                    <p class="mb-1">Ventas Totales</p>
                                    <h3 class="text-white">{{$ventasHoy->count()}}</h3>
                                </div>
                            </div>
                        </div>
                       </a>
                       </div>
                       <div class="widget-stat card">
                        <div class="card-body p-4">
                            <div class="media ai-icon">
                                <span class="me-3 bgl-primary text-primary">
                                    <!-- <i class="ti-user"></i> -->
                                    <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <div class="media-body">
                                    <p class="mb-1">Otorgados a clientes</p>
                                    <h4 class="mb-0">{{$ventasHoy->sum('puntos')}}</h4>
                                    <span class="badge badge-primary">Puntos</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="widget-stat card">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDetalle">
                        <div class="card-body  p-4">
                            <div class="media ai-icon">
                                <span class="me-3 bgl-danger text-danger">
                                    <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </span>
                                <div class="media-body">
                                    <p class="mb-1">Ventas de esta caja</p>
                                    <h4 class="mb-0">{{$ventasHoy->sum('total')}}</h4>
                                    <span class="badge badge-danger">BS</span>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
                  
                    </div>
                </div>
            </div>
         
            
        </div>
    </div>
  
    <div class="col-xl-4 col-lg-12 col-xxl-7 col-sm-12">
        <div class="card overflow-hidden">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detalle de caja</h4><span class="badge badge-pill badge-primary">Total items: {{$lista->count()}}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>Producto</strong></th>
                                    <th><strong>Cantidad</strong></th>
                                    <th><strong>Subtotal</strong></th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lista as $item)
                                <tr>
                                    <td>{{$item['nombre']}}</td>
                                    <td><span class="badge light badge-info">{{$item['cantidad']}}</span></td>
                                    <td><span class="badge light badge-warning">{{$item['subtotal']}}  Bs</span></td>

                                   
                                    
                                </tr>
                                
                               
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="row  mx-auto">
                        <div class="col">
                           
                        </div>
                        <div class="col">
                           
                        </div>
                        <div class="col">
                            <span class="badge badge-pill badge-lg badge-info m-2">Total sin adicionales:{{$resumen}} Bs</span>  
                        </div>
                                        
                    </div>
                </div>
              
               
            </div>
        
        </div>
    </div>
  
   
    
    <div class="modal fade" id="modalVentas" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Todas las ventas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-responsive-sm">
                            <thead>
                                <tr>
                                    
                                    <th>Cliente</th>
                                    <th>Descuento</th>
                                    <th>Metodo</th>
                                    <th>Puntos</th>
                                    <th>Total</th>
                                    <th>Detalle</th>
                                    <th>Usuario</th>
                                    <th>Hora Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($ventasHoy)
                                @foreach ($ventasHoy as $item)
                                <tr>
                                   @if($item->cliente)
                                   <td>{{Str::words($item->cliente->name, 1,'')}}</td>
                                   @else
                                   <td>S/N</td>
                                   @endif
                                    <td><span class="badge badge-info light">{{$item->descuento}} Bs</span>
                                    </td>
                                    <td><span class="badge badge-warning light">{{$item->tipo}}</span></td>
                                    <td>{{$item->puntos}} pts</td>
                                    <td class="color-primary"><span class="badge badge-pill badge-success">{{$item->total-$item->descuento}} Bs</span></td>
                                    <td>
                                        <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                        </button>
                                        <div class="dropdown-menu" style="width: 350px">
                                            @foreach ($item->productos as $prod)
                                                <small class="m-1"><span class="badge badge-secondary light badge-xxs">{{$prod->nombre}} : {{$prod->pivot->cantidad}}</span></small><br>
                                            @endforeach
                                        </div>
                                        </div>
                                   </td>
                                    @if($item->usuario)
                                    <td>{{Str::words($item->usuario->name, 1,'')}}</td>
                                    @else
                                    <td>S/N</td>
                                    @endif
                                    <td>{{$item->created_at->format('h:i A')}}</td>
                                </tr>
                                @endforeach
                                @endisset
                                
                                
                               
                            </tbody>
                        </table>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
@endisset
@isset($ventasHoy)
<div class="modal fade" id="modalDetalle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mas Detalles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body pb-0">
                    <p>Ventas por cada metodo de pago</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>En efectivo</strong>
                            <span class="mb-0">{{$ventasHoy->where('tipo','efectivo')->sum('total')}}</span>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Tarjeta</strong>
                            <span class="mb-0">{{$ventasHoy->where('tipo','tarjeta')->sum('total')}}</span>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Banco Bisa</strong>
                            <span class="mb-0">{{$ventasHoy->where('tipo','banco-bisa')->sum('total')}}</span>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Banco Mercantil</strong>
                            <span class="mb-0">{{$ventasHoy->where('tipo','banco-mercantil')->sum('total')}}</span>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Banco Sol</strong>
                            <span class="mb-0">{{$ventasHoy->where('tipo','banco-sol')->sum('total')}}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer pt-0 pb-0 text-center">
                    <div class="row">
                        <div class="col-4 pt-3 pb-3 border-end">
                            <h3 class="mb-1 text-primary">{{$ventasHoy->sum('total')}} Bs</h3>
                            <span>Total</span>
                        </div>
                        <div class="col-4 pt-3 pb-3 border-end">
                            <h3 class="mb-1 text-primary">{{$ventasHoy->sum('total')-$resumen}} Bs</h3>
                            <span>Adicionales</span>
                        </div>
                        <div class="col-4 pt-3 pb-3">
                            <h3 class="mb-1 text-primary">{{$lista->sum('cantidad')}}</h3>
                            <span>Cantidad Neto de Productos Vendidos</span>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>

</div>
@endisset
</div>
