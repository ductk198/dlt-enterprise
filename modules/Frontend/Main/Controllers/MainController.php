<?php

namespace Modules\Frontend\Main\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\Frontend\Main\Models\MainModel;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\Core\DLT\Xml;
use Modules\Frontend\Main\Helpers\WebserviceHelper;
use DB;
use Request;
use Illuminate\Http\Request as Requests;

/**
 * Tổng hợp số liệu
 *
 * @author Duclt
 */
class MainController extends Controller {

    public function index(Requests $rq) {
        $path = Request::path();
        $arrPath = explode('/', $path);
        $tinhurl = $arrPath[0];
        $arrInput = $rq->input();
        $sqlmatinh = "select C_CODE from T_DLT_LIST where FK_LISTTYPE = 1  and  dbo.f_GetValueOfXMLtag(C_XML_DATA,'url_tinh_tp')='$tinhurl' ";
        $arrMatinh = DB::select($sqlmatinh);
        $textsearch = '';
        if (isset($arrInput['search']))
            $textsearch = $arrInput['search'];
        $matinh = '';
        if (isset($arrMatinh[0]->C_CODE))
            $matinh = $arrMatinh[0]->C_CODE;
        $objLibrary = new Library();
        $arrResult = array();
        $data = array();
        $sqlGetData = "select TOP 30 * from T_DLT_ENTERPRISE where 1=1";
        if ($matinh != null && $matinh != '') {
            $sqlGetData .= " and MATINH = '$matinh'";
        }
        if ($textsearch != null && $textsearch != '') {
            $sqlGetData .= " and ( TENCONGTY like N'%" . $textsearch . "%' or MASOTHUE like N'$textsearch')";
        }
        $arrData = DB::select($sqlGetData);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'Backend/Main/Js_Viewrecord.js', ',', $arrResult);
        $data['strJsCss'] = json_encode($arrResult);
        $sql = "select C_NAME,C_CODE,dbo.f_GetValueOfXMLtag(C_XML_DATA,'url_tinh_tp') as C_SHORTCUT from T_DLT_LIST where FK_LISTTYPE = 1 order by C_ORDER ";
        $arrTinh = DB::select($sql);
        $data['arrTinh'] = $arrTinh;
        $data['tinhSelected'] = $matinh;
        $data['search'] = $textsearch;
        $data['arrData'] = $arrData;
//        $data['htmUnitLevel'] = $objLibrary->_generateSelectOption($arrUnitLevel, 'code', 'code', 'name');
        return view("Main::Main.index", $data);
    }

}
