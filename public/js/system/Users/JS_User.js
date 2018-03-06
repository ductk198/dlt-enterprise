/**
 * Xử lý Js về người dùng
 *
 * @author duclt
 */
function Js_User(baseUrl, module, controller) {
    this.objTree = $("#jstree-tree");
    $("#main_users").attr("class", "active");
    $("#child_user_2").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    JS_Tree = new JS_Tree(baseUrl, 'system/users', 'zendtree', this.objTree);
    this.controller = controller;
    this.loadding = DLTLib.loadding();
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}

/**
 * Hàm load các sử kiện cho màn hình index
 *
 * @return void
 */
Js_User.prototype.loadIndex = function () {
    $(document).ajaxSend(function () {
        DLTLib.showmainloadding();
    });
    $(document).ajaxStop(function () {
        DLTLib.successLoadImage();
    });
    var myClass = this;
    var oForm = 'form#frmUser_index';
    myClass.loadList(oForm, false);
    $('.chzn-select').chosen({height: '100%', width: '100%'});
    $('.search-panel .dropdown-menu').find('a').click(function (e) {
        e.preventDefault();
        var searchid = $(this).attr("search-id");
        var param = $(this).attr("href").replace("#", "");
        var concept = $(this).text();
        $('.search-panel span#search_concept').text(concept);
        $('.input-group #search_param').val(param);
    });
    $("#search-user-unit").click(function () {
        var param = $('.input-group #search_param').val();
        var idunit = $('#tree-header-prev').attr("id-current");
        var search = $('#search_text').val();
        myClass.search(param, idunit, search);
    });
    $("#search_text").keyup(function () {
        var param = $('.input-group #search_param').val();
        if (param == 'tree') {
            var search = $('#search_text').val();
            myClass.search_current_tree(search);
        }
    });
    $("#btn-add").click(function () {
        var idunit = $('#check_add').attr('id-unit');
        var check_add = $('#check_add').val();
        if (check_add == 'unit') {
            myClass.add_department(idunit, 'unit');
        } else if (check_add == 'department') {
            myClass.add_department(idunit, 'department');
        } else if (check_add == 'user') {
            myClass.add_user(idunit);
        }
    });
    $('#btn_import').click(function () {
        myClass.import(oForm);
    });
}

