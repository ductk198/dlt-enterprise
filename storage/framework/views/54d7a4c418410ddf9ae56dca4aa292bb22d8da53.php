<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!--<link rel="icon" type="image/ico" href="<?php echo e(URL::asset('public/images/favicon.png')); ?>"/>-->
        <title><?php echo e(Lang::get('System::Header.tittle')); ?></title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <!-- Bootstrap -->
        <link href="<?php echo e(URL::asset('public/css/assets/bootstrap.min.css')); ?>" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="<?php echo e(URL::asset('public/css/assets/font-awesome-4.7.0/css/font-awesome.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(URL::asset('public/css/system/style.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(URL::asset('public/css/system/skins/green.css')); ?>" rel="stylesheet">
        <!-- jQuery -->
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/jquery-3.1.1.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/jquery-confirm.js')); ?>"></script>
        <!-- Bootstrap Core JavaScript -->
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/bootstrap.min.js')); ?>"></script>
        <!-- jQuery sticky menu -->
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/bootstrap-confirmation.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/nanobar/loadbar.js')); ?>"></script>
        <!-- main Script -->
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/system/app.min.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/DLTLibrary.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/TableXml.js')); ?>"></script>
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
    <?php echo $__env->make('System.layouts.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Sidebar -->
    <?php echo $__env->make('System.layouts.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- Content -->
    <div id="message-alert" class="content">
        <h5><strong><i id="message-icon"></i> <span id="message-label"></span></strong></h5>
        <span id="message-infor"></span>
    </div>
    <div id = "main-content">   
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <!--<?php echo $__env->make('System.layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>-->
</body>
</html>
