<?php

namespace Modules\Core\DLT;

/**
 * Duclt 
 * Class giao tiep voi fileServer
 */
class FileServer {

    private $_sAuth;
    private $_sOwnerCode;
    private $_sOwnerCodeCenter;
    private $_sAppCode;
    private $_FileServerUrl;
    private $_sPkRecord;

    public function __construct() {

        $this->_sAuth = env('FILE_SERVER_AUTHEN', '');
        $this->_sOwnerCodeCenter = env('FILE_SERVER_APPCODE', '');
        $this->_sOwnerCode = env('FILE_SERVER_OWNERCODE', '');
        $this->_sAppCode = env('FILE_SERVER_APPCODE', '');
        $this->_FileServerUrl = env('URL_FILE_SERVER', '');
    }

    public function _upload($uploadfile, $sPkRecord, $sRecordType, $sTableObject) {
        if ($sPkRecord == '') {
            if (!isset($this->_sPkRecord)) {
                $this->_sPkRecord = $this->unique();
                $this->_registryPk();
            }
        } else {
            if (!isset($this->_sPkRecord)) {
                $this->_sPkRecord = $sPkRecord;
            }
        }
        $sOwnerCodeView = $this->_sOwnerCode;
        $sOwnerCodeShare = $this->_sOwnerCode;
        if ((isset($this->_sOwnerCodeCenter)) && ($this->_sOwnerCodeCenter != $this->_sOwnerCode)) {
            $sOwnerCodeView .= ',' . $this->_sOwnerCodeCenter;
            $sOwnerCodeShare .= ',' . $this->_sOwnerCodeCenter;
        }
        $postResult = '';
        $sUrl = $this->_FileServerUrl;
        $ch = curl_init($sUrl);
        if (!$ch) {
            die("Không kết nối được đến FileServer!");
        } else {
            if ((version_compare(PHP_VERSION, '5.5') >= 0)) {
                $uploadfile = new \CURLFile($uploadfile);
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
            } else {
                $uploadfile = "@" . $uploadfile;
            }
            $arrPostFields = array(
                'function' => 0,
                'sampfile' => $uploadfile,
                'owner_code' => $this->_sOwnerCode,
                'app_code' => $this->_sAppCode,
                'pk_record' => $this->_sPkRecord,
                'record_type' => $sRecordType,
                'table_obj' => $sTableObject,
                'owner_code_view' => $sOwnerCodeView,
                'owner_code_share' => $sOwnerCodeShare
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $this->_sAuth);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $postResult = curl_exec($ch);
            // echo $postResult; exit;
            curl_close($ch);
        }
        return $postResult;
    }

    public function _uploadmeeting($sPkRecord, $pktable, $filename) {
        if ($sPkRecord == '') {
            if (!isset($this->_sPkRecord)) {
                $this->_sPkRecord = $this->unique();
                $this->_registryPk();
            }
        } else {
            if (!isset($this->_sPkRecord)) {
                $this->_sPkRecord = $sPkRecord;
            }
        }
        $sOwnerCodeView = $this->_sOwnerCode;
        $sOwnerCodeShare = $this->_sOwnerCode;
        if ((isset($this->_sOwnerCodeCenter)) && ($this->_sOwnerCodeCenter != $this->_sOwnerCode)) {
            $sOwnerCodeView .= ',' . $this->_sOwnerCodeCenter;
            $sOwnerCodeShare .= ',' . $this->_sOwnerCodeCenter;
        }
        $postResult = '';
        $sUrl = $this->_FileServerUrl;
        $ch = curl_init($sUrl);
        if (!$ch) {
            die("Không kết nối được đến FileServer!");
        } else {
            $arrPostFields = array(
                'function' => 3,
                'owner_code' => $this->_sOwnerCode,
                'app_code' => $this->_sAppCode,
                'pk_record' => $this->_sPkRecord,
                'pk_filetable' => $pktable,
                'owner_code_view' => $sOwnerCodeView,
                'owner_code_share' => $sOwnerCodeShare,
                'file_name' => $filename
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostFields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $this->_sAuth);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $postResult = curl_exec($ch);
            echo $postResult;
            exit;
            curl_close($ch);
        }
        return $postResult;
    }

    public function _open($sFileId, $sFileName) {
        if ($sFileId != 'action') {
            $ch = curl_init($this->_FileServerUrl);
            if (!$ch) {
                die("error 404.1!");
            } else {
                $arrPostFields = array(
                    'function' => 1,
                    'file_id' => $sFileId,
                    'owner_code' => $this->_sOwnerCode,
                    'app_code' => $this->_sAppCode
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostFields);
                curl_setopt($ch, CURLOPT_USERPWD, $this->_sAuth);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                $data = curl_exec($ch);
                if ($data != '') {
                    curl_close($ch);
//                    header('Content-type: ' . $this->mime_content_type($sFileName));
                    return $data;
                } else {
                    die("error 404.2!");
                }
            }
        } else {
            die("error 404.3!");
        }
    }