/**
 * Hàm load các sử kiện cho màn hình khác
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_User.prototype.loadevent = function () {
    var myClass = this;
    $('form#frmAddDepartment').find('#btn_update').click(function () {
        myClass.update_department('form#frmAddDepartment');
    });
    $('form#frmAddUser').find('#btn_update').click(function () {
        myClass.update_user('form#frmAddUser');
    });
}

Js_User.prototype.loadEvenTree = function () {
    var myClass = this;
    $('.unit-edit').unbind("click");
    $('.unit-edit').click(function () {
        // lay Id cua unit
        var id = $(this).attr('id-unit');
        myClass.edit_common(name, id, 'unit');
        //alert(id);
    });

    $('.delete-unit').unbind("click");
    $('.delete-unit').click(function () {
        // lay Id cua unit
        var id = $(this).attr('id-unit');
        var name = $(this).attr('name-unit');
        myClass.delete_common(name, id, 'unit');
        //
    });

    $('.user-edit').unbind("click");
    $('.user-edit').click(function () {
        // lay Id cua unit
        var id = $(this).attr('id-user');
        //
        myClass.edit_common('', id, 'user');
    });

    $('.user-delete').unbind("click");
    $('.user-delete').click(function () {
        // lay Id cua unit
        var id = $(this).attr('id-user');
        var name = $(this).attr('name-user');
        //
        myClass.delete_common(name, id, 'user');
    });

    $('.tr-tree-unit').unbind("click");
    $('.tr-tree-unit').click(function () {
        $('.tr-tree-unit').removeClass('selected');
        $(this).addClass('selected');
    });

    $('.tr-tree-user').unbind("click");
    $('.tr-tree-user').click(function () {
        $('.tr-tree-user').removeClass('selected');
        $(this).addClass('selected');
    });

    $('.tr-tree-user').unbind("dblclick");
    $('.tr-tree-user').dblclick(function () {
        var id = $(this).attr('id-user');
        myClass.edit_common('', id, 'user');
    });

    $('.tr-tree-unit').unbind("dblclick");
    $('.tr-tree-unit').dblclick(function () {
        var id = $(this).attr('id-unit');
        var node = $('#jstree-tree').jstree(true).get_node(id);
        $('#jstree-tree').jstree('create_node', node);
        JS_Tree.zendList(node);
    });

    $('.tree-header-breadcrumb').unbind("click");
    $('.tree-header-breadcrumb').click(function () {
        var id = $(this).attr('id-unit');
        var node = $('#jstree-tree').jstree(true).get_node(id);
        JS_Tree.zendList(node);
    });

}

/**
 * Load màn hình danh sách
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_User.prototype.loadList = function (oForm, checksearch) {
    JS_Tree.zend_tree();
}

Js_User.prototype.search = function (param, idunit, search) {
    if (search != '') {
        var myClass = this;
        var url = this.urlPath + '/search';
        data = {
            param: param,
            idunit: idunit,
            search: search
        };
        $.ajax({
            url: url,
            type: "GET",
            dataType: 'json',
            //cache: true,
            data: data,
            success: function (arrResult) {
                var htmls = 'Không tìm thấy kết quả';
                if (arrResult['data'].length > 0) {
                    if (arrResult['type'] == 'user') {
                        htmls = JS_TempHtml.zend_user(arrResult['data']);
                    } else {
                        var node_child = 3;
                        var node = $('#jstree-tree').jstree(true).get_node(idunit);
                        htmls = JS_TempHtml.zend_unit_search(arrResult['data'], node_child);
                        JS_Tree.zend_breadcrumb_tree(node);
                    }
                    $('#zend_list').html(htmls);
                    // mo thu muc
                    //$('#'+idunit+'>a>i').removeClass('glyphicon-folder-close').addClass('glyphicon-folder-open');
                } else {
                    $('#zend_list').html(htmls);
                }
                Js_User.loadEvenTree();
            }
        });
    }
}

/**
 * Thêm mới người dùng
 *
 * @param node (đối tượng click)
 *
 * @return object
 */
Js_User.prototype.add_user = function (id) {
    var myClass = this;
    var url = this.urlPath + '/user_add';
    data = '&id=' + id;
    $.ajax({
        url: url,
        type: "GET",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            myClass.loadevent();
            myClass.validateAddForm($('#frmAddUser'));
            $('.chzn-select').chosen({height: '100%', width: '100%'});
            DLTLib.successLoadImage();
        }
    });
}

// Cap nhat nguoi dung
Js_User.prototype.update_user = function (oForm) {
    var myClass = this;
    var url = this.urlPath + '/user_update';
    if ($(oForm).valid()) {
        // check
        var idunser = $(oForm).find("input[name=id]").val();
        var pass = $(oForm).find('#password').val();
        var passcheck = $(oForm).find('#repassword').val();
        if (idunser !== '') {
            // sua
            if (pass != '' && pass != passcheck) {
                $("#password").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                $("#repassword").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                return false;
            }
        } else {
            if (pass == '') {
                $("#password").after('<label for="username" generated="true" class="error">Mật khẩu không được để trống</label>');
                return false;
            }
            if (passcheck == '') {
                $("#repassword").after('<label for="username" generated="true" class="error">Nhập lại mật khẩu không được để trống</label>');
                return false;
            }
            if (pass != passcheck) {
                $("#password").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                $("#repassword").after('<label for="username" generated="true" class="error">Xác nhận mật khẩu chưa đúng</label>');
                return false;
            }
        }
        // if(1==1) {
        var data = $(oForm).serialize();
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            //cache: true,
            data: data,
            success: function (arrResult) {
                DLTLib.successLoadImage();
                if (arrResult['success']) {
                    $('#addmodal').modal('hide');
                    // load tree
                    var node = $('#jstree-tree').jstree(true).get_node(arrResult['parent_id']);
                    // check if parent 
                    JS_Tree.zendList(node);
                    DLTLib.successLoadImage();
                } else {
                    DLTLib.alertMessage('danger', arrResult['message'], 6000);
                }
            }
        });
    }
}

