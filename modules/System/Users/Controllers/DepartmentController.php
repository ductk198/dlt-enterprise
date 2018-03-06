<?php

namespace Modules\System\Users\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Users\Models\UserModel;
use Modules\System\Users\Helpers\UserHelper;
use Modules\Core\DLT\Xml;
use Illuminate\Http\Request;
use DB;
use URL;
use Uuid;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author duclt
 */
class DepartmentController extends Controller {

    /**
     * Thêm phòng ban
     *
     * @param Request $request
     *
     * @return view
     */
    public function add(Request $request) {
        //
        $objlibrary = new Library();
        $objxml = new Xml();
        $xml_file_name = 'Department.xml';
        $sxmlFileName = URL::asset('xml/Backend/User/' . $xml_file_name);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $p_xml_string_in_db = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        $p_arr_item_value = array();
        $strrHtml = $objxml->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, $p_xml_string_in_db, $p_arr_item_value);
        $data['strHTML'] = $strrHtml;
        // lay thu tu hien thi
        $data['parent_id'] = $request->id;
        $data['id'] = '';
        $data['listtype_xml'] = $xml_file_name;
        // kiem tra xem don vi them moi nay co la don vi trien khai hay khong
        $p_arr_item_value = UnitModel::find($data['parent_id'])->toArray();
        if (is_null($p_arr_item_value['FK_UNIT']) || $p_arr_item_value['FK_UNIT'] == '') {
            $type = 'unit';
            $data['check_dvtk'] = "checked";
        } else {
            $type = 'department';
            $data['check_dvtk'] = "disabled";
        }
        $data['data'] = new UnitModel();
        $data['unitparent'] = $p_arr_item_value['C_NAME'];
        $data['data']['C_STATUS'] = 'HOAT_DONG';
        $count = UnitModel::where('FK_UNIT', $data['parent_id'])->count();
        if ($count >= 1) {
            $count++;
        } else {
            $count = 1;
        }
        $data['data']['C_ORDER'] = $count;
        if ($type == 'department') {
            return view('Users::Department.add', $data);
        } else {
            return view('Users::Unit.add', $data);
        }
    }

    /**
     * Sửa phòng ban
     *
     * @param Request $request
     *
     * @return view
     */
    public function edit(Request $request) {
        //
        $objxml = new Xml();
        $id = $request->id;
        $p_arr_item_value = UnitModel::find($id)->toArray();
        $p_arr_parrent_value = UnitModel::find($p_arr_item_value['FK_UNIT'])->toArray();
        if (is_null($p_arr_parrent_value['FK_UNIT']) || $p_arr_parrent_value['FK_UNIT'] == '') {
            $type = 'unit';
        } else {
            $type = 'department';
        }
        $data['data'] = $p_arr_item_value;
        $data['id'] = $request->id;
        $data['parent_id'] = $p_arr_item_value['FK_UNIT'];
        $data['unitparent'] = $p_arr_parrent_value['C_NAME'];
        // kiem tra xem don vi co la don vi trien khai hay khong
        if ($p_arr_item_value['C_TYPE_GROUP'] == 'QUAN_HUYEN' || $p_arr_item_value['C_TYPE_GROUP'] == 'SO_NGANH') {
            $data['check_dvtk'] = "checked";
        } else {
            $data['check_dvtk'] = "disabled";
        }

        if ($type == 'department') {
            return view('Users::Department.add', $data);
        } else {
            return view('Users::Unit.add', $data);
        }
    }

    public function update(Request $request) {
        //
        $id = $request->id;
        if ($id !== '') {
            $UnitModel = UnitModel::find($id);
        } else {
            $UnitModel = new UnitModel;
            $id = Uuid::generate();
            $UnitModel->PK_UNIT = $id;

            // kiem tra da ton tai ma don vi hay chua
            $checkcode = UnitModel::where("C_CODE", $request->C_CODE)->count();
            if ($checkcode > 0) {
                return array('success' => false, 'message' => 'Mã đơn vị đã tồn tại');
            }
        }
        $status = 'KHONG_HOAT_DONG';
        if ($request->C_STATUS == 'on') {
            $status = 'HOAT_DONG';
        }
        // kiem tra xem don vi co phai la don vi trien khai hay khong
        if (isset($request->check_dvtk) && $request->check_dvtk == 'on') {
            $UnitModel->C_OWNER_CODE = $request->C_CODE;
        } else {
            $p_arr_item_value = UnitModel::find($request->parent_id)->toArray();
            $UnitModel->C_OWNER_CODE = $p_arr_item_value['C_OWNER_CODE'];
        }
        // kiem tra xem don vi co phai la phuong xa hay khong
        if (isset($request->group_unit) && $request->group_unit == 'PHUONG_XA') {
            $district_ward = $request->C_CODE;
        } else {
            $district_ward = '';
        }
        $UnitModel->FK_UNIT = $request->parent_id;
        $UnitModel->C_TYPE_GROUP = $request->group_unit;
        $UnitModel->C_CODE = $request->C_CODE;
        $UnitModel->C_NAME = $request->C_NAME;
        $UnitModel->C_ADDRESS = $request->C_ADDRESS;
        $UnitModel->C_TEL = $request->C_TEL;
        $UnitModel->C_FAX = $request->C_FAX;
        $UnitModel->C_EMAIL = $request->C_EMAIL;
        $UnitModel->C_DISTRICT_WARD_PROCESS = $district_ward;
        $UnitModel->C_REPORT_UPPERCASE = $request->C_REPORT_UPPERCASE;
        $UnitModel->C_REPORT_LOWERCASE = $request->C_REPORT_LOWERCASE;
        $UnitModel->C_REPORT_PLACE = $request->C_REPORT_PLACE;
        $UnitModel->C_REPORT_UNIT = $request->C_REPORT_UNIT;
        $UnitModel->C_REPORT_SYMBOL = $request->C_REPORT_SYMBOL;
        $UnitModel->C_REPORT_CONTACT = $request->C_REPORT_CONTACT;
        $UnitModel->C_WEB_SITE = $request->C_WEB_SITE;
        $UnitModel->C_STATUS = $status;
        $UnitModel->C_ORDER = $request->C_ORDER;
        $UnitModel->save();
        // update order
        $units = UnitModel::where("C_ORDER", ">=", $request->C_ORDER)->where("FK_UNIT", $request->parent_id)->where("PK_UNIT", '<>', $id)->orderBy('C_ORDER', 'asc')->get(['PK_UNIT', 'C_ORDER'])->toArray();
        $i = $request->C_ORDER;
        foreach ($units as $unit) {
            $i++;
            $UnitModel = UnitModel::find($unit['PK_UNIT']);
            $UnitModel->C_ORDER = $i;
            $UnitModel->save();
        }
        return array('success' => true, 'message' => 'Cập nhật thành công', 'parent_id' => $request->parent_id);
    }

    public function delete(Request $request) {
        //
        $UnitModel = UnitModel::find($request->id);
        // lay parrent id
        $Checks = UnitModel::where("FK_UNIT", $request->id)->count();
        if ($Checks > 0) {
            return array('success' => false, 'message' => 'Bạn không thể xóa đơn vị khi còn người dùng hoặc phòng ban trong đơn vị đó!');
        }
        $Checkusers = UserModel::where("FK_UNIT", $request->id)->count();
        if ($Checkusers > 0) {
            return array('success' => false, 'message' => 'Bạn không thể xóa đơn vị khi còn người dùng hoặc phòng ban trong đơn vị đó!');
        }
        $UnitModel->delete();
        return array('success' => true, 'message' => 'Xóa thành công', 'parent_id' => $UnitModel->FK_UNIT);
    }

}
