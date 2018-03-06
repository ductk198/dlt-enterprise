<form id="frmAddDepartment" role="form" action="" method="POST">
<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
<input class="form-control" name="parent_id" type="hidden" value="<?php echo e($parent_id); ?>">
<input class="form-control" name="id" type="hidden" value="<?php echo e($id); ?>">
<div class="modal-dialog modal-lg">
  	<!-- Modal content-->
  	<div class="modal-content">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  <h4 class="modal-title">CẬP NHẬT THÔNG TIN ĐƠN VỊ</h4>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<span class="radio col-md-2 text-right">Thuộc đơn vị</span>
					<div class="col-md-10">
						<input disabled class="form-control input-md" type="text" value="<?php echo $unitparent; ?>">
					</div>
				</div>
			</div>
		    <div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Cấp</span>
					<div class="col-md-8">
						<select class="form-control" id="group_unit" name="group_unit">
						    <option value="">-- Chọn cấp --</option>
						    <option <?php if($data['C_TYPE_GROUP'] == 'SO_NGANH'): ?> selected <?php endif; ?> value="SO_NGANH">Sở Ngành</option>
						    <option <?php if($data['C_TYPE_GROUP'] == 'QUAN_HUYEN'): ?> selected <?php endif; ?> value="QUAN_HUYEN">Quận Huyện</option>
					  	</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Đơn vị triển khai</span>
					<div class="col-md-8">
						<label><input <?php echo $check_dvtk; ?> type="checkbox" id="check_dvtk" name="check_dvtk">Đơn vị là đơn vị triển khai</label>
					</div>
				</div>
			</div>
			 <div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right control-label required">Mã đơn vị</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_CODE" name="C_CODE" value="<?php echo $data['C_CODE']; ?>" xml_data="false" column_name="C_CODE">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right control-label required">Tên đơn vị</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_NAME" name="C_NAME" value="<?php echo $data['C_NAME']; ?>" xml_data="false" column_name="C_NAME">
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<span class="radio col-md-2 text-right">Địa chỉ</span>
					<div class="col-md-10">
						<input class="form-control input-md" type="text" id="C_ADDRESS" name="C_ADDRESS" value="<?php echo $data['C_ADDRESS']; ?>" xml_data="false" column_name="C_ADDRESS" "="">
					</div>
				</div>
			</div>
			 <div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Số điện thoại</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_TEL" name="C_TEL" value="<?php echo $data['C_TEL']; ?>" xml_data="false" column_name="C_TEL">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Fax</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_FAX" name="C_FAX" value="<?php echo $data['C_FAX']; ?>" xml_data="false" column_name="C_FAX">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Website</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_WEB_SITE" name="C_WEB_SITE" value="<?php echo $data['C_WEB_SITE']; ?>" xml_data="false" column_name="C_WEB_SITE">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Email</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_EMAIL" name="C_EMAIL" value="<?php echo $data['C_EMAIL']; ?>" xml_data="false" column_name="C_EMAIL">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Trạng thái</span>
					<div class="col-md-8">
						<label><input type="checkbox" <?php if($data['C_STATUS'] == 'HOAT_DONG'): ?> checked="" <?php endif; ?>  id="C_STATUS" name="C_STATUS">Hoạt động</label>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Thứ tự hiển thị</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_ORDER" name="C_ORDER" value="<?php echo $data['C_ORDER']; ?>" xml_data="false" column_name="C_ORDER" style="width:20%">
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<label class="radio col-md-4"><i style="color:blue">Thông tin mẫu in</i></label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Đơn vị Chủ quản</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_REPORT_UNIT" name="C_REPORT_UNIT" value="<?php echo $data['C_REPORT_UNIT']; ?>" xml_data="false" column_name="C_REPORT_UNIT">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Người liên hệ</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_REPORT_CONTACT" name="C_REPORT_CONTACT" value="<?php echo $data['C_REPORT_CONTACT']; ?>" xml_data="false" column_name="C_REPORT_CONTACT">
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<span class="radio col-md-2 text-right">Địa danh</span>
					<div class="col-md-10">
						<input class="form-control input-md" type="text" id="C_REPORT_PLACE" name="C_REPORT_PLACE" value="<?php echo $data['C_REPORT_PLACE']; ?>" xml_data="false" column_name="C_REPORT_PLACE">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Tên viết hóa</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_REPORT_UPPERCASE" name="C_REPORT_UPPERCASE" value="<?php echo $data['C_REPORT_UPPERCASE']; ?>" xml_data="false" column_name="C_REPORT_UPPERCASE">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<span class="radio col-md-4 text-right">Tên viết thường</span>
					<div class="col-md-8">
						<input class="form-control input-md" type="text" id="C_REPORT_LOWERCASE" name="C_REPORT_LOWERCASE" value="<?php echo $data['C_REPORT_LOWERCASE']; ?>" xml_data="false" column_name="C_REPORT_LOWERCASE">
					</div>
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