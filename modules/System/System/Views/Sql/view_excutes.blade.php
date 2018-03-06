<form action="sql" method="get" id="frmlist_index">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
  <section class="content-wrapper">
    <ol class="breadcrumb" ></ol>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row form-group" style="overflow: scroll; width: 1129px; height: 150px;">
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
                                @foreach ($data as $database)
                                <td align="center"><input type="checkbox" name="chk_item_id" id="chk_item_id" value="{{$database->name}}"></td>
                                <td class="data" align="left"><label style="text-align: left;" class="normal_label">{{$database->name}}</label></td>
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
            <div class="row form-group input-group-index">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-btn">
                        <button class="btn btn-primary btn-flat run" id="btn_run" data-loading-text="{{Lang::get('System::Common.search')}}..." type="button">Thực thi lệnh</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row form-group input-group-index">
                <h4>Câu lệnh sql :</h4>
                <textarea id="statesql" name="statesql" class="form-control" rows="5"></textarea>
            </div>
            <div class="row form-group input-group-index">
                <h4>Kết quả :</h4>
                <div id="result" style="overflow: scroll; width: 1129px; height: 150px;"></div>
            </div>
		</div>
    </div>
</section>
</form>
<div class="modal fade" id="addListModal" role="dialog">
</div>
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