Js_User.prototype.validateAddForm = function (oForm) {
    jQuery.validator.addMethod("rangePhone", function (value, element, params) {
        if (value.length >= params[0] && value.length <= params[1]) {
            return true;
        } else {
            return false;
        }
    });

    oForm.validate({
        rules: {
            name: "required",
            username: "required",
            department: "required",
            email: "email",
            vaitro: "required",
        },
        messages: {
            name: "Họ và tên không được để trống",
            username: "Tên đăng nhập không được để trống",
            password: "Mật khẩu không được để trống",
            department: "Phòng ban không được để trống",
            phone_number: {
                digits: "Số điện thoại phải là các chữ số",
                rangePhone: "Số điện thoại phải từ 9 đến 15 ký tự",
            },
            email: "Email phải đúng định dạng email",
            vaitro: "Vai trò không được để trống",
        }
    });
}

//Sua mot doi tuong nguoi dung hoac phong ban
Js_User.prototype.edit_common = function (name, id, type) {
    var myClass = this;
    if (type == "user") {
        var url = this.urlPath + '/user_edit';
    } else {
        var url = this.urlPath + '/department_edit';
    }
    data = '&id=' + id;
    $.ajax({
        url: url,
        type: "GET",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            myClass.loadevent();
            if (type == "user") {
                myClass.validateAddForm($('#frmAddUser'));
            } else {
                myClass.validateAddDepartmentForm($('#frmAddDepartment'));
            }
            $('.chzn-select').chosen({height: '100%', width: '100%'});
        }
    });
}

// Xoa mot doi tuong
Js_User.prototype.delete_common = function (name, id, type) {
    var myClass = this;
    var check = false;
    if (type == "user") {
        if (confirm("Bạn có chắc chắn muốn xóa người dùng: " + name)) {
            check = true;
        }
        var url = this.urlPath + '/delete_user';
    } else {
        if (confirm("Bạn có chắc chắn muốn xóa phòng ban: " + name)) {
            check = true;
        }
        var url = this.urlPath + '/department_delete';
    }
    if (check) {
        var data = $('form#frmUser_index').serialize();
        data += '&id=' + id;
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            //cache: true,
            data: data,
            success: function (arrResult) {
                if (type == "user") {
                    // load tree
                    var node = $('#jstree-tree').jstree(true).get_node(arrResult['parent_id']);
                    // check if parent 
                    JS_Tree.zendList(node);
                    DLTLib.successLoadImage();
                } else {
                    if (arrResult['success']) {
                        // load tree
                        var node = $('#jstree-tree').jstree(true).get_node(arrResult['parent_id']);
                        JS_Tree.zendList(node);
                        $('#jstree-tree').jstree('refresh');
                    } else {
                        DLTLib.alertMessage('danger', arrResult['message'], 6000);
                    }
                }
            }
        });
    }
}


/**
 * Lay phong ban tu don vi trien khai
 *
 * @param oForm (tên form)
 *
 * @return void
 */
Js_User.prototype.GetDepartment = function (oForm) {
    var myClass = this;
    myClass.loadding.go(20);
    var url = myClass.urlPath + '/user_GetDepartment';
    var idunit = $(oForm).find('#Units').val();
    if (idunit !== '') {
        var data = $("form#frmUser_index").serialize();
        data = {
            idunit: idunit,
            _token: $("input[name=_token]").val()
        };
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'text',
            //cache: true,
            data: data,
            success: function (arrResult) {
                $('#department').html(arrResult);
                $('#department').trigger("chosen:updated");
                //myClass.loadList(oForm,true);
            }
        });
    } else {
        $('#department').html('<option value="">-- Chọn phòng ban --</option>');
        $('#department').trigger("chosen:updated");
        myClass.loadList(oForm);
    }

}

/**
 * Thêm mới một phòng ban
 *
 * @param node (đối tượng click)
 *
 * @return object
 */
Js_User.prototype.add_department = function (id, type) {
    var myClass = this;
    var url = this.urlPath + '/department_add';
    data = '&id=' + id;
    data += '&type=' + type;
    $.ajax({
        url: url,
        type: "GET",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            myClass.validateAddDepartmentForm($('#frmAddDepartment'));
            myClass.loadevent();
            DLTLib.successLoadImage();
        }
    });
}

