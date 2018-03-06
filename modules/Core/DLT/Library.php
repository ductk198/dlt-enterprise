<?php

namespace Modules\Core\DLT;

use Illuminate\Http\Request;
use XmlParser;
use Illuminate\Support\Facades\Cache;

/**
 * Thư viện dùng chung cho toàn dự án.
 *
 * @author Duclt
 */
class Library {

    /**
     * Tạo các option của đối tượng selectbox từ một array
     *
     * @return string html
     */
    public function _generateSelectOption($arr_list, $IdColumn, $ValueColumn, $NameColumn, $SelectedValue = "") {
        $strHTML = "";
        $i = 0;
        $count = sizeof($arr_list);
        for ($row_index = 0; $row_index < $count; $row_index++) {
            $strID = trim($arr_list[$row_index][$IdColumn]);
            $strValue = trim($arr_list[$row_index][$ValueColumn]);
            $gt = $SelectedValue;
            if ($strID != $SelectedValue) {
                $optSelected = "";
            } else {
                $optSelected = "selected";
            }
            $DspColumn = trim($arr_list[$row_index][$NameColumn]);
            $strHTML .= '<option id=' . '"' . $strID . '"' . ' ' . 'name=' . '"' . $strID . '"' . ' ';
            $strHTML .= 'value=' . '"' . $strValue . '"' . ' ' . $optSelected . '>' . $DspColumn . '</option>';
            $i++;
        }
        return $strHTML;
    }

    /**
     * Load các file Js,Css từ controller
     *
     * @return array
     */
    public static function _getAllFileJavaScriptCssArray($psExtension, $parrFileName = "", $psDelimitor = ",", $result = array()) {
        // Thuc hien lay file js o nhung module khac	
        $filetype = strtolower($psExtension);
        $sResHtml = null;
        if ($filetype != "") {
            //
            $sDir = url('public/' . $filetype);
            $file = '';
            $count = sizeof($result);
            $arrTemp = explode($psDelimitor, $parrFileName);
            //print_r($arrTemp); exit;
            for ($index = 0; $index < sizeof($arrTemp); $index++) {
                //Thuc hien include file JavaScript
                $filetypeinDirJs = @substr($arrTemp[$index], strlen($file) - 2, 2);
                $filetypeinDirJs = @strtolower($filetypeinDirJs);
                if ($filetype == "js" && $filetypeinDirJs == "js") {
                    $sDirFull = $sDir . "/" . $arrTemp[$index];
                    $result[$count]['type'] = 'js';
                    $result[$count]['src'] = $sDirFull;
                }
                //Thuc hien include file Css
                $filetypeinDirCss = @substr($arrTemp[$index], strlen($file) - 3, 3);
                $filetypeinDirCss = @strtolower($filetypeinDirCss);
                if ($filetype == "css" && $filetypeinDirCss == "css") {
                    $sDirFull = $sDir . "/" . $arrTemp[$index];
                    $result[$count]['type'] = 'css';
                    $result[$count]['src'] = $sDirFull;
                }
                $count++;
            }
        }
        return $result;
    }

    /**
     * Lấy các file có trong một thư mục trên server
     *
     * @return array
     */
    public static function _dirToArray($dir, $code) {
        $dir = base_path() . $dir;
        $result = array();
        $cdir = scandir($dir);
        $i = 0;
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$i][$code] = self::_dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    //print_r($value); exit;
                    $result[$i]['code'] = (string) $value;
                    $i++;
                }
            }
        }
        return $result;
    }

    public static function _createFolder($pathLink, $folderYear, $folderMonth, $sCurrentDay = "") {
        $sPath = str_replace("/", "\\", $pathLink);
        if (!file_exists($sPath . $folderYear)) {
            mkdir($sPath . $folderYear, 0777);
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(92) . $folderMonth)) {
                mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
        } else {
            $sPath = $sPath . $folderYear;
            if (!file_exists($sPath . chr(92) . $folderMonth)) {
                mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
        }
        //Tao ngay trong nam->thang
        if (!file_exists($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay)) {
            mkdir($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay, 0777);
        }
        //
        $strReturn = $pathLink . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay . '/';
        return str_replace("/", "\\", $strReturn);
    }

    public static function _get_randon_number() {
        $ret_value = mt_rand(1, 1000000);
        return $ret_value;
    }

    public static function _get_randon_number_100_999() {
        $ret_value = mt_rand(100, 999);
        return $ret_value;
    }

    public function _writeFile($spFilePath, $spContent) {
        if (file_exists($spFilePath)) {
            chmod($spFilePath, 0777);
        }
        $handle = fopen($spFilePath, "w+");
        if ($handle) {
            fwrite($handle, $spContent);
            fclose($handle);
        }
    }

    public static function _exportsql($arrParameter, $spname) {
        $sql = '';
        if (is_array($arrParameter)) {
            foreach ($arrParameter as $key => $value) {
                if ($sql != '') {
                    $sql .= ",N'" . $value . "'";
                } else {
                    $sql .= " N'" . $value . "'";
                }
            }
        }
        return 'Exec ' . $spname . $sql;
    }

    function convert_vi_to_en($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        return $str;
    }

}
