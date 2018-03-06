<?php

namespace Modules\Backend\Chart\Helpers;

use DB;
use Log;
use Request;

use Illuminate\Support\Facades\Cache;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\Backend\Main\Helpers\WebserviceHelper;

/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class ChartHelper
{
	
	/**
     * Lấy danh sách các quyền từ một user
     *
     * @param string $user_id : id user
     *
     * @return string string
     */
	public static function getdataUnit($currentUnit,$year){
        $ListtypeHelper  = new ListtypeHelper();
		$arrOwnerdatas = $ListtypeHelper->_GetAllListObjectByListCode('DM_DON_VI_TRIEN_KHAI');
        foreach($arrOwnerdatas as $arrOwnerdata){
            if($arrOwnerdata['code'] == $currentUnit){
                $wsdl = $arrOwnerdata['link_webservice'];
                $username = $arrOwnerdata['username'];
                $password = $arrOwnerdata['password'];
                $code = $arrOwnerdata['code'];
                $name = $arrOwnerdata['name'];
            }
        }
        $objWebservice = new WebserviceHelper($wsdl);
        if($objWebservice){
            $arrParam = array(
                'sOwnerCode' => $currentUnit,
                'status' => 'DA_GIAI_QUYET',
                'CateId' => '',
                'recordType' => '',
                'sMonth' => '',
                'year' => $year,
                'wUser' => $username,
                'wPass' => $password,
            );
            $data['data'] = $objWebservice->_getData('EFY_baocaobieudo',$arrParam);
            $data['name'] = $name;
            if(isset($data['STATUS']) && $data['STATUS'] == 0){
                // loi webservect
                return false;
            }else{
                return $data;
            } 
        }else{
            return false;
        }  
	}


    public static function getalldataUnit($currentUnit,$year){
        $ListtypeHelper  = new ListtypeHelper();
        $arrOwnerdatas = $ListtypeHelper->_GetAllListObjectByListCode('DM_DON_VI_TRIEN_KHAI');
        foreach($arrOwnerdatas as $arrOwnerdata){
            if($arrOwnerdata['code'] == $currentUnit){
                $wsdl = $arrOwnerdata['link_webservice'];
                $username = $arrOwnerdata['username'];
                $password = $arrOwnerdata['password'];
                $code = $arrOwnerdata['code'];
                $name = $arrOwnerdata['name'];
            }
        }
        $fromdate = date("01/01/".$year);
        $todate = date("31/12/".$year);
        $objWebservice = new WebserviceHelper($wsdl);
        if($objWebservice){
            $arrParam = array(
            'fromdate' => $fromdate,
            'todate' => $todate,
            'ownercode' => $currentUnit,
            'wUser' => $username,
            'wPass' => $password,
            );
            $arrResult = $objWebservice->_getData('EFY_tonghopsolieu',$arrParam);
            $data['total'] = $arrResult['tong_ho_so'];
            $data['name'] = $name;
            if(isset($data['STATUS']) && $data['STATUS'] == 0){
                // loi webservect
                return false;
            }else{
                return $data;
            } 
        }else{
            return false;
        }  
    }

    public static function Pie_doughnut($arrResults){
        $dunghan = $quahan = $total = 0;
        $i=0;
        foreach($arrResults as $arrResult){
            $dunghan = $dunghan + $arrResult[0][0]->tong_dung_han;
            $quahan  = $quahan + $arrResult[0][0]->tong_qua_han; 
             $total = $total + $arrResults[$i]['total'];  
            $i++;
        }

        $result = [$dunghan, $quahan, $total];
        return $result;
    }

    public static function Pie_default($arrResults){
        $dunghan = $quahan = 0;
        foreach($arrResults as $arrResult){
            $dunghan = $dunghan + $arrResult[0][0]->tong_dung_han;
            $quahan  = $quahan + $arrResult[0][0]->tong_qua_han;
        }
        $result['label'] = ["Đúng hạn","Quá hạn"];
        $result['data'] = [$dunghan, $quahan];
        return $result;
    }

    public static function Barchart_month($arrResults){
        $dht1=$dht2=$dht3=$dht4=$dht5=$dht6=$dht7=$dht8=$dht9=$dht10=$dht11=$dht12='';
        $qht1=$qht2=$qht3=$qht4=$qht5=$qht6=$qht7=$qht8=$qht9=$qht10=$qht11=$qht12='';
        foreach($arrResults as $arrResult){
            if($arrResult[1]){
                $arrmonths = $arrResult[1];
                foreach($arrmonths as $arrmonth){
                    $moth = $arrmonth->C_MONTH;
                    if($arrmonth->C_DUNG_HAN > 0){
                         ${"dht" . $moth} = ${"dht" . $moth} + $arrmonth->C_DUNG_HAN;
                    }
                    if($arrmonth->C_QUAN_HAN > 0){
                        ${"qht" . $moth} = ${"qht" . $moth} + $arrmonth->C_QUAN_HAN;
                    }
                }
                //$dht.$arrResult->C_MONTH = $arrResult->C_DUNG_HAN;
            }
        }
        $arrReturn['label'] = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
         $arrReturn['datadunghan'] = [$dht1, $dht2, $dht3, $dht4, $dht5, $dht6, $dht7,$dht8,$dht9,$dht10,$dht11,$dht12];
         $arrReturn['dataquahan'] = [$qht1, $qht2, $qht3, $qht4, $qht5, $qht6, $qht7,$qht8,$qht9,$qht10,$qht11,$qht12];
         $arrReturn['status'] = false;
         return $arrReturn;
    }

    public static function Barchart_unit($arrResults){
        $dunghan = $quahan = '';
        $i = 0;
        foreach($arrResults as $arrResult){
            $arrLabel[$i] = $arrResult['unit_name'];
            if($arrResult[0][0]->tong_dung_han > 0){
                $arrDunghan[$i] = $arrResult[0][0]->tong_dung_han;
            }else{
                $arrDunghan[$i] = '';
            }
            if($arrResult[0][0]->tong_qua_han > 0){
                $arrQuahan[$i] = $arrResult[0][0]->tong_qua_han;
            }else{
                $arrQuahan[$i] = '';
            }
            $i++;
        }
        $arrReturn['label'] = $arrLabel;
        $arrReturn['datadunghan'] = $arrDunghan;
        $arrReturn['dataquahan'] = $arrQuahan;
        $arrReturn['status'] = false;
        return $arrReturn;
    }
}
