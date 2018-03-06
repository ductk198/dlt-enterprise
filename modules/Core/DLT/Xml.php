<?php

namespace Modules\Core\DLT;
use Illuminate\Http\Request;
use XmlParser;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\Core\DLT\Generate;

/**
 * Thư viện xử lý Xml
 *
 * @author duclt
 */
class Xml {

	 /**
		* Generate form HTML tu file XML
		*
		* @param unknown_type $spXmlFileName		: Duong dan Filexml
		* @param unknown_type $pathXmlTagStruct	: Duong dan den the chua cau truc form
		* @param unknown_type $pathXmlTag			: Duong dan den the chua thong tin cua cac phan tu tren form
		* @param unknown_type $column_xml	: Gia tri trong cot XML vi du: C_XML_DATA
		* @param unknown_type $p_arr_item_value	: Gia tri trong cot luu trong Database
		*
		* @return string html
	 **/
   public function xmlGenerateFormfield($sxmlFileName,$pathXmlTagStruct,$pathXmlTag, $column_xml, $p_arr_item_value) {
   		$objXml = XmlParser::load($sxmlFileName);
   		$objLibrary = new Library();
   		$objConfigXml = $objXml->getContent();
   		//Doi tuong xml luu thong tin trong database
   		$p_xml_string = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
		isset($p_arr_item_value[$column_xml]) ? $p_xml_string = $p_arr_item_value[$column_xml] : $p_xml_string = '';	
		if($p_xml_string <> ''){
				$p_xml_string = '<?xml version="1.0" encoding="UTF-8"?>' . $p_xml_string;
				$objxmlStringdata = simplexml_load_string($p_xml_string);
				$objXmlData = $objxmlStringdata->data_list;
		}
   		//Tao mang luu cau truc cua form
		$arrTagsStruct = explode("/", $pathXmlTagStruct);
		$strcode = $arrTagsStruct[0];
		for($i = 1; $i < sizeof($arrTagsStruct); $i++)
				$strcode .= '->'.$arrTagsStruct[$i];
		eval("\$objTagsStructs = \$objConfigXml"."->$strcode;"); 
		$arrTempTagsStructs = (array)$objTagsStructs;
		//Tao mang luu thong tin cua cac phan tu tren form
		$arrTags = explode("/", $pathXmlTag);
		$strcode = $arrTags[0];
		for($i = 1; $i < sizeof($arrTags); $i++)
				$strcode .= '->'.$arrTags[$i];
		eval("\$objTable_rows = \$objConfigXml"."->$strcode;"); 
		//Thuc hen general cho tung row
		$i=0;
		$sContentXml = '';
		//echo "<pre>";print_r($objTable_rows->registor_name); echo"</pre>"; exit;
		foreach($arrTempTagsStructs['update_row'] as $objTempTagsStruct){
			$row = (array)$objTempTagsStruct;
			isset($row["row_id"]) ? $rowId = $row["row_id"] : $rowId = '';
			isset($row["row_display"]) ? $v_row_display = $row["row_display"] : $v_row_display = '';
			//Duyet tung doi tuong tren row
			$v_tag_list = $row["tag_list"];            
			$arr_tag = explode(",", $v_tag_list);
			$iNumberTag = sizeof($arr_tag);
			$psHtmlTable = "";
			$psHtmlTag = "";
			$checkshowdiv = FALSE;
			//echo $v_tag_list; exit;
			for($i=0;$i < $iNumberTag;$i++){
				if($row["row_id"] == $arr_tag[0]){
					$checkshowdiv = TRUE;
				}
				//mang thong tin cua mot doi tuong
				$arrTagItem = array();
				$arrTagItem = $objTable_rows->$arr_tag[$i];
				$xml_data = $objTable_rows->$arr_tag[$i]->xml_data;
				//gia tri cua doi tuong
				$sValue = '';
				isset($arrTagItem->xml_data) ? $sXmlData = $arrTagItem->xml_data : $sXmlData = '';			
				if (($column_xml != '' || $p_xml_string != "") && $sXmlData == "true"){
					isset($arrTagItem->xml_tag_in_db) ? $tag = (string)$arrTagItem->xml_tag_in_db : $tag = '';
					isset($objXmlData->$tag) ? $sValue = $objXmlData->$tag : $sValue = '';
				}else{
					isset($arrTagItem->column_name) ? $sColumnName = (string)$arrTagItem->column_name : $sColumnName = '';
					isset($p_arr_item_value[$sColumnName]) ? $sValue = $p_arr_item_value[$sColumnName] : $sValue = '';
				}
			}
			if($checkshowdiv){
				$sContentXml .= '<div class="row form-group">';
			}
			$sContentXml .= self::_generateHtmlInput($iNumberTag,$arrTagItem,$sValue,$rowId);
			if($checkshowdiv){
				$sContentXml .='</div>';
			}	
		}
		return $sContentXml;
	}
 
