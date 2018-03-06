<?php

namespace Modules\System\Users\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\System\Users\Models\UserModel;
use Modules\System\Users\Helpers\UserHelper;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Users\Models\PositionModel;
use Modules\System\Users\Models\GroupModel;
use Illuminate\Support\Facades\Hash;
use Modules\Core\DLT\Xml;
use Illuminate\Http\Request;
use Modules\Backend\User\Helpers\ProvinceHelper;
use DB;
use URL;
use Excel;
use Uuid;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author duclt
 */
class UserController extends Controller {

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index() {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/jquery-ui-1.12.1.custom/jquery-ui.min.js,assets/chosen/chosen.jquery.min.js,assets/bootstrap-confirmation.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,System/Users/Js_User.js,System/Users/JS_Tree.js,System/Users/JS_TempHtml.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/tree/style.min.css', ',', $arrResult);
        // lay don vi trien khai
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        // lay don vi trien khai
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->orderBy('C_ORDER', 'asc')->get();
        $data['Units'] = $Units;
        $data['id_root'] = $UnitRoot[0]['PK_UNIT'];
        // lay nhom quyen
        // Chuc vu
        $data['Positions'] = PositionModel::all();
        //$data['permissions'] = GroupModel::all();
        $data['strJsCss'] = json_encode($arrResult);
        return view("Users::User.index", $data);
    }

    /**
     * Load màn hình danh sách
     *
     * @param Request $request
     *
     * @return view
     */
    public function loadlist(Request $request) {
        $Position = $request->Position;
        $user_name = $request->user_name;
        $showallpermission = false;
        $department = '';
        if ($request->Units !== '') {
            $department = $request->Units;
        }
        if ($request->department !== '') {
            $department = $request->department;
        }
        // lay don vi cap mot
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        // lay don vi trien khai
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->orderBy('C_ORDER', 'asc')->get(['PK_UNIT', 'C_NAME', 'C_ADDRESS', 'C_STATUS', 'C_CODE'])->toArray();
        return \Response::JSON($Units);
    }

    public function GetDepartment(Request $request) {
        $objlibrary = new Library();
        $valueselect = '';
        $id = $request->idunit;
        $Units = UnitModel::where("FK_UNIT", '=', $id)->get()->toArray();
        $htmls = '<option value="">-- Chọn phòng ban --</option>';
        foreach ($Units as $Unit) {
            $strID = $Unit['PK_UNIT'];
            $strValue = $Unit['C_NAME'];
            $htmls .= '<option value=' . '"' . $strID . '"' . '>' . $strValue . '</option>';
        }
        return $htmls;
    }

    public function add(Request $request) {
        //
        $data['parent_department'] = $request->id;
        $data['id'] = '';
        // lay ma don vi cap 1
        $departments = UnitModel::where("PK_UNIT", $request->id)->get()->toArray();
        $units = UnitModel::where("PK_UNIT", $departments[0]['FK_UNIT'])->get()->toArray();
        // lay phong ban
        $data['departments'] = UnitModel::where("FK_UNIT", $departments[0]['FK_UNIT'])->orderBy('C_ORDER', 'asc')->get();
        // lay chuc vu
        $data['departmentname'] = $departments[0]['C_NAME'];
        $data['unitname'] = $units[0]['C_NAME'];

        $data['positions'] = DB::table('USER_POSITION')
                ->join('USER_POSITION_GROUP', 'USER_POSITION.FK_POSITION_GROUP', '=', 'USER_POSITION_GROUP.PK_POSITION_GROUP')
                ->select('USER_POSITION.*', 'USER_POSITION_GROUP.C_NAME as C_NAME_GROUP')->orderBy('USER_POSITION_GROUP.C_ORDER', 'asc')->orderBy('USER_POSITION.C_ORDER')
                ->get();
        // $data['positions'] = PositionModel::all()->join('_USER_POSITION_GROUP', 'FK_POSITION_GROUP', '=', 'T_USER_POSITION_GROUP.PK_POSITION_GROUP');

        $data['required'] = " control-label required";
        $data['data'] = new UserModel();
        $data['data']->C_ROLE = 'USER';
        // order
        $count = UserModel::where('FK_UNIT', $request->id)->count();
        if ($count >= 1) {
            $count++;
        } else {
            $count = 1;
        }
        $data['check'] = 'checked';
        $data['data']['C_ORDER'] = $count;
        return view('Users::User.add', $data);
    }

