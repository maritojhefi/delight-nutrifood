@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="{{$plan->nombre}}" cabecera="bordeado"/>
    <div class="card card-style">
        <div class="content text-white">
        <h4>Personaliza tu plan de esta semana!</h4>
        <p>
        Quedan {{$coleccion->count()}} dias, personaliza cada uno! 
        </p>
        </div>
        <div class="accordion mt-4" id="accordion-2">
        @foreach ($coleccion as $lista)
        <div class="card card-style shadow-0 bg-highlight mb-1">
            <button class="btn accordion-btn color-white no-effect {{$lista['detalle']==null?'':'bg-green-dark'}} collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{$lista['id']}}" aria-expanded="false">
            <i class="fa fa-star me-2"></i>
            {{$lista['dia']}}({{$lista['fecha']}})
            @if ($lista['detalle'] ==null || $lista['detalle'] =="") 
            <i class="fa fa-chevron-down font-10 accordion-icon"></i>
            @else
           <label for="" class="text-magenta"><span class="fa fa-check color-yellow-dark "></span> Guardado!</label> 
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
                            <h4 class="col-6 font-500  font-13 text-white"><mark class="highlight ps-2 font-12 pe-2 bg-magenta-dark">Sopa</mark></h4>
                            <p class="col-6 mb-3 text-end text-white font-900 mb-0">
                            {{$lista['sopa']}}
                            </p>
                            <h4 class="col-6 font-500 font-13 text-white"><mark class="highlight ps-2 font-12 pe-2 bg-blue-dark">Ensalada</mark></h4>
                            <p class="col-6 mb-3 text-end text-white font-900 mb-0">
                                {{$lista['ensalada']}}
                            </p>
                            <h4 class="col-6 font-500 font-13 text-white"><mark class="highlight ps-2 font-12 pe-2 bg-orange-dark">Jugo</mark></h4>
                            <p class="col-6 mb-3 text-end text-white font-900 mb-0">
                                {{$lista['jugo']}}
                            </p>
                            <input type="hidden" value="{{$lista['dia']}}" name="dia">
                            <input type="hidden" value="{{$lista['id']}}" name="id">
                            <input type="hidden" value="{{$lista['ensalada']}}" name="ensalada">
                            <input type="hidden" value="{{$lista['sopa']}}" name="sopa">
                            <input type="hidden" value="{{$lista['jugo']}}" name="jugo">
                            <div class="col">
                                <button type="submit" class="btn btn-3d btn-m btn-full  rounded-xl text-uppercase font-900 shadow-s  border-green-dark bg-green-light">Guardar {{$lista['dia']}}</button>
                                </div>
                            </div>
    
                    </form>
                    @else
                    <ul class="icon-list">
                    @foreach (json_decode($lista['detalle'],true) as $plato=>$valor)
                   
                        <li><i class="fa fa-check color-yellow-dark"></i>{{$plato}} : <label for="" class="font-700 mb-0">{{$valor}}</label>  </li>
                      
                      
                        
                    @endforeach
                    </ul>
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
        @once
        <div class="card card-style bg-18" data-card-height="150" style="height: 150px;">
            <div class="card-center ms-3">
            <h1 class="color-white">A tener en cuenta!</h1>
            <p class="color-white mt-n1 mb-0 opacity-70">Es deber del cliente personalizar cada dia de su plan vigente para realizar una correcta entrega del pedido.</p>
            </div>
            <div class="card-overlay bg-black opacity-80"></div>
            </div>
        @endonce
        
@endsection