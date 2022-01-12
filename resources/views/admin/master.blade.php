

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Ventic : Ticketing Admin Template" />
	<meta property="og:title" content="Ventic : Ticketing Admin Template" />
	<meta property="og:description" content="Ventic : Ticketing Admin Template" />
	<meta property="og:image" content=""/>
	<meta name="format-detection" content="telephone=no">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<!-- PAGE TITLE HERE -->
	<title>DELIGHT</title>
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="images/favicon.png" />
	
	<link rel="stylesheet" href="vendor/chartist/css/chartist.min.css">
	<link href="vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href="vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
	<!-- Style css -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <link href="{{asset('vendor/lightgallery/css/lightgallery.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales-all.min.js"></script>
    <script src="{{asset('js/calendario.js')}}"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    @livewireStyles
	@livewireScripts
   
</head>
<body  version="dark">
    
@hasSection ('content')
<div id="preloader" >
    <div class="loader">
        <div class="loader--dot"></div>
        <div class="loader--dot"></div>
        <div class="loader--dot"></div>
        <div class="loader--dot"></div>
        <div class="loader--dot"></div>
        <div class="loader--dot"></div>
        <div class="loader--text"></div>
    </div>
</div>
<div id="main-wrapper">
    <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="{{route('ventas.listar')}}" class="brand-logo">
                <!-- <img class="logo-abbr" src="./images/logo.png" alt="">
				<div class="brand-title">Ventic</div> -->
				<img class="logo-abbr" width="54"  viewBox="0 0 54 54" fill="none" src="{{asset('delight_logo.jpg')}}"/>
					
					<span class="brand-title" width="97" height="25" fill="none">Delight
                    </span>
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
      
      @include('admin.partials.header')
      
        @include('admin.partials.sidebar')
       
        <div class="content-body">
            <div class="container-fluid">
                @include('admin.partials.alertas')
                @yield('content')
            </div>
            <!-- row -->
		
			
        </div>
    
	</div>
@elseif('login')
@yield('login')
@endif

@stack('scripts')

    <!-- Required vendors -->
    <script src="{{asset('vendor/global/global.min.js')}}"></script>
	<script src="{{asset('vendor/chart.js/Chart.bundle.min.js')}}"></script>
   
	<script src="{{asset('vendor/jquery-nice-select/js/jquery.nice-select.min.js')}}"></script>
 
	<script src="{{asset('js/sweetalert.min.js')}}"></script>
	<!-- Chart piety plugin files -->
    <script src="{{asset('vendor/peity/jquery.peity.min.js')}}"></script>
	
	<!-- Apex Chart -->
	
	
	<!-- Dashboard 1 -->
	<script src="{{asset('js/dashboard/dashboard-1.js')}}"></script>
  
    <script src="{{asset('js/custom.min.js')}}"></script>
	<script src="{{asset('js/deznav-init.js')}}"></script>
	<script src="{{asset('js/demo.js')}}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            showCloseButton: true,
            timer: 5000,
            timerProgressBar:true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    
        window.addEventListener('alert',({detail:{type,message}})=>{
            Toast.fire({
                icon:type,
                title:message
            })
        })

        window.addEventListener('copiarTexto', ({detail: {id,ag}}) => {
            var content = document.getElementById('copiar'+id);
            content.select();
            document.execCommand('copy');
        })
    </script>
</body>
</html>