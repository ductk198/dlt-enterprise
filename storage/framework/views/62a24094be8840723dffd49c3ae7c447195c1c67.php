    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Thành phố Hà Nội</title>
        <link rel="icon" type="image/ico" href="<?php echo e(URL::asset('public/images/favicon.ico')); ?>"/>
        <!-- CSS -->
        <!-- Bootstrap -->
        <link href="<?php echo e(URL::asset('public/css/assets/bootstrap.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(URL::asset('public/css/assets/font-awesome-4.7.0/css/font-awesome.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(URL::asset('public/css/backend/style.css')); ?>" rel="stylesheet">

        <link href="<?php echo e(URL::asset('public/css/assets/daterangepicker.css')); ?>" rel="stylesheet">
        <!-- JS -->
		<script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/jquery-3.1.1.js')); ?>"></script>
        <!-- Bootstrap Core JavaScript -->
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/bootstrap.min.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/daterangepicker/moment.min.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/daterangepicker/daterangepicker.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/assets/nanobar/loadbar.js')); ?>"></script>
        <script type="text/jscript" src="<?php echo e(URL::asset('public/js/EfyLibrary.js')); ?>"></script>
    </head>
    <body>
        <div class="main_loadding"></div>
        <!-- Banner -->
        <?php echo $__env->make('backend.layouts.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('backend.layouts.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
         <!-- content -->
         <div id="message-alert" class="content">
            <h4><strong><i id="message-icon"></i> <span id="message-label"></span></strong></h4>
            <span id="message-infor"></span>
        </div>
        <div id="content">
         <?php echo $__env->yieldContent('content'); ?>
         </div>
        <!-- footer -->
         <?php echo $__env->make('backend.layouts.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
         <!-- Script to Activate the Carousel -->
         <div id="search_infor_record" role="dialog" class="modal fade"></div>
    </body>
    </html>
