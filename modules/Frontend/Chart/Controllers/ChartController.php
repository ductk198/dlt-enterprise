<?php

namespace Modules\Frontend\Chart\Controllers;

use Modules\Frontend\Chart\Requests\Chart as Requests;
use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\Frontend\Chart\Models\ChartModel;
use Modules\Frontend\Main\Helpers\WebserviceHelper;
use Modules\Frontend\Chart\Helpers\ChartHelper;
use Modules\Frontend\Chart\Models\DataUnitYear;
use Illuminate\Support\Facades\Cache;

/**
 * Mô tả về class...
 *
 * @author ...
 */
class ChartController extends Controller {

    public function index() {
        dd(123);
        $ListtypeHelper = new ListtypeHelper();
        $objlibrary = new Library();
        $result = array();
        $result = $objlibrary->_getAllFileJavaScriptCssArray('js', 'assets/chart/Chart.min.js,backend/Chart/Js_Viewchart.js', ',', $result);
        $data['strJsCss'] = json_encode($result);
        $arrcolumn = array('code', 'name');
        $arrOwnerdata = $ListtypeHelper->_GetAllListObjectByListCode('DM_DON_VI_TRIEN_KHAI', $arrcolumn);
        $arrUnitLevel = $ListtypeHelper->_GetAllListObjectByListCode('DM_CAP_DV', $arrcolumn);
        $arrStatus = $ListtypeHelper->_GetAllListObjectByListCode('DM_TRANG_THAI_GQ', $arrcolumn);
        $data['htmlUnit'] = $objlibrary->_generateSelectOption($arrOwnerdata, 'code', 'code', 'name');
        $data['htmUnitLevel'] = $objlibrary->_generateSelectOption($arrUnitLevel, 'code', 'code', 'name');
        for ($i = 0; $i <= 5; $i++) {
            $arrYear[$i] = array('code' => date("Y") - $i, 'name' => date("Y") - $i);
        }
        $data['htmYear'] = $objlibrary->_generateSelectOption($arrYear, 'code', 'code', 'name');
        $strh = '(Dữ liệu tự động cập nhật vào: ';
        // lay gio cap nhat
        if (date('H') >= 12) {
            if (date('H') >= 14) {
                $time = "14h00' ngày " . date("d") . "/" . date("m") . "/" . date("Y");
            } else {
                $time = "8h00' ngày " . date("d") . "/" . date("m") . "/" . date("Y");
            }
        } else {
            if (date('H') >= 8) {
                $time = "8h00' ngày " . date("d") . "/" . date("m") . "/" . date("Y");
            } else {
                $date = date('d/m/Y', strtotime("-1 days"));
                $time = "14h00' ngày " . $date;
            }
        }
        $strh = $strh . " " . $time . ")";
        $data['strh'] = $strh;
        return view('Chart::index', $data);
    }

    public function loadlist(Requests $request) {
        $objDataUnitYear = new DataUnitYear();
        $objlibrary = new Library();
        $ListtypeHelper = new ListtypeHelper();
        $ChartHelper = new ChartHelper();
        $type = $request->type;
        $year = $request->year;
        $stringunit = $request->stringunit;
        $arrUnit = explode(',', $stringunit);
        $arrResults = array();
        if ($arrUnit) {
            $i = 0;
            foreach ($arrUnit as $currentUnit) {
                // Kiem tra xem da co du lieu don vi chua
                $arrdata = $objDataUnitYear->_checkexits($currentUnit, $year);
                if (sizeof($arrdata) == 0) {
                    // Neu chua co thi goi webservice lay du lieu cua don vi
                    $result = $ChartHelper->getdataUnit($currentUnit, $year);
                    // Cap nhat du lieu vao database
                    $strjsonarrUpdate = json_encode($result['data']);
                    $objDataUnitYear->_updatedataunit($currentUnit, $result['name'], $request->year, $strjsonarrUpdate);
                    $arrResults[$i] = $result['data'];
                    $arrResults[$i]['unit_name'] = $result['name'];
                    $i++;
                } else {
                    $arrdataUnit = json_decode($arrdata[0]['data_json']);
                    $arrResults[$i] = $arrdataUnit;
                    $arrResults[$i]['total'] = $arrdata[0]['total'];
                    $arrResults[$i]['unit_name'] = $arrdata[0]['unit_name'];
                    $i++;
                }
            }
        }
        if ($arrResults) {
            if ($type == 0 || $type == 1) {
                $data['Pie_doughnut'] = $ChartHelper->Pie_doughnut($arrResults);
            }
            // Tinh ty le dung han qua han (Bieu do hinh tron)
            if ($type == 0 || $type == 2) {
                $data['Barchart_month'] = $ChartHelper->Barchart_month($arrResults);
            }
            if ($type == 0 || $type == 3) {
                $data['Barchart_unit'] = $ChartHelper->Barchart_unit($arrResults);
            }
        }
        return $data;
    }

