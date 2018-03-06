/**
 * Xử lý Js về người dùng
 *
 * @author Duclt
 */
function Js_Code(baseUrl, module, controller) {
    this.objTree = $("#jstree-tree");
    $("#main_system").attr("class", "active");
    $("#child_code").attr("class", "active");
    // $("#child_user_3").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    // this.loadding = DLTLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
    this.current_path = '';
    this.type = '';
    this.path_file = '';
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
Js_Code.prototype.loadIndex = function () {
    $(document).ajaxSend(function () {
        DLTLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        DLTLib.successLoadImage();
    });
    var myClass = this;
    var oForm = 'form#frmCode_index';
    myClass.loadList(oForm, false);

    // Right Click
    var $contextMenu = $("#contextMenu");
    $("body").on("contextmenu", "table#tb_list_record tbody tr", function (e) {
        myClass.rename_remove();
        if (!$(this).hasClass("selected")) {
            $('.tr-tree-unit').removeClass('selected');
            $(this).addClass('selected');
        }
        $('.icon-path-fill').attr('fill', '#337ab7');
        $("#tb_list_record").find('tr.selected > td > svg > path.icon-path-fill').attr('fill', 'white');
        // show right click
        myClass.showContextMenu($(this), $contextMenu);
        var tope = e.pageY;
        if ((e.pageY + $("#contextMenu").height()) > $(document).height()) {
            var e1 = $(document).height() - e.pageY + $("#contextMenu").height();
            tope = $(document).height() - e1;
        }
        $contextMenu.css({
            display: "block",
            'z-index': '9999',
            left: e.pageX,
            top: tope
        });
        return false;
    });
    $contextMenu.on("click", "a", function () {
        var v_class = $(this).attr('class');
        myClass.contextMenuClick(v_class);
        $contextMenu.hide();
    });
    $("body").click(function (event) {
        if (!$(event.target).is('.vakata-contextmenu-sep') && !$(event.target).is('.glyphicon-pencil') && !$(event.target).is('.contextMenu-rename') && !$(event.target).is('.input-rename')) {
            myClass.rename_remove();
        }
        $contextMenu.hide();
    });

    $(".search-input").keyup(function() {

        var searchString = $(this).val();
        var objTree = myClass.objTree;
        objTree.jstree('search', searchString);
    });
}

/**
 * Hàm load các sử kiện cho màn hình khác
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_Code.prototype.loadEvenTree = function () {
    var myClass = this;

    $('.tr-tree-unit').unbind("click");
    $('.tr-tree-unit').click(function () {
        $('.tr-tree-unit').removeClass('selected');
        $(this).addClass('selected');
    });

    $('.tr-tree-unit').unbind("dblclick");
    $('.tr-tree-unit').dblclick(function () {
        var id = $(this).attr('id-unit');
        var node = myClass.objTree.jstree(true).get_node(id);
        myClass.objTree.jstree('create_node', node);
        myClass.zendList(node);
    });

    $('#btn-add-folder').unbind("click");
    $('#btn-add-folder').click(function () {
        myClass.add_folder("");
    });

    $('#btn-add-file').unbind("click");
    $('#btn-add-file').click(function () {
        myClass.add_file();
    });

    $('#btn-upload').unbind("click");
    $('#btn-upload').click(function () {
        myClass.upload_file();
    });

    $('#btn-export-modules').unbind("click");
    $('#btn-export-modules').click(function () {
        myClass.export_module();
    });
}

/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_Code.prototype.loadList = function (oForm, checksearch) {
    var myClass = this;
    var objTree = myClass.objTree;
    var url = myClass.urlPath + '/getall';
    objTree.on('changed.jstree', function (e, data) {
        var node;
        for (i = 0, j = data.selected.length; i < j; i++) {
            node = data.instance.get_node(data.selected[i]);
        }
        if (node) {
            objTree.jstree('create_node', node);
            myClass.zendList(node);
        }
    }).jstree({
        "plugins": ["contextmenu", "json_data", "dnd", "search"],
        'core': {
            'data': {
                'url': function (node) {
                    return url + '?root=' + node.parents.length;
                },
                "data": function (node) {
                    return { "id": node.id };
                },
                "success": function (node) {
                    myClass.zendList(node);
                },
                'dataType': "json"
            }
        },
        'contextmenu': {
            'select_node': false,
            'items': myClass.rightclick
        },
          "search": {
                "case_insensitive": true,
                "show_only_matches" : true
           }
    });
}

Js_Code.prototype.zendList = function (node) {
    var myClass = this;
    var node_lv = 0;
    var is_file = false;
    if(node.type == 'folder'){
        this.current_path = node.id;
        this.type = node.type;
    }else{
        if(node.original.type == 'folder'){
            this.current_path = node.id;
            this.type = node.original.type;
        }
    }
    if (node.state.loaded) {
        myClass.zend_breadcrumb_tree(node);
    } else {
        if (!node.state.opened) {
            myClass.zend_breadcrumb_tree(node);
        }
    }
    if (typeof (node.original) != "undefined" && node.original !== null) {
        if (typeof (node.original.type) != "undefined" && node.original.type !== null) {
            if (node.original.type == 'file') {
                is_file = true;
            }
        }
    }
    if (is_file) {
        var path_file = node.id;
        Js_Code.open_file(path_file);
    } else {
        htmls = myClass.zend_folder_file(this.objTree, node);
        $('#zend_list').html(htmls);
        Js_Code.loadEvenTree();
    }
}

Js_Code.prototype.zend_folder_file = function (objTree, node) {
    var check_loaded = false;
    if (node.state.loaded) {
        check_loaded = true;
    }
    var htmls = '';
    htmls += '<table id="tb_list_record" class="table table-hover">';
    htmls += '<thead>';
    htmls += '<tr class="thead-inverse">';
    htmls += '<th align="center" class="col-md-4">Tên</th>';
    htmls += '<th align="center" class="col-md-3">Kích cỡ</td>';
    htmls += '<th align="center" class="col-md-3">Ngày sửa đổi</td>';
    htmls += '</tr>';
    htmls += '</thead>';
    htmls += '<tbody>';
    htmls += '</tbody>';
    i = 1;
    var _class = v_status = '';
    var id = order = name = code = address = status = '';
    var id_file = 0;
    for (var prop in node.children) {
        if (check_loaded) {
            var objchild = objTree.jstree(true).get_node(node.children[prop]);
            id = objchild.original.id;
            name = objchild.original.text;
            size = objchild.original.size;
            datemidify = objchild.original.date_modify;
            type = objchild.original.type;
            _class = this.get_node_level(type);
        } else {
            id = node.children[prop].id;
            name = node.children[prop].text;
            size = node.children[prop].size;
            status = node.children[prop].status;
            type = node.children[prop].type;
            datemidify = node.children[prop].date_modify;
            _class = this.get_node_level(type);
        }
        if (type == 'file') {
            var objfiletree = objTree.jstree(true).get_node(id);
        }
        if (status == 'HOAT_DONG') {
            v_status = 'Hoạt động';
        } else {
            v_status = 'Không hoạt động';
        }
        htmls += '<tr class="tr-tree-unit" id-unit="' + id + '" id-type="' + type + '">';
        htmls += '<td><i class="' + _class + '"></i> ' + name + '</td>';
        htmls += '<td>' + size + '</td>';
        htmls += '<td>' + datemidify + '</td>';
        htmls += '</tr>';
        i++;
    };
    htmls += '</table>';
    // hide file

    return htmls;
}

Js_Code.prototype.get_node_level = function (type) {
    var icon = '';
    if (type == 'folder') {
        icon = 'fa fa-folder fa-hight folder-v1';
    } else {
        icon = 'fa fa-file-text fa-hight file-v1';
    }
    return icon;
}

Js_Code.prototype.rightclick = function (node) {
    // check if node unit or node user
    return false;
}

Js_Code.prototype.zend_breadcrumb_tree = function (node) {
    var idunit, htmlheader, idparent, idroot;
    var parents = [];
    if (node.original.type == 'folder') {
        var html = '<li><a role="button"><i class="fa fa-home"></i> ROOT PROJECT</a></li>';
        if (node.parent !== "#") {
            var arrText = node.id.split('\\');
            for (var i in arrText) {
                if (arrText[i] !== "") {
                    html += '<li>';
                    html += arrText[i];
                    html += '</li>';
                }
            }
        }
        // show node
        $("#breadcrumb_tree").html(html);
    }
}

Js_Code.prototype.rename_remove = function () {
    var myClass = this;
    var obj = $("#tb_list_record");
    var classi = obj.find('div.div-rename').attr('i-class');
    var text = obj.find('div.div-rename').attr('text');
    var html = $("#html-old-rename").html();
    obj.find('div.div-rename').parent().html(html);
}

Js_Code.prototype.showContextMenu = function (vthis, objContextMenu) {
    var myClass = this;
    var numItemSelect = $("#tb_list_record").find('tr.selected').length;
    var type = vthis.attr('id-type');
    // check context menu
    var objContext = myClass.getContextMenu(numItemSelect, type);
    objContextMenu.html(myClass.getHtmlContextMenu(objContext));
    if (vthis.attr('star') == 1) {
        $("li#contextMenu-star").remove();
    } else {
        $("li#contextMenu-removestar").remove();
    }
}

Js_Code.prototype.getContextMenu = function (numItemSelect, type) {
    var _htmlcontext = {
        // Common
       /** edit: {
            icon: 'glyphicon-edit',
            name: 'Sửa'
        },**/
        delete: {
            icon: 'glyphicon-trash',
            name: 'Xóa'
        },
        download: {
            icon: '	glyphicon glyphicon-download-alt',
            name: 'Tải xuống'
        },
    }
    return _htmlcontext;
}

