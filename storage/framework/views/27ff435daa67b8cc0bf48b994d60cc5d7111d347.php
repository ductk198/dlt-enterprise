<!DOCTYPE html>
<!-- saved from url=(0069)http://localhost/ehslt_quangbinh/backend/web/index.php?r=site%2Flogin -->
<html lang="vi"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-param" content="_csrf">
    <title>Đăng nhập</title>
    <link href="<?php echo e(URL::asset('public/css/assets/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('public/css/assets/font-awesome-4.7.0/css/font-awesome.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('public/css/backend/login/main.css')); ?>" rel="stylesheet">
    <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/jquery-3.1.1.js')); ?>"></script>
    <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/bootstrap.min.js')); ?>"></script>
    <script type="text/jscript" src="<?php echo e(URL::asset('public/js/backend/Login/JS_Login.js')); ?>"></script>
    <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/jquery.validate.js')); ?>"></script>
    <link rel="shortcut icon" href="">
<body>
<div class="wrap">
<div class="login-wrapper">
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-offset-3 col-sm-6 col-xs-offset-1 col-xs-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="text-center" style="margin: 0;text-transform: uppercase">đăng nhập hệ thống</h4>
                </div>
                <div class="panel-body">
                    <div class="site-login">
                        <div class="row">
                            <img src="<?php echo e(URL::asset('public/images/logo.jpg')); ?>" alt="" class="img-responsive" style="width: 250px;margin: 0 auto">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <form id="frm_login" action="login/checklogin" method="POST" role="form">
                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <div class="form-group required ">
                                        <input type="username" id="username" class="form-control" name="username" placeholder="Tên đăng nhập">
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <i class="fa fa-lock"></i>
                                        <input type="password" id="password" class="form-control" name="password" placeholder="Mật khẩu">
                                    </div>                               
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row wrap-row-login">
                        <button type="text" id="btn_submit" class="btn btn-primary btn-lg btn-block">Đăng nhập
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</body>
</html>