<?php

namespace Modules\Backend\Main\Controllers;

use Modules\Backend\Main\Requests\Chart as Requests;
use App\Http\Controllers\Controller;
use Modules\Core\Efy\Library as Library;
use Modules\Backend\Chart\Models\ChartModel;
use Modules\Backend\Main\Helpers\WebserviceHelper;
use Modules\Backend\Chart\Helpers\ChartHelper;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\Backend\Chart\Models\DataUnitYear;
use Illuminate\Support\Facades\Cache;
use DB;

/**
 * Mô tả về class...
 *
 * @author ...
 */
class ChartController extends Controller {

    public function index() {
        $objLibrary = new Library();
        $result = array();
        $result = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chart/Chart.min.js,backend/Chart/Js_Viewchart.js', ',', $result);
        $data['strJsCss'] = json_encode($result);
        $arrUnitLevel = array(
            array(
                'code' => 'SO_NGANH',
                'name' => 'Sở ngành',
            ),
            array(
                'code' => 'QUAN_HUYEN',
                'name' => 'Quận huyện',
            )
        );
        $arrquanhuyen = DB::select("select PK_UNIT,C_CODE,C_NAME from T_USER_UNIT where C_TYPE_GROUP in  ('SO_NGANH','QUAN_HUYEN') ORDER BY C_TYPE_GROUP DESC,C_CODE");
        $arrquanhuyen = array_map(function ($value) {
            return (array) $value;
        }, $arrquanhuyen);
        $i = 0;
        // lay quan huyen
        $data['htmlUnit'] = $objLibrary->_generateSelectOption($arrquanhuyen, 'C_CODE', 'C_CODE', 'C_NAME');
        $data['htmUnitLevel'] = $objLibrary->_generateSelectOption($arrUnitLevel, 'code', 'code', 'name');
        for ($i = 0; $i <= 5; $i++) {
            $arrYear[$i] = array('code' => date("Y") - $i, 'name' => date("Y") - $i);
        }
        $data['htmYear'] = $objLibrary->_generateSelectOption($arrYear, 'code', 'code', 'name');
        return view('Main::chart.index', $data);
    }

    public function loadlist(Requests $request) {
        $objDataUnitYear = new DataUnitYear();
        $arrInput = $request->input();
        $objlibrary = new Library();
        $ListtypeHelper = new ListtypeHelper();
        $ChartHelper = new ChartHelper();
        $type = $arrInput['type'];
        $year = $arrInput['year'];
        $fromdate = $year . '/01/01 ' . date('H:m:s');
        $todate = $year . '/12/31 ' . date('H:m:s');
        $ownerunit = $arrInput['ownerunit'];
        $UnitLevel = $arrInput['UnitLevel'];
        $stringunit = $request->stringunit;
        $dbprifix = env('PRE_FIX_DB', '');
        $dbsystem = env('DB_DATABASE', '');
        if ($ownerunit == '' || $ownerunit == null || $type == 3) {
            if ($UnitLevel == 'QUAN_HUYEN') {
                $sqlGetUnit = "select PK_UNIT,C_CODE,C_NAME from T_USER_UNIT where  C_TYPE_GROUP ='QUAN_HUYEN'";
            } elseif ($UnitLevel == 'SO_NGANH') {
                $sqlGetUnit = "select PK_UNIT,C_CODE,C_NAME from T_USER_UNIT where  C_TYPE_GROUP ='SO_NGANH'";
            } else {
                $sqlGetUnit = "select PK_UNIT,C_CODE,C_NAME from T_USER_UNIT where  C_TYPE_GROUP in ('SO_NGANH','QUAN_HUYEN')";
            }

            $arrUnit = DB::select($sqlGetUnit);
            $i = 0;
            $sqlGetDB = "SELECT name FROM sys.databases where name like '%" . env('PRE_FIX_DB', 'hanoi-kntc-') . "%'";
            $arrDB = DB::select($sqlGetDB);
            $arrDB = array_map(function ($value) {
                return (array) $value;
            }, $arrDB);
            foreach ($arrUnit as $key => $val) {
                $codeUnit = $val->C_CODE;
                $dbcurrent = $dbprifix . $codeUnit;
                if ($this->checkdb($dbcurrent, $arrDB)) {
                    $sql = "EXEC [" . $dbcurrent . "].dbo.[Lookup_SynthesisSearchSystem] N'$fromdate',N'$todate',N'',N'',N'$codeUnit',N'',N'[$dbsystem]',N'$codeUnit'";
                    try {
                        $arrDataSingle = DB::select($sql);
                        $arrDataSingle = array_map(function ($value) {
                            return (array) $value;
                        }, $arrDataSingle);
                        $arrReturn[$i] = $arrDataSingle;
                        $i++;
                    } catch (Exception $ex) {
                        throw $ex;
                    }
                }
            }
        } else {
            $dbgeneral = $dbprifix . $ownerunit;
            $sql = "EXEC [$dbgeneral].dbo.Lookup_SynthesisSearchSystem N'$fromdate',N'$todate',N'',N'',N'$ownerunit',N'',N'[$dbsystem]',N'$ownerunit'";
            $arrData = DB::select($sql);
            $arrData = array_map(function ($value) {
                return (array) $value;
            }, $arrData);
            $arrReturn[0] = $arrData;
        }
//        dd($arrReturn);
        $tongDanggiaiquyet = 0;
        $tongDagiaiquyet = 0;
        $arrNameUnit = array();
        $arrDangGQ = array();
        $arrDaGQ = array();
        foreach ($arrReturn as $key => $val) {
            array_push($arrNameUnit, $val[0]['C_NAME']);
            array_push($arrDangGQ, $val[0]['C_TONG_DANG_GIAI_QUYET']);
            array_push($arrDaGQ, $val[0]['C_TONG_DA_GIAI_QUYET']);
            $tongDanggiaiquyet += $val[0]['C_TONG_DANG_GIAI_QUYET'];
            $tongDagiaiquyet += $val[0]['C_TONG_DA_GIAI_QUYET'];
        }
        if ($arrReturn) {
            if ($type == 0 || $type == 1) {
                $data['Pie_doughnut'] = array(
                    'danggiaiquyet' => $tongDanggiaiquyet,
                    'dagiaiquyet' => $tongDagiaiquyet,
                );
            }
            // Tinh ty le dung han qua han (Bieu do hinh tron)
//            if ($type == 0 || $type == 2) {
//                $data['Barchart_month'] = $ChartHelper->Barchart_month($arrResults);
//            }
            if ($type == 0 || $type == 3) {
                $data['Barchart_unit'] = array(
                  'label'=>$arrNameUnit,  
                  'dangiaiquyet'=>$arrDangGQ,  
                  'dagiaiquyet'=>$arrDaGQ,  
                  'status'=>$arrDaGQ,  
                );
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

    public function checkdb($currentdb, $arrdb) {
        $check = false;
        if ($currentdb == 'hanoi-kntc-20' || $currentdb == 'hanoi-kntc-17') {
            return true;
        } else {
            return false;
        }
        foreach ($arrdb as $key => $val) {
            if ($currentdb == $val['name']) {
                $check = true;
                break;
            }
        }
        return $check;
    }

}
