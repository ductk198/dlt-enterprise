<?php

namespace App\Library;
use Illuminate\Http\Request;
use XmlParser;

/**
 * Thư viện xử lý fillter.
 *
 * @author Duclt
 */
class Fillter {
	 
   public function _FillterXml($sxmlFileName) {
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
}