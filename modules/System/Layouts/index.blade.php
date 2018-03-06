<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!--<link rel="icon" type="image/ico" href="{{URL::asset('public/images/favicon.png')}}"/>-->
        <title>{{Lang::get('System::Header.tittle')}}</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Bootstrap -->
        <link href="{{URL::asset('public/css/assets/bootstrap.min.css')}}" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="{{URL::asset('public/css/assets/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
        <link href="{{URL::asset('public/css/system/style.css')}}" rel="stylesheet">
        <link href="{{URL::asset('public/css/system/skins/green.css')}}" rel="stylesheet">
        <!-- jQuery -->
        <script type="text/jscript" src="{{URL::asset('public/js/assets/jquery-3.1.1.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/assets/jquery-confirm.js')}}"></script>
        <!-- Bootstrap Core JavaScript -->
        <script type="text/jscript" src="{{URL::asset('public/js/assets/bootstrap.min.js')}}"></script>
        <!-- jQuery sticky menu -->
        <script type="text/jscript" src="{{URL::asset('public/js/assets/bootstrap-confirmation.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/assets/nanobar/loadbar.js')}}"></script>
        <!-- main Script -->
        <script type="text/jscript" src="{{URL::asset('public/js/system/app.min.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/DLTLibrary.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/TableXml.js')}}"></script>
    </head>
    <body class="skin-green sidebar-mini">
    </div>
    <div class="main_loadding">
        <svg class="spinner-container" viewBox="0 0 44 44">
        <circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle>  
        </svg>
    </div>
    <div id="loadding"></div>
    <!-- Banner -->
    @include('System.layouts.header')
    <!-- Sidebar -->
    @include('System.layouts.sidebar')
    <!-- Content -->
    <div id="message-alert" class="content">
        <h5><strong><i id="message-icon"></i> <span id="message-label"></span></strong></h5>
        <span id="message-infor"></span>
    </div>
    <div id = "main-content">   
        @yield('content')
    </div>
    <!--@include('System.layouts.footer')-->
</body>
</html>
