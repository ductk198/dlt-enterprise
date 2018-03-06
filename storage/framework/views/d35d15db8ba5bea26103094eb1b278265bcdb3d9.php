<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="navbar-banner">
            <a class="btn btn-sm hty-btn-fab hty-ripple-effect pull-left hty-sidebar-toggle">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>	
            <a class="navbar-brand f-logo" href="#">
                <p class="first-text">CÔNG TY TNHH DLT</p>
                <p class="second-text">HỆ THỐNG QUẢN TRỊ DOANH NGHIỆP</p>
            </a>
        </div>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown notifications-menu">
                    <a href="<?php echo e(url('/')); ?>">
                        <i class="fa fa-sliders" aria-hidden="true"></i> <?php echo e(Lang::get('System::Header.backend')); ?>

                    </a>

                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo e(URL::asset('public/images/backend/avatar5.png')); ?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?php echo e(URL::asset('public/images/backend/avatar5.png')); ?>" class="img-circle" alt="User Image">
                            <p>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a id="change-password" url="<?php echo e(url('system/login/changepassword')); ?>" href="#" class="btn btn-default btn-flat"><?php echo e(Lang::get('System::Login.change_password')); ?></a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo e(url('system/login/logout')); ?>" class="btn btn-default btn-flat"><?php echo e(Lang::get('System::Login.sign_out')); ?></a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>