<style>
#code_1 {
	max-height: 100%;
    height: 510px;
	    overflow-x: hidden;
    overflow-y: auto;
}
</style>
<form id="frmUpdateFile" role="form" action="" method="POST">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" name="path_file" value="{{ $path_file }}">
<div class="modal-dialog modal-hg">
	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">CHỈNH SỬA FILE MÃ NGUỒN</h4>
		</div>
		<div class="modal-body">
			<div  class="code" id="code_1" data-ace-editor-id="1" data-ace-editor-allow-execution="true" {{$mode}}>
			<pre class="editor" id="code_editor_1" >{{ $content }}</pre>
			</div>
		</div>
	<div class="modal-footer">
		<button id='btn_update' class="btn btn-primary btn-flat" type="button">Lưu lại</button>
		<button id='btn_update_close' class="btn btn-primary btn-flat" type="button">Lưu & đóng lại</button>
		<button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
	 </div>
  </div>
</div>
</form>