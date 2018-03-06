<form id="frmImportEnterprise" role="form" action="" method="POST">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">IMPORT ĐƠN VỊ</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <span class="radio text-right control-label required">Chọn file</span>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control input-md" type="file" id="file" name="file" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id='btn_submit_import' class="btn btn-primary btn-flat" onclick="JS_Enterprise.saveimport()" type="button">{{Lang::get('System::Common.submit')}}</button>
                <button type="input" class="btn btn-default" data-dismiss="modal">{{Lang::get('System::Common.close')}}</button>
            </div>
        </div>
    </div>
</form>
<style>
    .radio-container label.error{
        float: right;
    }
</style>