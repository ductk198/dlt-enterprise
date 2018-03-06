<form id="frmAddPositionType" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="positiontype" value="">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{Lang::get('System::Position.addgroup')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Mã chức vụ</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span><input class="form-control input-md" type="text" id="C_CODE" name="C_CODE" value="" xml_data="false" column_name="C_CODE">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Tên nhóm chức vụ</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span><input class="form-control input-md" type="text" id="C_NAME" name="C_NAME" value="" xml_data="false" column_name="C_NAME">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label required">Số thứ tự</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-barcode"></i></span><input class="form-control input-md" type="text" id="C_ORDER" name="C_ORDER" value="{{$order}}" xml_data="false" column_name="C_ORDER">
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-3 control-label">Trạng thái</label>
                    <div class="col-md-6">
                        <div class="input-group checkbox">
                            <label><input type="checkbox" checked="" id="C_STATUS" name="C_STATUS">Hoạt động</label>
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