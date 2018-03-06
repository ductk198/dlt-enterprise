<form id="frmCheckLogin" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Xác nhận lại mật khẩu</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input class="form-control input-md" type="password" id="txtpass" name="txtpass">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary btn-flat" type="button">{{Lang::get('System::Common.submit')}}</button>
                <button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>
    </div>
</form>