Js_Code.prototype.getHtmlContextMenu = function (objContext) {
    html = '<ul class="dropdown-menu vakata-context jstree-contextmenu jstree-default-contextmenu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">';
    $.each(objContext, function (index, value) {
        var checkper = value.per;
        html += ' <li id="contextMenu-' + index + '"><a tabindex="-1" href="#" class="contextMenu-' + index + '"><i class="glyphicon ' + value.icon + '"></i><span class="vakata-contextmenu-sep">&nbsp;</span>' + value.name + '</a></li>';
    });
    html += '</ul>';
    return html;
}

Js_Code.prototype.contextMenuClick = function (v_class) {
    var myClass = this;
    var path = $("table#tb_list_record tbody tr.selected").attr('id-unit');
    var type = $("table#tb_list_record tbody tr.selected").attr('id-type');
    var name = $("table#tb_list_record tbody tr.selected > td:first").text();
    if (v_class == 'contextMenu-edit') {
        if(type == 'folder'){
            myClass.add_folder(name);
        }else{

        }
    } else if (v_class == 'contextMenu-delete') {
        $.confirm({
            text: "Bạn có chắc chắn muốn xóa: <span class='js-tree-text'>" + name + '</span>',
            confirm: function (button) {
                myClass.delete(path, type);
            },
            confirmButton: "Đồng ý",
            cancelButton: "Không"
        });
    }else if(v_class == 'contextMenu-download'){
        myClass.download(path,type,name);
    }
}
/**
* ----------------------------------- Action -------------------
**/
Js_Code.prototype.loadevent = function () {
    var myClass = this;

    $('#btn_update').unbind("click");
    $('#btn_update').click(function () {
        var oForm = 'form#frmUpdateFile';
        myClass.update_file(oForm, false);
    });
    $('#btn_update_close').unbind("click");
    $('#btn_update_close').click(function () {
        var oForm = 'form#frmUpdateFile';
        myClass.update_file(oForm, true);
    });
    // $('#btnUpload').unbind("click");
    // $('#btnUpload').click(function () {
    //     var oForm = 'form#frmUploadFile';
    //     myClass.upload();
    // });
    $('#Upload').unbind("click");
    $('#Upload').click(function () {
        var oForm = 'form#frmUploadFile';
        myClass.upload();
    });

    jQuery(document).ready(function ($) {
        jQuery('div[data-ace-editor-id]').each(function () {
            new PHPEditor(this);
        });
    })
}

