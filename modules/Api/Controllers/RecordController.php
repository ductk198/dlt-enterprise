<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Efy\Library;
use Modules\Api\Models\EsbRecordModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;
use URL;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Modules\Core\Efy\FileServer;

/**
 *
 * @author Duclt
 */
class RecordController extends Controller {

    /**
     * Check users
     *
     * @return json
     */
    public function GuiDon(Request $request) {
        $objFileserver = new FileServer();
        $stringpost = file_get_contents("php://input");
        $arrInput = (array) json_decode($stringpost);
        $donvigui = $arrInput['donvigui'];
        $donvinhan = $arrInput['donvinhan'];
        $nguoinopdon = $arrInput['nguoinopdon'];
        $diachinguoinopdon = $arrInput['diachinguoinopdon'];
        $loainguoinop = $arrInput['loainguoinop'];
        $soluongnguoinop = $arrInput['soluongnguoinop'];
        $dienthoainguoinop = $arrInput['dienthoainguoinop'];
        $noidungdon = $arrInput['noidungdon'];
        $loaidon = $arrInput['loaidon'];
        $ngayhen = $arrInput['ngayhen'];
        $file_dinh_kem = $arrInput['file_dinh_kem'];
        $pathfiletemp = $_SERVER['DOCUMENT_ROOT'] . '/hanoi-kntc-system/public/temp_upload/';
        $listfileTemp = '';
        foreach ($file_dinh_kem as $key => $value) {
            $filename = time() . '!~!' . $value->filename;
            $listfileTemp .= '!~~!' . $filename;
            file_put_contents($pathfiletemp . $filename, base64_decode($value->content));
        }
        $listfileTemp = trim($listfileTemp, '!~~!');
        //Them don thu vao truc
        $sql = "Exec ESB_InputRecord ";
        $sql .= "N''";
        $sql .= ",N'" . $donvigui . "'";
        $sql .= ",N'" . $donvinhan . "'";
        $sql .= ",N'" . $noidungdon . "'";
        $sql .= ",N'" . $loaidon . "'";
        $sql .= ",N'" . $ngayhen . "'";
        $sql .= ",N'" . $nguoinopdon . "'";
        $sql .= ",N'" . $diachinguoinopdon . "'";
        $sql .= ",N'" . $dienthoainguoinop . "'";
        $sql .= ",N'" . $loainguoinop . "'";
        $sql .= ",N'" . $soluongnguoinop . "'";
        $sql .= ",N'" . 'DA_GUI' . "'";
        $arrResult = DB::select($sql);
        $pkrecord = $arrResult[0]->C_NEWID;

        $arrFileTemp = explode('!~~!', $listfileTemp);
        $listfileserverid = '';
        foreach ($arrFileTemp as $val) {
            $fileserverId = $objFileserver->_upload($pathfiletemp . $val, $pkrecord, 'DON_KEM_THEO', 'T_ESB_RECORD');
            $listfileserverid .= '!###!' . $fileserverId . '!~!' . $val;
        }
        ///$listfileserverid = trim('!###!' . $listfileserverid);
        $listfileserverid = substr($listfileserverid, 5);
        $sqlUpdateFile = "EXEC ESB_SaveAtachFile ";
        $sqlUpdateFile .= "'" . $pkrecord . "'";
        $sqlUpdateFile .= ",'" . $listfileserverid . "'";

        $arrResultFile = DB::select($sqlUpdateFile);
        if ($arrResultFile[0]->C_MESSAGE == 'ok') {
            return json_encode(['code' => 200, 'mesage' => 'Cập nhật thành công!'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            return json_encode(['code' => 400, 'mesage' => 'Lỗi cập nhật CSDL!'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    public function GetFile($idfile, $filename) {
        $objFileserver = new FileServer();
        $file = $objFileserver->_open($idfile, $filename);
        return $file;
        dd(base64_encode($file));
    }

    public function NhanDon() {
        $objFileserver = new FileServer();
        $stringpost = file_get_contents("php://input");
        $arrInput = (array) json_decode($stringpost);
        $donvinhan = $arrInput['donvinhan'];
        $tungay = $arrInput['tungay'];
        $denngay = $arrInput['denngay'];
        $sql = "EXEC ESB_GetAllRecordByUnitReceive ";
        $sql .= "'" . $donvinhan . "'";
        $sql .= ",'" . $tungay . "'";
        $sql .= ",'" . $denngay . "'";
        $arrData = DB::select($sql);
        $i = 0;
        foreach ($arrData as $key => $value) {
            if ($value->C_FILE_ATTACH != '' && $value->C_FILE_ATTACH != null) {
                $arrFileAttach = explode(',', $value->C_FILE_ATTACH);
                $j = 0;
                foreach ($arrFileAttach as $val) {
                    $arrFileAttachSingle = explode('!~!', $val);
                    if (sizeof($arrFileAttachSingle) == 3) {
                        $idfile = $arrFileAttachSingle[0];
                        $filename = $arrFileAttachSingle[2];
                        $file = base64_encode($this->GetFile($idfile, $filename));
                        $arrData[$i]->filedinhkem[$j]['content'] = $file;
                        $arrData[$i]->filedinhkem[$j]['filename'] = $filename;
                        $j++;
                    }
                }
            }
            $i++;
        }
        return json_encode($arrData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function NhacViec() {
        // dem so luong ho so can lay ve cho tung don vi
        $stringpost = file_get_contents("php://input");
        $arrInput = (array) json_decode($stringpost);
        $donvinhan = $arrInput['donvinhan'];
        $tungay = $arrInput['tungay'];
        $denngay = $arrInput['denngay'];
        $sql = "EXEC ESB_GetAllReminderByUnitReceive ";
        $sql .= "'" . $donvinhan . "'";
        $sql .= ",'" . $tungay . "'";
        $sql .= ",'" . $denngay . "'";
        $arrData = DB::select($sql);
        $arrData = (array) $arrData[0];
        $arrData['note'] = 'Đơn thu cần nhận';
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function UpdateStatus() {
        $stringpost = file_get_contents("php://input");
        $arrInput = (array) json_decode($stringpost);
        if (!isset($arrInput['idrecord'])) {
            echo json_encode(array('status' => 401, 'msg' => 'Sai tham số truyền lên'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        } else {
            if ($arrInput['idrecord'] != '' && $arrInput['idrecord'] != null) {
                DB::table('ESB_RECORD')
                        ->where('PK_RECORD', $arrInput['idrecord'])
                        ->update(['C_STATUS_RECEIVE' => 'DA_NHAN_VE_DV']);
                echo json_encode(array('status' => 200, 'msg' => 'Cập nhật thành công!'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            } else {
                echo json_encode(array('status' => 400, 'msg' => 'Id đơn thư không được để trống'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
            }
        }
    }

}
