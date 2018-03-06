<form id="frmImportUnit" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="<?php echo e(csrf_token()); ?>">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">IMPORT ĐƠN VỊ</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <span class="radio text-right control-label required">Chọn file</span>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control input-md" type="file" id="file" name="file" >
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <span class="radio col-md-12 text-right control-label required">Loại đơn vị</span>
                        </div>
                    </div>
                    <div class="col-md-8" style="margin-top: 7px">
                        <input type="radio" id="qhsn" name="unittype" checked value="QHSN">
                        <label for="qhsn">Quận huyện, Sở ngành</label> &nbsp; &nbsp; &nbsp;
                        <input type="radio" id="pxpb" name="unittype" value="PXPB">
                        <label for="pxpb">Phường xã, Phòng ban</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_submit_import' class="btn btn-primary btn-flat" onclick="Js_User.saveimport()" type="button"><?php echo e(Lang::get('System::Common.submit')); ?></button>
                <button type="input" class="btn btn-default" data-dismiss="modal"><?php echo e(Lang::get('System::Common.close')); ?></button>
            </div>
        </div>
    </div>
</form>
<style>
    .radio-container label.error{
        float: right;
    }
</style>