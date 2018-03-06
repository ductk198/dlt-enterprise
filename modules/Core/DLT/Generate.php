<?php

namespace Modules\Core\DLT;
use Illuminate\Http\Request;
use XmlParser;
use Modules\System\Listtype\Helpers\ListtypeHelper;

/**
 * Thư viện xử lý Xml
 *
 * @author Duclt
 */
class Generate {

	public function _generateTextbox($spRetHtml,$sValue,$sDataFormat,$sformFielName,$sStrOptionXml,$sWidth){ 
		if (1==2){
			$spRetHtml .= '<span class="data">'.$sValue.'</span>';
		}else{
			if($sDataFormat == 'order_list'){
				if(is_null($sValue) || $sValue==''){
					$arrList = array();							
					$sValue = sizeof($arrList)+1;
				}					
				$spRetHtml .= '<input class="form-control input-md" type="text" id="'. $sformFielName.'" name="'.$sformFielName.'" value="'.$sValue.'"'.$sStrOptionXml.' style="width:'.$sWidth.'">';
			}elseif($sDataFormat == 'ismoney'){
			    $spRetHtml .= '<input class="form-control input-md" type="text" id="'. $sformFielName.'" name="'.$sformFielName.'" value="'.$sValue.'" '.$sStrOptionXml.'>';
			}elseif($sDataFormat == 'password'){
			    $spRetHtml .= '<input class="form-control input-md" type="password" id="'. $sformFielName.'" name="'.$sformFielName.'" value="'.$sValue.'" '.$sStrOptionXml.'>';
			}else{					
				$spRetHtml .= '<input class="form-control input-md" type="text" id="'. $sformFielName.'" name="'.$sformFielName.'" value="'.$sValue.'" '.$sStrOptionXml.'">';
			}	
		}
		return $spRetHtml;
	}

	public function _generateTextarea($spRetHtml,$sValue,$sReadonlyInEditMode,$sformFielName,$sStrOptionXml,$sWidth,$arrTagItem){ 
		if (($sReadonlyInEditMode=="true")){
			$spRetHtml .= '<span class="form-control">'.$sValue.'</span>';
		}else{
			isset($arrTagItem->row) ? $sRow = $arrTagItem->row : $sRow = '';		
			$spRetHtml .= '<textarea class="form-control" id = "'.$sformFielName.'" name="'.$sformFielName.'" rows="'.$sRow.'" style="width:'.$sWidth.'" '.$sStrOptionXml.'>'.$sValue.'</textarea>';
		}
		return $spRetHtml;
	}
	
	public function _generateCheckbox($spRetHtml,$sValue,$label_check,$sformFielName,$sStrOptionXml,$sWidth){ 
		$checked = '';
		if($sValue == 'HOAT_DONG'){
			$checked = 'checked';
		};
		$spRetHtml .= '<div class="input-group checkbox"><label><input type="checkbox" '.$checked.' id = "'.$sformFielName.'" name="'.$sformFielName.'" "'.$sStrOptionXml.'">'.$label_check.'</label></div>';
		return $spRetHtml;
	}

	public function _generateCheckboxstatus($spRetHtml,$sValue,$label_check,$sformFielName,$sStrOptionXml,$sWidth){ 
		$checked = '';
		if($sValue=='HOAT_DONG'){
			$checked = 'checked';
		};
		$spRetHtml .= '<div class="input-group checkbox"><label><input type="checkbox" '.$checked.' id = "'.$sformFielName.'" name="'.$sformFielName.'">'.$label_check.'</label></div>';
		return $spRetHtml;
	}

