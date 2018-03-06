<?php $__env->startSection('content'); ?>
<!-- /.content --> 
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
    DLTLib.loadFileJsCss(arrJsCss);
</script>
<form action="index" method="POST" id="frmlisttype_index">
<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
<input type="hidden" id="_filexml" name="_filexml" value="loai_danh_muc.xml">
  <section class="content-wrapper">
    <ol class="breadcrumb" style="margin-bottom: 0px;">
        <li><a role="button" onclick="LoadModul('<?php echo e(url("admin/listype")); ?>','listype');"><i class="fa fa-list"></i> <?php echo e(Lang::get('System::Modul.listype')); ?></a></li>
        <li class="active"><?php echo e(Lang::get('System::Modul.list_listype')); ?></li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-body">
			<div class="col-md-6 form-group">
               <div class="row ">
                    <label class="control-label col-md-3">Chọn đơn vị</label>
                    <div class="col-md-8">
                        <select id="Units" name="Units" class="form-control input-sm chzn-select" message="">
                            <option value="">-- Tất cả đơn vị --</option>
                             <?php $__currentLoopData = $Units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Unit): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                            <option value="<?php echo e($Unit->C_CODE); ?>"><?php echo e($Unit->C_NAME); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        </select>
                    </div>
                </div>
            </div> 
            <div class="row form-group input-group-index">
				
                <div class="col-md-4 input-group input-group-sm">
                    <input id="search_text" name="search" class="form-control" type="text">
                    <span class="input-group-btn">
                    <button class="btn btn-primary btn-flat search" id="btn_search" data-loading-text="<?php echo e(Lang::get('System::Common.search')); ?>..." type="button"><?php echo e(Lang::get('System::Common.search')); ?></button>
                    </span>
                </div>
			</div>
            <div class="row form-group input-group-index">
                <div class="pull-right">
                    <button class="btn btn-primary btn-flat" id="btn_add" type="button" data-toggle="tooltip"  data-original-title="<?php echo e(Lang::get('System::Common.add')); ?>"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-success btn-flat" id="btn_edit" type="button" data-toggle="tooltip"  data-original-title="<?php echo e(Lang::get('System::Common.edit')); ?>"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation" data-btn-ok-label="<?php echo e(Lang::get('System::Common.delete')); ?>" data-btn-ok-icon="fa fa-trash-o" data-btn-ok-class="btn-danger" data-btn-cancel-label="<?php echo e(Lang::get('System::Common.close')); ?>" data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default" data-placement="left" data-title="<?php echo e(Lang::get('System::Common.delete_title')); ?>"><i class="fa fa-trash-o"></i></button>
                    <button class="btn btn-default glyphicon glyphicon-cloud" id="btn_export_cache" type="button" data-toggle="tooltip" data-original-title="<?php echo e(Lang::get('System::Common.export_cache')); ?>">
                    </button>
                </div>
            </div>
            <!-- Màn hình danh sách -->
            <div class="row" id="table-container"></div>
            <!-- Phân trang dữ liệu -->
            <div class="row" id="pagination"></div>
		</div>
    </div>
</section>
</form>
<!-- Hien thi modal -->
<div class="modal fade" id="addListypeModal" role="dialog">
</div>
<script type="text/javascript">
    var baseUrl = '<?php echo e(url("")); ?>';
    var Js_Listtype = new Js_Listtype(baseUrl,'system/listtype','listtype');
    jQuery(document).ready(function($) {
        Js_Listtype.loadIndex();
})
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('System.layouts.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>