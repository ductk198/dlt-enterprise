/*
* Author: Duclt
* Idea: Class xu ly du lieu xml
*/
var TableXml= function(dirxml){
    this.dirxml = dirxml;
    this.xml ='';
    this.identity = 0;
    this.loadfile();
    this.htmlexport = '';
    this.row_index = '';
    this.v_current_style_name = '';
    this.v_id_column = '';
    // this.v_onclick_up = '';
    this.v_onclick = '';
    this.v_ondblclick = '';
    // this.v_onclick_down = '';
    this.v_have_move = '';
    this.v_table = '';
    this.v_pk_column = '';
    this.v_filename_column = '';
    this.content_column = '';
    this.v_append_column = '';
    this.p_arr_item = '';
    this.display_option = '';
    this.url_exec = '';
    this.pClassname = '';
    this.objectId = '';
    this.v_width = '';
    this.v_label = '';
    this.v_align = '';
    this.columnName = '';
    this.alias_name = '';
    this.xmlData = '';
    this.xmlTagInDb = '';
    this.v_value = '';
    this.tablename = '';
    this.datasearch = false;
    this.oClass ='';
    this.phpfunction = '';
    this.modaldialog = false;
    this.functionload = 'loadList()';
}
TableXml.prototype.loadfile = function(){
    var efy_xml = this;
    $.ajax({
        type: "GET",
        url: this.dirxml,
        dataType: "xml",
        success: function(xml) {
            efy_xml.xml = xml;
        }
    })
}
/*
* @Idea: Xuat man hinh danh sach
* Param: 	- arrResult: Mang du lieu can xuat ra
			- objupdate: Doi tuong can update toi
			- v_pk_column: Key cua col
*/
TableXml.prototype.exportTable = function(arrResult,v_pk_column,objupdate,fulltextsearch,frm,numRow,addrow){
    if (typeof(addrow)==='undefined') {
        addrow = true;
    };
     if (typeof(numRow)==='undefined') {
        numRow = 10;
    };
    var classXml = this;
    if(typeof(this.xml) !=='object'){
        setTimeout(function() {
            classXml.exportTable(arrResult,v_pk_column,objupdate,fulltextsearch,frm);
        }, 100);
    }else{
        classXml.generalTable(arrResult,v_pk_column,objupdate,fulltextsearch,frm,numRow,addrow);
    }
}
//Generate column of table structure
TableXml.prototype.generalTable = function(arrResult,v_pk_column,objupdate,fulltextsearch,frm,numRow,addrow){
    var efy_xml = this,count_column = '',colspan = 0, xmlString = '';
    var hdn_columnName = $('#hdn_columnName').val();
    var hdn_orderby = $('#hdn_orderby').val();
    efy_xml.v_pk_column = v_pk_column;
    efy_xml.htmlexport = '';
    var xml = this.xml, iTotalRecord = arrResult.length;
    efy_xml.htmlexport += '<table class="table table-striped table-bordered dataTable no-footer" align="center" id="table-data">';
    var psHtmlTempWidth = '', psHtmlTempLabel = '', v_type = '';
    $(xml).find('list_body col').each(function(){
        efy_xml.v_width = $(this).find('width').text();
        efy_xml.v_label = $(this).find('label').text();
        psHtmlTempLabel += '<td align="center"><b>'+ efy_xml.v_label+'</b></td>';
    })
    efy_xml.htmlexport += psHtmlTempWidth + '<thead  class="thead-inverse"><tr class="header">'+ psHtmlTempLabel + '<tr></thead>';
    // groupcol
    var groupname = $(xml).find('level1groupid').text(),v_default1 = '',function_group = $(xml).find('function_group').text(),groupdisplay='';
    if(groupname ==='')
        groupname = $(xml).find('group_column').text();
    count_column = parseInt($(xml).find('col').length);
    colspan = count_column -1;
    // lay cac phan tu trong bang
    for (var i = 0; i < iTotalRecord; i++) {
        efy_xml.objectId = arrResult[i][efy_xml.v_pk_column];
        //render rows
        $(xml).find('list_body').find('col').each(function(){
            efy_xml.v_width = $(this).find('width').text();
            efy_xml.v_label = $(this).find('label').text();
            v_type = $(this).find('type').text();
            v_type = v_type.toLowerCase();
            efy_xml.v_align = $(this).find('align').text();
            if (typeof(efy_xml.v_align) ==='undefined') efy_xml.v_align='';
            efy_xml.xmlData = $(this).find('xml_data').text();

            if (typeof(efy_xml.xmlData) ==='undefined') efy_xml.xmlData='false';//Default get data in column name
            efy_xml.columnName = $(this).find('column_name').text();
            if (typeof(efy_xml.columnName) ==='undefined') efy_xml.columnName='';
            efy_xml.alias_name = $(this).find('alias_name').text();
            if (typeof(efy_xml.alias_name) ==='undefined' || efy_xml.alias_name ==='') efy_xml.alias_name=efy_xml.columnName;
            efy_xml.xmlTagInDb = $(this).find('xml_tag_in_db').text();
            if (typeof(efy_xml.xmlTagInDb) ==='undefined') efy_xml.xmlTagInDb='';
            //efy_xml.v_value = arrResult[i][efy_xml.columnName];
            if (typeof(efy_xml.v_value) ==='undefined') efy_xml.v_value='';
            //Xử lý kiểm tra lấy dữ liệu trong column or xml string
            if(efy_xml.xmlData == 'false'){
                efy_xml.v_value = arrResult[i][efy_xml.alias_name];
            }else{
                //get xml string in database
                if (efy_xml.xmlData == 'true'){
                    xmlString = arrResult[i][efy_xml.alias_name];
                    efy_xml.v_value = efy_xml._xmlGetXmlTagValue(xmlString,"data_list",efy_xml.xmlTagInDb);
                }else{
                    ;
                }
            }
            efy_xml.v_onclick ='';
            efy_xml.v_ondblclick = ($(this).find('event_url') ? $(this).find('event_url').text() : '');
            efy_xml.htmlexport += efy_xml._generateHtmlForColumn(v_type,i);
        })
        efy_xml.htmlexport += '</tr>';

    }
    $(objupdate).html(efy_xml.htmlexport);
}
/**
* @todo: Ham xuat ra chuoi column html
* @param obj: Doi tuong duoc chon
*/
TableXml.prototype._generateHtmlForColumn = function(v_type,i){
    var htmloutput = '', efy_xml = this, cstar='unstar';
    v_type = v_type.toLowerCase();

    switch(v_type) {
        case "checkbox":
             efy_xml.v_onclick = 'select_checkbox_row(this);'+ efy_xml.v_onclick;
            htmloutput += '<td align="'+ this.v_align + '"><input type="checkbox" ondblclick="'+efy_xml.v_ondblclick+'" onclick="{'+efy_xml.v_onclick+'}" name="chk_item_id" value="'+ this.v_value +'" />';
            htmloutput  += '</td>';
            break;
        case "text_status":
            efy_xml.v_onclick = 'select_row(this);'+ efy_xml.v_onclick;
            var value_status = 'Không hoạt động';
            if(this.v_value == 'HOAT_DONG'){
                var value_status = 'Hoạt động';
            }
            htmloutput += '<td class="data" align="'+ this.v_align +'" ondblclick="'+efy_xml.v_ondblclick+'" onclick="{'+efy_xml.v_onclick+'}">' + ''+ value_status +'</td>';
            break;
        case "status":
            efy_xml.v_onclick = 'select_row(this);'+ efy_xml.v_onclick;
            var value_status = 'Không hoạt động';
            if(this.v_value == 1){
                var value_status = 'Hoạt động';
            }
            htmloutput += '<td class="data" align="'+ this.v_align +'" ondblclick="'+efy_xml.v_ondblclick+'" onclick="{'+efy_xml.v_onclick+'}">' + ''+ value_status +'</td>';
            break;
        case "text":
            efy_xml.v_onclick = 'select_row(this);'+ efy_xml.v_onclick;
            htmloutput += '<td class="data" align="'+ this.v_align +'" ondblclick="'+efy_xml.v_ondblclick+'" onclick="{'+efy_xml.v_onclick+'}">' + ''+ this.v_value +'</td>';
            break;
        //Kiểu date
        case "date":
            htmloutput += '<td class="data" align="'+ this.v_align +'" ondblclick="'+efy_xml.v_ondblclick+'" onclick="select_row(this)">' + ''+ efy_xml._yyyymmddToDDmmyyyy(this.v_value) +'</td>';
            break;
        case "identity":
            var identity = parseInt(i)+1;
            efy_xml.v_onclick = 'select_row(this);'+ efy_xml.v_onclick;
            htmloutput += '<td class="data" align="'+ this.v_align +'" ondblclick="'+efy_xml.v_ondblclick+'" onclick="{'+efy_xml.v_onclick+'}">' + ''+ identity +'</td>';
            break;
        case "inputtype":
            htmloutput += '<td class="data" align="'+ this.v_align +'" "><button><i class="fa fa-pencil fa-fw"></i></button><button><i class="fa fa-trash-o fa-fw"></i></button></td>';
            break;
        default:
            htmloutput += this.v_value;
    }
    return htmloutput;
}

