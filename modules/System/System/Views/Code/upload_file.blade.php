<form id="frmUploadFile" role="form" action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="_token" id='_token' value="{{ csrf_token() }}">
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
				<br> {{--
				<input class="button" id="btnUpload" name="btnUpload" type="submit" value="Upload" /> --}} {{--
				<input class="button" id="btnUpload" name="btnUpload" type="button" value="Upload" /> --}} {{--
				<button id="Upload" type="button">Upload</button> --}}
			</div>
			<div class="modal-footer">
				<button id='Upload' class="btn btn-primary btn-flat" type="button">{{Lang::get('System::Common.submit')}}</button>
				<button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
			</div>
		</div>
	</div>
</form>