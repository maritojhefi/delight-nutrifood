@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="{{$plan->nombre}}" cabecera="bordeado"/>
    <div class="card card-style">
        <div class="content text-white">
        <h4>Tus proximos 5 dias!(Restante:{{$coleccion->count()}} dias)</h4>
        <p>
        Personaliza el pedido de tu semana!
        </p>
        </div>
        <div class="accordion mt-4" id="accordion-2">
        @foreach ($coleccion as $lista)
        <div class="card card-style shadow-0 bg-highlight mb-1">
            <button class="btn accordion-btn color-white no-effect collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{$lista['id']}}" aria-expanded="false">
            <i class="fa fa-star me-2"></i>
            {{$lista['dia']}}({{$lista['fecha']}})
            @if ($lista['detalle'] ==null || $lista['detalle'] =="") 
            <i class="fa fa-chevron-down font-10 accordion-icon"></i>
            @else
           <label for="" class="text-magenta"><i class="fa fa-check color-yellow-dark "></i> Guardado!</label> 
            @endif
            </button>
            
            <div id="collapse{{$lista['id']}}" class="bg-theme collapse" data-bs-parent="#accordion-2" style="">
                <div class="p-2">
                    @if ($lista['detalle'] ==null || $lista['detalle'] =="")
                    <form action="{{route('personalizardia')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <mark class="highlight ps-2 font-12 pe-2 bg-yellow-dark">Elija su plato</mark>
                                <div class="fac fac-radio fac-default"><span></span>
                                <input id="box1-fac-radio{{$lista['id']}}" type="radio" name="plato{{$lista['id']}}" value="{{$lista['ejecutivo']}}" >
                                <label for="box1-fac-radio{{$lista['id']}}">{{$lista['ejecutivo']}}</label>
                                </div>
                                <div class="fac fac-radio fac-default"><span></span>
                                <input id="box2-fac-radio{{$lista['id']}}" type="radio" name="plato{{$lista['id']}}" value="{{$lista['dieta']}}">
                                <label for="box2-fac-radio{{$lista['id']}}">{{$lista['dieta']}}</label>
                                </div>
                                <div class="fac fac-radio fac-default"><span></span>
                                <input id="box3-fac-radio{{$lista['id']}}" type="radio" name="plato{{$lista['id']}}" value="{{$lista['vegetariano']}}">
                                <label for="box3-fac-radio{{$lista['id']}}">{{$lista['vegetariano']}}</label>
                                </div>
                                
                                </div>
                                <div class="col-6">
                                    <mark class="highlight ps-2 font-12 pe-2 bg-yellow-dark">Elija su carbohidrato</mark>
                                    <div class="fac fac-radio fac-default"><span></span>
                                    <input id="box4-fac-radio{{$lista['id']}}" type="radio" name="carb{{$lista['id']}}" value="{{$lista['carbohidrato_1']}}" >
                                    <label for="box4-fac-radio{{$lista['id']}}">{{$lista['carbohidrato_1']}}</label>
                                    </div>
                                    <div class="fac fac-radio fac-default"><span></span>
                                    <input id="box5-fac-radio{{$lista['id']}}" type="radio" name="carb{{$lista['id']}}" value="{{$lista['carbohidrato_2']}}">
                                    <label for="box5-fac-radio{{$lista['id']}}">{{$lista['carbohidrato_2']}}</label>
                                    </div>
                                    <div class="fac fac-radio fac-default"><span></span>
                                    <input id="box6-fac-radio{{$lista['id']}}" type="radio" name="carb{{$lista['id']}}" value="{{$lista['carbohidrato_3']}}">
                                    <label for="box6-fac-radio{{$lista['id']}}">{{$lista['carbohidrato_3']}}</label>
                                    </div>
                                    
                                    
                                    </div>
                          </div>
                          
                          <div class="row mb-0">
                            <h4 class="col-6 font-500 font-13 text-white"><mark class="highlight ps-2 font-12 pe-2 bg-magenta-dark">Sopa</mark></h4>
                            <p class="col-6 mb-3 text-end text-white">
                            {{$lista['sopa']}}
                            </p>
                            <h4 class="col-6 font-500 font-13 text-white"><mark class="highlight ps-2 font-12 pe-2 bg-blue-dark">Ensalada</mark></h4>
                            <p class="col-6 mb-3 text-end text-white">
                                {{$lista['ensalada']}}
                            </p>
                            <h4 class="col-6 font-500 font-13 text-white"><mark class="highlight ps-2 font-12 pe-2 bg-orange-dark">Jugo</mark></h4>
                            <p class="col-6 mb-3 text-end text-white">
                                {{$lista['jugo']}}
                            </p>
                            <input type="hidden" value="{{$lista['dia']}}" name="dia">
                            <input type="hidden" value="{{$lista['id']}}" name="id">
                            <input type="hidden" value="{{$lista['ensalada']}}" name="ensalada">
                            <input type="hidden" value="{{$lista['sopa']}}" name="sopa">
                            <input type="hidden" value="{{$lista['jugo']}}" name="jugo">
                            <div class="col-4 m-2 ">
                                <button type="submit" class="btn btn-3d btn-m btn-full  rounded-xl text-uppercase font-900 shadow-s  border-green-dark bg-green-light">Guardar de este dia</button>
                                </div>
                            </div>
    
                    </form>
                    @else
                    <a href="{{route('editardia',$lista['id'])}}" class="btn btn-xxs  rounded-s text-uppercase font-900 shadow-s border-red-dark  bg-red-light">Editar</a>
                    @endif
                    
               
                </div>
                </div>
           
        </div>
        @endforeach
        
        
        </div>
        </div>
        @if (session('danger'))
        <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-red-dark" role="alert">
            <span><i class="fa fa-times"></i></span>
            <strong>{{session('danger')}}</strong>
            <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">×</button>
            </div>
        @endif
        @if (session('success'))
        <div class="ms-3 me-3 mb-5 alert alert-small rounded-s shadow-xl bg-green-dark" role="alert">
            <span><i class="fa fa-check"></i></span>
            <strong>{{session('success')}}</strong>
            <button type="button" class="close color-white opacity-60 font-16" data-bs-dismiss="alert" aria-label="Close">×</button>
            </div>
        @endif
@endsection