    public function edit(Request $request) {
        // 
        $id = $request->id;
        // lay thong tin users
        $users = UserModel::find($id);
        if ($users->C_STATUS == 'HOAT_DONG') {
            $data['check'] = 'checked';
        } else {
            $data['check'] = '';
        }
        if ($users->C_ROLE !== 'ADMIN_SYSTEM' && $users->C_ROLE !== 'ADMIN_OWNER') {
            $users->C_ROLE = 'USER';
        }
        $data['required'] = " ";
        $data['id'] = $id;
        $data['data'] = $users;
        $data['parent_department'] = $users->FK_UNIT;
        // lay ma don vi cap 1
        $departments = UnitModel::where("PK_UNIT", $data['parent_department'])->get()->toArray();
        // lay phong ban
        $data['positions'] = DB::table('USER_POSITION')
                ->join('USER_POSITION_GROUP', 'USER_POSITION.FK_POSITION_GROUP', '=', 'USER_POSITION_GROUP.PK_POSITION_GROUP')
                ->select('USER_POSITION.*', 'USER_POSITION_GROUP.C_NAME as C_NAME_GROUP')->orderBy('USER_POSITION_GROUP.C_ORDER', 'asc')->orderBy('USER_POSITION.C_ORDER')
                ->get();
        $data['departments'] = UnitModel::where("FK_UNIT", $departments[0]['FK_UNIT'])->orderBy('C_ORDER', 'asc')->get();
        $units = UnitModel::where("PK_UNIT", $departments[0]['FK_UNIT'])->get()->toArray();
        $data['departmentname'] = $departments[0]['C_NAME'];
        $data['unitname'] = $units[0]['C_NAME'];
        // lay chuc vu

        return view('Users::User.add', $data);
    }

