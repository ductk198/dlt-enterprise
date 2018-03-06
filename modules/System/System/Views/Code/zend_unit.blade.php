<table id="GROUP_OWNERCODE" class="griddata table table-bordered" width="100%" cellspacing="0" cellpadding="0">
		<colgroup><col width="5%"><col width="28%"><col width="5%"><col width="28%"><col width="5%"><col width="29%"></colgroup>
		<tbody>
			<tr class="header">
				<td align="center"><input type="checkbox" name="checkall_process_per_group" onclick="checkallper(this,'GROUP_OWNERCODE')">
				</td>
				<td colspan="5" align="center"><b>Chọn đơn vị kế thừa</b></td>
			</tr>
		<tr>
		@php $i = 0  @endphp
		@foreach ($Units as $Unit)
				<td align="center"><input type="checkbox" class="GROUP_OWNERCODE" name="GROUP_OWNERCODE" id="GROUP_OWNERCODE{{$i}}" value="{{$Unit->C_CODE}}"></td>
				<td><label style="text-align: left;" class="normal_label" for="GROUP_OWNERCODE{{$i}}">{{$Unit->C_NAME}}</label></td>
				@if(($i + 1) % 3 == 0)
					</tr>
				<tr>
				@endif
					@php $i = $i + 1  @endphp
		@endforeach
		</tr>
	</tr>
	</tbody>
</table> 