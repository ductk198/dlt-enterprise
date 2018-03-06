function JS_Position(baseUrl, module, action) {
    // check side bar
    $("#main_position").attr("class", "active");
    $("#child_position").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + action;//Biên public lưu tên module
}
// Load su kien tren man hinh index
JS_Position.prototype.loadIndex = function () {
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

    $(document).keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            myClass.loadList(oForm);
            return false;
        }
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
JS_Position.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
JS_Position.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var myClass = this;
    var loadding = DLTLib.loadding();
    loadding.go(20);
    var url = myClass.urlPath + '/loadList';
    var dirxml = myClass.baseUrl + '/xml/System/position/danh_sach_chuc_vu.xml';
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
            loadding.go(100);
            var shtml = myClass.genTable(arrResult);
            // phan trang
        }
    });
}
JS_Position.prototype.genTable = function (arrResult) {
    var shtml = '<table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">'
    shtml += '<thead class="thead-inverse"><tr class="header"><td align="center"><b><input type="checkbox" name="chk_all_item_id" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);"></b></td><td align="center"><b>Mã chức vụ</b></td><td align="center"><b>Tên chức vụ</b></td><td align="center"><b>Thứ tự</b></td><td align="center"><b>Tình trạng</b></td></tr><tr></tr></thead>';
    shtml += '<tbody>';
    var status = 'Không hoạt động';
    var oldPosGroup = '';
    for (var x in arrResult) {
        if (arrResult[x]['FK_POSITION_GROUP'] != oldPosGroup) {
            oldPosGroup = arrResult[x]['FK_POSITION_GROUP'];
            shtml += '<tr>';
            shtml += '<td colspan="5" style="background-color:white" class="data" align="left" colspan ondblclick="" onclick="{select_row(this);}"><b> ' + arrResult[x]['TEN_NHOM_QUYEN'] + '</b></td>';
            shtml += '</tr>';
        }
        if (arrResult[x]['C_STATUS'] == 'HOAT_DONG') {
            status = 'Hoạt động'
        } else {
            status = 'Không hoạt động'
        }
        shtml += '<tr>';
        shtml += '<td align="center"><input type="checkbox" ondblclick="" onclick="{select_checkbox_row(this);}" name="chk_item_id" value="' + arrResult[x]['PK_POSITION'] + '"></td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_CODE'] + '</td>';
        shtml += '<td class="data" align="left" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_NAME'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + arrResult[x]['C_ORDER'] + '</td>';
        shtml += '<td class="data" align="center" ondblclick="" onclick="{select_row(this);}">' + status + '</td>';
        shtml += '</tr>';
    }
    shtml += '</tbody></table>';
    $('div#table-container').html(shtml);
}
// Them loai danh muc
JS_Position.prototype.add = function (oForm) {
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
JS_Position.prototype.edit = function (oForm) {
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
        DLTLib.alertMessage('danger', "Bạn chưa chọn chức vụ cần sửa");
        return false;
    }
    if (i > 1) {
        DLTLib.alertMessage('danger', "Bạn chỉ được chọn một chức vụ để sửa");
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
JS_Position.prototype.delete = function (oForm) {
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
        DLTLib.alertMessage('danger', "Bạn chưa chọn chức vụ cần xóa");
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
JS_Position.prototype.exportCache = function (oForm) {
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
JS_Position.prototype.update = function (oForm) {
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