    // cap nhat
    public function update(Request $request) {
        $checkpass = true;
        $id = $request->id;
        // Kiem tra xem ten nguoi dung da ton tai chua
        if ($id !== '') {
            $Checkusers = UserModel::where("C_USERNAME", $request->username)->where("PK_STAFF", '<>', $id)->count();
        } else {
            $Checkusers = UserModel::where("C_USERNAME", $request->username)->count();
        }
        if ($Checkusers > 0) {
            return array('success' => false, 'message' => 'Tên người dùng đã tồn tại, vui lòng nhập tên khác!');
        }
        // update user
        if ($id !== '') {
            $checkpass = false;
            $UserModel = UserModel::find($id);
            if ($request->password !== '' && $request->password == $request->repassword) {
                $checkpass = true;
            }
        } else {
            $UserModel = new UserModel;
            $id = Uuid::generate();
            $UserModel->PK_STAFF = $id;
        }
        if ($request->status == 'HOAT_DONG') {
            $status = 'HOAT_DONG';
        } else {
            $status = 'KHONG_HOAT_DONG';
        }
        if ($checkpass) {
            $passwordmd5 = md5($request->password);
            $passwordhas = Hash::make($request->password);
            $UserModel->C_PASSWORD = $passwordmd5;
            $UserModel->password = $passwordhas;
        }
        $UserModel->FK_UNIT = $request->department;
        $UserModel->FK_POSITION = $request->position;
        $UserModel->C_NAME = $request->name;
        $UserModel->C_EMAIL = $request->email;
        $UserModel->C_TEL = $request->phone_number;
        $UserModel->C_USERNAME = $request->username;
        $UserModel->C_ORDER = $request->oder;
        $UserModel->C_STATUS = $status;
        $UserModel->C_ROLE = $request->vaitro;
        $UserModel->C_SEX = 'NAM';
        $UserModel->save();
        // update  order
        $users = UserModel::where("C_ORDER", ">=", $request->oder)->where("FK_UNIT", $request->department)->where("PK_STAFF", '<>', $id)->orderBy('C_ORDER', 'asc')->get(['PK_STAFF', 'C_ORDER'])->toArray();
        $i = $request->oder;
        foreach ($users as $user) {
            $i++;
            $UserModel = UserModel::find($user['PK_STAFF']);
            $UserModel->C_ORDER = $i;
            $UserModel->save();
        }
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->department);
    }

    public function delete(Request $request) {
        //
        $UserModel = UserModel::find($request->id);
        $UserModel->delete();
        return array('success' => true, 'message' => 'Xóa thành công', 'parent_id' => $UserModel->FK_UNIT);
    }

    // tim kiem
    public function search(Request $request) {
        $idunit = $request->idunit;
        $type = $request->param;
        $search = $request->search;
        if ($idunit == '' || $idunit == '#') {
            // lay id root
            $Units = UnitModel::where("FK_UNIT", '=', NULL)->get(['PK_UNIT'])->toArray();
            $idunit = $Units[0]['PK_UNIT'];
        }
        if ($type == 'user') {
            $data = UserHelper::get_user_by_child_unit($idunit, $search);
        } else if ($type == 'unit') {
            $data = UserHelper::get_unit_by_child_unit($idunit, $search);
        }
        $return['type'] = $type;
        $return['data'] = $data;
        return \Response::JSON($return);
    }

    //Lay giao dien import file 
    public function import() {
        return view('Users::User.import');
    }

    public function saveimport(Request $request) {
        $unittype = $request->input()['unittype'];
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/FILEIMPORT' . time() . '.xlsx';
        $order = UnitModel::count();
        $order ++;
        move_uploaded_file($_FILES['file']['tmp_name'], $filename);
        $data = Excel::load($filename, function($reader) {
                    
                })->get()->toArray();
        $arrIdRoot = DB::select("select PK_UNIT  from T_USER_UNIT where FK_UNIT is null");
        $idRoot = $arrIdRoot[0]->PK_UNIT;
        if ($unittype == 'QHSN') {
            foreach ($data as $value) {
                $UnitModel = new UnitModel;
                $archeck = DB::select("select PK_UNIT  from T_USER_UNIT where C_CODE = '" . $value['ma_don_vi'] . "'");
                if (!$archeck) {
                    $uid = Uuid::generate();
                    $UnitModel->PK_UNIT = $uid;
                    $UnitModel->FK_UNIT = $idRoot;
                    $UnitModel->C_TYPE_GROUP = $value['nhom_don_vi'];
                    $UnitModel->C_CODE = $value['ma_don_vi'];
                    $UnitModel->C_NAME = $value['ten_don_vi'];
                    $UnitModel->C_ADDRESS = $value['dia_chi'];
                    $UnitModel->C_TEL = $value['dien_thoai'];
                    $UnitModel->C_FAX = $value['fax'];
                    $UnitModel->C_EMAIL = $value['email'];
                    $UnitModel->C_OWNER_CODE = $value['ma_don_vi_su_dung'];
                    $UnitModel->C_DISTRICT_WARD_PROCESS = null;
                    $UnitModel->C_REPORT_UPPERCASE = $value['ten_viet_hoa'];
                    $UnitModel->C_REPORT_LOWERCASE = $value['ten_viet_thuong'];
                    $UnitModel->C_REPORT_PLACE = $value['dia_danh_tren_mau_in'];
                    $UnitModel->C_REPORT_UNIT = $value['ten_don_vi_chu_quan'];
                    $UnitModel->C_REPORT_SYMBOL = null;
                    $UnitModel->C_REPORT_CONTACT = $value['nguoi_len_he_tren_mau_in'];
                    $UnitModel->C_WEB_SITE = $value['website_cua_don_vi'];
                    $UnitModel->C_STATUS = 'HOAT_DONG';
                    $UnitModel->C_ORDER = $order;
                    $UnitModel->C_DATE_IMPORT = date('Y/m/d H:i:s');
                    $UnitModel->C_IS_IMPORT = '1';
                    $UnitModel->C_SHORT_NAME_UNIT = $value['ten_viet_tat'];
                    $UnitModel->save();
                    $order++;
                }
            }
        } else {
            foreach ($data as $value) {
                if ($value['ma_don_vi'] == null || $value['ma_don_vi'] == '') {
                    break;
                }
                $archeck = DB::select("select PK_UNIT  from T_USER_UNIT where C_CODE = '" . $value['ma_don_vi'] . "'");
                if ($archeck == array()) {
                    $uid = Uuid::generate();
                    $UnitModel = new UnitModel;
                    $arrUnit = DB::select("select PK_UNIT  from T_USER_UNIT where C_OWNER_CODE = '" . $value['ma_don_vi_su_dung'] . "' and  FK_UNIT = '502AE491-1C3C-4FE4-99D1-C6CEC676F64B'");
                    $fk_unit = $arrUnit[0]->PK_UNIT;
                    $UnitModel->PK_UNIT = $uid;
                    $UnitModel->FK_UNIT = $fk_unit;
                    $UnitModel->C_TYPE_GROUP = $value['nhom_don_vi'];
                    $UnitModel->C_CODE = $value['ma_don_vi'];
                    $UnitModel->C_NAME = $value['ten_don_vi'];
                    $UnitModel->C_ADDRESS = $value['dia_chi'];
                    $UnitModel->C_TEL = $value['dien_thoai'];
                    $UnitModel->C_FAX = $value['fax'];
                    $UnitModel->C_EMAIL = $value['email'];
                    $UnitModel->C_OWNER_CODE = $value['ma_don_vi_su_dung'];
                    $UnitModel->C_DISTRICT_WARD_PROCESS = $value['ma_phuong_xa'];
                    $UnitModel->C_REPORT_UPPERCASE = $value['ten_viet_hoa'];
                    $UnitModel->C_REPORT_LOWERCASE = $value['ten_viet_thuong'];
                    $UnitModel->C_REPORT_PLACE = $value['dia_danh_tren_mau_in'];
                    $UnitModel->C_REPORT_UNIT = $value['ten_don_vi_chu_quan'];
                    $UnitModel->C_REPORT_SYMBOL = null;
                    $UnitModel->C_REPORT_CONTACT = $value['nguoi_len_he_tren_mau_in'];
                    $UnitModel->C_WEB_SITE = $value['website_cua_don_vi'];
                    $UnitModel->C_STATUS = 'HOAT_DONG';
                    $UnitModel->C_ORDER = $order;
                    $UnitModel->C_DATE_IMPORT = date('Y/m/d H:i:s');
                    $UnitModel->C_IS_IMPORT = '1';
                    $UnitModel->C_SHORT_NAME_UNIT = $value['ten_viet_tat'];
                    $UnitModel->save();
                    $order++;
                }
            }
        }
        unlink($filename);
        return 'Cập nhật thành công';
    }

}
