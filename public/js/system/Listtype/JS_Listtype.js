/*
 * Creater: Duclt
 * Date:01/12/2016#
 * Idea: Lop xu ly lien quan den loai danh muc#
 */

function Js_Listtype(baseUrl, module, controller) {
    // check side bar
    $("#main_listtype").attr("class", "active");
    $("#child_listtype").attr("class", "active");
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}
// Load su kien tren man hinh index
Js_Listtype.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmlisttype_index';
    myClass.loadList(oForm);
    $('.chzn-select').chosen({height: '100%', width: '100%'});
    // Them moi loai danh muc
    $(oForm).find('#btn_add').click(function () {
        myClass.add(oForm);
    });
    $(oForm).find('#Units').change(function () {
        myClass.loadList(oForm);
    });
    $(oForm).find('#btn_edit').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#btn_export_cache').click(function () {
        myClass.exportcache(oForm);
    });
    // Xoa doi tuong
    $(oForm).find('#btn_delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function () {
            myClass.delete(oForm);
        }
    });
    // Tim kiem loai danh muc
    $(oForm).find('#btn_search').click(function () {
        var page = $(oForm).find('#_currentPage').val();
        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
        myClass.loadList(oForm, page, perPage);

    });
    // Tim kiem
    $('#search_text').keypress(function (e) {
  	  if(e.which == 13 && e.shiftKey) {
  		  JS_Search.search_file('content');
  	   }
  	  if(e.which == 13)  // the enter key code
  	   {
	  		var page = $(oForm).find('#_currentPage').val();
	        var perPage = $(oForm).find('#cbo_nuber_record_page').val();
	        myClass.loadList(oForm, page, perPage);
  	   }
    });
}
// Load su kien tren cac minh khac
Js_Listtype.prototype.loadevent = function (oForm) {
	$('.chzn-select').chosen({height: '100%', width: '100%'});
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    });
}
// Lay du lieu cho man hinh danh sach
Js_Listtype.prototype.loadList = function (oForm, currentPage = 1, perPage = 15) {
    var loadding = DLTLib.loadding();
    loadding.go(20);
    var myClass = this;
    var url = myClass.urlPath + '/loadList';
    var filexml = $("#_filexml").val();
    var dirxml = myClass.baseUrl + '/xml/System/listtype/' + filexml;
    if (typeof (Tablelisttype) === 'undefined') {
        Tablelisttype = new TableXml(dirxml);
    }
    var search = $(oForm).find('#inp_search').val();
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
            Tablelisttype.exportTable(arrResult.Dataloadlist.data, 'id', $('div#table-container'), '', oForm);
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
Js_Listtype.prototype.add = function (oForm) {
    var url = this.urlPath + '/add';
    var myClass = this;
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addListypeModal').html(arrResult);
            $('#addListypeModal').modal('show');
            var oForm = $('form#frmAddListType');
            myClass.loadevent(oForm);
        }
    });
}
// sua
Js_Listtype.prototype.edit = function (oForm) {
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
        DLTLib.alertMessage('danger', "Bạn chưa chọn danh mục cần sửa");
        return false;
    }
    if (i > 1) {
        DLTLib.alertMessage('danger', "Bạn chỉ được chọn một danh mục để sửa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#addListypeModal').html(arrResult);
            $('#addListypeModal').modal('show');
            var oForm = $('form#frmAddListType');
            myClass.loadevent(oForm);
        }
    });
}
// Them loai danh muc
Js_Listtype.prototype.delete = function (oForm) {
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
        DLTLib.alertMessage('danger', "Bạn chưa chọn danh mục cần xóa");
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
                myClass.loadList('form#frmlisttype_index');
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
Js_Listtype.prototype.update = function (oForm) {
    var url = this.urlPath + '/update';
    var myClass = this;
    $listownercode = '';
    $('[name="GROUP_OWNERCODE"]:checked').each(function (index) {
        $listownercode += $(this).val() + ",";
    });
    var data = $(oForm).serialize();
    data += '&ListOwnercode=' + $listownercode;
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#addListypeModal').modal('hide');
                myClass.loadList('form#frmlisttype_index');
                DLTLib.alertMessage('success', arrResult['message']);
            } else {
                DLTLib.alertMessage('danger', arrResult['message']);
            }
        },
        error: function (arrResult) {
            DLTLib.alertMessage('warning', arrResult.responseJSON, 6000);
        }
    });
}

// Xuat cache loai danh muc
Js_Listtype.prototype.exportcache = function (oForm) {
    var url = this.urlPath + '/exportcache';
    var myClass = this;
    var data = $(oForm).serialize();
    DLTLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                $('#addListypeModal').modal('hide');
                myClass.loadList('form#frmlisttype_index');
                DLTLib.alertMessage('success', arrResult['message']);

            } else {
                DLTLib.alertMessage('danger', arrResult['message']);
            }
            DLTLib.successLoadImage();
        },
        error: function (arrResult) {
            DLTLib.alertMessage('warning', arrResult.responseJSON);
            DLTLib.successLoadImage();
        }
    });
}