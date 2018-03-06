<?php

namespace Modules\Backend\Chart\Controllers;

use Modules\Core\Efy\Library;
use Modules\Core\Efy\Xml;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\System\Listtype\Helpers\ListtypeHelper;

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
        $i=0;
        if($valueselect !== ''){
            foreach ($arrlisttypes as $arrlisttype) {
                $checkvalue = $arrlisttype[$xmltaglist];
                if($checkvalue == $valueselect){
                    $result[$i] = $arrlisttype;
                    $i++;
                }
            }
        }else{
            $result = $arrlisttypes;
        }
        $htmls = '';
        if (sizeof($result)>1 || sizeof($result) == 0)
            $htmls = '<option selected="selected" id="" name="" value="">' . $textdefault . '</option>';
        $htmls .=$objlibrary->_generateSelectOption($result, 'code', 'code', 'name', $valueselect);
        return $htmls;
    }
}