	public function _generateSelectbox($spRetHtml,$sValue,$arrTagItem,$sDataFormat,$column_name,$sformFielName,$sStrOptionXml,$sWidth,$sLabel){
	  	isset($arrTagItem->selectbox_option_id_column) ? $selectBoxIdColumn = (string)$arrTagItem->selectbox_option_id_column : $selectBoxIdColumn = '';
    	isset($arrTagItem->selectbox_option_name_column) ? $selectBoxNameColumn = (string)$arrTagItem->selectbox_option_name_column : $selectBoxNameColumn = '';
    	isset($arrTagItem->selectbox_option_value_column) ? $selectBoxValueColumn = (string)$arrTagItem->selectbox_option_value_column : $selectBoxValueColumn = '';
    	$ListtypeHelper = new ListtypeHelper();
    	$objlibrary = new library();
    	$arrListItem = array();
        // echo $spRetHtml = $spRetHtml . $v_str_label;die();
        if ($sDataFormat == "xmlserver") {
        	$arrListItem = $objlibrary->_dirToArray('\xml\System\list',$selectBoxIdColumn);
        }else if ($sDataFormat == "listtype") {
        	isset($arrTagItem->list_type) ? $list_type = (string)$arrTagItem->list_type : $list_type = '';
        	$arrListItem = $ListtypeHelper->_GetAllListObjectByListCode($list_type);
        }
        $spRetHtml = $spRetHtml . "<select class='form-control input-sm chzn-select' ".$sStrOptionXml." name=".$column_name." style='width:$sWidth'>";
            $spRetHtml = $spRetHtml . "<option id='' value='' name=''>--- Chọn $sLabel ---</option>";
            $spRetHtml = $spRetHtml . $objlibrary->_generateSelectOption($arrListItem, $selectBoxIdColumn, $selectBoxValueColumn, $selectBoxNameColumn,$sValue);
            $spRetHtml = $spRetHtml . "</select>";
        return $spRetHtml;
    }

    public function _generateMultiplecheckbox($spRetHtml,$sValue,$arrTagItem,$sDataFormat,$column_name,$sformFielName,$sStrOptionXml,$sWidth,$sOptional){
    	$ListtypeHelper = new ListtypeHelper();
        if ($sDataFormat == "session") {
           // session
        } elseif ($sDataFormat == "listtype") {
    		isset($arrTagItem->selectbox_option_id_column) ? $selectBoxIdColumn = (string)$arrTagItem->selectbox_option_id_column : $selectBoxIdColumn = '';
	    	isset($arrTagItem->selectbox_option_name_column) ? $selectBoxNameColumn = (string)$arrTagItem->selectbox_option_name_column : $selectBoxNameColumn = '';
	    	isset($arrTagItem->selectbox_option_value_column) ? $selectBoxValueColumn = (string)$arrTagItem->selectbox_option_value_column : $selectBoxValueColumn = '';
	    	isset($arrTagItem->list_type) ? $list_type = (string)$arrTagItem->list_type : $list_type = '';
            $arrListItem = $ListtypeHelper->_GetAllListObjectByListCode($list_type);
            $spRetHtml = $spRetHtml . self::_generateHtmlForMultipleCheckbox($arrListItem, $selectBoxIdColumn, $selectBoxNameColumn, $sValue,$sformFielName,$sWidth,$sStrOptionXml);
        } else {
            //default
        }
        return $spRetHtml;
    }

    public function _generateLine($spRetHtml,$sValue,$sStyle){
    	$spRetHtml .= '<hr '.$sStyle.'>';
    	return $spRetHtml;
	}

