<form id="frmAddListType" role="form" action="" method="POST">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input class="form-control" name="listtype_xml" type="hidden" value="{{$data['listtype_xml']}}">
<input class="form-control" name="listtype_id" type="hidden" value="{{$data['id']}}">
<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">{{Lang::get('System::Listtype.add')}}</h4>
		</div>
		<div class="modal-body">
            {!! $data['strHTML'] !!}
			<div class="row form-group">
				<div class="col-md-12">
					<table id="GROUP_OWNERCODE" class="griddata table table-bordered" width="100%" cellspacing="0" cellpadding="0">
							<colgroup><col width="5%"><col width="28%"><col width="5%"><col width="28%"><col width="5%"><col width="29%"></colgroup>
							<tbody>
								<tr class="header">
									<td align="center"><input type="checkbox" name="checkall_process_per_group" onclick="checkallper(this,'GROUP_OWNERCODE')">
									</td>
									<td colspan="5" align="center"><b>Danh sách đơn vị sử dụng</b></td>
								</tr>
							<tr>
							{{ '', $i = 0  }}
							@foreach ($Units as $Unit)
								@if (strpos($data['ownercode'], $Unit->C_CODE) !== false)
									{{ '', $checked = 'checked'  }}
								@else
									{{ '', $checked = ''  }}
								@endif
								<td align="center"><input type="checkbox" {{$checked}}  class="GROUP_OWNERCODE" name="GROUP_OWNERCODE" id="GROUP_OWNERCODE{{$i}}" value="{{$Unit->C_CODE}}"></td>
								<td><label style="text-align: left;" class="normal_label" for="GROUP_OWNERCODE{{$i}}">{{$Unit->C_NAME}}</label></td>
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
		</div>
		<div class="modal-footer">
			<button id='btn_update' class="btn btn-primary btn-flat" type="button">{{Lang::get('System::Common.submit')}}</button>
			<button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
		</div>
	</div>
</div>
</form>