<form id="frmChangePassword" role="form" action="" method="POST" class="form-horizontal">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" id="urlupdate" value="{{ url('system/login/updateChangePassword') }}">
  <div class="modal-dialog modal-lg">
      <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">THAY ĐỔI MẬT KHẨU</h4>
      </div>
      <div class="modal-body">
        <div class="row form-group">
          <label class="control-label col-md-5 required">Mật khẩu cũ</label>
          <div class="col-md-4">
            <input class="form-control input-sm" name="old_password" value="" type="password">
          </div>
        </div>
        <div class="row form-group">
          <label class="control-label col-md-5 required">Mật khẩu mới</label>
          <div class="col-md-4">
            <input class="form-control input-sm" id="new_password" name="new_password" value="" type="password">
          </div>
        </div>
        <div class="row form-group">
          <label class="control-label col-md-5 required">Nhập lại mật khẩu</label>
          <div class="col-md-4">
            <input class="form-control input-sm" name="re_password" value="" type="password">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id='btn_update_password' onclick="updateChangePassword()" class="btn btn-primary btn-flat" type="button">Cập nhật</button>
        <button type="input" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript" src="{{ URL::asset('public/js/assets/jquery.validate.js') }}"></script>
<script>
  $('#frmChangePassword').validate({
  rules: {
    old_password: "required",
    new_password: "required",
    re_password: {required: true, equalTo: "#new_password"},
  },
  messages: {
    old_password: "Mật khẩu cũ không được để trống",
    new_password: "Mật khẩu mới không được để trống",
    re_password: {
              required: "Nhập lại mật khẩu không được để trống"
              ,equalTo: 'Mật khẩu không khớp'
    },
  }
  });
  function updateChangePassword() {
    // console.log(1);return false;
    var oForm = '#frmChangePassword';
    if($(oForm).valid()) {
      var loadding = EfyLib.loadding();
      var data = $(oForm).serialize();
      $.ajax({
          url: $("#urlupdate").val(),
          type: "POST",
          data: data,
          dataType: 'json',
          success: function(arrResult){
            if(arrResult['success']){
                $('#addmodal').modal('hide');
                EfyLib.alertMessage('success',arrResult['message']);
            }else{
                EfyLib.alertMessage('danger',arrResult['message']);
            }
          },
          error: function(arrResult) {
              EfyLib.alertMessage('warning',arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
          },
      }); 
    }
  }
</script>
<style>
#frmChangePassword .modal-dialog {
  margin:  30px auto;
}
</style>