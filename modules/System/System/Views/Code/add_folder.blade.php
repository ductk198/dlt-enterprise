<form action="" method="POST" id="frmAdd_folder">
<input type="hidden" name="_token" id='_token' value="{{ csrf_token() }}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">CẬP NHẬT THÔNG TIN NGƯỜI DÙNG</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<h4>Tên folder :</h4>
					<input type="text" id="name_folder" placeholder="Tên thư mục" value="" class="name form-control" required />
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary btn-flat" id="btn_export" type="button">Xuất module</button>
					<button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
				 </div>
			</div>
		</div>
	</div>
</form>

