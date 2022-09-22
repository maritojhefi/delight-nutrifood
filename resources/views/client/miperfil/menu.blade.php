@extends('client.master')
@section('content')
@push('header')
    <style>
        .card-delight
        {
           margin:0px 5px 20px 5px;
        }
    </style>
@endpush
    <x-cabecera-pagina titulo="Mi Perfil" cabecera="bordepequeno" />
    <div class="page-content pb-0">
        {{-- <div class="mt-3"></div> --}}
        <div data-card-height="cover-card " class="card bg-transparent" style="height: 670px;">
            <div class="card-center text-center">
                {{-- <h1 class="fa-3x font-900 mb-0 color-highlight">{{auth()->user()->name}}</h1> --}}
                <p class="font-13 mt-5">Eres miembro oficial de<strong> Delight!</strong> </p>
                <div class="row mx-auto  mb-0" >
                    <div class="card card-style card-delight col-3  pt-4 ">
                        <a href="{{ route('misplanes') }}" class="">
                            <i class="fa fa-crown font-50 color-yellow-dark"></i>
                            <p>Mis Planes</p>
                        </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('carrito') }}" >
                        <i class="fa fa-shopping-cart font-50 color-orange-dark"></i>
                        <p>Mi Carrito</p>
                    </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('usuario.saldo') }}" >
                        <i class="fa fa-wallet font-50 color-mint-dark"></i>
                        <p>Mi Saldo</p>
                    </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('construccion') }}" >
                        <i class="fa fa-handshake font-50 color-blue-dark"></i>
                        <p>Asistente Virtual</p>

                    </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('construccion') }}" >
                        <i class="fa fa-video font-50 color-red-dark"></i>
                        <p>Tutoriales</p>
                    </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('llenarDatosPerfil') }}" >
                        <i class="fa fa-user font-50 color-green-dark"></i>
                        <p>Editar mi perfil</p>
                    </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('construccion') }}" >
                        <i class="fa fa-shopping-bag font-50 color-pink-dark"></i>
                        <p>Mis Compras</p>
                    </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('construccion') }}" >
                        <i class="fa fa-newspaper font-50 color-aqua-dark"></i>
                        <p>Novedades!</p>
                    </a>
                    </div>
                    <div class="card card-style card-delight col-3  pt-4 ">
                    <a href="{{ route('construccion') }}" >
                        <i class="fa fa-coins font-50 color-teal-dark"></i>
                        <p>Mis Puntos</p>
                    </a>
                    </div>
                    {{-- <a href="index.html" >
                        <i class="fa fa-coins font-50 color-teal-dark"></i>
                         <p>Vista General</p>
                     </a>
                     <a href="{{route('construccion')}}" >
                        <i class="fa fa-coins font-50 color-teal-dark"></i>
                         <p>Descuentos!</p>
                     </a> --}}

                </div>
                <div class="row mx-auto  mb-0">
                    <div class="col-4">
                        <a href="https://www.facebook.com/DelightNutriFoodEcoTienda"
                            class="icon icon-l color-facebook rounded-xl "><i class="font-20 fab fa-facebook-f"></i></a>
                    </div>
                    <div class="col-4">
                        <a href="https://wa.link/ewfjau" class="icon icon-l color-whatsapp rounded-xl"><i
                                class="font-20 fab fa-whatsapp"></i></a>
                    </div>
                    <div class="col-4">
                        <a href="https://www.instagram.com/delight_nutrifood_ecotienda/"
                            class="icon icon-l color-instagram rounded-xl "><i class="font-20 fab fa-instagram"></i></a>
                    </div>
                </div>
                <p class="opacity-60 font-10">Made with <i class="fa fa-heart"></i> by <span
                        class="copyright-year"><strong>Macrobyte</strong></span>
                </p>
            </div>
        </div>
    </div>
@endsection
