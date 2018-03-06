function JS_TempHtml(obj){
	
}

JS_TempHtml.prototype.zend_user = function(arrResult){
	var htmls = '';
	htmls +='<table id="tb_list_record" class="table table-bordered">';
	htmls +='<thead>';
    htmls +='<tr class="thead-inverse">';
    htmls +='<th align="center" class="col-md-1" style="width:2%;">STT</td>';
    htmls +='<th align="center" class="col-md-3">Tên người dùng</th>';
    htmls +='<th align="center" class="col-md-3">Tên đăng nhập</td>';
    htmls +='<th align="center" class="col-md-2">Vai trò</td>';
    htmls +='<th align="center" class="col-md-2">Trạng thái</td>';
    htmls +='<th align="center" class="col-md-1"></td>';
    htmls +='</tr>';
    htmls +='</thead>';
    htmls +='<tbody>';
    htmls +='</tbody>';
    i=1;
    var role = status = '';
    var name = '';
	$.each( arrResult, function( key, value ) {
		name = value.C_POSITION + ' - ' + value.C_NAME;
		role = 'Người dùng';
		if(value.C_ROLE == 'ADMIN_SYSTEM'){
			role = 'Quản trị hệ thống';
		}else if(value.C_ROLE == 'ADMIN_OWNER'){
			role = 'Quản trị đơn vị triển khai';
		}
		if(value.C_STATUS == 'HOAT_DONG'){
			status = 'Hoạt động';
		}else{
			status = 'Không hoạt động';
		}
		htmls +='<tr class="tr-tree-user" id-user="'+value.PK_STAFF+'">';
		htmls +='<td align="center">' + value.C_ORDER + '</td>';
		htmls +='<td></span> ' + name  + '</td>';
		htmls +='<td>' + value.C_USERNAME + '</td>';
		htmls +='<td>' + role + '</td>';
		htmls +='<td>' + status + '</td>';
		htmls +='<td><a class="user-edit" id-user="'+value.PK_STAFF+'"><span class="glyphicon glyphicon-pencil"></span></a> | <a class="user-delete" id-user="'+value.PK_STAFF+'"  name-user ="' + value.C_NAME + '"><span class="glyphicon glyphicon-trash"></span></a></td>';
		htmls +='</tr>';
		i++;
	});
	htmls +='</table>';
	return htmls;
}

JS_TempHtml.prototype.zend_unit = function(objTree,node){
	var check_loaded = false;
	if(node.state.loaded){
		check_loaded = true;
	}
	var htmls = '';
	htmls +='<table id="tb_list_record" class="table table-hover">';
    htmls +='<thead>';
    htmls +='<tr class="thead-inverse">';
    htmls +='<th align="center" class="col-md-1" style="width:2%;">STT</td>';
    htmls +='<th align="center" class="col-md-5">Tên đơn vị</th>';
    htmls +='<th align="center" class="col-md-3">Mã đơn vị</td>';
    htmls +='<th align="center" class="col-md-2">Trạng thái</td>';
    htmls +='<th align="center" class="col-md-1"></td>';
    htmls +='</tr>';
    htmls +='</thead>';
    htmls +='<tbody>';
    htmls +='</tbody>';
    i=1;
    var _class = v_status = '';
    var id = order = name = code = address = status = '';
    for (var prop in node.children) {
    	if(check_loaded){
    		var objchild = objTree.jstree(true).get_node(node.children[prop]);
    		id = objchild.original.id;
    		order = objchild.original.order;
    		name = objchild.original.name;
    		code = objchild.original.code;
    		address = objchild.original.address;
    		status = objchild.original.status;
    		_class = this.get_node_level(objchild.original.node_lv,objchild.original.type);
    	}else{
    		id = node.children[prop].id;
    		order = node.children[prop].order;
    		name = node.children[prop].name;
    		code = node.children[prop].code;
    		address = node.children[prop].address;
    		status = node.children[prop].status;
    		_class = this.get_node_level(node.children[prop].node_lv,node.children[prop].type);
    	}
    	if(status == 'HOAT_DONG'){
    		v_status = 'Hoạt động';
    	}else{
    		v_status = 'Không hoạt động';
    	}
		htmls +='<tr class="tr-tree-unit" id-unit="'+id+'">';
		htmls +='<td align="center">' + order + '</td>';
		htmls +='<td><i class="'+_class+'"></i> ' + name + '</td>';
		htmls +='<td>' + code + '</td>';
		htmls +='<td>' + v_status + '</td>';
		htmls +='<td><a class="unit-edit" id-unit="'+id+'"><span  class="glyphicon glyphicon-pencil"></span></a> | <a class="delete-unit" id-unit="'+id+'" name-unit =  "' + name + '"><span class="glyphicon glyphicon-trash"></span></a></td>';
		htmls +='</tr>';
		i++;
	};
	htmls +='</table>';
	return htmls;
}


JS_TempHtml.prototype.zend_unit_search = function(arrResult,node){
	var htmls = '';
	 var myClass = this;
	htmls +='<table id="tb_list_record" class="table table-hover">';
    htmls +='<thead>';
    htmls +='<tr class="thead-inverse">';
    htmls +='<th align="center" class="col-md-5">Tên đơn vị</th>';
    htmls +='<th align="center" class="col-md-2">Mã đơn vị</td>';
    htmls +='<th align="center" class="col-md-4">Địa chỉ</td>';
    htmls +='<th align="center" class="col-md-1"></td>';
    htmls +='</tr>';
    htmls +='</thead>';
    htmls +='<tbody>';
    htmls +='</tbody>';
    i=1;
    var _class = this.get_node_level(3,'');
	$.each( arrResult, function( key, value ) {
		htmls +='<tr>';
		htmls +='<td><i class="'+_class+'"></i> ' + value.C_NAME + '</td>';
		htmls +='<td>' + value.C_CODE + '</td>';
		htmls +='<td>' + value.C_ADDRESS + '</td>';
		htmls +='<td><a class="unit-edit" id-unit="'+value.PK_UNIT+'"><span  class="glyphicon glyphicon-pencil"></span></a> | <a class="unit-delete" id-unit="'+value.PK_UNIT+'"><span class="glyphicon glyphicon-trash unit-delete"></span></a></td>';
		htmls +='</tr>';
		i++;
	});
	htmls +='</table>';
	return htmls;
}

JS_TempHtml.prototype.get_node_level = function(node,type){
	var icon = '';
	if(node == 1){
		icon = 'fa fa-home mfa-2x folder-lv1';
	}else if(node == 2){
		icon= 'fa fa-university fa-hight folder-lv2';
	}else if(node == 3){
		if(type == 'PHUONG_XA'){
			icon= 'fa fa-university  folder-lv3';
		}else{
			icon= 'fa fa-square  fa-hight folder-lv3';
		}
	}else if(node == 4){
		icon= 'glyphicon glyphicon-folder-close folder-lv4';
	}else if(node == 5){
		icon= 'glyphicon glyphicon-folder-close folder-lv5';
	}else if(node == 0){
		icon= 'glyphicon glyphicon-folder-close folder-lv0';
	}
	return icon;
}


JS_TempHtml = new JS_TempHtml();