	/**
	 * Tao chuoi HTML de dinh nghia 1 danh sach cac checkbox
	 *
	 * @param $arrList : Mang luu thong tin cac phan tu can hien thi
	 * @param $IdColumn : Ten cot the hien Id
	 * @param $NameColumn : Ten cot se hien thi (ten)
	 * @param $Valuelist :
	 *
	 * @return CHuoi HTML multil checkbox
	 */
	public function _generateHtmlForMultipleCheckbox($arrListItem, $selectBoxIdColumn, $selectBoxNameColumn, $sValuelist,$sformFielName,$sWidth,$sStrOptionXml) {
		$v_current_style_name = "round_row";
		$arr_value = explode(",", $sValuelist);
		$count_item = sizeof($arrListItem);	
		$count_value = sizeof($arr_value);
		$v_tr_name = 'tr_'.$sformFielName;
		$v_radio_name = 'rad_'.$sformFielName;
		$strHTML = '<input type="text" id="'. $sformFielName.'" name="'.$sformFielName.'" value="'.$sValuelist.'"'.$sStrOptionXml.' style="display:none">';
		$strHTML .= '<table id = "table_'.$sformFielName.'" class="griddata table table-bordered table-striped"  width="100%" cellpadding="0" cellspacing="0">';
		$strHTML .='<colgroup><col width="5%"><col width="28%"><col width="5%"><col width="28%"><col width="5%"><col width="29%"></colgroup>';
		if ($count_item > 0){
			$i=0;
			$v_item_url_onclick = "";
			while ($i<$count_item) {
				$v_item_id = $arrListItem[$i][$selectBoxIdColumn];
				$v_item_name = $arrListItem[$i][$selectBoxNameColumn];			
				if($i % 2 == 0){
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";
					}				
				}
				$v_item_checked = "";
				for ($j=0; $j<$count_value; $j++){
					$tr_class = '';
					if ($arr_value[$j]==$v_item_id){
						$v_item_checked = "checked";
						break;
					}
				}	
				if($i % 2 == 0)
					$strHTML = $strHTML . "<tr>";
				// check box
				$strHTML = $strHTML . "<td align='center'>";
				$strHTML = $strHTML . "<input onclick='set_checked_checbox(this)' id-value='$sformFielName' class='checkvaluemark' type='checkbox' $v_item_checked name='$v_item_id' value='$v_item_id' id='GROUP_OWNERCODE$i'>";
				$strHTML = $strHTML . "</td>";
				// label                        
				$strHTML = $strHTML . "<td onclick='set_checked_multi(this)'>";
				$strHTML = $strHTML . "$v_item_name";
				$strHTML = $strHTML . "</td>";
				if($i % 2 != 1 && $i == $count_item - 1){
					$strHTML = $strHTML . "<td colspan = \"2\"> </td>";
				}
				if($i % 2 == 1){
					$strHTML = $strHTML . "</tr>";
				}
				$i++;
			}
		}
        $strHTML = $strHTML ."</table>";
		return $strHTML;
	}

	/**
	 * Sinh ra XAU chua thuoc tinh cua doi tuong
	 *
	 * @param $this->spType : Kieu du lieu can sinh
	 * @param $this->value : Gia tri so sanh
	 *
	 * @return Tra ve tuy chon xac dinh doi tuong co bat nhap hay khon bat nhap
	 */
	public function _generatePropertyType($pType, $pValue){
		switch($pType) {
			case "optional";
				if ($pValue=="false"){
					$psRetHtml = "";
				}else{
					$psRetHtml = " optional = true ";
				}
				break;
			case "readonly";
				if ($pValue=="false"){
					$psRetHtml = "";
				}else{
					$psRetHtml = " readonly=true ";
				}
				break;
			case "disabled";
				if ($pValue=="false"){
					$psRetHtml = " ";
				}else{
					$psRetHtml = " disabled=true ";
				}
				break;
			default:
				$psRetHtml = "";
		}
		return $psRetHtml;	
	}

	/**
	 * Tao chuoi HTML chua ham va cac su kien tuong ung voi ham cua cac doi tuong
	 *
	 * @param $this->jsFunctionList : Danh sach ham
	 * @param $this->jsActionList  : hanhf dong
	 *
	 * @return unknown
	 */
	public function _generateEventAndFunction($psJsFunctionList, $psJsActionList){  
		$v_temp = "";
		if(($psJsFunctionList!='')&&($psJsActionList!='')){
			$arrJsFunctionList = explode(",", $psJsFunctionList);
			$arrJsActionList =   explode(",", $psJsActionList);
			$pCountFunction =     sizeof($arrJsFunctionList);
			$pCountAction =       sizeof($arrJsActionList);
			$count = $pCountFunction > $pCountAction ? $pCountAction : $pCountFunction;
			for ($i=0;$i<$count;$i++){
				$v_temp .= " $arrJsActionList[$i]='$arrJsFunctionList[$i]' ";
			}
		}
		return $v_temp;
	}

	 public function _generateHtmlForTreeUser($spRetHtml,$p_valuelist,$display = false) {
       
        return $spRetHtml;
    }
}