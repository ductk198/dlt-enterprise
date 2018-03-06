function Js_Sql(baseUrl, module, action) {
    // check side bar
    this.objTree = $("#jstree-tree");
    $("#main_system").attr("class", "active");
    $("#child_sql").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
}
// load su kien tren man hinh index
Js_Sql.prototype.loadIndex = function () {
    $(document).ajaxSend(function () {
        DLTLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        DLTLib.successLoadImage();
    });
    var myClass = this;
    var oForm = 'form#frmlist_index';
    myClass.loadList(oForm, false);

    $(oForm).find('#btn_run').click(function () {
        myClass.run(oForm);
    });

    $('#btn-new-query').click(function () {
        myClass.new_query(oForm);
    });

    $('#btn-excute').click(function () {
        myClass.excute(oForm);
    });

    $('#btn-excutes').click(function () {
        myClass.view_excutes(oForm);
    });

    $('#hide-database').click(function () {
        myClass.hide_database(oForm);
    });

    jQuery(document).ready(function ($) {
        jQuery('div[data-ace-editor-id]').each(function() {
            new PHPEditor(this);
        });
    })

    $(".search-input").keyup(function() {

        var searchString = $(this).val();
        var objTree = myClass.objTree;
        objTree.jstree('search', searchString);
    });
}

Js_Sql.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}

Js_Sql.prototype.loadList = function (oForm, checksearch) {
    var myClass = this;
      var objTree = myClass.objTree;
      var url = myClass.urlPath +'/getall';
      objTree.on('changed.jstree', function (e, data) {
            var node;
            for(i = 0, j = data.selected.length; i < j; i++) {
                node = data.instance.get_node(data.selected[i]);
            }
            if(node){
                var type = node.original.type;
                if(type == 'database'){
                    var db_name = node.original.text;
                    $("#select_db").val(db_name);
                }
                objTree.jstree('create_node', node);
            }
      }).jstree({
          "plugins": ["contextmenu","json_data", "dnd", "search"],
          'core': { 
              'data': {
                  'url': function (node) {
                      return url + '?root=' + node.parents.length;
                  },
                  "data" : function (node) {
                      return { "id" : node.id };
                  },
                  "success": function(node) {
                  }, 
                  'dataType': "json"
              }
          },
          'contextmenu': {
              'select_node': false ,
              'items': myClass.rightclick
          },
          "search": {
                "case_insensitive": true,
                "show_only_matches" : true
           }
      });
}

/**
 * click object node
 */
Js_Sql.prototype.rightclick = function (node) {
    var type = node.original.type;
    var gen_db = {
        gen_db: {
            label: 'Create database',
            icon: 'fa fa-plus',
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                Js_Sql.zendDb(node);
            }
        },
        restore_db: {
            label: 'Restore Database By Temp',
            icon: 'fa fa-reply',
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                Js_Sql.restoreDb(node);
            }
        }
    }
    var db_backup = {
        createDepartment: {
            label: 'Sao lưu db',
            icon: 'fa fa-book',
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                Js_Sql.zendList(node);
            }
        }
    }
    var db_openscript = {
        open: {
            label: 'Xem',
            icon: 'fa fa-eye',
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                Js_Sql.open_script(node);
            }
        },
        coppy: {
            label: 'Sap chép',
            icon: 'fa fa-clone',
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                Js_Sql.copyToClipboard(node.text);
            }
        }
    }
    var db_opentable = {
        open: {
            label: 'Xem',
            icon: 'fa fa-eye',
            //action: self.addinfo(node.id,'add')copyToClipboard
            action: function (obj) {
                Js_Sql.open_table(node);
            }
        },
        coppy: {
            label: 'Sap chép',
            icon: 'fa fa-clone',
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                Js_Sql.copyToClipboard(node.text);
            }
        }
    }
    var db_other = {
        refresh: {
            label: 'Tải lại',
            icon: 'fa fa-refresh',
            //action: self.addinfo(node.id,'add')
            action: function (obj) {
                Js_Sql.db_refresh(node);
            }
        }
    }
    if(node.parent == "#"){
        var returns = gen_db;
    }
    /**if(type == 'database'){
        var returns = db_backup;
    }**/
    if(type == 'other'){
        var returns = db_other;
    }
    if(type == 'table'){
        var returns = db_opentable;
    }
    if(type == 'store' || type == 'trigger' || type == 'function'){
        var returns = db_openscript;
    }
    
    return returns;
}

Js_Sql.prototype.zendDb = function (node) {
    var myClass = this;
    var url = myClass.urlPath + '/zendDb';
    $.ajax({
        url: url,
        type: "get",
        success: function (arrResult) {
            $("#openDialog").html(arrResult);
            $('#openDialog').modal('show');
            var oForm = 'form#frmCreateDb';
            $(oForm).find('#btn_update').click(function () {
                myClass.create_db(oForm);
            });
        }
    });
}

