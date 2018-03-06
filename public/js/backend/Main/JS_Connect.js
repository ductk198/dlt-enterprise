function Js_Connect(baseUrl, module, controller) {
    this.module = module;
    this.baseUrl = baseUrl;
    this.urlPath = baseUrl + '/' + module + '/' + controller;//Biên public lưu tên module
}

// load su kien tren man hinh index
Js_Connect.prototype.loadIndex = function () {
    var myClass = this;
    var oForm = 'form#frmConnect_index';
    $(oForm).find('#txtPrintt').click(function () {
        myClass.print(oForm);
    });
    $(oForm).find('#modify').click(function () {
        myClass.edit(oForm);
    });
    $(oForm).find('#connect').click(function () {
        myClass.connect(oForm);
    });
    $(oForm).find('#delete').click(function () {
        var noti = confirm('Bạn có chắc chắn muốn xóa');
        if(noti == true){
            myClass.delete(oForm);
        }else{
            return false;
        }
    });
    $(oForm).find('.showReport').click(function () {
        myClass.showReport(oForm);
    });
} 
// load su kien tren man hinh khac
Js_Connect.prototype.loadevent = function (oForm) {
    var myClass = this;
    $(oForm).find('#btn_update').click(function () {
        myClass.update(oForm);
    })
    $(oForm).find('.create_report').click(function () {
        myClass.export(oForm);
    });
    $(oForm).find('#clsave_tttp').click(function () {
        myClass.connectgosol();
    });
    $('#frmReport_index').find('select').change(function () {
        var type_report = $('#type_report').val();
        var html = myClass.genHtmlHeader(type_report);
        $('#result').html(html);
    });
    $(oForm).find('#perodi_report').change(function(){
        myClass.getdatetime();
    });
    $(oForm).find('#year').change(function(){
        myClass.getdatetime();
    });
}
// tao bao cao
Js_Connect.prototype.print = function (oForm) {
    var url = this.urlPath + '/report';
    var myClass = this;
    var data = $(oForm).serialize();
    EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            EfyLib.successLoadImage();
            $('#addListModal').html(arrResult);
            $('#addListModal').modal('show');
            myClass.getdatetime();
            var oForm2 = $('form#frmReport_index');
            myClass.loadevent(oForm2);
            type_report = $('#type_report').val();
            var html = myClass.genHtmlHeader(type_report);
            $('#result').html(html);
        }
    });
}
// lien thong thanh tra thanh pho
Js_Connect.prototype.connectgosol = function () {
    var pkreport = $("#pkreport").val();
    var option = $('input[name=type_TTCP]:checked').val();
    if(option == "" || option == "undefined" || option == null){
        EfyLib.alertMessage('danger', "Bạn chưa chọn đầy đủ thông tin");
        return false;
    }
    var url = this.urlPath + '/connectgosol';
    var myClass = this;
    var arrdata = {
        pkreport : pkreport,
        option : option,
        _token : $('#_token').val()
    };
    EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        data: arrdata,
        success: function (arrResult) {
            if(arrResult['success']){
                window.open(arrResult['success'],'_blank');
                EfyLib.successLoadImage();
            }else{
                if(arrResult['succes']){
                    EfyLib.alertMessage('success',arrResult['message']);
                    window.location.reload();
                }
            }
            
            // window.location.reload();
        }
    });
}
// ket noi toi ttcp
Js_Connect.prototype.connect = function () {
    var url = this.urlPath + '/connectreport';
    var myClass = this;
    var myClass = this,pkrecord='',count=0;
    var oForm = $('form#frmConnect_index');
    oForm.find('input[type="checkbox"][name="chk_item_id"]:checked').each(function(){
        pkrecord += $(this).val() ;
        count++;
    })
    if(count === 0) {
        EfyLib.alertMessage('danger', "Bạn chưa chọn báo cáo");
        return false;
    }
    if(count > 1) {
        EfyLib.alertMessage('danger','Bạn chỉ được chọn một báo cáo để gửi!');
        return false;
    }
    var arrdata = {
        pkrecord : pkrecord,
        _token:$('#_token').val()
    };
    EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        data: arrdata,
        success: function (arrResult) {
            if(arrResult === 'false'){
                EfyLib.alertMessage('success','Báo cáo này đã được gửi đến TTCP!');
                EfyLib.successLoadImage();
                return false;
            }
            EfyLib.successLoadImage();
            $('#addListModal').html(arrResult);
            $('#addListModal').modal('show');
            var oForm2 = $('form#frmReport_index');
            myClass.loadevent(oForm2);
            type_report = $('#type_report').val();
            var html = myClass.genHtmlHeader(type_report);
            $('#result').html(html);
            aa = JSON.parse(aa['C_DATA']);
            htmls = myClass.gendata(aa);
            $('table#tablesorter tr#data_append').after(htmls);
            if(type_report === 'KN_BCTH01'){
                $('#BCTH01_1').css('display','none');
            }else if(type_report === 'KN_BCTH02'){
                $('#BCTH02_2').css('display','none');
            }else if(type_report === 'KN_BCTH03'){
                $('#BCTH03_3').css('display','none');
            }else{
                $('#BCTH04_4').css('display','none');
            }
        }
    });
}
// ham tinh tu ngay den ngay
Js_Connect.prototype.getdatetime = function(){
    var perodi_report = $("#perodi_report").val();
    var year = $("#year").val();
    var url = this.urlPath + '/getdatetime';
    var myClass = this;
    var arrdata = {
        perodi_report : perodi_report,
        year : year,
        _token:$('#_token').val()
    };
    $.ajax({
        url: url,
        type: 'POST',
        data: arrdata,
        dataType:'json',
        success: function(arrResult){
            $("#txtFromDate").val(arrResult[0]);
            $("#txtToDate").val(arrResult[1]);
        }
    });
    return !history.pushState;
}
// showReport
Js_Connect.prototype.showReport = function(oForm){
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    EfyLib.showmainloadding();
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
    data += '&listitem=' + listitem;
    $.ajax({
        url : url,
        type : 'POST',
        data : data,
        success : function(result){
            EfyLib.successLoadImage();
            $('#addListModal').html(result);
            $('#addListModal').modal('show');
            type_report = $('#type_report').val();
            var html = myClass.genHtmlHeader(type_report);
            $('#result').html(html);
            aa = JSON.parse(aa['C_DATA']);
            htmls = myClass.gendata(aa);
            $('table#tablesorter tr#data_append').after(htmls);
            if(type_report === 'KN_BCTH01'){
                $('#BCTH01_1').css('display','none');
                $('.create_report').css('display','none');
                $('#btn_update').css('display','none');
                $('h3').text('Báo cáo');
            }else if(type_report === 'KN_BCTH02'){
                $('#BCTH02_2').css('display','none');
                $('.create_report').css('display','none');
                $('#btn_update').css('display','none');
                $('h3').text('Báo cáo');
            }else if(type_report === 'KN_BCTH03'){
                $('#BCTH03_3').css('display','none');
                $('.create_report').css('display','none');
                $('#btn_update').css('display','none');
                $('h3').text('Báo cáo');
            }else{
                $('#BCTH04_4').css('display','none');
                $('.create_report').css('display','none');
                $('#btn_update').css('display','none');
                $('h3').text('Báo cáo');
            }
        }
    });
}
// update
Js_Connect.prototype.update = function (oForm) {
    var url = this.urlPath + '/update';
    var myClass = this;
    var data = $(oForm).serialize();
    EfyLib.showmainloadding();
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            if (arrResult['success']) {
                EfyLib.successLoadImage();
                $('#frmReport_index').modal('hide');
                EfyLib.alertMessage('success', arrResult['message']);
                window.location.reload();
            }

        },
        error: function (arrResult) {
            EfyLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}

