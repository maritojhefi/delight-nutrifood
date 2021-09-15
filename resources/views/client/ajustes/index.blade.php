@extends('client.master')
@section('content')
<x-cabecera-pagina titulo="Ajustes" cabecera="bordeado"/>

<div class="card card-style">
    <div class="content mt-0 mb-2">
    <div class="list-group list-custom-large mb-4">
    <a href="#" onclick="myFunction()" data-toggle-theme="" class="show-on-theme-light">
    <i class="fa font-14 fa-moon bg-brown-dark rounded-sm"></i>
    <span>Dark Mode</span>
    <strong>Auto Dark Mode Available Too</strong>
    <i class="fa fa-angle-right me-2"></i>
    </a>
    <a href="#" onclick="myFunction()" data-toggle-theme="" class="show-on-theme-dark">
    <i class="fa font-14 fa-lightbulb bg-yellow-dark rounded-sm"></i>
    <span>Light Mode</span>
    <strong>Auto Dark Mode Available Too</strong>
    <i class="fa fa-angle-right me-2"></i>
    </a>
    <a href="#" data-menu="menu-highlights">
    <i class="fa font-14 fa-brush bg-highlight color-white rounded-sm"></i>
    <span>Color Scheme</span>
    <strong>A tone of Colors, Just for You</strong>
    <i class="fa fa-angle-right me-2"></i>
    </a>
    <a href="#" data-menu="menu-share">
    <i class="fa font-14 fa-share-alt bg-red-dark rounded-sm"></i>
    <span>Share Azures</span>
    <strong>Just one tap! We'll do the rest!</strong>
    <i class="fa fa-angle-right me-2"></i>
    </a>
    <a href="#" data-menu="menu-language">
    <i class="fa font-14 fa-globe bg-green-dark rounded-sm"></i>
    <span>Language Picker</span>
    <strong>A Sample for Demo Purposes</strong>
    <i class="fa fa-angle-right me-2"></i>
    </a>
    <a href="index-shapes.html">
    <i class="far font-14 fa-heart bg-pink-dark rounded-sm"></i>
    <span>Azures Shapes</span>
    <strong>Header and Footer Shapes</strong>
    <i class="fa fa-angle-right me-2"></i>
    </a>
    </div>
    <h5>Did you know?</h5>
    <p>
    Fast loading, great support, premium quality. We have a tone of awesome features, that make us stand out from our competitors.
    </p>
    <div class="divider mb-1"></div>
    <div class="list-group list-custom-large">
    <a href="#" data-menu="menu-tips-1" class="border-0">
    <i class="fa font-14 fa-gift bg-magenta-light rounded-sm"></i>
    <span>Tap Here to Start</span>
    <strong>A few Tips About Azures</strong>
    <i class="fa fa-angle-right me-2"></i>
    </a>
    </div>
    </div>
    </div>

@endsection