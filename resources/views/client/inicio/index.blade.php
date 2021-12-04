@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Inicio" cabecera="bordeado"/>

   
    
<div data-card-height="cover-card" class="card card-style" style="height: 513px;">
    <div class="card-center text-center">
    <i class="fa fa-clock fa-9x color-highlight mb-5"></i>
    <h1 class="font-36 font-800 mb-2">We're on It!</h1>
    <p class="boxed-text-xl">
    We're currently working to get our page up and running. We estimate we'll be online in about:
    </p>
    <div class="countdown row mb-4 mt-5 ms-2 me-2">
    <div class="disabled">
    <h1 class="mb-0 color-theme font-30" id="years">8</h1>
    <p class="mt-n1 color-theme font-11 opacity-30">years</p>
    </div>
    <div class="col-3">
    <h1 class="mb-0 color-theme font-30" id="days">48</h1>
    <p class="mt-n1 color-theme font-11 opacity-30">days</p>
    </div>
    <div class="col-3">
    <h1 class="mb-0 color-theme font-30" id="hours">03</h1>
    <p class="mt-n1 color-theme font-11 opacity-30">hours</p>
    </div>
    <div class="col-3">
    <h1 class="mb-0 color-theme font-30" id="minutes">34</h1>
    <p class="mt-n1 color-theme font-11 opacity-30">minutes</p>
    </div>
    <div class="col-3">
    <h1 class="mb-0 color-theme font-30" id="seconds">06</h1>
    <p class="mt-n1 color-theme font-11 opacity-30">seconds</p>
    </div>
    </div>
    <div class="row mb-0 px-4">
    <div class="col-6">
    <a href="#" class="btn btn-m bg-highlight btn-full rounded-sm bg-highlight font-900 text-uppercase">Back Home</a>
    </div>
    <div class="col-6">
    <a href="#" class="btn btn-m bg-highlight btn-full rounded-sm bg-highlight font-900 text-uppercase">Contact</a>
    </div>
    </div>
    </div>
    </div>
    
    
@endsection