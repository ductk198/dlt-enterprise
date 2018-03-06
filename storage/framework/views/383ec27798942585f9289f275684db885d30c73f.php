<?php $__env->startSection('content'); ?>
<!-- /.content  --> 
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $strJsCss; ?>');
    DLTLib.loadFileJsCss(arrJsCss);
</script>
<form action="index" method="POST" id="frmUser_index">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" id="check_add" value="unit" id-unit='<?php echo e($id_root); ?>'>
    <section class="content-wrapper">
        <ol class="breadcrumb" >
            <li><a role="button"><i class="fa fa-user-circle"></i> Quản trị người dùng</a></li>
            <li class="active">Danh sách phòng ban, người dùng</li>
        </ol>
        <div class="col-md-4" style="width: 28%;" id="jstree-tree">
        </div>
        <!-- Màn hình danh sách -->
        <div  class="col-md-8" style="width: 72%;" id="wrap-user-top">
            <div>
                <ol class="breadcrumb mb0 ng-scope breadcrumb_tree">
                    <li>
                        <a id="tree-header-root" class="tree-header-breadcrumb" id-unit="">
                            <i class="glyphicon glyphicon-folder-open mr2"></i>
                        </a>
                    </li>
                    <li id="tree-header-lv2" class="tree-header" style="display: none">
                    </li>
                    <li id="tree-header-lv3" class="tree-header" style="display: none">
                    </li>
                    <li id="tree-header-lv4" class="tree-header" style="display: none">
                    </li>
                    <li id="tree-header-lv5" class="tree-header" style="display: none">
                    </li>
                    <li>
                        <button id="tree-header-prev" class="btn btn-primary btn-xs tree-header-breadcrumb" id-unit="" id-current="" type=button>
                            <i class="glyphicon glyphicon-level-up"></i>
                        </button>
                    </li>
                </ol>
            </div>
            <div class="col-md-8 col-sm-8 search-wrap-input">
                <div class="input-group">
                    <div class="input-group-btn search-panel">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span id="search_concept">Tìm trang hiện tại</span> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                        	 <li><a search-id="tree" href="#tree">Tìm trang hiện tại</a></li>
                            <li><a search-id="user" href="#user">Tìm người dùng</a></li>
                            <li><a search-id="unit" href="#unit">Tìm phòng ban</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="search_param" value="tree" id="search_param">         
                    <input type="text" class="form-control" name="x" id="search_text" placeholder="Nhập từ khóa tìm kiếm">
                    <span class="input-group-btn">
                        <button id="search-user-unit" class="btn btn-default btn-sm" type="button"><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="pull-right">
                    <button id="btn-add" class="btn btn-default btn-sm ng-binding" type="button">
                        <i class="glyphicon glyphicon-plus"></i> Thêm đơn vị
                    </button>
                    <button class="btn btn-default btn-sm ng-binding" id="btn_import" type="button">
                        <i class="glyphicon glyphicon glyphicon-import"></i> Import
                    </button>
                </div>
            </div>
            <div class="row" id="pagination"></div>
        </div>
        <div class="col-md-8" style="width: 72%;" id="zend_list">
        </div>
        </div>
    </section>
</form>
<!-- Hien thi modal -->
<div class="modal fade" id="addmodal" role="dialog">
</div>
<div id="dialogconfirm">
</div>
<script type="text/javascript">
    var baseUrl = '<?php echo e(url("")); ?>';
    var Js_User = new Js_User(baseUrl, 'system', 'users');
    jQuery(document).ready(function ($) {
        Js_User.loadIndex();
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('System.layouts.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>