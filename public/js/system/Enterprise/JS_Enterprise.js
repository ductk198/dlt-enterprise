function JS_Enterprise(baseUrl, module, action) {
    // check side bar
    $("#main_enterprise").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
}
// Load su kien tren man hinh index
JS_Enterprise.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmlist_index';
    myClass.loadList(oForm);
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_search').click(function () {
        myClass.loadList(oForm);
    });

    $(oForm).find('#btn_export_cache').click(function () {
        myClass.exportCache(oForm);
    });
    $(oForm).find('#btn_import').click(function () {
        myClass.import(oForm);
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function () {
            myClass.delete(oForm);
        }
    });
    // Tim kiem loai danh muc
    $(oForm).find('#listtype').change(function () {
        myClass.loadList(oForm);
    });
}
JS_Enterprise.prototype.import = function (oForm) {
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
            $('#DialogModal').html(arrResult);
            $('#DialogModal').modal('show');
//            myClass.loadevent();
//            myClass.validateAddForm($('#frmImportUnit'));
////            $('.chzn-select').chosen({height: '100%', width: '100%'});
            DLTLib.successLoadImage();
        }
    });
}
JS_Enterprise.prototype.saveimport = function () {
    var myform = 'form#frmImportEnterprise';
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
// Load su kien tren cac minh khac
JS_Enterprise.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_Enterprise.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var myClass = this;
    var loadding = DLTLib.loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadList';
    var dirxml = myClass.baseUrl + '/xml/System/enterprise/danh_sach_doanh_nghiep.xml';
    if (typeof (Tablelist) === 'undefined') {
        Tablelist = new TableXml(dirxml);
    }
    var data = $(oForm).serialize();
    data += '&currentPage=' + currentPage;
    data += '&perPage=' + perPage;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            Tablelist.exportTable(arrResult.Dataloadlist.data, 'id', $('div#table-container'), '', oForm);
            // phan trang
            $('#pagination').html(arrResult.pagination);
            $(oForm).find('.main_paginate .pagination a').click(function () {
                var page = $(this).attr('page');
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').change(function () {
                var page = $(oForm).find('#_currentPage').val();
                var perPage = $(oForm).find('#cbo_nuber_record_page').val();
                myClass.loadList(oForm, page, perPage);
            });
            $(oForm).find('#cbo_nuber_record_page').val(arrResult.perPage);
            loadding.go(100);
        }
    });
}
// Them loai danh muc
JS_Enterprise.prototype.add = function (oForm) {
    var url = this.urlPath + '/add';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#DialogModal').html(arrResult);
            $('#DialogModal').modal('show');
            var oForm = $('form#frmAddPositionType');
            $('.chzn-select').chosen({height: '100%', width: '100%'});
            myClass.loadevent(oForm);
        }
    });
}
// sua
JS_Enterprise.prototype.edit = function (oForm) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
            i++;
        }
    });
    if (listitem == '') {
        DLTLib.alertMessage('danger', "Bạn chưa chọn nhóm chức vụ cần sửa");
        return false;
    }
    if (i > 1) {
        DLTLib.alertMessage('danger', "Bạn chỉ được chọn một nhóm chức vụ để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#DialogModal').html(arrResult);
            $('#DialogModal').modal('show');
            var oForm = $('form#frmAddPositionType');
            myClass.loadevent(oForm);
        }
    });
}
// Them loai danh muc
JS_Enterprise.prototype.delete = function (oForm) {
    var url = this.urlPath + '/delete';
    var myClass = this;
    var listitem = '';
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    $(p_chk_obj).each(function () {
        if ($(this).is(':checked')) {
            if (listitem !== '') {
                listitem += ',' + $(this).val();
            } else {
                listitem = $(this).val();
            }
        }
    });
    if (listitem == '') {
        DLTLib.alertMessage('danger', "Bạn chưa chọn nhóm chức vụ cần xóa");
        return false;
    }
    var data = $(oForm).serialize();
    data += '&listitem=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                myClass.loadList(oForm);
                DLTLib.alertMessage('success', arrResult['message']);
            } else {
                DLTLib.alertMessage('danger', arrResult['message']);
            }
        }
    });
}

// Xuat caches
JS_Enterprise.prototype.exportCache = function (oForm) {
    var url = this.urlPath + '/exportCache';
    var myClass = this;

    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        dataType: 'json',
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                myClass.loadList(oForm);
                DLTLib.alertMessage('success', arrResult['message']);
            } else {
                DLTLib.alertMessage('danger', arrResult['message'], 6000);
            }
        },
        error: function (arrResult) {
            DLTLib.alertMessage('warning', arrResult.responseJSON);
        }
    });
}

// Cap nhat loai danh muc
JS_Enterprise.prototype.update = function (oForm) {
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#DialogModal').modal('hide');
                myClass.loadList(oForm);
                DLTLib.alertMessage('success', arrResult['message']);
            } else {
                DLTLib.alertMessage('danger', 'Cảnh báo', arrResult['message'], 6000);
            }
        },
        error: function (arrResult) {
            DLTLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}
JS_Enterprise.prototype.getExtension = function (path) {
    var basename = path.split(/[\\/]/).pop(),
            pos = basename.lastIndexOf(".");
    if (basename === "" || pos < 1)
        return "";
    return basename.slice(pos + 1);
}