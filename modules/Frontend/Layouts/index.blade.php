<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Thành phố Hà Nội</title>
        <link rel="icon" type="image/ico" href="{{URL::asset('public/images/favicon.ico')}}"/>
        <!-- CSS -->
        <!-- Bootstrap -->
        <link href="{{URL::asset('public/css/assets/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{URL::asset('public/css/assets/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
        <!--<link href="{{URL::asset('public/css/backend/style.css')}}" rel="stylesheet">-->

        <link href="{{URL::asset('public/css/assets/daterangepicker.css')}}" rel="stylesheet">
        <!-- JS -->
        <script type="text/jscript" src="{{URL::asset('public/js/assets/jquery-3.1.1.js')}}"></script>
        <!-- Bootstrap Core JavaScript -->
        <script type="text/jscript" src="{{URL::asset('public/js/assets/bootstrap.min.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/assets/daterangepicker/moment.min.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/assets/daterangepicker/daterangepicker.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/assets/nanobar/loadbar.js')}}"></script>
        <script type="text/jscript" src="{{URL::asset('public/js/DLTLibrary.js')}}"></script>
    </head>
    <body>
        <div class="main_loadding"></div>
        <!-- Banner -->
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">ENTERPRISE</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="/">Doanh nghiệp mới thành lập</a></li>
                        <li><a href="/" rel="nofollow" target="_blank"><span style="color:#0000ff;">Tuyển dụng kế toán</span></a></li>
                        <li><a href="/"><span style="color:#ff0000;">CHỮ KÝ SỐ</span></a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="message-alert" class="content">
            <h4><strong><i id="message-icon"></i> <span id="message-label"></span></strong></h4>
            <span id="message-infor"></span>
        </div>
        <div id="content">
            @yield('content')
        </div>
        <!-- footer -->
        @include('Frontend.layouts.footer')
        <!-- Script to Activate the Carousel -->
        <div id="search_infor_record" role="dialog" class="modal fade"></div>
    </body>
</html>