// edit
Js_Connect.prototype.edit = function (oForm) {
    var url = this.urlPath + '/edit';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    EfyLib.showmainloadding();
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
        EfyLib.alertMessage('danger', "Bạn chưa chọn báo cáo cần sửa");
        EfyLib.successLoadImage();
        return false;
    }
    if (i > 1) {
        EfyLib.alertMessage('danger', "Bạn chỉ được chọn một báo cáo để sửa");
        return false;
    }
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            EfyLib.successLoadImage();
            $('#addListModal').html(arrResult);
            $('#addListModal').modal('show');
            var oForm2 = $('form#frmReport_index');
            myClass.loadevent(oForm2);
            type_report = $('#type_report').val();
            var html = myClass.genHtmlHeader(type_report);
            $('#result').html(html);
            aa = JSON.parse(aa['C_DATA']);
            htmls = myClass.gendata(aa);
            $('table#tablesorter tr#data_append').after(htmls);
            if(type_report === 'KN_BCTH01'){
                $('#BCTH01_1').css('display','none');
            }else if(type_report === 'KN_BCTH02'){
                $('#BCTH02_2').css('display','none');
            }else if(type_report === 'KN_BCTH03'){
                $('#BCTH03_3').css('display','none');
            }else{
                $('#BCTH04_4').css('display','none');
            }
        },
        error: function (arrResult) {
            EfyLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}

