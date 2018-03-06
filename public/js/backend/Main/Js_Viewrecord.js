function classchild(ownercode) {
    $('tr.class' + ownercode).toggle();
}
function roundToTwo(num) {
    return +(Math.round(num + "e+2") + "e-2");
}
function Js_Viewrecord(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.controller = controller;
    this.urlPath = baseUrl + '/' + module;//Biên public lưu tên module
    this.objBodyGenaral = $('#genaral-header');
}
// Load su kien tren man hinh index
Js_Viewrecord.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmviewrecord_index';
    myClass.loadevent()
    DLTLib.daterangepicker($('input[name="daterange"]'));
    myClass.loadlist(oForm);
    $(oForm).find('#btn_search').click(function () {
        myClass.loadlist(oForm);
    });

    $('.breadcrumb1').click(function () {
        $('div#duclt').hide();
        $('div#table_all_unit').show();
        $('div#title-huongdan span#child_1 a').html('');
    })
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
Js_Viewrecord.prototype.loadevent = function () {
    var myClass = this;
    $('.redirect').click(function () {
        myClass.loadallrecord(this);
    });

}

// Load su kien tren cac minh khac
Js_Viewrecord.prototype.loadlist = function (oForm) {
    //kiem tra xem co ton tai don vi tim kiem hay khong
    var checksearch = false;
    $("#ownerunit > option").each(function () {
        if (this.value !== "") {
            checksearch = true;
        }
    });
    if (!checksearch) {
        DLTLib.alertMessage('danger', "Không tìm thấy đơn vị để tìm kiếm!");
        return false;
    }
    var myClass = this;
    var url = myClass.urlPath + '/loadList';
//    $("#btn_search").attr("disabled", "");
    var iTotalRecord = this.iTotalRecord;
    DLTLib.showmainloadding();

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
            var index = 1;
            for (var x in arrResult) {
                html += myClass.genHtmlrecord(arrResult[x], index);
                index++;
            }
            $('table#tablesorter tr').not(':eq(0),:eq(1),:eq(2),:eq(3),:eq(4)').remove();
            $('table#tablesorter tr#data_append').after(html);
            DLTLib.successLoadImage();
            myClass.loadevent();
        }
    });
}
Js_Viewrecord.prototype.genHtmlrecord = function (arrValue, index) {
    var count = arrValue.length, htmls = '';
    for (var i = 0; i < count; i++) {
        var sClass,sClass1, sAction, sTyLeDangGQ = '', sTyLeDaGQ = '', sClasschild;
        sClass = 'style=" text-align:center" class="redirect normal_label" ';
        sClass1 = 'style=" text-align:center" class="normal_label" ';
        sAction = 'onmouseover="this.style.backgroundColor=\'#ABD8FF\'" onmouseout="this.style.backgroundColor=\'\'"';
        if (arrValue[i]['C_TONG_VV'] > 0) {
            if (arrValue[i]['C_DANG_GQ_DH'] > 0) {
                sTyLeDangGQDH = roundToTwo(arrValue[i]['C_DANG_GQ_DH'] / arrValue[i]['C_TONG_DANG_GIAI_QUYET'] * 100);
            }
            if (arrValue[i]['C_DANG_GQ_QH'] > 0) {
                sTyLeDangGQQH = roundToTwo(arrValue[i]['C_DANG_GQ_QH'] / arrValue[i]['C_TONG_DANG_GIAI_QUYET'] * 100);
            }
            if (arrValue[i]['C_DA_GQ_DH'] > 0) {
                sTyLeDaGQDH = roundToTwo(arrValue[i]['C_DA_GQ_DH'] / arrValue[i]['C_TONG_DA_GIAI_QUYET'] * 100);
            }
            if (arrValue[i]['C_DA_GQ_QH'] > 0) {
                sTyLeDaGQQH = roundToTwo(arrValue[i]['C_DA_GQ_QH'] / arrValue[i]['C_TONG_DA_GIAI_QUYET'] * 100);
            }
        }

        arrValue[i]['C_TONG_DANG_GIAI_QUYET'] = parseInt(arrValue[i]['C_TONG_DANG_GIAI_QUYET']) + parseInt(arrValue[i]['C_DON_PB_CHO_NHAN']);
        if (i == 0) {
            sClasschild = 'class' + arrValue[i]['C_CODE'];
            htmls += '<tr class="tr_data" >';
            htmls += '<td class="normal_label" style="width: 2%; text-align:center">' + index + '</td>';
            htmls += '<td class="normal_label" searchGenaral" onclick="classchild(\'' + arrValue[i]['C_CODE'] + '\')" ownerReceive="' + arrValue[i]['C_CODE'] + '" style="width: 18; font-weight:bold">' + arrValue[i]['C_NAME'] + '</td>';
            htmls += '<td ' + sClass1 + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" ownername="' + arrValue[i]['C_NAME'] + '" status="C_DON_NHAN_TRONG_KY" >' + (arrValue[i]['C_DON_NHAN_TRONG_KY'] == '0' ? '' : arrValue[i]['C_DON_NHAN_TRONG_KY']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_CHUA_PHAN_LOAI" >' + (arrValue[i]['C_CHUA_PHAN_LOAI'] == '0' ? '' : arrValue[i]['C_CHUA_PHAN_LOAI']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TTQ_XL" >' + (arrValue[i]['C_TTQ_XL'] == '0' ? '' : arrValue[i]['C_TTQ_XL']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TTQ_LD" >' + (arrValue[i]['C_TTQ_LD'] == '0' ? '' : arrValue[i]['C_TTQ_LD']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TRA_LAI" >' + (arrValue[i]['C_TRA_LAI'] == '0' ? '' : arrValue[i]['C_TRA_LAI']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_HUONG_DAN" >' + (arrValue[i]['C_HUONG_DAN'] == '0' ? '' : arrValue[i]['C_HUONG_DAN']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_CHUYEN_DON" >' + (arrValue[i]['C_CHUYEN_DON'] == 0 ? '' : arrValue[i]['C_CHUYEN_DON']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_VV_KY_TRUOC" >' + (arrValue[i]['C_VV_KY_TRUOC'] == '0' ? '' : arrValue[i]['C_VV_KY_TRUOC']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TONG_VV" >' + (arrValue[i]['C_TONG_VV'] == '0' ? '' : arrValue[i]['C_TONG_VV']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TONG_DA_GIAI_QUYET" >' + (arrValue[i]['C_TONG_DA_GIAI_QUYET'] == '0' ? '' : arrValue[i]['C_TONG_DA_GIAI_QUYET']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DA_GQ_DH" >' + (arrValue[i]['C_DA_GQ_DH'] == '0' ? '' : arrValue[i]['C_DA_GQ_DH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #0000FF;">' + (arrValue[i]['C_DA_GQ_DH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DA_GQ_DH'] / arrValue[i]['C_TONG_DA_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DA_GQ_QH" >' + (arrValue[i]['C_DA_GQ_QH'] == '0' ? '' : arrValue[i]['C_DA_GQ_QH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: red;">' + (arrValue[i]['C_DA_GQ_QH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DA_GQ_QH'] / arrValue[i]['C_TONG_DA_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TONG_DANG_GIAI_QUYET" >' + (arrValue[i]['C_TONG_DANG_GIAI_QUYET'] == '0' ? '' : arrValue[i]['C_TONG_DANG_GIAI_QUYET']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DON_PB_CHO_NHAN" >' + (arrValue[i]['C_DON_PB_CHO_NHAN'] == '0' ? '' : arrValue[i]['C_DON_PB_CHO_NHAN']) + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DANG_GQ_DH" >' + (arrValue[i]['C_DANG_GQ_DH'] == '0' ? '' : arrValue[i]['C_DANG_GQ_DH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #0000FF;">' + (arrValue[i]['C_DANG_GQ_DH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DANG_GQ_DH'] / arrValue[i]['C_TONG_DANG_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DANG_GQ_QH" >' + (arrValue[i]['C_DANG_GQ_QH'] == '0' ? '' : arrValue[i]['C_DANG_GQ_QH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: red;">' + (arrValue[i]['C_DANG_GQ_QH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DANG_GQ_QH'] / arrValue[i]['C_TONG_DANG_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass1 + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DON_TRUNG" >' + (arrValue[i]['C_DON_TRUNG'] == '0' ? '' : arrValue[i]['C_DON_TRUNG']) + '</td>';
        } else {
            htmls += '<tr class="tr_data ' + sClasschild + '" name="' + sClasschild + '" style="display:none" >';
            htmls += '<td class="normal_label" style="width: 2%; text-align:center"></td>';
            htmls += '<td class="normal_label" searchGenaral" ownerReceive="' + arrValue[i]['C_CODE'] + '" style="width: 18;  font-style: italic">' + index + '.' + i + '. ' + arrValue[i]['C_NAME'] + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '" ownername="' + arrValue[i]['C_NAME'] + '" status="C_DON_NHAN_TRONG_KY" >' + (arrValue[i]['C_DON_NHAN_TRONG_KY'] == '0' ? '' : arrValue[i]['C_DON_NHAN_TRONG_KY']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_CHUA_PHAN_LOAI" >' + (arrValue[i]['C_CHUA_PHAN_LOAI'] == '0' ? '' : arrValue[i]['C_CHUA_PHAN_LOAI']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TTQ_XL" >' + (arrValue[i]['C_TTQ_XL'] == '0' ? '' : arrValue[i]['C_TTQ_XL']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TTQ_LD" >' + (arrValue[i]['C_TTQ_LD'] == '0' ? '' : arrValue[i]['C_TTQ_LD']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TRA_LAI" >' + (arrValue[i]['C_TRA_LAI'] == '0' ? '' : arrValue[i]['C_TRA_LAI']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_HUONG_DAN" >' + (arrValue[i]['C_HUONG_DAN'] == '0' ? '' : arrValue[i]['C_HUONG_DAN']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_CHUYEN_DON" >' + (arrValue[i]['C_CHUYEN_DON'] == 0 ? '' : arrValue[i]['C_CHUYEN_DON']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_VV_KY_TRUOC" >' + (arrValue[i]['C_VV_KY_TRUOC'] == '0' ? '' : arrValue[i]['C_VV_KY_TRUOC']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TONG_VV" >' + (arrValue[i]['C_TONG_VV'] == '0' ? '' : arrValue[i]['C_TONG_VV']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TONG_DA_GIAI_QUYET" >' + (arrValue[i]['C_TONG_DA_GIAI_QUYET'] == '0' ? '' : arrValue[i]['C_TONG_DA_GIAI_QUYET']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DA_GQ_DH" >' + (arrValue[i]['C_DA_GQ_DH'] == '0' ? '' : arrValue[i]['C_DA_GQ_DH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #0000FF;">' + (arrValue[i]['C_DA_GQ_DH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DA_GQ_DH'] / arrValue[i]['C_TONG_DA_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DA_GQ_QH" >' + (arrValue[i]['C_DA_GQ_QH'] == '0' ? '' : arrValue[i]['C_DA_GQ_QH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: red;">' + (arrValue[i]['C_DA_GQ_QH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DA_GQ_QH'] / arrValue[i]['C_TONG_DA_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_TONG_DANG_GIAI_QUYET" >' + (arrValue[i]['C_TONG_DANG_GIAI_QUYET'] == '0' ? '' : arrValue[i]['C_TONG_DANG_GIAI_QUYET']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DON_PB_CHO_NHAN" >' + (arrValue[i]['C_DON_PB_CHO_NHAN'] == '0' ? '' : arrValue[i]['C_DON_PB_CHO_NHAN']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DANG_GQ_DH" >' + (arrValue[i]['C_DANG_GQ_DH'] == '0' ? '' : arrValue[i]['C_DANG_GQ_DH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #0000FF;">' + (arrValue[i]['C_DANG_GQ_DH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DANG_GQ_DH'] / arrValue[i]['C_TONG_DANG_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DANG_GQ_QH" >' + (arrValue[i]['C_DANG_GQ_QH'] == '0' ? '' : arrValue[i]['C_DANG_GQ_QH']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: red;">' + (arrValue[i]['C_DANG_GQ_QH'] <= 0 ? '' : (roundToTwo(arrValue[i]['C_DANG_GQ_QH'] / arrValue[i]['C_TONG_DANG_GIAI_QUYET'] * 100)) + '%') + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['C_CODE'] + '"  ownername="' + arrValue[i]['C_NAME'] + '" status="C_DON_TRUNG" >' + (arrValue[i]['C_DON_TRUNG'] == '0' ? '' : arrValue[i]['C_DON_TRUNG']) + '</td>';

        }


    }
    return htmls;
}
//hien thi cấu trúc header BM
Js_Viewrecord.prototype.showhidecate = function (obj) {
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

Js_Viewrecord.prototype.loadlistype = function (form, GroupSelectBox, SelectBox, listtype, TagGroup, textdefault, valueselect, xmltaglist) {
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

Js_Viewrecord.prototype.sortTable = function () {
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
Js_Viewrecord.prototype.loadallrecord = function (obj) {
    var myClass = this;
    var url = myClass.urlPath + '/getallrecord';
    var ownername = $(obj).attr('ownername');
    DLTLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        //cache: true,
        data: {
            daterange: $('#ip_search').val(),
            _token: $('#_token').val(),
            ownercode: $(obj).attr('ownerreceive'),
            recordtype: $(obj).attr('status')
        },
        success: function (arrResult) {
            var shtml;
            $('div#duclt').show();
            $('div#table_all_unit').hide();
            $('div#title-huongdan span#child_1 a').html('>>' + ownername);
            if ($(obj).attr('status') == 'C_TONG_VV') {
                shtml = myClass.gentableresuleC_TONG_VV(arrResult, 'C_TONG_VV');
                $('div#duclt').html(myClass.genheader('BMTC04'));
                $('table#BMTC04 tr#data_append_sum').after(shtml);
            } else if ($(obj).attr('status') == 'C_DON_NHAN_TRONG_KY') {
                shtml = myClass.gentableresuleC_TONG_VV(arrResult, 'BMTC01');
                $('div#duclt').html(myClass.genheader('BMTC01'));
                $('table#BMTC01 tr#data_append').after(shtml);
            } else {
                shtml = myClass.gentableresuleC_TONG_VV(arrResult, 'BMTC02');
                $('div#duclt').html(myClass.genheader('BMTC02'));
                $('table#BMTC02 tr#data_append_detail').after(shtml);
            }
            DLTLib.successLoadImage();
        }
    });
}
Js_Viewrecord.prototype.genheader = function (optionView) {
    var htmls = '';
    switch (optionView) {
        case 'BMTC04':
            htmls += '<table id="BMTC04" class="table-bordered table table-striped dataTable no-footer list-table-data ">';
            htmls += '<col width="2%"></col><col width="25%"></col>';
            htmls += '<col width="5%"></col><col width="5%"></col>';
            htmls += '<col width="5%"></col><col width="5%"></col>';
            htmls += '<col width="5%"></col><col width="5%"></col>';
            htmls += '<col width="5%"></col><col width="5%"></col>';
            htmls += '<col width="5%"></col><col width="5%"></col>';
            htmls += '<col width="5%"></col><col width="5%"><col width="5%"></col>';
            htmls += '<col width="5%"></col>';
            htmls += '<tbody><tr>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3"></td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3">Đơn vị được giao giải quyết</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3">Tổng số vụ việc</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3">Kỳ trước chuyển sang</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3">Trong kỳ</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3">Chờ nhận</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="5">Đã giải quyết</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="5">Đang giải quyết</td>';

            htmls += '</tr>';
            htmls += '<tr >';
            htmls += '<td class="header" style="text-align: center;" rowspan="2" >Tổng</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="2">Đúng hạn</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="2">Quá hạn</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="2" >Tổng</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="2">Chưa tới hạn</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="2">Quá hạn</td>';
            htmls += '</tr>';
            htmls += '<tr >';
            htmls += '<td class="header" style="text-align: center;" >SL</td>';
            htmls += '<td class="header" style="text-align: center;color: #0000FF;" >Tỷ lệ %</td>';
            htmls += '<td class="header" style="text-align: center;" >SL</td>';
            htmls += '<td class="header" style="text-align: center;color: #FF0000;" >Tỷ lệ %</td>';
            htmls += '<td class="header" style="text-align: center;" >SL</td>';
            htmls += '<td class="header" style="text-align: center;color: #0000FF;" >Tỷ lệ %</td>';
            htmls += '<td class="header" style="text-align: center;" >SL</td>';
            htmls += '<td class="header" style="text-align: center;color: #FF0000;" >Tỷ lệ %</td>';
            htmls += '</tr>';
            htmls += '<tr class="interpret">';
            htmls += '<td class="header">(A)</td>';
            htmls += '<td class="header">(B)</td>';
            htmls += '<td class="header">(1)=2+3+4</td>';
            htmls += '<td class="header">(2)</td>';
            htmls += '<td class="header">(3)</td>';
            htmls += '<td class="header">(4)</td>';
            htmls += '<td class="header">(5)=6+8</td>';
            htmls += '<td class="header">(6)</td>';
            htmls += '<td class="header">(7)=6/5</td>';
            htmls += '<td class="header">(8)</td>';
            htmls += '<td class="header">(9)=8/5</td>';
            htmls += '<td class="header">(10)=11+13</td>';
            htmls += '<td class="header">(11)</td>';
            htmls += '<td class="header">(12)=11/10</td>';
            htmls += '<td class="header">(13)</td>';
            htmls += '<td class="header">(14)=13/10</td>';
            htmls += '</tr>';
            htmls += '<tr id="data_append_sum"></tr>';
            htmls += '</tbody>';
            htmls += '</table>';
            break;
        case 'BMTC01':
            htmls += '<table id ="BMTC01" class="table-bordered table table-striped dataTable no-footer list-table-data ">';
            htmls += '<col width="3%"></col><col width="8%"></col>';
            htmls += '<col width="12%"></col><col width="28%"></col>';
            htmls += '<col width="45%"></col><col width="4%"></col>';
            htmls += '<tbody><tr>';
            htmls += '<td class="header" style="text-align: center;" ">STT</td>';
            htmls += '<td class="header" style="text-align: center;" >Ngày nhận</td>';
            htmls += '<td class="header" style="text-align: center;" >Tổ chức/Cá nhân</td>';
            htmls += '<td class="header" style="text-align: center;" >Địa chỉ</td>';
            htmls += '<td class="header" style="text-align: center;" >Nội dung đơn</td>';
            htmls += '<td class="header" style="text-align: center;" >#</td>';
            htmls += '</tr>';
            htmls += '<tr id="data_append"></tr>';
            htmls += '</tbody>';
            htmls += '</table>';
            break;
        case 'BMTC02':
            htmls += '<table id="BMTC02" class="table-bordered table table-striped dataTable no-footer list-table-data ">';
            htmls += '<col width="3%"></col><col width="7%"></col>';
            htmls += '<col width="11%"></col><col width="14%"></col>';
            htmls += '<col width="18%"></col><col width="19%"></col>';
            htmls += '<col width="10%"></col>';
            htmls += '<col width="10%"></col><col width="4%"></col>';
            htmls += '</col><col width="4%"></col>';
            htmls += '<tbody><tr>';
            htmls += '<td class="header" style="text-align: center;" >STT</td>';
            htmls += '<td class="header" style="text-align: center;" >Ngày nhận</td>';
            htmls += '<td class="header" style="text-align: center;" >Tổ chức/Cá nhân</td>';
            htmls += '<td class="header" style="text-align: center;" >Địa chỉ</td>';
            htmls += '<td class="header" style="text-align: center;" >Nội dung đơn</td>';
            htmls += '<td class="header" style="text-align: center;" >Nơi xử lý</td>';
            htmls += '<td class="header" style="text-align: center;" >Cán bộ thụ lý</td>';
            htmls += '<td class="header" style="text-align: center;" >Ngày quận giao việc</td>';
            htmls += '<td class="header" style="text-align: center;" >Hạn trả lời</td>';
//            htmls += '<td class="header" style="text-align: center;" >Kết quả</td>';
            htmls += '<td class="header" style="text-align: center;" >#</td>';
            htmls += '</tr>';
            htmls += '<tr id="data_append_detail"></tr>';
            htmls += '</tbody>';
            htmls += '</table>';
            break;
        case 'BMTC03':
            htmls += '<table class="list-table-data detail_record">';
            htmls += '<col width="3%"></col><col width="8%"></col>';
            htmls += '<col width="12%"></col><col width="18%"></col>';
            htmls += '<col width="25%"></col><col width="14%"></col>';
            htmls += '<col width="16%"></col><col width="4%"></col>';
            htmls += '<tbody><tr>';
            htmls += '<td class="header" style="text-align: center;" ">STT</td>';
            htmls += '<td class="header" style="text-align: center;" >Ngày nhận</td>';
            htmls += '<td class="header" style="text-align: center;" >Tổ chức/Cá nhân</td>';
            htmls += '<td class="header" style="text-align: center;" >Địa chỉ</td>';
            htmls += '<td class="header" style="text-align: center;" >Nội dung đơn</td>';
            htmls += '<td class="header" style="text-align: center;" >Nơi thụ lý</td>'
            htmls += '<td class="header" style="text-align: center;" >Kết quả xử lý</td>'
            htmls += '<td class="header" style="text-align: center;" >#</td>';
            htmls += '</tr> </tbody>';
            htmls += '</table>';
            break;
        case 'DUPLICATE':
            htmls += '<table class="list-table-data duplicate">';
            htmls += '<col width="3%"></col><col width="8%"></col>';
            htmls += '<col width="12%"></col><col width="27%"></col>';
            htmls += '<col width="45%"></col><col width="5%"></col>';
            htmls += '<tbody><tr>';
            htmls += '<td class="header" style="text-align: center;" ">STT</td>';
            htmls += '<td class="header" style="text-align: center;" >Ngày nhận</td>';
            htmls += '<td class="header" style="text-align: center;" >Tổ chức/Cá nhân</td>';
            htmls += '<td class="header" style="text-align: center;" >Địa chỉ</td>';
            htmls += '<td class="header" style="text-align: center;" >Nội dung đơn</td>';
            htmls += '<td class="header" style="text-align: center;" >#</td>';
            htmls += '</tr> </tbody>';
            htmls += '</table>';
            break;
        default:
            htmls += '<table class="list-table-data">';
            htmls += '<col width="2%"></col><col width="13%"></col>';
            htmls += '<col width="5%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="5%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col><col width="4%"></col>';
            htmls += '<col width="4%"></col>';
            htmls += '<tbody><tr>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3"></td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3">Đơn vị</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="7">Đơn phát sinh trong kỳ</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="13">Tổng vụ việc phải giải quyết</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3">Đơn trùng</td>';
            htmls += '</tr>';
            htmls += '<tr >';
            htmls += '<td class="header" style="text-align: center;" rowspan="2">Tổng đơn</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="2">Chưa phân loại</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="2">Thuộc thẩm quyền</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="3">Không thuộc thẩm quyền</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="2">Kỳ trước chuyển sang</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="2">Tổng số vụ việc</td>';

            htmls += '<td class="header" style="text-align: center;" colspan="5">Đã giải quyết</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="6">Đang giải quyết</td>';
            htmls += '</tr>';
            htmls += '<tr >';
            htmls += '<td class="header" style="text-align: center;" >Giao thụ lý</td>';
            htmls += '<td class="header" style="text-align: center;" >Lưu đơn</td>';
            htmls += '<td class="header" style="text-align: center;" >Trả lại, lưu đơn</td>';
            htmls += '<td class="header" style="text-align: center;" >Hướng dẫn</td>';
            htmls += '<td class="header" style="text-align: center;" >Chuyển đơn</td>';
            htmls += '<td class="header" style="text-align: center;" >SL</td>';
            htmls += '<td class="header" style="text-align: center;" >Đúng hạn</td>';
            htmls += '<td class="header" style="text-align: center;color: #0000FF;" >Tỷ lệ %</td>';
            htmls += '<td class="header" style="text-align: center;" >Quá hạn</td>';
            htmls += '<td class="header" style="text-align: center;color: red;" >Tỷ lệ %</td>';
            htmls += '<td class="header" style="text-align: center;" >SL</td>';
            htmls += '<td class="header" style="text-align: center;" >Chờ nhận</td>';
            htmls += '<td class="header" style="text-align: center;" >Đúng hạn</td>';
            htmls += '<td class="header" style="text-align: center;color: #0000FF;" >Tỷ lệ %</td>';
            htmls += '<td class="header" style="text-align: center;" >Quá hạn</td>';
            htmls += '<td class="header" style="text-align: center;color: red;" >Tỷ lệ %</td>';
            htmls += '</tr>';
            htmls += '<tr class="interpret">';
            htmls += '<td class="header">(A)</td>';
            htmls += '<td class="header">(B)</td>';
            htmls += '<td class="header">(1)= 2+3+4+ 5+6+7</td>';
            htmls += '<td class="header">(2)</td>';
            htmls += '<td class="header">(3)</td>';
            htmls += '<td class="header">(4)</td>';
            htmls += '<td class="header">(5)</td>';
            htmls += '<td class="header">(6)</td>';
            htmls += '<td class="header">(7)</td>';
            htmls += '<td class="header">(8)</td>';
            htmls += '<td class="header">(9) =3+8 =10+11+16</td>';
            htmls += '<td class="header">(10)</td>';
            htmls += '<td class="header">(11)</td>';
            htmls += '<td class="header">(12)</td>';
            htmls += '<td class="header">(13) =12/11</td>';
            htmls += '<td class="header">(14)</td>';
            htmls += '<td class="header">(15) =14/11</td>';
            htmls += '<td class="header">(16)</td>';
            htmls += '<td class="header">(17)</td>';
            htmls += '<td class="header">(18) =17/15</td>';
            htmls += '<td class="header">(19)</td>';
            htmls += '<td class="header">(20) =19/15</td>';
            htmls += '<td class="header">(21)</td>';
            htmls += '</tr> </tbody>';
            htmls += '</table>';
    }

    return htmls;
}
Js_Viewrecord.prototype.gentableresuleC_TONG_VV = function (arrValue, optionview) {
    var count = arrValue.length, htmls = '', myClass = this;
    if (optionview == 'C_TONG_VV') {
        for (var i = 0; i < count; i++) {
            var sClass, sAction, sTyLeDangGQ = '', sTyLeDaGQ = '', sTyLeDaGQDungHan = '', sTyLeDaGQQuaHan = '', sTyLeDangGQDungHan = '', sTyLeDangGQQuaHan = '';
            sClass = 'style=" text-align:center" class="redirect normal_label" ';
            sAction = 'onmouseover="this.style.backgroundColor=\'#ABD8FF\'" onmouseout="this.style.backgroundColor=\'\'"';
            if (arrValue[i]['DA_GQ'] > 0) {
                if (arrValue[i]['DA_GQ_DUNG_HAN'] > 0)
                    sTyLeDaGQDungHan = roundToTwo(arrValue[i]['DA_GQ_DUNG_HAN'] * 100 / arrValue[i]['DA_GQ']);
                if (arrValue[i]['DA_GQ_QUA_HAN'] > 0)
                    sTyLeDaGQQuaHan = roundToTwo(arrValue[i]['DA_GQ_QUA_HAN'] * 100 / arrValue[i]['DA_GQ']);
            }
            if (arrValue[i]['DANG_GQ'] > 0) {
                if (arrValue[i]['DANG_GQ_CHUA_TOI_HAN'] > 0)
                    sTyLeDangGQDungHan = roundToTwo(arrValue[i]['DANG_GQ_CHUA_TOI_HAN'] * 100 / arrValue[i]['DANG_GQ']);
                if (arrValue[i]['DANG_GQ_QUA_HAN'] > 0)
                    sTyLeDangGQQuaHan = roundToTwo(arrValue[i]['DANG_GQ_QUA_HAN'] * 100 / arrValue[i]['DANG_GQ']);
            }
            htmls += '<tr class="tr_data">';
            htmls += '<td class="normal_label" style="width: 2%; text-align:center">' + (i + 1) + '</td>';
            htmls += '<td class="normal_label" searchGenaral" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" style="width: 18;">' + arrValue[i]['C_NAME'] + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="TONG_VV" >' + (arrValue[i]['TONG_VV'] == '0' ? '' : arrValue[i]['TONG_VV']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="VV_KY_TRUOC" >' + (arrValue[i]['VV_KY_TRUOC'] == '0' ? '' : arrValue[i]['VV_KY_TRUOC']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '"  ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="VV_TRONG_KY" >' + (arrValue[i]['VV_TRONG_KY'] == '0' ? '' : arrValue[i]['VV_TRONG_KY']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '"  ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="VV_CHO_NHAN" >' + (arrValue[i]['VV_CHO_NHAN'] == '0' ? '' : arrValue[i]['VV_CHO_NHAN']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="DA_GQ" >' + (arrValue[i]['DA_GQ'] == 0 ? '' : arrValue[i]['DA_GQ']) + '</td>';
            htmls += '<td ' + sClass + sAction + '  ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="DA_GQ_DUNG_HAN" >' + (arrValue[i]['DA_GQ_DUNG_HAN'] == '0' ? '' : arrValue[i]['DA_GQ_DUNG_HAN']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #0000FF;">' + sTyLeDaGQDungHan + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="DA_GQ_QUA_HAN" >' + (arrValue[i]['DA_GQ_QUA_HAN'] == '0' ? '' : arrValue[i]['DA_GQ_QUA_HAN']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #FF0000;">' + sTyLeDaGQQuaHan + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="DANG_GQ" >' + (arrValue[i]['DANG_GQ'] == '0' ? '' : arrValue[i]['DANG_GQ']) + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="DANG_GQ_CHUA_TOI_HAN" >' + (arrValue[i]['DANG_GQ_CHUA_TOI_HAN'] == '0' ? '' : arrValue[i]['DANG_GQ_CHUA_TOI_HAN']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #0000FF;">' + sTyLeDangGQDungHan + '</td>';
            htmls += '<td ' + sClass + sAction + ' ownerReceive="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" ownerProcess="' + arrValue[i]['FK_DEPARTMENT_ID'] + '" status="DANG_GQ_QUA_HAN" >' + (arrValue[i]['DANG_GQ_QUA_HAN'] == '0' ? '' : arrValue[i]['DANG_GQ_QUA_HAN']) + '</td>';
            htmls += '<td  class="normal_label" style="text-align:right;color: #FF0000;">' + sTyLeDangGQQuaHan + '</td>';

        }
    }
    if (optionview == 'BMTC02') {

        var tmp = 0;
        for (i = 0; i < count; i++) {
            if (arrValue[i]['C_OWNERS_HANDLE_DATE'] == null) {
                arrValue[i]['C_OWNERS_HANDLE_DATE'] = '';
            }
            var duplicate = '';
            if (arrValue[i]['C_NUMBER_DUPLICATE'] != '' && arrValue[i]['C_NUMBER_DUPLICATE'] != 0 && arrValue[i]['C_NUMBER_DUPLICATE']) {
                var onmouseover = 'onmouseover="this.style.backgroundColor=\'#ABD8FF\'"';
                var onmouseout = 'onmouseout="this.style.backgroundColor=\'\'" ';
                duplicate = '<label title="Xem số hồ sơ trùng lặp" class="duplicate" ' + onmouseover + onmouseout + ' style="color:blue;" > (' + arrValue[i]['C_NUMBER_DUPLICATE'] + ')</label>';
            }
            tmp++;
            htmls += '<tr class="tr_data" >';
            htmls += '<td align="center"><input type="hidden" value="' + arrValue[i]['PK_RECORD'] + '" name="chk_item_id"  ondblclick="">' + tmp + '</td>';
            htmls += '<td align = "center" class="normal_label" >' + arrValue[i]['C_RECEIVED_DATE'] + '</td>';
            htmls += '<td align = "left" class="normal_label" >' + arrValue[i]['C_REGISTOR_NAME'] + duplicate + '</td>';
            htmls += '<td align = "left" class="normal_label" >' + arrValue[i]['C_REGISTOR_ADDRESS'] + '</td>';
            htmls += '<td align = "justify" class="normal_label" >' + arrValue[i]['C_PETITION_CONTENT'] + '</td>';
            htmls += '<td align = "left" class="normal_label" >' + arrValue[i]['C_OWNER_PROCESS'] + '</td>';
            htmls += '<td align = "left" class="normal_label" >' + arrValue[i]['C_NAME_EMPLOYE'] + '</td>';
            htmls += '<td align = "left" class="normal_label" >' + arrValue[i]['C_OWNERS_HANDLE_DATE'] + '</td>';
            htmls += '<td align = "center" class="normal_label" >' + arrValue[i]['C_APPOINTED_DATE'] + '</td>';
//            htmls += '<td align = "center" class="normal_label" >' + arrValue[i]['C_KETQUA_HTML'] + '</td>';
            htmls += '<td align = "center" class="normal_label" ><a onclick="Js_Viewrecord.viewtempo(\'' + arrValue[i]['PK_RECORD'] + '\',\'' + arrValue[i]['C_OWNER_CODE'] + '\')">Xem</a></td>';
            htmls += '</tr>';
        }

    }
    if (optionview == 'BMTC01') {
        var numperpage = 5, name;
        var tmp = (1 - 1) * numperpage;
        var dfGroupRecord = '', groupName = '';
        for (i = 0; i < count; i++) {
            if (typeof (arrValue[i]['C_GROUP_RECORD']) != 'undefined') {
                var date = new Date(arrValue[i]['C_RECEIVED_DATE']);
                var day = date.getDay()+1,strday, month = (date.getMonth()+1),strmonth;
                if(day<10){
                    strday = '0'+day;
                }else{
                    strday = day;
                }
                if(month<10){
                    strmonth = '0'+month;
                }else{
                    strmonth = month;
                }
                var dateprint = strday +'/'+strmonth+'/'+date.getFullYear() + ' ' + date.getHours()+':'+date.getMinutes()+':' + date.getSeconds();
                if (dfGroupRecord != arrValue[i]['C_GROUP_RECORD']) {
                    switch (arrValue[i]['C_GROUP_RECORD']) {
                        case 'C_CHUA_PHAN_LOAI':
                            groupName = "Chưa phân loại ";
                            break;
                        case 'C_THUOC_THAM_QUYEN':
                            groupName = "Thuộc thẩm quyền";
                            break;
                        case 'C_KHONG_THUOC_THAM_QUYEN':
                            groupName = "Không thuộc thẩm quyền";
                            break;
                        case 'C_DON_TRUNG':
                            groupName = "Đơn trùng";
                            break;
                    }
                    dfGroupRecord = arrValue[i]['C_GROUP_RECORD'];
                    name = groupName + ' (' + arrValue[i]['TOTAL_RECORD'] + ')';
                    tmp = 0;
                    htmls += '<tr class="tr_group" ownerreceive="' + arrValue[i]['C_OWNER_CODE'] + '"  status="' + dfGroupRecord + '" >';
                    htmls += '<td colspan="6"><input class="groupcol" type="hidden" value="' + groupName + '" name="chk_item_group" onclick="selectrow(this);checkgroup(this)">';
                    htmls += '<div class="icon_group_open" loading="false"  value="' + groupName + '" onclick="slgroup(this)">&nbsp;</div>';
                    htmls += '<label class="icon_group" style="font-weight: bold;" >' + name + '</label>';
                    htmls += '<div class="generateStringNumberPage" style="display: -moz-stack;padding-left:10px;float:right"></div>';
                    htmls += '</td>';
                    htmls += '</tr>';
                }
                var duplicate = '';
                if (arrValue[i]['C_NUMBER_DUPLICATE'] != '' && arrValue[i]['C_NUMBER_DUPLICATE'] != 0 && arrValue[i]['C_NUMBER_DUPLICATE']) {
                    var onmouseover = 'onmouseover="this.style.backgroundColor=\'#ABD8FF\'"';
                    var onmouseout = 'onmouseout="this.style.backgroundColor=\'\'" ';
                    duplicate = '<label title="Xem số hồ sơ trùng lặp" class="duplicate" ' + onmouseover + onmouseout + ' style="color:blue;" > (' + arrValue[i]['C_NUMBER_DUPLICATE'] + ')</label>';
                }
                tmp++;
                htmls += '<tr class="tr_data">';
                htmls += '<td align="center"><input type="hidden" value="' + arrValue[i]['PK_RECORD'] + '" name="chk_item_id"  ondblclick="">' + tmp + '</td>';
                htmls += '<td align = "center" class="normal_label" >' + dateprint + '</td>';
                htmls += '<td align = "left" class="normal_label" >' + arrValue[i]['C_REGISTOR_NAME'] + duplicate + '</td>';
                htmls += '<td align = "left" class="normal_label" >' + arrValue[i]['C_REGISTOR_ADDRESS'] + '</td>';
                htmls += '<td align = "justify" class="normal_label" >' + arrValue[i]['C_PETITION_CONTENT'] + '</td>';
                htmls += '<td align = "center" class="normal_label" ><a onclick="Js_Viewrecord.viewtempo(\'' + arrValue[i]['PK_RECORD'] + '\',\'' + arrValue[i]['C_OWNER_CODE'] + '\')">Xem</a></td>';
                htmls += '</tr>';
            } else {
                var namelabel = arrValue[i]['C_NAME'] + ' (' + arrValue[i]['C_NUM'] + ')';
                var code = arrValue[i]['C_CODE']
                htmls += '<tr class="tr_group " ownerreceive="' + arrValue[i]['C_OWNER_CODE'] + '" status="' + code + '" >';
                htmls += '<td colspan="6">';
                htmls += '<input class="groupcol" type="hidden" value="' + arrValue[i]['C_NAME'] + '" name="chk_item_group" onclick="selectrow(this);checkgroup(this)">';
                htmls += '<div class="icon_group_close" loading="false"  value="' + arrValue[i]['C_NAME'] + '" onclick="slgroup(this)">&nbsp;</div>';
                htmls += '<label class="icon_group" style="font-weight: bold;" >' + namelabel + '</label>';
                htmls += '<div class="generateStringNumberPage" style="display: -moz-stack;padding-left:10px;float:right"></div>';
                htmls += '</td>';
                htmls += '</tr>';
            }
        }
    }
    return htmls;
}
function slgroup(obj) {
    var classStyle = '', v_group = '', flag = false, code = '', lbloading = '';
    var group_value = $(obj).parent().parent().find('input[type="hidden"][name="chk_item_group"]').val();
    var oTableCurrent = $(obj).parent().parent().parent().parent();
    var data = getIndexs(group_value);
    var startIndex = data.startIndex;
    var endIndex = data.endIndex;

    lbloading = $(obj).attr('loading');
    if ($(obj).hasClass('icon_group_open')) {
        $(obj).addClass('icon_group_close');
        $(obj).removeClass('icon_group_open');
        for (var i = startIndex + 1; i < endIndex; i++) {
            $(oTableCurrent).find('tr:eq(' + i + ')').hide();
        }
        ;
    } else {
        $(obj).addClass('icon_group_open');
        $(obj).removeClass('icon_group_close');
        for (var i = startIndex + 1; i < endIndex; i++) {
            $(oTableCurrent).find('tr:eq(' + i + ')').show();
        }
        ;
    }
}
function getIndexs(group_value) {
    var startIndex = 0;
    var endIndex = 0;
    var index = 0;
    $('table#BMTC01 tr').each(function () {
        if ($(this).hasClass('tr_group')) {
            v_group = $(this).find('input[type="hidden"][name="chk_item_group"]').val();
            if (v_group === group_value)
                startIndex = $(this).index();
            if (v_group != group_value && startIndex != 0 && endIndex === 0)
                endIndex = $(this).index();
        }
        if ($(this).find('input[type="hidden"]').length > 0)
            index = $(this).index();
    })
    if (endIndex === 0)
        endIndex = index + 1;
    return data = {startIndex: startIndex, endIndex: endIndex};
}
Js_Viewrecord.prototype.viewtempo = function (pkrecord, ownercode) {
    var myClass = this;
    var url = myClass.urlPath + '/viewtempo';
    var data = {
        pkrecord: pkrecord,
        _token: $('#_token').val(),
        ownercode: ownercode,
    };
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'html',
        //cache: true,
        data: data,
        success: function (arrResult) {
            $('#modal_inforRecord').html(arrResult);
            $('#modal_inforRecord').modal('show');
            DLTLib.successLoadImage();
        }
    });
}