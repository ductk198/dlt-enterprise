<?php

namespace Modules\System\Listtype\Helpers;

use DB;
use Log;
use Request;

use Modules\System\Listtype\Models\MenuModel;
use Illuminate\Support\Facades\Cache;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Listtype\Models\ListtypeModel;

/**
 * Helper xử lý liên quan đến danh mục
 *
 * @author Duclt
 */
class ListtypeHelper
{

	/**
     * Lấy danh mục từ một danh mục đối tượng
     *
     * @return array
     */
    public function _GetSingleListObjectByListCode($sCode,$value,$typeJson=false){
        $arrObject = ListtypeModel::_getSinglebyCode($sCode,$value,$typeJson);
        return $arrObject;
    }

    /**
     * Lấy tất cả danh mục từ một danh mục đối tượng
     *
     * @return array
     */
    public function _GetAllListObjectByListCode($sCode,$arrcolumn = array( '*'),$typeJson=false){
        $ListModel = new ListModel();
        $arrObject = $ListModel->_getAllbyCodeAStatus($sCode,$typeJson,$arrcolumn);
        return $arrObject;
    }
}