// delete
Js_Connect.prototype.delete = function (oForm) {
    var url = this.urlPath + '/delete';
    var myClass = this;
    var data = $(oForm).serialize();
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var listitem = '';
    var i = 0;
    EfyLib.showmainloadding();
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
        EfyLib.alertMessage('danger', "Bạn chưa chọn báo cáo cần xóa");
        return false;
    }
    data += '&itemId=' + listitem;
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (arrResult) {
            EfyLib.successLoadImage();
            if (arrResult['success']) {
                window.location.reload();
                EfyLib.alertMessage('success', arrResult['message']);
            } else {
                EfyLib.alertMessage('danger', arrResult['message']);
            }
        },
        error: function (arrResult) {
            EfyLib.alertMessage('danger', arrResult.responseJSON[Object.keys(arrResult.responseJSON)[0]]);
        }
    });
}

// export
Js_Connect.prototype.export = function (oForm) {
    var typereport = $("#type_report").val();
    var url = this.urlPath + '/createreport';
    var myClass = this;
    // EfyLib.alertMessage('danger', "Đang tải dữ liệu");
    EfyLib.showmainloadding();
    var oForm = $('form#frmReport_index');
    var data = $(oForm).serialize();
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (arrResult) {
            EfyLib.successLoadImage();
            if (typereport === 'KN_BCTH01') {
                for (var j in arrResult) {
                    if (arrResult[j] == '') {
                        arrResult[j] = 0;
                    }
                    $('#' + typereport + '_' + j).attr('value', arrResult[j]);
                }
            } else if (typereport === 'KN_BCTH02') {
                for (var j in arrResult) {
                    if (arrResult[j] == '') {
                        arrResult[j] = 0;
                    }
                    $('#' + typereport + '_' + j).attr('value', arrResult[j]);
                }
            }
        }
    });
}

