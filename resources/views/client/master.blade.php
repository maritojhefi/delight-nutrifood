
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
<title>Delight</title>
<link rel="stylesheet" type="text/css" href="styles/bootstrap.css">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="fonts/css/fontawesome-all.min.css">
<link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
</head>
<body class="{{session('theme')}}">
<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>
<div id="page">

<div class="header header-fixed header-auto-show header-logo-app">
<a href="#" data-back-button class="header-title header-subtitle">Atras</a>
<a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a>
<a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a>
<a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a>
<a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>
<a href="#" data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a>
</div>
@include('client.partials.footer-menu')

<div class="page-content">
@yield('content')
</div>

<div id="menu-share" class="menu menu-box-bottom menu-box-detached rounded-m" data-menu-height="420" data-menu-effect="menu-over">
   
</div>
<div id="menu-highlights" class="menu menu-box-bottom menu-box-detached rounded-m"  data-menu-height="510" data-menu-effect="menu-over">
    @include('client.partials.menu-colors')
</div>
<div id="menu-main" class="menu menu-box-right menu-box-detached rounded-m" data-menu-width="260"  data-menu-active="nav-pages" data-menu-effect="menu-over">
    @include('client.partials.menu-sidebar')
</div>
</div>
<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>
<script>
    function myFunction() {
  var element = document.body;
  element.classList.toggle("theme-dark");
 
}
</script>
</body>
