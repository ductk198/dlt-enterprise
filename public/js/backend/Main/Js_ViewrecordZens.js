/*
 * Creater: Duclt
 * Date:01/12/2016#
 * Idea: Lop xu ly lien quan den loai danh muc#
 */
function Js_ViewrecordZens(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + '/' + module;//Biên public lưu tên module
    this.objBodyGenaral = $('#genaral-header');
}
// Load su kien tren man hinh index
Js_ViewrecordZens.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmviewrecord_index';
    EfyLib.daterangepicker($('input[name="daterange"]'));
    myClass.loadlist(oForm);
    $(oForm).find('#btn_search').click(function () {
        myClass.loadlist(oForm);
    });
    $('#UnitLevel').change(function () {

        var unitlever = $(this).val();
        var url = myClass.urlPath + '/dropdownunit';
        $.ajax({
            url: url,
            type: "POST",
            //cache: true,
            data: {
                unitlever: unitlever,
                _token: $("input[name=_token]").val()
            },
            success: function (string) {
                $('select#ownerunit option').not(':first').remove();
                $('select#ownerunit').html(string);

            }
        });
    });
}
Js_ViewrecordZens.prototype.loadevent = function (oForm) {
    var myClass = this;

}

// Load su kien tren cac minh khac
Js_ViewrecordZens.prototype.loadlist = function (oForm) {
    //kiem tra xem co ton tai don vi tim kiem hay khong
    var checksearch = false;
    $("#ownerunit > option").each(function () {
        if (this.value !== "") {
            checksearch = true;
        }
    });
    if (!checksearch) {
        EfyLib.alertMessage('danger', "Không tìm thấy đơn vị để tìm kiếm!");
        return false;
    }
    var myClass = this;
    var url = myClass.urlPath + '/loadList';
    //  $("#btn_search").attr("disabled", "");
    var iTotalRecord = this.iTotalRecord;
    EfyLib.showmainloadding();
    // kiem tra xem tim kiem all hay mot don vi
    var data = $(oForm).serialize();
    data += '&currentUnit=' + $('#ownerunit').val();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: data,
        success: function (arrResult) {
            var html = '';
            for (var x in arrResult) {
                html += myClass.genHtmlrecord(arrResult[x]);
            }
            $('table#tablesorter tr').not(':eq(0),:eq(1),:eq(2),:eq(3)').remove();
            $('table#tablesorter tr#data_append').after(html);
            EfyLib.successLoadImage();
        }
    });
}
Js_ViewrecordZens.prototype.genHtmlrecord = function (arrValue) {
    var count = arrValue.length, htmls = '';
    for (var i in arrValue) {
        var sClass, sAction, sTyLeDangGQ = '', sTyLeDaGQ = '';
        sClass = 'style=" text-align:center" class="redirect normal_label" ';
        sAction = 'onmouseover="this.style.backgroundColor=\'#ABD8FF\'" onmouseout="this.style.backgroundColor=\'\'"';
        htmls += '<tr class="tr_data">';
        htmls += '<td class="normal_label" searchGenaral" ownerReceive="' + arrValue[i]['C_CODE'] + '" style="width: 18;">' + arrValue[i]['C_NAME'] + '</td>';
        htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDTX" >' + (arrValue[i]['TDTX'] == '0' ? '' : arrValue[i]['TDTX']) + '</td>';
        htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDTX_KN" >' + (arrValue[i]['TDTX_KN'] == '0' ? '' : arrValue[i]['TDTX_KN']) + '</td>';
        htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDTX_TC" >' + (arrValue[i]['TDTX_TC'] == '0' ? '' : arrValue[i]['TDTX_TC']) + '</td>';
        htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDTX_KNPA" >' + (arrValue[i]['TDTX_KNPA'] == '0' ? '' : arrValue[i]['TDTX_KNPA']) + '</td>';
        htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDTX_KHAC" >' + (arrValue[i]['TDTX_KHAC'] == '0' ? '' : arrValue[i]['TDTX_KHAC']) + '</td>';
        htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDDK" >' + (arrValue[i]['TDDK'] == '0' ? '' : arrValue[i]['TDDK']) + '</td>';
        htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDDK_LUOT" >' + (arrValue[i]['TDDK_LUOT'] == '0' ? '' : arrValue[i]['TDDK_LUOT']) + '</td>';
        htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDDK_KN" >' + (arrValue[i]['TDDK_KN'] == 0 ? '' : arrValue[i]['TDDK_KN']) + '</td>';
        htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDDK_TC" >' + (arrValue[i]['TDDK_TC'] == '0' ? '' : arrValue[i]['TDDK_TC']) + '</td>';
        htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDDK_KNPA" >' + (arrValue[i]['TDDK_KNPA'] == '0' ? '' : arrValue[i]['TDDK_KNPA']) + '</td>';
        htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '" status="TDDK_KHAC" >' + (arrValue[i]['TDDK_KHAC'] == '0' ? '' : arrValue[i]['TDDK_KHAC']) + '</td>';
    }
    return htmls;
}
//hien thi cấu trúc header BM
Js_ViewrecordZens.prototype.showhidecate = function (obj) {
    var open = $(obj).attr('opened');
    if (open == 'true') {
        $(obj).find('i').attr('class', 'fa fa-plus-square');
        $("." + $(obj).attr('id')).hide();
        $(obj).attr('opened', 'false');
    } else {
        $("." + $(obj).attr('id')).show();
        $(obj).find('i').attr('class', 'fa fa-minus-square');
        $(obj).attr('opened', 'true');
    }
}

Js_ViewrecordZens.prototype.loadlistype = function (form, GroupSelectBox, SelectBox, listtype, TagGroup, textdefault, valueselect, xmltaglist) {
    var myClass = this;
    var url = myClass.urlPath + '/dropdowndyamic';
    $("#btn_search").attr("disabled", "");
    data = {
        GroupSelectBox: $(form).find(' #' + GroupSelectBox).val(),
        listtype: listtype,
        TagGroup: TagGroup,
        textdefault: textdefault,
        valueselect: valueselect,
        xmltaglist: xmltaglist,
        _token: $("input[name=_token]").val()
    };
    $(form).find(' #' + SelectBox).load(url, data, function () {
        $("#btn_search").removeAttr("disabled");
        //$(form).find(' select#'+SelectBox).select('refresh');
    });
}

Js_ViewrecordZens.prototype.sortTable = function () {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("tablesorter");
    switching = true;
    /*lap cac dong de sap xeptable*/
    while (switching) {
        //Neu da hoan thanh thi set lai switching
        switching = false;
        rows = table.getElementsByTagName("TR");
        /*dem so row cua table*/
        for (i = 2; i < (rows.length - 2); i++) {
            shouldSwitch = false;
            /*lay ten de so sanh:*/
            x = rows[i].getElementsByTagName("TH")[0];
            y = rows[i + 1].getElementsByTagName("TH")[0];
            //sap xep ten len tren:
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}