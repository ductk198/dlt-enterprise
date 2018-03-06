function JS_PositionGroup(baseUrl, module, action) {
    // check side bar
    $("#main_position").attr("class", "active");
    $("#child_positiongroup").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
}
// Load su kien tren man hinh index
JS_PositionGroup.prototype.loadIndex = function () {
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
// Load su kien tren cac minh khac
JS_PositionGroup.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_PositionGroup.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var myClass = this;
    var loadding = DLTLib.loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadList';
    var dirxml = myClass.baseUrl + '/xml/System/position/danh_sach_nhom_chuc_vu.xml';
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
JS_PositionGroup.prototype.add = function (oForm) {
    var url = this.urlPath + '/add';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addListModal').html(arrResult);
            $('#addListModal').modal('show');
            var oForm = $('form#frmAddPositionType');
            myClass.loadevent(oForm);
        }
    });
}
// sua
JS_PositionGroup.prototype.edit = function (oForm) {
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
            $('#addListModal').html(arrResult);
            $('#addListModal').modal('show');
            var oForm = $('form#frmAddPositionType');
            myClass.loadevent(oForm);
        }
    });
}
// Them loai danh muc
JS_PositionGroup.prototype.delete = function (oForm) {
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
JS_PositionGroup.prototype.exportCache = function (oForm) {
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
JS_PositionGroup.prototype.update = function (oForm) {
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
                $('#addListModal').modal('hide');
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