Js_Code.prototype.open_file = function (path_file) {
    var myClass = this;
    if (path_file) {
        var url = myClass.urlPath + '/open_file';
        data = 'path_file=' + path_file;
        $.ajax({
            url: url,
            type: "Get",
            data: data,
            success: function (arrResult) {
                $('#openDialog').html(arrResult);
                $('#openDialog').modal('show');
                myClass.loadevent();
            }
        });
    }
}

Js_Code.prototype.update_file = function (oForm, closed) {
    var myClass = this;
    var id = 1;
    var url = myClass.urlPath + '/update_file';
    var editor = PHPEditor.get(id)
    var code = editor.editor.getValue();
    var data = {
        content: code,
        _token: $("input[name=_token]").val(),
        path: $("input[name=path_file]").val(),
        parent_path: myClass.current_path
    };
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: data,
        crossDomain: true,
        success: function (arrResult) {
            if (arrResult['success']) {
                DLTLib.alertMessage('success', arrResult['message']);
                if (closed) {
                    $('#openDialog').modal('hide');
                    var id = myClass.current_path;
                    var objTree = myClass.objTree;
                    var node = objTree.jstree(true).get_node(id);
                    // check if parent 
                    node.state.loaded = false;
                    objTree.jstree('create_node', node);
                }
            } else {
                DLTLib.alertMessage('danger', arrResult['message'], 6000);
            }
        }
    });
}