//Cap nhat loai danh muc
Js_User.prototype.update_department = function (oForm) {
    var myClass = this;
    var url = this.urlPath + '/department_update';
    var data = $(oForm).serialize();
    if ($(oForm).valid()) {
        $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            //cache: true,
            data: data,
            success: function (arrResult) {
                if (arrResult['success']) {
                    $('#addmodal').modal('hide');
                    // load tree
                    var node = $('#jstree-tree').jstree(true).get_node(arrResult['parent_id']);
                    node.state.loaded = false;
                    // check if parent 
                    $('#jstree-tree').jstree('create_node', node);
                } else {
                    DLTLib.alertMessage('danger', arrResult['message'], 6000);
                }
            }
        });
    }
}

Js_User.prototype.validateAddDepartmentForm = function (oForm) {
    oForm.validate({
        rules: {
            C_CODE: "required",
            C_NAME: "required",
            C_EMAIL: "email",
            C_TEL: 'digits',
            C_EMAIL: "email",
        },
        messages: {
            C_CODE: "Mã phòng ban không được để trống",
            C_NAME: "Tên phòng ban không được để trống",
            C_TEL: {
                digits: "Số điện thoại phải là các chữ số",
            },
            C_EMAIL: "Email phải đúng định dạng email"
        }
    });
}

Js_User.prototype.import = function (oForm) {
    DLTLib.showmainloadding();
    var myClass = this;
    var data = $(oForm).serialize();
    var url = this.urlPath + '/import';
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addmodal').html(arrResult);
            $('#addmodal').modal('show');
            myClass.loadevent();
            myClass.validateAddForm($('#frmImportUnit'));
//            $('.chzn-select').chosen({height: '100%', width: '100%'});
            DLTLib.successLoadImage();
        }
    });
}
//save import
Js_User.prototype.saveimport = function () {
    var myform = 'form#frmImportUnit';
    var myClass = this;
    var url = this.urlPath + '/saveimport';
    var tenfile = $('#file').val();
    if (tenfile == '' || tenfile == null) {
        DLTLib.alertMessage('danger', 'Thông báo', 'Bạn phải chọn file  để import dữ liệu');
        return false;
    } else {
        if (myClass.getExtension(tenfile) != 'xls' && myClass.getExtension(tenfile) != 'xlsx') {
            DLTLib.alertMessage('danger', 'Thông báo', 'Bạn phải chọn file xls hoặc xlsx để import dữ liệu');
            return false;
        }
    }
    var form_data = new FormData();

    $('[type=file]').each(function () {
        form_data.append("file", $('[type=file]')[0].files[0]);
    });
    form_data.append('_token', $('#_token').val());
    form_data.append('unittype', $('input[name="unittype"]:checked').val());
    DLTLib.showmainloadding();
    $.ajax({
        url: url,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        type: "POST",
        data: form_data,
        success: function (arrResult) {
            $('#addmodal').modal('hide');
            // load tree
            var node = $('#jstree-tree').jstree(true).get_node($('#check_add').attr('id-unit'));
            node.state.loaded = false;
            // check if parent 
            $('#jstree-tree').jstree('create_node', node);
            DLTLib.successLoadImage();
        },
        error: function (arrResult) {
            DLTLib.alertMessage('warning', 'File import không đúng định dạng');
            $('#addmodal').modal('hide');
            DLTLib.successLoadImage();
        }
    });
}

Js_User.prototype.search_current_tree = function (search) {
    var input, filter, table, tr, td, i, tendonvi, madonvi, chuoitimkiem;
    $('#tb_list_record > tbody  > tr').each(function () {
        tendonvi = $(this).find("td").eq(1).text();
        madonvi = $(this).find("td").eq(2).text();
        chuoitimkiem = tendonvi + ' ' + madonvi;
        if (chuoitimkiem.toUpperCase().indexOf(search.toUpperCase()) >= 0) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

Js_User.prototype.getExtension = function (path) {
    var basename = path.split(/[\\/]/).pop(),
            pos = basename.lastIndexOf(".");
    if (basename === "" || pos < 1)
        return "";
    return basename.slice(pos + 1);
}
