@extends('System.layouts.index')
@section('content')
<!-- /.content --> 
<script type="text/javascript">
    var arrJsCss = $.parseJSON('<?php echo $stringJsCss; ?>');
    DLTLib.loadFileJsCss(arrJsCss);
</script>
<form action="index" method="POST" id="frmlist_index">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<input type="hidden" id="_filexml" value="danh_sach_don_vi_trien_khai.xml">
  <section class="content-wrapper">
    <ol class="breadcrumb" >
        <li><a role="button" onclick="LoadModul('{{ url("admin/list") }}','list');"><i class="fa fa-list"></i> {{Lang::get('System::Modul.listobject')}}</a></li>
        <li class="active">{{Lang::get('System::modul.list_listobject')}}</li>
    </ol>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row form-group input-group-index">
				<div class="col-md-6 form-group">
				   <div class="row ">
						<label class="control-label col-md-3">Chọn đơn vị</label>
						<div class="col-md-8">
							<select id="Units" name="Units" class="form-control input-sm chzn-select" message="">
								<option value="">-- Tất cả đơn vị --</option>
								@foreach ($Units as $Unit)
								<option value="{{ $Unit->C_CODE }}">{{ $Unit->C_NAME }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-6 form-group">
					<div class="row ">
						<label class="col-md-4 control-label">Chọn danh mục</label>
						<div class="col-md-8">
							<select class="form-control input-sm chzn-select" name="listtype" id="listtype">
								@foreach($arrListTypes as $arrListType)
									@if( $arrListType['C_NAME'])
										<option value="{{$arrListType['PK_LISTTYPE']}}">{{$arrListType['C_NAME']}}</option>
									@endif
								@endforeach
							</select>
						</div>
					</div>
                </div>
                <div class="col-md-7">
                    <div class="input-group input-group-sm">
                        <input name="search" class="form-control" type="text">
                        <span class="input-group-btn">
                        <button class="btn btn-primary btn-flat search" id="btn_search" data-loading-text="{{Lang::get('System::Common.search')}}..." type="button">{{Lang::get('System::Common.search')}}</button>
                        </span>
                    </div>
                </div>
                
            </div>
            <div class="row form-group input-group-index">
                <div class="pull-right">
                    <button class="btn btn-primary btn-flat" id="btn_add" type="button" data-toggle="tooltip"  data-original-title="{{Lang::get('System::Common.add')}}"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-success btn-flat" id="btn_edit" type="button" data-toggle="tooltip"  data-original-title="{{Lang::get('System::Common.edit')}}"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger" id="btn_delete" type="button" data-toggle="confirmation" data-btn-ok-label="{{Lang::get('System::Common.delete')}}" data-btn-ok-icon="fa fa-trash-o" data-btn-ok-class="btn-danger" data-btn-cancel-label="{{Lang::get('System::Common.close')}}" data-btn-cancel-icon="fa fa-times" data-btn-cancel-class="btn-default" data-placement="left" data-title="{{Lang::get('System::Common.delete_title')}}"><i class="fa fa-trash-o"></i></button>
                    <!-- <button class="btn btn-default" id="btn_export_cache" type="button" data-toggle="tooltip" data-original-title="{{Lang::get('System::Common.export_cache')}}"> -->
                    <!-- </button> -->
                </div>
            </div>
            <!-- Màn hình danh sách -->
            <div class="row" id="table-container"></div>
            <!-- Phân trang dữ liệu -->
            <div class="row" id="pagination"></div>
		</div>
    </div>
</section>
</form>
<!-- Hien thi modal -->
<div class="modal fade" id="addListModal" role="dialog">
</div>
<script type="text/javascript">
    var baseUrl = '{{ url("") }}';
    var Js_List = new Js_List(baseUrl,'system/listtype','list');
    jQuery(document).ready(function($) {
        Js_List.loadIndex();
})
</script>
@endsection