// genHtml
Js_Connect.prototype.genHtmlHeader = function (optionView) {
    var htmls = '';
    switch (optionView) {
        case 'KN_BCTH01':
            htmls += '<table class="list-table-data table-reponsive"  id="tablesorter">';
            htmls += '<tbody>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;" colspan="8">Tiếp thường xuyên</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="8">Tiếp định kỳ và đột xuất của lãnh đạo</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="9">Nội dung tiếp công dân (số vụ việc)</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="5">Kết quả tiếp công dân (số vụ việc)</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="3">Lượt</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="3">Người</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="2">Vụ việc</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="4">Đoàn đông người</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="3">Lượt</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="3">Người</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="2">Vụ việc</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="4">Đoàn đông người</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="6">Khiếu nại</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="3">Tố cáo</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="3">Phản anh, kiến nghị, khác</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="3">Chưa được giải quyết</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="3">Đã được giải quyết </td>';
            htmls += '</tr>';
            htmls += '<tr >';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Cũ</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Mới phát sinh</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Số đoàn</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Người</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" colspan="2">Vụ việc</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Cũ</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Mới phát sinh</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Số đoàn</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="2" >Người</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Vụ việc</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4">Lĩnh vực hành chính </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Lĩnh vực tư pháp</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Lĩnh vực CT,VH, XH khác</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Lĩnh vực hành chính</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Lĩnh vực tư pháp</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Tham nhũng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Chưa có QĐ giải quyết</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Đã có QĐ giải quyết (lần 1,2, cuối cùng)</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2" >Đã có bản án của Tòa</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Cũ</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Mới phát sinh</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Cũ</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Mới phát sinh</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Về tranh chấp, đòi đất cũ, đền bù, giải tỏa</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Về chính sách</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Về nhà, tài sản</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Về chế đô CC, CV</td>';
            htmls += '</tr>';
            htmls += '<tr class="interpret">';
            for (var i = 1; i <= 30; i++) {
                htmls += '<td class="header">(' + i + ')</td>';
            }
            htmls += '<tr id="BCTH01_1">';
            for (var i = 1; i <= 30; i++) {
                htmls += '<td><input id="KN_BCTH01_' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="" name="KN_BCTH01_' + i + '"></td>';
            }
            htmls += '</tr>';
            htmls += '<tr id="data_append"></tr>';
            htmls += '</table>';
            break;
        case 'KN_BCTH02':
            htmls += '<table class="list-table-data" id="tablesorter">';
            htmls += '<tbody>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;" colspan="6">Tiếp nhận</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="19">Phân Loại Đơn Khiếu Nại,Tố Cáo(số đơn)</td>';
            htmls += '<td class="header" style="text-align: center; font-weight: lighter;" rowspan="5">Đơn Khác(kiến nghị phản ánh,đơn nặc danh)</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="5">Kết quả Xử Lý Đơn Khiếu Nại,Tố Cáo</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="4">Tổng Số Đơn</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Đơn Tiếp Nhận Trong Kỳ</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Đơn Kỳ Trước Chuyển Sang</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="4">Đơn Đủ Điều Kiện Xử Lý</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="13">Theo Nội Dung</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="3">Theo Thẩm Quyền Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="3">Theo Trình Tự Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="4">Số Văn Bản Hướng Dẫn</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="4">Số Đơn Chuyển Cơ Quan Có Thẩm Quyền</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="4">Số Công Văn Đôn Đốc Việc Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Đơn Thuộc Thẩm Quyền</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="7">Khiếu Nại</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="6">Tố Cáo</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Của Các Cơ Quan Hành Chính Các cấp</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Của Cơ Quan Tư Pháp Các Cấp</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Của Cơ Quan Đảng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Chưa Được Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Đã Được Giải Quyết Lần Đầu</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Đã Được Giải Quyết Nhiều Lần</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Đơn Có Nhiều người Đứng Tên</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Đơn Một Người Đứng Tên</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Đơn Có Nhiều người Đứng Tên</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Đơn Một Người Đứng Tên</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="5">Lĩnh Vực Hành Chính</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Lĩnh Vực Tư Pháp</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Về Đảng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Tổng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Lĩnh Vực Hành Chính</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Lĩnh Vực Tư Pháp</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Tham nhũng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Về Đảng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Lĩnh Vực Khác</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">khiếu nại</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">tố cáo</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Tổng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Liên Quan Đến Đất Đai</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Về Nhà ,Tài Sản</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Về Chính Sách Chế Độ CC,VC</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;">Lĩnh Vực CT, VH, XH Khác</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<tr class="interpret">';
            for (var i = 1; i <= 31; i++) {
                htmls += '<td class="header">(' + i + ')</td>';
            }
            htmls += '<tr id="BCTH02_2">';
            for (var i = 1; i <= 31; i++) {
                htmls += '<td><input id="KN_BCTH02_' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="" name="KN_BCTH02_' + i + '"></td>';
            }
            htmls += '</tr>';
            htmls += '<tr id="data_append"></tr>';
            htmls += '</table>';
            break;
        case 'KN_BCTH03':
            htmls += '<table class="list-table-data" id="tablesorter">';
            htmls += '<tbody>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;" colspan="4">Đơn Khiếu Nại Thuộc Thẩm Quyền</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="21">Kết Quả Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="2" colspan="2">Chấp Hành Thời Gian Giải Quyết Theo Quy Định</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="10">Việc Thi Hành Quyết Định Giải Quyết Khiếu nại</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Tổng Số Đơn Khiếu nại</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="3" rowspan="2">Trong Đó</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4" rowspan="2">Đã Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="6">Phân tích Kết Quả (Vụ Việc)</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Kiến Nghị Thu Hồi Cho Nhà nước</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Trả nại Cho Công Dân</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Số Người Được Trả Lại Quyền Lợi</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Kiến nghị Xử Lý Hành Chính</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4">Chuyển Cơ Quan Điều Tra Khởi Tố</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Tổng Số Quyết Định Phải Tổ Chức Thực Hiện trong kì Báo Cáo</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Đã Thực Hiện</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4">Thu Hồi Cho Nhà Nước</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4">Trả Nại Cho Công Dân</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Khiếu Nại Đúng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Khiếu Nại Sai</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Khiếu Nại Đúng Một Phần</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Giải Quyết Lần 1</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Giải Quyết Lần 2</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Số Vụ</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Số Đối Tượng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Kết Quả</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2"> Số Vụ Việc Giải Quyết Đúng Thời hạn </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2"> Số Vụ Việc Giải Quyết Quá Thời hạn </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2"> Phải Thu </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2"> Đã Thu </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2"> Phải Trả </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2"> Đã Trả </td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đơn nhận Trong Kỳ Báo Cáo </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đơn Tồn Kỳ Trước Chuyển sang </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tổng Số Vụ Việc </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số Đơn Thuộc Thẩm Quyền </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số Vu Việc Thuộc Thẩm Quyền </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số Vụ Việc Giải Quyết Bằng QĐ Hành Chính </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số Vụ Việc Rút Đơn Thông Qua Giải Thích, Thuyết Phục</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Công Nhận QĐ g/q lần 1 </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Hủy, sửa QĐ g/q Lần 1 </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trd) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trd) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tổng Số Người </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số Người Đã bị Xử Lý </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số Vụ Đã Khởi Tố </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số Đối Tượng Đã Khởi Tố </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trd) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trd) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trd) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trd) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đất (m2) </td>';
            htmls += '</tr>';
            htmls += '<tr class="interpret">';
            for (var i = 1; i <= 37; i++) {
                htmls += '<td class="header">(' + i + ')</td>';
            }
            htmls += '<tr id="BCTH03_3">';
            for (var i = 1; i <= 37; i++) {
                htmls += '<td><input id="KN_BCTH03_' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="" name="KN_BCTH03_' + i + '"></td>';
            }
            htmls += '</tr>';
            htmls += '<tr id="data_append"></tr>';
            htmls += '</table>';
            break;
        case 'KN_BCTH04':
            htmls += '<table class="list-table-data" id="tablesorter">';
            htmls += '<tbody>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;" colspan="4">Đơn tố cáo thuộc quyền</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="16">Kết Quả Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;" rowspan="3" colspan="2">Chấp Hành Thời Gian Giải Quyết Theo Quy Định</td>';
            htmls += '<td class="header" style="text-align: center;" colspan="10">Việc thi hành quyết định xử lý tố cáo</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Tổng số đơn tố cáo</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="3" rowspan="2">Trong Đó</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Đã Giải Quyết</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="3" rowspan="2">Phân tích Kết Quả (Vụ Việc)</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Kiến Nghị Thu Hồi Cho Nhà nước</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Trả nại Cho Công Dân</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Số Người Được Trả Lại Quyền Lợi</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2" rowspan="2">Kiến nghị Xử Lý Hành Chính</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4">Chuyển Cơ Quan Điều Tra Khởi Tố</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Tổng Số Quyết Định Phải Tổ Chức Thực Hiện trong kì Báo Cáo</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="3">Đã thực hiện xong</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4">Thu Hồi Cho Nhà Nước</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="4">Trả Nại Cho Công Dân</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Số vụ</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" rowspan="2">Số đối tượng</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Kết quả</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Phải thu</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Đã thu</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Phải thu</td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;" colspan="2">Đã thu</td>';
            htmls += '</tr>';
            htmls += '<tr>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đơn nhận trong kỳ báo cáo </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Đơn tồn kỳ trước chuyển sang </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tổng số vụ việc </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số đơn thuộc thẩm quyền </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số vụ việc thuộc thẩm quyền </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tố cáo đúng </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tố cáo sai </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tố cáo đúng một phần </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trđ) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trđ) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tổng số người </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số người đã bị xử lý </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số vụ đã khởi tố </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số đối tượng đã khởi tố </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số vụ giải quyết đúng thời hạn </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Số vụ giải quyết quá thời hạn </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trđ) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trđ) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trđ) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> đất (m2) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> Tiền (trđ) </td>';
            htmls += '<td class="header" style="text-align: center;font-weight: lighter;"> đất (m2) </td>';
            htmls += '</tr>';
            htmls += '<tr class="interpret">';
            for (var i = 1; i <= 32; i++) {
                htmls += '<td class="header">(' + i + ')</td>';
            }
            htmls += '<tr id="BCTH04_4">';
            for (var i = 1; i <= 32; i++) {
                htmls += '<td><input id="KN_BCTH04_' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="" name="KN_BCTH04_' + i + '"></td>';
            }
            htmls += '</tr>';
            htmls += '<tr id="data_append"></tr>';
            htmls += '</table>';
            break;
    }
    return htmls;
}

