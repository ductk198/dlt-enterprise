<form action="" method="POST" id="frmExport_modules">
<input type="hidden" name="_token" id='_token' value="{{ csrf_token() }}">
<input type="hidden" id="_filexml" value="danh_sach_don_vi_trien_khai.xml">
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">CẬP NHẬT THÔNG TIN NGƯỜI DÙNG</h4>
		</div>
		<div class="modal-body">
			<div class="row form-group input-group-index">
                <label class="col-md-3 control-label required">Chọn đơn gốc</label>
                <div class="col-md-6">
                    <select class="form-control" name="C_TYPE_UNIT" id="C_TYPE_UNIT">
                        <option value="17">Phiên bản sở nghành ( Thanh tra thành phố - 17)</option>
						<option value="20">Phiên bản quận huyện (Quận ba đình - 20)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12" id="zend_unit"></div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-flat" id="btn_export" type="button">Xuất module</button>
				<button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
			 </div>
		</div>
	</div>
</div>
</form>

