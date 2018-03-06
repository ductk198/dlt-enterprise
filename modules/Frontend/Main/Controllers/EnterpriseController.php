<?php

namespace Modules\Frontend\Main\Controllers;

//use Modules\Frontend\Main\Requests\Main as Requests;
use App\Http\Controllers\Controller;
use Modules\Core\Efy\Library;
use Modules\Core\Efy\Xml;
use DB;
use Request;

/**
 * Tổng hợp số liệu
 *
 * @author Duclt
 */
class EnterpriseController extends Controller {

    public function index() {
        $path = Request::path();
        $arrPath = explode('/', $path);
        $tencongty = $arrPath[1];
        $tencongty = substr($tencongty, 0, strlen($tencongty)-5);
        $arrTenCongty= explode('-', $tencongty);
        $size = sizeof($arrTenCongty);
        $masothue = $arrTenCongty[$size-1];
        $sql = "Select * from T_DLT_ENTERPRISE where masothue='$masothue'";
        $arrDataSingle = DB::select($sql);
        $arrDataSingle=$arrDataSingle[0];
        $data = array();
        $data['arrDataSingle'] =$arrDataSingle;
        return view("Main::Enterprise.index", $data);
    }

}
