 <?php $__env->startSection('content'); ?>
<!-- /.content  -->
<script type="text/javascript">
	var arrJsCss = $.parseJSON('<?php echo $strJsCss; ?>');
    DLTLib.loadFileJsCss(arrJsCss);

</script>
<form action="index" method="POST" id="frmCode_index" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
	<section class="content-wrapper">
		<div class="row breadcrumb" style="padding: 6px 45px;">
			
			<button id="btn-export-modules" class="btn btn-danger btn-sm ng-binding" type="button">
				<i class="glyphicon glyphicon-floppy-save"></i> Xuất modules
			</button>
			<div class="pmd-textfield pull-left" style="margin-right: 10px;">
				<input placeholder="Nhập từ khóa tìm kiếm..." class="search-input form-control"></input>
			</div>
			<div class="pull-right table-title-top-action">
				<button id="btn-add-folder" class="btn btn-default btn-sm ng-binding" type="button">
						<i class="glyphicon glyphicon-folder-close"></i> Thêm thư mục
					</button>
					<button id="btn-add-file" class="btn btn-default btn-sm ng-binding" type="button">
						<i class="glyphicon glyphicon-file"></i> Thêm file
					</button>
					<button id="btn-upload" class="btn btn-primary btn-sm ng-binding" type="button">
						<i class="glyphicon glyphicon-cloud-upload"></i> Tải lên
					</button>
			</div>
		</div>
		<div class="col-md-4" style="width: 28%;" id="jstree-tree">
		</div>
		<!-- Màn hình danh sách -->
		<div class="col-md-8" style="width: 72%;" id="zend_list">
		</div>
		</div>
	</section>
</form>
<!-- Hien thi modal -->
<div class="modal fade" id="openDialog" role="dialog">
</div>
<div id="dialogconfirm">
</div>
<div id="dialog" title="Confirmation Required">
</div>
<!-- contextMenu -->
<div id="contextMenu" class="dropdown clearfix">
</div>
<script type="text/javascript">
	var baseUrl = '<?php echo e(url("")); ?>';
    var Js_Code = new Js_Code(baseUrl, 'system/system', 'code');
    jQuery(document).ready(function ($) {
        Js_Code.loadIndex();
    })

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('System.layouts.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>