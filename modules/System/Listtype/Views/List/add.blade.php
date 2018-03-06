<form id="frmAddListType" role="form" action="" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="listtype" value="{{ $listtype_id }}">
    <input type="hidden" name="idlist" value="{{$idlist}}">
    <input type="hidden" name="oldorder" value="{{$oldorder}}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{Lang::get('System::Listtype.add')}}</h4>
            </div>
            <div class="modal-body">
                {!! $strrHTML !!}
            </div>
            <div class="modal-footer">
                <button id='btn_update' class="btn btn-primary btn-flat" type="button">{{Lang::get('System::Common.submit')}}</button>
                <button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>
    </div>
</form>