    public function UpdateData(Requests $request) {
        $objDataUnitYear = new DataUnitYear();
        $objlibrary = new Library();
        $ListtypeHelper = new ListtypeHelper();
        $ChartHelper = new ChartHelper();
        $year = $request->year;
        $stringunit = $request->stringunit;
        $arrUnit = explode(',', $stringunit);
        $arrResults = array();
        if ($arrUnit) {
            $i = 0;
            foreach ($arrUnit as $currentUnit) {
                // goi webservice lay du lieu cua don vi
                $result = $ChartHelper->getdataUnit($currentUnit, $year);
                // tinh tong so ho so tiep nhan trong nam
                $resulttotal = $ChartHelper->getalldataUnit($currentUnit, $year);
                // Cap nhat du lieu vao database
                $strjsonarrUpdate = json_encode($result['data']);
                $objDataUnitYear->_updatedataunit($currentUnit, $result['name'], $request->year, $resulttotal['total'], $strjsonarrUpdate);
                // tinh tong so ho so
                $i++;
            }
            return $data['status'] = 'true';
        }
    }

    public function barchart_month(Requests $request) {
        $objDataUnitYear = new DataUnitYear();
        $objlibrary = new Library();
        $ListtypeHelper = new ListtypeHelper();
        $ChartHelper = new ChartHelper();
        $year = $request->year;
        $stringunit = $request->stringunit;
        $arrUnit = explode(',', $stringunit);
        $arrResults = array();
        if ($arrUnit) {
            $i = 0;
            foreach ($arrUnit as $currentUnit) {
                // Kiem tra xem da co du lieu don vi chua
                $arrdata = $objDataUnitYear->_checkexits($currentUnit, $year);
                if (sizeof($arrdata) == 0) {
                    // Neu chua co thi goi webservice lay du lieu cua don vi
                    $result = $ChartHelper->getdataUnit($currentUnit, $year);
                    // Cap nhat du lieu vao database
                    $strjsonarrUpdate = json_encode($result['data']);
                    $objDataUnitYear->_updatedataunit($currentUnit, $result['name'], $request->year, $strjsonarrUpdate);
                    $arrResults[$i] = $result['data'];
                    $arrResults[$i]['unit_name'] = $arrdata[0]['unit_name'];
                    $i++;
                } else {
                    $arrdataUnit = json_decode($arrdata[0]['data_json']);
                    $arrResults[$i] = $arrdataUnit;
                    $arrResults[$i]['unit_name'] = $arrdata[0]['unit_name'];
                    $i++;
                }
            }
        }
        if ($arrResults) {
            $data['Barchart_month'] = $ChartHelper->Barchart_month($arrResults);
        }
        return $data;
        //return $arrResult;
    }

    public function piechart(Requests $request) {
        $objDataUnitYear = new DataUnitYear();
        $objlibrary = new Library();
        // goi webservice
        $stringunit = $request->stringunit;
        $arrUnit = explode(',', $stringunit);
        $dunghan = 0;
        $quahan = 0;
        $arrResult = array();
        if ($arrUnit) {
            $i = 0;
            foreach ($arrUnit as $currentUnit) {
                $arrdata = $objDataUnitYear->_checkexits($currentUnit, $request->year);
                if ($arrdata) {
                    $data = json_decode($arrdata[0]['data_json']);
                    $dunghan = $dunghan + $data[0][0]->tong_dung_han;
                    $quahan = $quahan + $data[0][0]->tong_qua_han;
                }
            }
            $arrResult['label'] = ["Đúng hạn", "Quá hạn"];
            $arrResult['data'] = [$dunghan, $quahan];
            $arrResult['status'] = false;
            //return $arrResult;
        }
        return $arrResult;
    }

}
