<form id="frmAddPositionType" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" name="PK_DLT_ENTERPRISE" value="<?php echo e($arrSingle['PK_DLT_ENTERPRISE']); ?>">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo e(Lang::get('System::Position.addgroup')); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Mã số thuế</label>
                    <div class="col-md-9">
                        <input class="form-control input-md" type="text" id="MASOTHUE" name="MASOTHUE" value="<?php echo e($arrSingle['MASOTHUE']); ?>">
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Tên công ty</label>
                    <div class="col-md-9">
                        <input class="form-control input-md" type="text" id="TENCONGTY" name="TENCONGTY" value="<?php echo e($arrSingle['TENCONGTY']); ?>" >
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Tên giao dịch</label>
                    <div class="col-md-9">
                        <input class="form-control input-md" type="text" id="TENGIAODICH" name="TENGIAODICH" value="<?php echo e($arrSingle['TENGIAODICH']); ?>" >
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Ngày cấp</label>
                    <div class="col-md-4">
                        <input class="form-control input-md" type="text" id="NGAYCAP" name="NGAYCAP" value="<?php echo e($arrSingle['NGAYCAP']); ?>" >
                    </div>
                    <label class="col-md-1 control-label">Tỉnh/TP</label>
                    <div class="col-md-4">
                        <select class="form-control input-md chzn-select" id="TENTINH" name="TENTINH" >
                            <?php 
                                foreach($arrTinh as $key => $value){
                                    echo '<option value"'.$value->C_CODE.'">'.$value->C_NAME.'</option>';
                                }
                             ?>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Địa chỉ trụ sở</label>
                    <div class="col-md-9">
                        <input class="form-control input-md" type="text" id="DIACHITRUSOCHINH" name="DIACHITRUSOCHINH" value="<?php echo e($arrSingle['DIACHITRUSOCHINH']); ?>" >
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Số điện thoại</label>
                    <div class="col-md-4">
                        <input class="form-control input-md" type="text" id="SODIENTHOAI" name="SODIENTHOAI" value="<?php echo e($arrSingle['SODIENTHOAI']); ?>" >
                    </div>
                    <label class="col-md-1 control-label required">Email</label>
                    <div class="col-md-4">
                        <input class="form-control input-md" type="text" id="EMAIL" name="EMAIL" value="<?php echo e($arrSingle['EMAIL']); ?>" >
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Người đại diện</label>
                    <div class="col-md-9">
                        <input class="form-control input-md" type="text" id="NGUOIDAIDIEN" name="NGUOIDAIDIEN" value="<?php echo e($arrSingle['NGUOIDAIDIEN']); ?>" >
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Địa chỉ người đại diện</label>
                    <div class="col-md-9">
                        <input class="form-control input-md" type="text" id="DIACHINGUOIDAIDIEN" name="DIACHINGUOIDAIDIEN" value="<?php echo e($arrSingle['DIACHINGUOIDAIDIEN']); ?>" >
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Ngành nghề chính</label>
                    <div class="col-md-9">
                        <textarea class="form-control input-md" type="text" id="NGANHNGHECHINH" name="NGANHNGHECHINH"><?php echo e($arrSingle['NGANHNGHECHINH']); ?></textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 control-label required">Lĩnh vực kinh tế</label>
                    <div class="col-md-9">
                        <input class="form-control input-md" type="text" id="LINHVUCKINHTE" name="LINHVUCKINHTE" value="<?php echo e($arrSingle['LINHVUCKINHTE']); ?>" >
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