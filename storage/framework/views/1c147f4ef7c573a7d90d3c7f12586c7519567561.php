<?php

use Modules\System\Helpers\MenuHelper;
?>
<!-- Navigation -->
<nav class="navbar navbar-default" role="navigation" style="">
    <div class="navbar-top">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <li id="slbd" >
                    <a href="<?php echo e(url('backend/chart')); ?>"> <i class="fa fa-bar-chart fa-hight " aria-hidden="true"></i> Số liệu biểu đồ</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li id="thxld" >
                    <a href="<?php echo e(url('backend')); ?>"> <i class="fa fa-database" aria-hidden="true"></i> Tổng hợp xử lý đơn</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li id="thtd">
                    <a href="<?php echo e(url('backend/citizens')); ?>"> <i class="fa fa-cogs" aria-hidden="true"></i> Tổng hợp tiếp dân</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li id="knbc">
                    <a href="<?php echo e(url('backend/connect')); ?>"> <i style="padding-right:5px;" class="fa fa-connectdevelop" aria-hidden="true"></i>Kết nối Báo cáo TCCP</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
<!--                <li>
                    <form id="frm-search-form" class="form-search form-horizontal pull-right">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <div class="input-append search-sidebar">
                            <input id="input-search-form" name="txt_search" type="text" class="form-control input-md search-query" placeholder="Tìm kiếm hồ sơ ..."> <span onclick="search_record()" id="id-search-record" class="fa fa-search" aria-hidden="true"></span>
                        </div>
                    </form>
                </li>-->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user" aria-hidden="true"></i> 
                        <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo e(url('backend/login/logout')); ?>">Thoát</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
