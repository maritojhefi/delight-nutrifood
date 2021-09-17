

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
	
	<!-- PAGE TITLE HERE -->
	<title>DELIGHT</title>
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="images/favicon.png" />
	
	<link rel="stylesheet" href="vendor/chartist/css/chartist.min.css">
	<link href="vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href="vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet">
	<!-- Style css -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
	
</head>
<body>
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
            <a href="index.html" class="brand-logo">
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
            <!-- row -->
		@yield('content')
			
        </div>
    
	</div>
   
    <!-- Required vendors -->
    <script src="{{asset('vendor/global/global.min.js')}}"></script>
	<script src="{{asset('vendor/chart.js/Chart.bundle.min.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-datetimepicker/js/moment.js')}}"></script>
	<script src="{{asset('vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script src="{{asset('vendor/jquery-nice-select/js/jquery.nice-select.min.js')}}"></script>
	
	<!-- Chart piety plugin files -->
    <script src="{{asset('vendor/peity/jquery.peity.min.js')}}"></script>
	
	<!-- Apex Chart -->
	<script src="{{asset('vendor/apexchart/apexchart.js')}}"></script>
	
	<!-- Dashboard 1 -->
	<script src="{{asset('js/dashboard/dashboard-1.js')}}"></script>

    <script src="{{asset('js/custom.min.js')}}"></script>
	<script src="{{asset('js/deznav-init.js')}}"></script>
	<script src="{{asset('js/demo.js')}}"></script>
   
</body>
</html>