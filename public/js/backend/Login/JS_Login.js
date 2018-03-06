$(function() {

  $("#btn_submit").click( function()
     {
       if($("form#frm_login").valid()) {
          $("form#frm_login").submit();
       }
     }
  );

  $('#username').keypress(function (e) {
    if (e.which == 13) {
      if($("form#frm_login").valid()) {
          $("form#frm_login").submit();
       }
    }
  });

  $('#password').keypress(function (e) {
    if (e.which == 13) {
      if($("form#frm_login").valid()) {
          $("form#frm_login").submit();
       }
    }
  });

  // validate signup form on keyup and submit
  $("form#frm_login").validate({
    rules: {
      username: "required",
      password: "required",
      username: {
        required: true,
        //minlength: 2
      },
      password: {
        required: true,
       // minlength: 1
      }
    },
    messages: {
      username: {
        required: "Chưa nhập tên đăng nhập!",
        minlength: "Tên đăng nhập ít nhất phải trên 2 ký tự!"
      },
      password: {
        required: "Chưa nhập mật khẩu!",
        minlength: "Mật khẩu ít nhất chứa 6 ký tự!"
      }
    }
  });


});