Js_Sql.prototype.restoreDb = function (node) {
    var myClass = this;
    var url = myClass.urlPath + '/restoreDb';
    $.ajax({
        url: url,
        type: "get",
        success: function (arrResult) {
            $("#openDialog").html(arrResult);
            $('#openDialog').modal('show');
            var oForm = 'form#frmCreateDb';
            $(oForm).find('#btn_update').click(function () {
                myClass.update_restoreDb(oForm);
            });
        }
    });
}

Js_Sql.prototype.update_restoreDb = function (oForm) {
    var myClass = this;
    var url = myClass.urlPath + '/update_restoreDb';
    listdb = '';
    $('[name="chk_item_id"]:checked').each(function (index) {
        listdb += $(this).val() + ",";
    });
    if(listdb !== ""){
        var data = {   
        listdb: listdb,
        _token : $("input[name=_token]").val()
        };
        $.ajax({
            url: url,
            type: "Post",
            data: data,
            success: function (arrResult) {
                $("#zendcode").html(arrResult['data']);
            }
        });
    }else{
         DLTLib.alertMessage('danger', "Một số trường không được để trống!", 6000);
    }
}

Js_Sql.prototype.create_db = function (oForm) {
    var myClass = this;
    var url = myClass.urlPath + '/create_db';
    listdb = '';
    $('[name="chk_item_id"]:checked').each(function (index) {
        listdb += $(this).val() + ",";
    });
    if(listdb !== ""){
        var data = {   
        listdb: listdb,
        _token : $("input[name=_token]").val()
        };
        $.ajax({
            url: url,
            type: "Post",
            data: data,
            success: function (arrResult) {
                $('#openDialog').modal('hide');
                DLTLib.alertMessage('danger', "Tạo thành công", 6000);
            }
        });
    }else{
         DLTLib.alertMessage('danger', "Một số trường không được để trống!", 6000);
    }
}

Js_Sql.prototype.db_refresh = function (node) {
    var myClass = this;
    var objTree = myClass.objTree;
    var id = node.id;
    var node1 = objTree.jstree(true).get_node(id);
    // check if parent 
    node1.state.loaded = false;
    objTree.jstree('create_node', node1);
}

Js_Sql.prototype.open_script = function (node) {
    var myClass = this;
    var url = myClass.urlPath + '/open_script';
    var type = node.original.type;
    var id = node.id;
    var dbname = node.original.dbname;
    data = 'type=' + type;
    data += '&id=' + id;
    data += '&dbname=' + dbname;
    $.ajax({
        url: url,
        type: "Get",
        data: data,
        success: function (arrResult) {
            $("#zend_code").html(arrResult);
            jQuery(document).ready(function ($) {
                jQuery('div[data-ace-editor-id]').each(function() {
                    new PHPEditor(this);
                });
            })
            $("#zend_code").css("height", "560px");
            $("#zend_result").hide();
        }
    });
}

Js_Sql.prototype.open_table = function (node) {
    var myClass = this;
    var url = myClass.urlPath + '/open_table';
    var type = node.original.type;
    var id = node.id;
    var dbname = node.original.dbname;
    data = 'type=' + type;
    data += '&id=' + id;
    data += '&dbname=' + dbname;
    $.ajax({
        url: url,
        type: "Get",
        data: data,
        success: function (arrResult) {
            $("#zend_code").html(arrResult);
            jQuery(document).ready(function ($) {
                jQuery('div[data-ace-editor-id]').each(function() {
                    new PHPEditor(this);
                });
            })
            $("#zend_code").css("height", "560px");
            $("#zend_result").hide();
        }
    });
}

Js_Sql.prototype.view_excutes = function (form) {
    var myClass = this;
    var url = myClass.urlPath + '/view_excutes';
    var editor = PHPEditor.get(1);
    var code = editor.editor.getValue();
    if(code !== ""){
        var data = {
        code: code,
        _token : $("input[name=_token]").val()
        };
        $.ajax({
            url: url,
            type: "Post",
            data: data,
            success: function (arrResult) {
                $("#openDialog").html(arrResult);
                $('#openDialog').modal('show');
                var oForm = 'form#frmExcutedSql';
                $(oForm).find('#btn_update').click(function () {
                    myClass.excutes(oForm);
                });
            }
        });
    }else{
         DLTLib.alertMessage('danger', "Script SQl không được để trống!", 6000);
    }
}

Js_Sql.prototype.excutes = function (form) {
    var myClass = this;
    var url = myClass.urlPath + '/excutes';
    listdb = '';
    $('[name="chk_item_id"]:checked').each(function (index) {
        listdb += $(this).val() + ",";
    });
    var code = $("#txt_code").val();
    if(code !== "" && listdb !== ""){
        var data = {
        code: code,    
        listdb: listdb,
        _token : $("input[name=_token]").val()
        };
        $.ajax({
            url: url,
            type: "Post",
            data: data,
            success: function (arrResult) {
                $('#openDialog').modal('hide');
                DLTLib.alertMessage('danger', arrResult.result.data, 6000);
            }
        });
    }else{
         DLTLib.alertMessage('danger', "Một số trường không được để trống!", 6000);
    }
}

