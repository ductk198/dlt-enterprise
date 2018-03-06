<form id="frmAddListType" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" name="listtype" value="<?php echo e($listtype_id); ?>">
    <input type="hidden" name="idlist" value="<?php echo e($idlist); ?>">
    <input type="hidden" name="oldorder" value="<?php echo e($oldorder); ?>">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo e(Lang::get('System::Listtype.add')); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $strrHTML; ?>

            </div>
            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary btn-flat" type="button"><?php echo e(Lang::get('System::Common.submit')); ?></button>
                <button type="input" class="btn btn-default" data-dismiss="modal"><?php echo e(Lang::get('System::Common.close')); ?></button>
            </div>
        </div>
    </div>
</form>