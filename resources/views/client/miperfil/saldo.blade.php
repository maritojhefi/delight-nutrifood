@extends('client.master')
@section('content')
    <x-cabecera-pagina titulo="Mi saldo" cabecera="bordeado" />
    <div class="card card-style">
        <div class="content mb-2">
            <h4>Resumen detallado</h4>
            <p>
                Registros de saldos a continuacion:
            </p>
            <table class="table table-borderless text-center rounded-sm shadow-l" style="overflow: hidden;">
                <thead>
                    <tr class="bg-gray-light">
                        <th scope="col" class="color-theme py-3 font-14">Detalle</th>
                        <th scope="col" class="color-theme py-3 font-14">Fecha</th>
                        <th scope="col" class="color-theme py-3 font-14">Monto</th>
                        <th scope="col" class="color-theme py-3 font-14">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuario->saldos as $item)
                        <tr>
                            <td><a href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalSaldo{{ $item->id }}"><span
                                        class="fa-fw select-all fas"></span></a></td>
                            <th scope="row">
                                {{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at->) }}</th>
                            <td class="color-{{ $item->es_deuda ? 'red' : 'green' }}-dark">{{ $item->monto }} Bs</td>
                            @if ($item->es_deuda)
                                <td>
                                    <mark class="highlight ps-2 font-12 pe-2 bg-red-dark">DEUDA <strong>-</strong></mark> <i
                                        class="fa fa-arrow-right rotate-135 color-red-dark"></i>
                                </td>
                            @else
                                <td>
                                    <mark class="highlight ps-2 font-12 pe-2 bg-green-dark">A FAVOR
                                        <strong>+</strong></mark> <i class="fa fa-arrow-up rotate-45 color-green-dark"></i>
                                </td>
                            @endif

                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
    @foreach ($usuario->saldos as $item)
        <div class="modal fade" id="modalSaldo{{ $item->id }}" tabindex="-1" aria-labelledby="modalSaldo"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="card card-style">
                    <div class="content">
                        <div class="d-flex">
                            <div>
                                <h1>Informacion general</h1>
                                <p class="font-600 color-highlight mt-n3">Venta registrada</p>
                            </div>
                            <div class="ms-auto">
                                <img src="{{asset('delight_logo.jpg')}}" width="40">
                            </div>
                        </div>
                        <div class="divider mt-3 mb-3"></div>
                        <div class="row mb-0">
                            <div class="col-4">
                                <p class="color-theme font-700">Fecha</p>
                            </div>
                            <div class="col-8">
                                <p class="font-400"><strong>{{$item->created_at->format('d-M')}}</strong> ( {{ App\Helpers\WhatsappAPIHelper::timeago($item->created_at) }} )</p>
                            </div>
                            <div class="col-4">
                                <p class="color-theme font-700">Subtotal de venta</p>
                            </div>
                            <div class="col-8">
                                <p class="font-400">{{$item->venta->total}} Bs</p>
                            </div>
                            <div class="col-4">
                                <p class="color-theme font-700">Descuento</p>
                            </div>
                            <div class="col-8">
                                <p class="font-400">{{$item->venta->descuento}} Bs</p>
                            </div>
                            <div class="col-4">
                                <p class="color-theme font-700">A saldo</p>
                            </div>
                            <div class="col-8">
                                <p class="font-400">{{$item->venta->saldo}} Bs</p>
                            </div>
                            <div class="col-4">
                                <p class="color-theme font-700">Total pagado</p>
                            </div>
                            <div class="col-8">
                                <p class="font-400"><strong>{{$item->venta->total-$item->venta->descuento-$item->venta->saldo}} Bs</strong></p>
                            </div>
                            <div class="col-4">
                                <p class="color-theme font-700">Atendido por</p>
                            </div>
                            <div class="col-8">
                                <p class="font-400">{{$item->venta->usuario->name}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-style">
                    <div class="content">
                        <h4 class="mb-n1">Detalle</h4>
                        <p>
                            Detalle de los items de esta venta:
                        </p>
                        <div class="row mb-0">
                            @foreach ($item->venta->productos->groupBy('nombre') as $detalle)
                            
                            <div class="col-3">
                                <p class="color-theme font-700">{{Str::limit($detalle[0]->nombre,15)}}</p>
                            </div>
                            <div class="col-3">
                                <p class="font-400">@foreach ($detalle as $pivot)
                                    {{$pivot->pivot->cantidad}}
                                    @break
                                @endforeach unidades</p>
                            </div>
                            @if ($detalle[0]->descuento!=null && $detalle[0]->descuento<$detalle[0]->precio)
                            <div class="col-3">
                                <p class="font-400">{{$detalle->sum('descuento')}} Bs c/u</p>
                            </div>
                            <div class="col-3">
                                <p class="font-400">{{$detalle[0]->pivot->cantidad*$detalle->sum('descuento')}} Bs</p>
                            </div>
                            @else
                            <div class="col-3">
                                <p class="font-400">{{$detalle->sum('precio')}} Bs c/u</p>
                            </div>
                            <div class="col-3">
                                <p class="font-400">{{$detalle[0]->pivot->cantidad*$detalle->sum('precio')}} Bs</p>
                            </div>
                            @endif
                            
                            @endforeach
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
