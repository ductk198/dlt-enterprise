<form id="frmUploadFile" role="form" action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="_token" id='_token' value="<?php echo e(csrf_token()); ?>">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Tải lên file</h4>
			</div>
			<div class="row form-group" style="margin-left:100px">
				<p id="msg"></p>
				<input type="file" name="files[]" id="multiFiles" multiple="">
				<br>   
			</div>
			<div class="modal-footer">
				<button id='Upload' class="btn btn-primary btn-flat" type="button"><?php echo e(Lang::get('System::Common.submit')); ?></button>
				<button type="input" class="btn btn-default" data-dismiss="modal"><?php echo e(Lang::get('System::Common.close')); ?></button>
			</div>
		</div>
	</div>
</form>