Js_Code.prototype.edit = function (path, type) {
    var name_edit = $('.name').val();
    if(name_edit == '' || name_edit == null){
        $.alert({
            title: 'Thông báo!',
            content: 'Vui lòng nhâp tên thay đổi!',
        });
        return false;
    }
    var myClass = this;
    var url = myClass.urlPath + '/edit';
    var data = {
        _token: $('input[name="_token"]').val(),
        path: path,
        new_path: name_edit
    };
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                DLTLib.alertMessage('success', arrResult['message']);
            }
        }
    });
}

Js_Code.prototype.delete = function (path, type) {
    var myClass = this;
    var url = myClass.urlPath + '/delete';
    var data = {
        _token: $('input[name="_token"]').val(),
        path: path
    };
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                DLTLib.alertMessage('success', arrResult['message']);
                var id = myClass.current_path;
                var objTree = myClass.objTree;
                var node = myClass.objTree.jstree(true).get_node(id);
                node.state.loaded = false;
                objTree.jstree('create_node', node);
            } else {
                DLTLib.alertMessage('danger', arrResult['message'], 6000);
            }
        }
    });
}

Js_Code.prototype.upload_file = function () {
    var myClass = this;
    var path = myClass.current_path;
    var url = myClass.urlPath + '/upload_file';
    var data = {
        path:path,
    };
    $.ajax({
        url: url,
        type: 'GET',
        data: data,
        success: function (arrResult) {
            $('#openDialog').html(arrResult);
            $('#openDialog').modal('show');
            myClass.loadevent();
        }
    });
}

Js_Code.prototype.export_module = function () {
    var myClass = this;
    var url = myClass.urlPath + '/export_module';
    $.ajax({
        url: url,
        type: 'GET',
        success: function (arrResult) {
            $('#openDialog').html(arrResult);
            $('#openDialog').modal('show');
            myClass.zend_unit($("#C_TYPE_UNIT").val());
            $( "#C_TYPE_UNIT" ).change(function() {
              myClass.zend_unit($("#C_TYPE_UNIT").val());
            });
            $('#btn_export').unbind("click");
            $('#btn_export').click(function () {
                var oForm = 'form#frmExport_modules';
                myClass.export(oForm);
            });
        }
    });
}

Js_Code.prototype.export = function (oForm) {
    var url = this.urlPath + '/export';
    var myClass = this;
    var listdes = '';
    // khi checked 1 thanh phan
    $('input[name="GROUP_OWNERCODE"]:checked').each(function(){
        listdes += ','+ $(this).val();
    });
    if(listdes == ''){
        DLTLib.alertMessage('danger', "Bạn chưa chọn đơn vị cần xuất");
        return false;
    }
    if($('#C_TYPE_DOCUMENT option:selected').val() == ''){
        DLTLib.alertMessage('danger', "Bạn chưa chọn thư mục nguồn");
        return false;
    }
    var data = {
        _token:$('#_token').val(),
        source: $('#C_TYPE_UNIT option:selected').val(),
        destination:listdes,
    };
    DLTLib.showmainloadding();
    $.ajax({
        url : url,
        type : 'POST',
        data : data,
        success : function(res){
            if(res == 2){
                $('#openDialog').modal('hide');
                DLTLib.alertMessage('danger', "Chỉ chấp nhận nguồn là thành phố Hải Dương");
            }else{
                DLTLib.alertMessage('success', "Xuất modules thành công");
            }
            DLTLib.successLoadImage();
        }
    });
    
}

Js_Code.prototype.zend_unit = function (idunit) {
    var myClass = this;
    var url = myClass.urlPath + '/zend_unit';
    var data = {
        unit: idunit,
    };
    $.ajax({
        url: url,
        data: data,
        type: 'GET',
        success: function (arrResult) {
            $("#zend_unit").html(arrResult);
        }
    });
}