    // xoa file voi id la data table ad
    public function _delete($sFileId) {
        // kiem tra xem file id co ton tai trong bang file hay khong. co thi khong xoa
        if (!class_exists('Efy_DB_Connection')) {
            Zend_Loader::loadClass('Efy_DB_Connection');
        }
        $postResult = '';
        $objEfyDBConnection = new Efy_DB_Connection();
        $sql = "Select * From T_EFYLIB_FILE Where CHARINDEX('" . $sFileId . "',C_FILE_NAME) > 0 and C_DOC_TYPE = 'HO_SO'";
        $arrResult = $objEfyDBConnection->adodbQueryDataInNameMode($sql);
        if (count($arrResult) == 0) {
            $sUrl = $this->_FileServerUrl;
            $ch = curl_init($sUrl);
            if ($ch) {
                $arrPostFields = array(
                    'function' => 2,
                    'file_id' => $sFileId,
                    'owner_code' => $this->_sOwnerCode,
                    'app_code' => $this->_sAppCode
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostFields);
                curl_setopt($ch, CURLOPT_USERPWD, $this->_sAuth);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                $postResult = curl_exec($ch);
                // echo $postResult; exit;
            }
        }
        return $postResult;
    }

    // Duclt: xoa file server
    public function _deleteListFile($sListFileName, $sDelimitor) {
        if (!class_exists('Efy_DB_Connection')) {
            Zend_Loader::loadClass('Efy_DB_Connection');
        }
        $objEfyDBConnection = new Efy_DB_Connection();
        $arrFileName = explode($sDelimitor, $sListFileName);
        $count = sizeof($arrFileName);
        for ($i = 0; $i < $count; $i ++) {
            $checkdelete = true;
            if ($arrFileName[$i] !== '') {
                // kiem tra xem con file dinh kem khong, neu con thi khong xoa
                $sql = "select * from T_EFYLIB_FILE where C_FILE_NAME = '" . $arrFileName[$i] . "'";
                $arrResult = $objEfyDBConnection->adodbQueryDataInNameMode($sql);
                if ($arrResult) {
                    $checkdelete = false;
                }
                if ($checkdelete) {
                    $arrFile = explode('!~!', $arrFileName[$i]);
                    $this->_delete($arrFile[0]);
                }
            }
        }
    }

    public function _updatePkFile($newpkrecord) {
        if (!class_exists('Zend_Registry')) {
            Zend_Loader::loadClass('Zend_Registry');
        }
        if (Zend_Registry::isRegistered('sPkRecord')) {
            $sPkRecord = Zend_Registry::get('sPkRecord');
            if ($sPkRecord != '') {
                $postResult = '';
                $sUrl = $this->_FileServerUrl;
                $ch = curl_init($sUrl);
                if ($ch) {
                    $arrPostFields = array(
                        'function' => 4,
                        'sPkRecord' => $sPkRecord,
                        'newpkrecord' => $newpkrecord
                    );
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostFields);
                    curl_setopt($ch, CURLOPT_USERPWD, $this->_sAuth);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    $postResult = curl_exec($ch);
                }
                Zend_Registry::set('sPkRecord', '');
                return $postResult;
            }
        }
    }

    public function _share($pkrecord, $sOwnerCodeList) {
        $postResult = '';
        $sUrl = $this->_FileServerUrl . 'sharerecord';
        $ch = curl_init($sUrl);
        if ($ch) {
            $arrPostFields = array(
                'sPkRecord' => $pkrecord,
                'owner_code' => $this->_sOwnerCode,
                'owner_list_share' => $sOwnerCodeList
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostFields);
            curl_setopt($ch, CURLOPT_USERPWD, $this->_sAuth);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $postResult = curl_exec($ch);
        }
        return $postResult;
    }

    private function mime_content_type($filename) {
        $mime_types = array(
            // 'txt' => 'text/plain',
            // 'htm' => 'text/html',
            // 'html' => 'text/html',
            // 'php' => 'text/html',
            // 'css' => 'text/css',
            // 'js' => 'application/javascript',
            // 'json' => 'application/json',
            // 'xml' => 'application/xml',
            // 'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            // 'tiff' => 'image/tiff',
            // 'tif' => 'image/tiff',
            // 'svg' => 'image/svg+xml',
            // 'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            // adobe
            'pdf' => 'application/pdf',
            // 'psd' => 'image/vnd.adobe.photoshop',
            // 'ai' => 'application/postscript',
            // 'eps' => 'application/postscript',
            // 'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint'

                // open office
                // 'odt' => 'application/vnd.oasis.opendocument.text',
                // 'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );
        // $ext = strtolower(array_pop(explode('.',$filename)));
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

    private function unique() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    private function _registryPk() {
        if (!class_exists('Zend_Registry')) {
            Zend_Loader::loadClass('Zend_Registry');
        }
        $registry = Zend_Registry::getInstance();
        $registry->set('sPkRecord', $this->_sPkRecord);
    }

}

?>