/**
 * Mô tả về class ...
 *
 * @author ...
 */
function Js_Main(baseUrl,module,controller){
  this.module = module;
  this.baseUrl = baseUrl;
  this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
Js_Main.prototype.loadIndex = function(){
  var myClass = this;
  var oForm = 'form#frmMain_index';
  myClass.loadList(oForm);
}

/**
 * Hàm load các sử kiện cho màn hình khác
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_Main.prototype.loadevent = function(oForm){
  var myClass = this;
  
}

/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_Main.prototype.loadList = function(oForm){
  var myClass = this;
  var loadding = EfyLib.loadding();
  EfyLib.showmainloadding();
  var url = myClass.urlPath + '/loadList';
  var data = $(oForm).serialize();
  $.ajax({
      url: url,
      type: "POST",
      dataType: 'json',
      //cache: true,
      data:data,
      success: function(arrResult){
        loadding.go(100); 
      }
  });
}
