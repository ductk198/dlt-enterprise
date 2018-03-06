<?php

namespace Modules\Backend\Main\Controllers;

use Modules\Core\DLT\Library;
use Modules\Core\DLT\Xml;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use DB;

class AjaxController extends Controller {

    public function dropdowndyamic(Request $request) {
        $objlibrary = new Library();
        $ListtypeHelper = new ListtypeHelper();
        $listtype = $request->listtype;
        $TagGroup = $request->TagGroup;
        $textdefault = $request->textdefault;
        $GroupSelectBox = $request->GroupSelectBox;
        $valueselect = $request->valueselect;
        $xmltaglist = $request->xmltaglist;
        $arrlisttypes = $ListtypeHelper->_GetAllListObjectByListCode($listtype);
        $result = array();
        $i = 0;
        if ($valueselect !== '') {
            foreach ($arrlisttypes as $arrlisttype) {
                $checkvalue = $arrlisttype[$xmltaglist];
                if ($checkvalue == $valueselect) {
                    $result[$i] = $arrlisttype;
                    $i++;
                }
            }
        } else {
            $result = $arrlisttypes;
        }
        $htmls = '';
        if (sizeof($result) > 1 || sizeof($result) == 0)
            $htmls = '<option selected="selected" id="" name="" value="">' . $textdefault . '</option>';
        $htmls .= $objlibrary->_generateSelectOption($result, 'code', 'code', 'name', $valueselect);
        return $htmls;
    }

    public function dropdownUnit(Request $request) {
        $objlibrary = new Library();
        $arrInput = $request->input();
        $unitlever = $arrInput['unitlever'];
        $htmls = '';
        $sql = "select PK_UNIT,C_CODE,C_NAME from T_USER_UNIT where  C_TYPE_GROUP in ('SO_NGANH','QUAN_HUYEN')";
        if ($unitlever != '' && $unitlever != null) {
            $sql .= " AND C_TYPE_GROUP = '" . $unitlever . "'";
        }
        $sql .= " ORDER BY C_TYPE_GROUP DESC,C_CODE";
        $arrquanhuyen = DB::select($sql);
        $arrquanhuyen = array_map(function ($value) {
            return (array) $value;
        }, $arrquanhuyen);
        $htmls .= '<option value="">--Chọn đơn vị--</option>';
        $htmls .= $objlibrary->_generateSelectOption($arrquanhuyen, 'C_CODE', 'C_CODE', 'C_NAME');
        return $htmls;
    }

    public function viewtempo(Request $request) {
        $arrInput = $request->input();
        $pkrecord = $arrInput['pkrecord'];
//        $sql = "Select * from T_KNTC_RECORD where PK_RECORD  =  '$pkrecord'";
        $dbsystem = env('DB_DATABASE', '');
        $dbgenneral = env('PRE_FIX_DB', '') . $arrInput['ownercode'];
        $sqlTTHS = "EXEC [$dbgenneral].dbo.KNTC_GetInforGeneralRecordBackEnd '$pkrecord','[$dbsystem]'";
        $sqlTDGQ = "EXEC [$dbgenneral].dbo.KNTC_GetAllRecordWorkByPkRecordBackEnd '$pkrecord','[$dbsystem]'";
        $arrTTHS = DB::select($sqlTTHS);
        $arrRecordWork = DB::select($sqlTDGQ);
        $data = array();
        $data['arrTTHS'] = $arrTTHS[0];
        $data['arrRecordWork'] = $arrRecordWork;
        return view('Main::viewtempo', $data);
    }

}
