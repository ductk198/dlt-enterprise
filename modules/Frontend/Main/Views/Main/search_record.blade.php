  <?php $objInfor = $data[0][0];
  $arrWorks =  $data[1];
  ?>
<div class="col-md-12">
	<div class="main-tab">
    	<!-- Tab panes -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#record" aria-controls="record" role="tab" data-toggle="tab">Hồ sơ</a></li>
            <li role="presentation"><a href="#work" aria-controls="work" role="tab" data-toggle="tab">Tiến độ</a></li>
        </ul>
        <!-- Tab panes -->
    	<div class="tab-content">
	        <div role="tabpanel" class="tab-pane active" id="record">
		    	<fieldset class="fieldset">
		    		<legend><h4>THÔNG TIN HỒ SƠ</h4></legend>
					<table id="tb_list_record" class="table table-striped table-bordered dataTable no-footer">
				        <tr>
				        	<td>Tên TTHC</td>
				        	<td>{{ $objInfor->ten_tthc }}</td>
				        </tr>
				        <tr>
				        	<td>Mã hồ sơ</td>
				        	<td>{{ $objInfor->ma_ho_so }}</td>
				        </tr>
				        <tr>
				        	<td>Ngày tiếp nhận</td>
				        	<td>{{ $objInfor->ngay_tiep_nhan }}</td>
				        </tr>
				        <tr>
				        	<td>Ngày hẹn</td>
				        	<td>{{ $objInfor->ngay_tra_ket_qua }}</td>
				        </tr>
				        <tr>
				        	<td>Cán bộ xử lý</td>
				        	<td>{{ $objInfor->can_bo_thu_ly }}</td>
				        </tr>
				        <tr>
				        	<td>Trạng thái hồ sơ</td>
				        	<td>{{ $objInfor->trang_thai }}</td>
				        </tr>
			         </table>
			 	</fieldset>
				<fieldset class="fieldset">
			        <legend><h4>THÔNG TIN ĐỐI TƯỢNG ĐỀ NGHỊ</h4></legend>
			        <table id="tb_list_record" class="table table-striped table-bordered dataTable no-footer">
			        <tr>
			        	<td>Tên tổ chức cá nhân</td>
			        	<td>{{ $objInfor->ten_nguoi_nop }}</td>
			        </tr>
			        <tr>
			        	<td>Địa chỉ</td>
			        	<td>{{ $objInfor->dia_chi }}</td>
			        </tr>
			        <tr>
			        	<td>Hồ sơ bao gồm</td>
			        	<td></td>
			        </tr>
			         </table>
				</fieldset>
	        </div>
        	<div role="tabpanel" class="tab-pane" id="work">
	        	 <fieldset class="fieldset">
	   				<legend><h4>DANH SÁCH CÔNG VIỆC ĐÃ THỰC HIỆN</h4></legend>
			        <table id="tb_list_record" class="table table-striped table-bordered dataTable no-footer">
				        <tr>
				        	<th>Ngày thực hiện</th>
				        	<th>Công việc</th>
				        	<th>Nội dung</th>
				        	<th>Cán bộ thực hiện</th>
				        	<th>Đơn vị</th>
				        </tr>
				        @foreach ($arrWorks as $arrWork)
						    <tr>
						    	<td>{{ $arrWork->C_WORK_DATE }}</td>
						    	<td>{{ $arrWork->C_WORKTYPE_NAME }}</td>
						    	<td>{{ $arrWork->C_RESULT }}</td>
						    	<td>{{ $arrWork->C_POSITION_NAME }}</td>
						    	<td>{{ $arrWork->C_POSITION_NAME }}</td>
						    </tr>
						@endforeach
			        </table>
	 			</fieldset>
        	</div>
		</div>
	</div>
</div>