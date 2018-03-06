function Js_Menu(baseUrl,module,action){
  $( "#main_module" ).attr( "class","active" );
  $( "#child_menu" ).attr( "class","active" );
  this.module = module;
  this.baseUrl = baseUrl;
  this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
}
// Load su kien tren man hinh index
Js_Menu.prototype.loadIndex = function(){
  var myClass = this;
  var oForm = 'form#frmMenu_index';
  //myClass.loadList(oForm);
  $('#menu-nestable').nestable({
        group: 1
    });
  $('#menu-nestable').on('change', function() {
      myClass.update_hierarchy(oForm);
  });
  $("#tab-modules .addModuleMenu").on("click", function() {
      var module_id = $(this).attr("module_id");
      myClass.addModuleToMenu(oForm,module_id);
   });
  $(oForm).find('#btn_add').click(function(){
    myClass.add(oForm);
  });
  $(oForm).find('#export_cache').click(function(){
    myClass.export(oForm);
  });
  $("#menu-nestable .editMenuBtn").on("click", function() {
    myClass.edit(oForm,$(this).parent().parent().attr('data-id'));
  });
  $("#menu-nestable .deleteMenuBtn").on("click", function() {
    myClass.delete(oForm,$(this).parent().parent().attr('data-id'));
  });
}
// Load su kien tren cac minh khac
Js_Menu.prototype.loadevent = function(oForm){
  var myClass = this;
  $(oForm).find('#btn_update').click(function(){
    myClass.update(oForm);
  });
}
// Lay du lieu cho man hinh danh sach
Js_Menu.prototype.loadList = function(oForm){
  
  var myClass = this;
  var currentPage =1;
  var perPage =15;
  var url = myClass.urlPath + '/loadList';
  var dirxml = myClass.baseUrl + '/xml/Backend/Menu/Menu.xml';
  if(typeof(Tablelist) === 'undefined'){
        Tablelist = new TableXml(dirxml);
  }
  var data = $(oForm).serialize();
  data +='&currentPage=' + currentPage;
  data +='&perPage=' + perPage;
  $.ajax({
      url: url,
      type: "POST",
      dataType: 'json',
      //cache: true,
      data:data,
      success: function(arrResult){
        successLoad();
        
      }
  }); 
}

Js_Menu.prototype.addModuleToMenu = function(oForm,module_id){
  var myClass = this;
  var url = myClass.urlPath + '/save';
    $.ajax({
      url: url,
      method: 'POST',
      data: {
        type: 'module',
        module_id: module_id,
        _token: $("input[name=_token]").val()
      },
      success: function( data ) {
        // console.log(data);
        window.location.reload();
      }
    });
}

Js_Menu.prototype.update_hierarchy = function(oForm,module_id){
  var myClass = this;
  var jsonData = $('#menu-nestable').nestable('serialize');
  var url = myClass.urlPath + '/update_hierarchy'; 
  $.ajax({
      url: url,
      method: 'POST',
      data: {
          jsonData: jsonData,
          _token: $("input[name=_token]").val()
      },
      success: function( data ) {
          // console.log(data);
      }
  });
}

// Them menu
Js_Menu.prototype.add = function(oForm){
  var url = this.urlPath + '/add';
    var myClass = this; 
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data:data,
        success: function(arrResult){
           $('#addMenu').html(arrResult);   
           $('#addMenu').modal('show');
           var oForm = 'form#frmAddMenu';
           myClass.loadevent(oForm);
        }
    }); 
}

// Them menu
Js_Menu.prototype.export = function(oForm){
  var url = this.urlPath + '/export';
    var myClass = this; 
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data:data,
        success: function(arrResult){  
          if(arrResult['success']){
              DLTLib.alertMessage('success',arrResult['message']);
          }else{
              DLTLib.alertMessage('danger',arrResult['message']);
          }
      },
      error: function(arrResult) {
          DLTLib.alertMessage('warning',arrResult.responseJSON,6000);
      }
    }); 
}

Js_Menu.prototype.edit = function(oForm,id){
  var url = this.urlPath + '/edit';
    var myClass = this; 
    var data = $(oForm).serialize();
         data +='&menu_id=' + id;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data:data,
        success: function(arrResult){
           $('#addMenu').html(arrResult);   
           $('#addMenu').modal('show');
           var oForm = 'form#frmAddMenu';
           myClass.loadevent(oForm);
        }
    }); 
}

// Cap nhat menu
Js_Menu.prototype.update = function(oForm){
  var url = this.urlPath + '/update';
  var myClass = this;
  var data = $(oForm).serialize();
  $.ajax({
      url: url,
      type: "POST",
      dataType: 'json',
      //cache: true,
      data:data,
      success: function(arrResult){  
          window.location.reload();
      },
      error: function(arrResult) {
          AlertMessage('warning',arrResult.responseJSON,6000);
      }
  }); 
}

Js_Menu.prototype.delete = function(oForm,id){
  var url = this.urlPath + '/delete';
  var myClass = this;
  var data = $(oForm).serialize();
  data +='&menu_id=' + id;
  $.ajax({
      url: url,
      type: "POST",
      dataType: 'json',
      //cache: true,
      data:data,
      success: function(arrResult){  
          window.location.reload();
      },
      error: function(arrResult) {
          AlertMessage('warning',arrResult.responseJSON,6000);
      }
  }); 
}