Js_Code.prototype.upload = function () {
    var myClass = this;
    var path_folder = myClass.current_path;
    console.log(path_folder);
    var ins = document.getElementById('multiFiles').files.length;
    if (ins > 20) {
        DLTLib.alertMessage('danger', "Dung lượng file không được vượt quá 20 file");
        return false;
    }
    var url = myClass.urlPath + '/upload';
    var oForm = 'form#frmUploadFile';
    var form_data = new FormData();
    for (var x = 0; x < ins; x++) {
        form_data.append("files[]", document.getElementById('multiFiles').files[x]);
    }
    form_data.append('_token', $('#_token').val());
    form_data.append('path_url', path_folder);
    $.ajax({
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        //cache: true,
        data: form_data,
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#openDialog').modal('hide');
                DLTLib.alertMessage('success', arrResult['message']);
                var id = myClass.current_path;
                var node = myClass.objTree.jstree(true).get_node(id);
                node.state.loaded = false;
                var objTree = myClass.objTree;
                objTree.jstree('create_node', node);
            } else {
                DLTLib.alertMessage('danger', arrResult['message'], 6000);
                return false;
            }
        }
    });
}
// download file zip
Js_Code.prototype.download = function (path, type, name) {
    var myClass = this;
    location.href = myClass.urlPath + '/download?path='+path+'&name='+name;
}

Js_Code.prototype.add_folder = function (name) {
    var myClass = this;
    var html = '<form action="" method="POST" id="frmAdd_folder">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<button type="button" class="close" data-dismiss="modal">&times;</button>';
    html += '<h4 class="modal-title">CẬP NHẬT THƯ MỤC</h4>';
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<div class="form-group">';
    html += '<h4>Tên thư mục :</h4>';
    html += '<input type="text" id="name_folder" placeholder="Tên thư mục" value="'+name+'" class="name form-control" required />';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button id="btn-update-folder" type="button" class="btn btn-primary">Cập nhật</button>';
    html += '<button type="input" class="btn btn-default" data-dismiss="modal">Thoát</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</form>';
    $('#openDialog').html(html);
    $('#openDialog').modal('show');

    $('#btn-update-folder').click(function () {
        if($("#name_folder").val() == ""){
            DLTLib.alertMessage('danger', "Tên thư mục không được để trống!");
        }
        var url = myClass.urlPath + '/add_folder';
        var data = {
            _token: $('input[name="_token"]').val(),
            parrent_path : myClass.current_path,
            name_folder: $("#name_folder").val()
        };
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (arrResult) {
                if(arrResult['success']){
                    $('#openDialog').modal('hide');
                    DLTLib.alertMessage('success', arrResult['message']);
                    var id = myClass.current_path;
                    var node = myClass.objTree.jstree(true).get_node(id);
                    node.state.loaded = false;
                    var objTree = myClass.objTree;
                    objTree.jstree('create_node', node);
                }else{
                    DLTLib.alertMessage('danger', arrResult['message'], 6000);
                    return false;
                }
            }
        });
    });
}

Js_Code.prototype.add_file = function () {
    var myClass = this;
    var html = '<form action="" method="POST" id="frmAdd_folder">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<button type="button" class="close" data-dismiss="modal">&times;</button>';
    html += '<h4 class="modal-title">Đường dẫn: '+myClass.current_path+'</h4>';
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<div class="form-group">';
    html += '<h4>Tên File :</h4>';
    html += '<input type="text" id="name_file" placeholder="Tên file" value="" class="name form-control" required />';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button id="btn-update-folder" type="button" class="btn btn-primary">Cập nhật</button>';
    html += '<button type="input" class="btn btn-default" data-dismiss="modal">Thoát</button>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    html += '</form>';
    $('#openDialog').html(html);
    $('#openDialog').modal('show');

    $('#btn-update-folder').click(function () {
        if($("#name_folder").val() == ""){
            DLTLib.alertMessage('danger', "Tên thư mục không được để trống!");
        }
        var url = myClass.urlPath + '/add_file';
        var data = {
            _token: $('input[name="_token"]').val(),
            parrent_path : myClass.current_path,
            name_file: $("#name_file").val()
        };
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (arrResult) {
                if(arrResult['success']){
                    $('#openDialog').modal('hide');
                    DLTLib.alertMessage('success', arrResult['message']);
                    var id = myClass.current_path;
                    var node = myClass.objTree.jstree(true).get_node(id);
                    node.state.loaded = false;
                    var objTree = myClass.objTree;
                    objTree.jstree('create_node', node);
                }else{
                    DLTLib.alertMessage('danger', arrResult['message'], 6000);
                    return false;
                }
            }
        });
    });
}