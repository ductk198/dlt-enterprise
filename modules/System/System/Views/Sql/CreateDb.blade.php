<form action="sql" method="get" id="frmCreateDb">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="modal-dialog modal-lg">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">{{$label}}</h4>
		</div>
		<div class="modal-body">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="row form-group">
							<h5> Đường dẫn: {{$path}}</h5>
						</div>
						<div class="row form-group" style="overflow: scroll; height: 250px;">
							<div class="col-md-12">
								<table id="table-data" class="griddata table table-bordered" width="100%" cellspacing="0" cellpadding="0">
									<colgroup><col width="5%"><col width="28%"><col width="5%"><col width="28%"><col width="5%"><col width="29%"></colgroup>
									<tbody>
										<tr class="header">
											<td align="center" width="20px"><input type="checkbox" id="checkall_process_per_group" name="checkall_process_per_group" onclick="checkallper(this, 'chk_item_id')">
											</td>
											<td colspan="5"><b>Chọn databases :</b></td>
										</tr>
										<tr>
											{{ '', $i = 0  }}
											@foreach ($alldbs as $database)
											<td align="center"><input type="checkbox" name="chk_item_id" id="chk_item_id" value="{{$database['code']}}"></td>
											<td class="data" align="left"><label style="text-align: left;" class="normal_label">{{$database['name']}}</label></td>
											@if(($i + 1) % 3 == 0)
										</tr>
										<tr>
											@endif
											{{ '', $i = $i + 1   }}
											@endforeach
										</tr>
										</tr>
									</tbody>
								</table> 
							</div>
						</div>
						<div id="zendcode" class="col-md-12">
							
						</div>
					</div>
				</div>
		</div>
	<div class="modal-footer">
		<button id='btn_update' class="btn btn-primary btn-flat" type="button">Sinh SQL</button>
		<button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
	 </div>	
</div>
</form>
<script>
function select_row_child(obj){
    var oTable = $(obj).parent().parent().parent();
    $(oTable).find('td').parent().removeClass('selected');
    $(oTable).find('td').parent().find('input[name="chk_item_id"]').prop('checked',false);
    $(obj).parent().addClass('selected');
    var attDisabled = $(obj).parent().find('input[name="chk_item_id"]').prop('disabled');
    if(typeof(attDisabled) === 'undefined' || attDisabled == ''){
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked',true);
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked','checked');
    }
}
</script>