Js_Connect.prototype.gendata = function (arrdata) {
    var typereport = $("#type_report").val();
    var html = '';
    html += '<tr style="font-size: 15px;color: blue;">';
    if(typereport === 'KN_BCTH01'){
        for(i in arrdata){
            if(arrdata[i] == ''){
                arrdata[i] = 0;
            }
            html += '<td><input id="' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="'+arrdata[i]+'" name="' + i + '"></td>';
        }
        html += '</tr>';
    }else if(typereport === 'KN_BCTH02'){
        for(i in arrdata){
            if(arrdata[i] == ''){
                arrdata[i] = 0;
            }
            html += '<td><input id="' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="'+arrdata[i]+'" name="' + i + '"></td>';
        }
        html += '</tr>';
    }else if(typereport === 'KN_BCTH03'){
        for(i in arrdata){
            if(arrdata[i] == ''){
                arrdata[i] = 0;
            }
            html += '<td><input id="' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="'+arrdata[i]+'" name="' + i + '"></td>';
        }
        html += '</tr>';
    }else if(typereport === 'KN_BCTH04'){
        for(i in arrdata){
            if(arrdata[i] == ''){
                arrdata[i] = 0;
            }
            html += '<td><input id="' + i + '" class="normal_textbox positive-integer" type="text" style="width:80%;text-align: center;" value="'+arrdata[i]+'" name="' + i + '"></td>';
        }
        html += '</tr>';
    }
    return html;
}