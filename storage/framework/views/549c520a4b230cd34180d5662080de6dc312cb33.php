<form id="frmAddPositionType" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" name="PK_POSITION" value="<?php echo e($PK_POSITION); ?>">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo e(Lang::get('System::Position.add')); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Mã chức vụ</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input class="form-control input-md" type="text" id="C_CODE" name="C_CODE" value="<?php echo e($C_CODE); ?>" xml_data="false" column_name="C_CODE">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Tên nhóm chức vụ</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input class="form-control input-md" type="text" id="C_NAME" name="C_NAME" value="<?php echo e($C_NAME); ?>" xml_data="false" column_name="C_NAME">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Nhóm chức vụ</label>
                    <div class="col-md-6">
                        <select class="form-control" style="width: 194px" name="FK_POSITION_GROUP" id="FK_POSITION_GROUP">
                            <option value="">Chọn nhóm chức vụ</option>
                            <?php echo $shtml; ?>

                        </select>              
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Số thứ tự</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input class="form-control input-md" type="text" id="C_ORDER" name="C_ORDER" value="<?php echo e($C_ORDER); ?>" xml_data="false" column_name="C_ORDER">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label">Trạng thái</label>
                    <div class="col-md-6">
                        <div class="input-group checkbox">
                            <label><input type="checkbox" <?php echo e($checked); ?> id="C_STATUS" name="C_STATUS">Hoạt động</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary btn-flat" type="button"><?php echo e(Lang::get('System::Common.submit')); ?></button>
                <button type="input" class="btn btn-default" data-dismiss="modal"><?php echo e(Lang::get('System::Common.close')); ?></button>
            </div>
        </div>
    </div>
</form>