 	/**
	 * Sinh html tu mot doi tuong xml
	 *
	 * @param $iNumberTag 
	 * @param $arrTagItem 
	 * @param $sValue 
	 * @param $rowId 
	 *
	 * @return string html
	 */
    private function _generateHtmlInput($iNumberTag,$arrTagItem,$sValue,$rowId){
    	$objGenerate = new Generate();
    	$sElemenType = $arrTagItem->type;
		isset($arrTagItem->input_data) ? $sInputData = $arrTagItem->input_data : $sInputData = '';
		isset($arrTagItem->optional) ? $sOptional = $arrTagItem->optional : $sOptional = '';
		isset($arrTagItem->width) ? $sWidth = $arrTagItem->width : $sWidth = '';
		isset($arrTagItem->message) ? $sMessage = $arrTagItem->message : $sMessage = '';
		isset($arrTagItem->readonly_in_edit_mode) ? $sReadonlyInEditMode = $arrTagItem->readonly_in_edit_mode : $sReadonlyInEditMode = '';
		isset($arrTagItem->disabled_in_edit_mode) ? $sDisabledInEditMode = $arrTagItem->disabled_in_edit_mode : $sDisabledInEditMode = '';
		isset($arrTagItem->js_function_list) ? $sjsFunctionList = $arrTagItem->js_function_list : $sjsFunctionList = '';
		isset($arrTagItem->js_action_list) ? $sjsActionList = $arrTagItem->js_action_list : $sjsActionList = '';
		isset($arrTagItem->data_format) ? $sDataFormat = $arrTagItem->data_format : $sDataFormat = '';	
		isset($arrTagItem->width_label) ? $sWidthLabel = $arrTagItem->width_label : $sWidthLabel = '';	
		isset($arrTagItem->note) ? $sNote = $arrTagItem->note : $sNote = '';		
		isset($arrTagItem->label) ? $sLabel = $arrTagItem->label : $sLabel = '';	
		isset($arrTagItem->default_value) ? $sDefault = $arrTagItem->default_value : $sDefault = '';
		isset($arrTagItem->xml_data) ? $sXmlData = $arrTagItem->xml_data : $sXmlData = '';
		isset($arrTagItem->label_check) ? $label_check = $arrTagItem->label_check : $label_check = '';
		isset($arrTagItem->column_name) ? $column_name = $arrTagItem->column_name : $column_name = '';
		isset($arrTagItem->xml_tag_in_db) ? $sXmlTagInDb = $arrTagItem->xml_tag_in_db : $sXmlTagInDb = '';
		isset($arrTagItem->class_view_icon) ? $class_view_icon = $arrTagItem->class_view_icon : $class_view_icon = '';
		isset($arrTagItem->col_label) ? $col_label = (int)$arrTagItem->col_label : $col_label = '';		
		// Dem xem tren mot dong co bao nhieu phan tu
		if($col_label > 0){
			$collabel = $col_label;
			$colinput = 12 - $collabel;
		}else{
			$collabel = 12/($iNumberTag*4);
			$colinput = 12/($iNumberTag*2);
		}
		//echo $totaldiv; exit;
		if(($sValue=='')&&($sDefault!='')){
			$sValue = $sDefault;
		}
		$classLabel = 'col-md-'.$collabel.' control-label';
		if($sOptional == "false"){
			$classLabel .= " required";
		}
		$v_str_label = '<label class="'.$classLabel.'">'.$sLabel.'</label>';
		//Thong tin luu trong xml
		if ($sXmlData=='true'){			
			$sformFielName = $sXmlTagInDb;
			$sStrOptionXml = ' xml_data="true"'.' xml_tag_in_db="'.$sXmlTagInDb.'"';
		}else{		
			$sformFielName = $column_name;
			$sStrOptionXml = ' xml_data="false"'.' column_name="'.$column_name.'"';

		}
		if($sMessage){
			//$sStrOptionXml .= ' message="'.$sMessage.'" ';
		}
		//Thiet lap cac option
		$sStrOption ='';
		$spRetHtml = '<div class="row form-group">'.$v_str_label;
		$spRetHtml .= '<div class="col-md-'.$colinput.'">';	
		if($class_view_icon !== ""){
			$spRetHtml .= '<div class="input-group">
			<span class="input-group-addon"><i class="'.(string)$class_view_icon.'"></i></span>';
		}			
		switch($sElemenType){
			case "textbox";
				$spRetHtml = $objGenerate->_generateTextbox($spRetHtml,$sValue,$sDataFormat,$sformFielName,$sStrOptionXml,$sWidth);
				break;				
			case "textarea";
				$spRetHtml = $objGenerate->_generateTextarea($spRetHtml,$sValue,$sReadonlyInEditMode,$sformFielName,$sStrOptionXml,$sWidth,$arrTagItem);
				break;
			case "checkbox";
				$spRetHtml = $objGenerate->_generateCheckbox($spRetHtml,$sValue,$label_check,$sformFielName,$sStrOptionXml,$sWidth);
				break;
			case "checkboxstatus";
				$spRetHtml = $objGenerate->_generateCheckboxstatus($spRetHtml,$sValue,$label_check,$sformFielName,$sStrOptionXml,$sWidth);
				break;	
			case "label";
				//$spRetHtml = '<div class="row form-group"><label style="font-style:italic;">'.$v_str_label.'</label></div>';
				break;	
			case "line";
				$spRetHtml = $objGenerate->_generateLine($spRetHtml,$sValue,$sStyle);
				break;
			 case "selectbox";
			 	$spRetHtml = $objGenerate->_generateSelectbox($spRetHtml,$sValue,$arrTagItem,$sDataFormat,$column_name,$sformFielName,$sStrOptionXml,$sWidth,$sLabel);
                break;
             case "multiplecheckbox";
                $spRetHtml = $objGenerate->_generateMultiplecheckbox($spRetHtml,$sValue,$arrTagItem,$sDataFormat,$column_name,$sformFielName,$sStrOptionXml,$sWidth,$sOptional);
                break;
             case "treeuser";
                $spRetHtml = $objGenerate->_generateHtmlForTreeUser($spRetHtml,$sValue);
             	break;
			default:
				$spRetHtml .= $v_str_label;
				break;
		}
		if($class_view_icon !== ""){
			$spRetHtml .= '</div>';	
		}
		return $spRetHtml."</div></div>";
    }

	public function _xmlGetXmlTagValue($stringxml,$parenttag,$tagxml) {
		$stringxml = '<?xml version="1.0" encoding="UTF-8"?>' . $stringxml;
		$objxmlStringdata = simplexml_load_string($stringxml);
   		return (string)$objxmlStringdata->$parenttag->$tagxml;
	}
}