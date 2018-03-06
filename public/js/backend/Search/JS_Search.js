/*
* Creater: Duclt
* Date:01/12/2016#
* Idea: Lop xu ly lien quan den loai danh muc#
*/
function Js_Search(baseUrl,module,controller){
  this.module = module;
  this.baseUrl = baseUrl;
  this.controller = controller;
  this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}
// Load su kien tren man hinh index
Js_Search.prototype.loadIndex = function(){
	 daterangepicker($('input[name="daterange"]'));
	var myClass = this;
    var oForm = 'form#frmSearch_record';
    $(oForm).find('#btn_search').click(function(){
    myClass.loadlist(oForm);
  });
}

// Load su kien tren cac minh khac
Js_Search.prototype.loadlist = function(oForm){
  var myClass = this;
  var url = myClass.urlPath + '/loadList';
  var data = $(oForm).serialize();
    data +='&currentUnit=' + this.value;
      $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data:data,
        success: function(arrResult){
          successLoad();
          $('#infor_record').html(arrResult);
        }
    });
}