Js_Sql.prototype.new_query = function (node) {
    var myClass = this;
    var url = myClass.urlPath + '/new_query';
    data = [];
    $.ajax({
        url: url,
        type: "Get",
        data: data,
        success: function (arrResult) {
            $("#zend_code").html(arrResult);
            jQuery(document).ready(function ($) {
                jQuery('div[data-ace-editor-id]').each(function() {
                    new PHPEditor(this);
                });
            })
            $("#zend_code").css("height", "560px");
            $("#zend_result").hide();
            $(".ace_identifier").select();
            
        }
    });
}

Js_Sql.prototype.excute = function (oForm) {
    var url = this.urlPath + '/excute';
    var myClass = this;
    var editor = PHPEditor.get(1);
    var code = editor.editor.getValue();
    var db =  $("#select_db").val();
    if(db !== ''){
        var data = {
        code: code,
        _token : $("input[name=_token]").val(),
        db : $("#select_db").val()
        };
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: data,
            success: function (arrResult) {
                if (arrResult['success']) {
                    var html = myClass.zendhtml(arrResult['result']);
                    $("#zend_code").css("height", "260px");
                    $("#zend_result").html(html);
                    $("#zend_result").show();
                } else {
                    DLTLib.alertMessage('danger', arrResult['message']);
                }
            }
        });
    }else{
         DLTLib.alertMessage('danger', "Bạn chưa chọn database cần thực thi");
    }
}

Js_Sql.prototype.zendhtml = function (data_result) {
    var html = '';
    var myClass = this;
    var check_error = data_result['message'];
    if(check_error == 'error'){
        html += '<ul style="background-color: #FAFAFA;" class="nav nav-tabs">';
        html += '<li class="active"><a href="#">Kết quả</a></li>';
        html += '</ul><br>';
        html += '<p>';
        html += data_result['data'];
        html += '</p>';
    }else{
        var data = data_result['data'];
        var total =  data.length;
        html += '<ul style="background-color: #FAFAFA;" class="nav nav-tabs">';
        html += '<li class="active"><a href="#">Kết quả</a></li>';
        html += '<li><a disabled href="#">Danh sách có '+total+' rows</a></li>';
        html += '</ul>';
        html += '<table class="table-bordered table-striped table-hover">';
        for (var i in data) {
            html += '<tr>';
            if (i == 0) {
                html += '<th class="success">';
                html += '</th>';
                for (var j in data[i]) {
                        html += '<th class="success">';
                        html += j;
                        html += '</th>';
                }
                html += '</tr><tr>';
                html += '<td>1</td>';
                for (var j in data[i]) {
                    html += '<td>';
                    if(myClass.isObject(data[i][j])){
                        html += '<input readonly  style=" width: auto; background-color: white" class="form-control" value="'+data[i][j].date+'">';
                    }else{
                       html += '<input readonly  style=" width: auto; background-color: white" class="form-control" value="'+data[i][j]+'">';  
                    }
                    html += '</td>';
                }
                html += '</tr>';
            }else{
                var stt = Number(i) + Number(1);
                html += '<td>'+stt+'</td>';
                for (var j in data[i]) {
                    html += '<td>';
                    if(myClass.isObject(data[i][j])){
                        html += '<input readonly  style=" width: auto; background-color: white" class="form-control" value="'+data[i][j].date+'">';
                    }else{
                       html += '<input readonly  style=" width: auto; background-color: white" class="form-control" value="'+data[i][j]+'">';  
                    }
                    html += '</td>';
                }
            }
            html += '</tr>';
        }
        html += '</table>';
    }
    return html;
}

Js_Sql.prototype.isObject = function (val) {
    return val instanceof Object; 
}

Js_Sql.prototype.hide_database = function (data) {
    if($('#jstree-tree').is(':hidden')) {
        $('#jstree-tree').show();
        $('#zend_code').removeClass("col-md-12");
        $('#zend_result').removeClass("col-md-12");
        $('#zend_code').addClass("col-md-8");
        $('#zend_result').addClass("col-md-8");
        $('#zend_code').css("width","72%");
        $('#zend_result').css("width","72%");
    }else{
        $('#jstree-tree').hide();
        $('#zend_code').removeClass("col-md-8");
        $('#zend_result').removeClass("col-md-8");
        $('#zend_code').addClass("col-md-12");
        $('#zend_result').addClass("col-md-12");
        $('#zend_code').css("width","100%");
        $('#zend_result').css("width","100%");
    }
}

Js_Sql.prototype.copyToClipboard = function (massage) {
  var aux = document.createElement("input");
  aux.setAttribute("value", massage);
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");
  document.body.removeChild(aux);
}