TableXml.prototype._xmlGetXmlTagValue = function (sXmlString, sXmlParentTag, sXmlTag) {
    var valueReturn = "";//create default variable
    $(sXmlString).find(sXmlParentTag).each(function () {
        valueReturn = $(this).find(sXmlTag).text();
    });
    return valueReturn;
}

/**
* @todo: Ham xac dinh khi NSD chon 1 row tren man hinh danh sách
* @param obj: Doi tuong duoc chon
*/
function select_row(obj){
    var oTable = $(obj).parent().parent().parent();
    $(oTable).find('td').parent().removeClass('selected');
    $(oTable).find('td').parent().find('input[name="chk_item_id"]').prop('checked',false);
    $(obj).parent().addClass('selected');
    var attDisabled = $(obj).parent().find('input[name="chk_item_id"]').prop('disabled');
    if(typeof(attDisabled) === 'undefined' || attDisabled == ''){
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked',true);
        $(obj).parent().find('input[name="chk_item_id"]').prop('checked','checked');
    }
}
function select_checkbox_row(obj){
    if (obj.checked) {
        $(obj).parent().parent().addClass('selected');
        $(obj).prop('checked',true);
        $(obj).prop('checked','checked');
    }
    else{
        $(obj).parent().parent().removeClass('selected');
        $(obj).prop('checked',false);
    }
}
//Ham checkbox all
function checkbox_all_item_id(p_chk_obj){
    var p_chk_obj = $('#table-data').find('input[name="chk_item_id"]');
    var v_count = p_chk_obj.length;
    //remove class cua tat ca cac tr trong table
    if ($('[name="chk_all_item_id"]').is(':checked')) {
        $(p_chk_obj).each(function() {
          $(this).prop('checked',true);
           $(this).parent().parent().addClass('selected');
        });
    }else{
        $(p_chk_obj).each(function() {
          $(this).prop('checked',false);
           $(this).parent().parent().removeClass('selected');
        });   
    }
}