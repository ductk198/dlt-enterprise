<?php

namespace Modules\System\Position\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\System\Position\Models\PositionModel;
use Modules\Core\DLT\Xml;
use Modules\System\Position\Requests\Position as PositionRequest;
use Illuminate\Http\Request;
use DB;
use URL;

/**
 * Lớp xử lý quản trị, nhóm chức vụ
 *
 * @author Duclt
 */
class PositionController extends Controller {

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index() {
        $objlibrary = new Library();
        $result = array();
        $result = $objlibrary->_getAllFileJavaScriptCssArray('js', 'system/Position/JS_Position.js', ',', $result);
        $data['stringJsCss'] = json_encode($result);
        // lay loai danh muc
        //$ListtypeModel  = new PositionModel;
        // $data['arrListTypes'] = $ListtypeModel->_getAllbyStatus();
        return view('Position::Position.index', $data);
    }

    /**
     * Lấy danh sách danh mục đối tượng của một danh mục
     *
     * @param Request $request
     *
     * @return string json
     */
    public function loadList(Request $request) {
        $PositionGroupModel = new PositionModel;
        $currentPage = $request->currentPage;
        $perPage = $request->perPage;
        $search = $request->search;
        // lay danh sach danh muc
        $objResult = $PositionGroupModel->_getAll($currentPage, $perPage, $search);
        return \Response::JSON($objResult);
//        return \Response::JSON(array(
//                    'Dataloadlist' => $objResult,
//                    'pagination' => (string) $objResult->links('System::vendor.pagination.default'),
//                    'perPage' => $perPage,
//        ));
    }

    /**
     * Lấy giao diện thêm mới một nhóm chức vụ
     *
     * @param Request $request
     *
     * @return string view System::Position.add
     */
    public function add(Request $request) {
        $data = array();
        $order = PositionModel::count() + 1;
        $arrDep = DB::select("select * from T_USER_POSITION_GROUP");
        $shtml = "";
        foreach ($arrDep as $obj) {
            $shtml .= '<option id="' . $obj->PK_POSITION_GROUP . '" value="' . $obj->PK_POSITION_GROUP . '">' . $obj->C_NAME . '</option>';
        }
        $data['order'] = $order;
        $data['shtml'] = $shtml;
        return view('Position::Position.add', $data);
    }

    /**
     * Cập nhật một nhóm chức vụ
     *
     * @param Request $request
     *
     * @return string view System::Position.add
     */
    public function update(PositionRequest $request) {
        $PositionModel = new PositionModel;
        if ($request->input('C_STATUS') == 'on') {
            $status = 'HOAT_DONG';
        } else {
            $status = 'KHONG_HOAT_DONG';
        }
        $idPosition = $request->input('PK_POSITION');
        $arrParameter = array(
            'C_CODE' => $request->input('C_CODE'),
            'C_NAME' => $request->input('C_NAME'),
            'FK_POSITION_GROUP' => $request->input('FK_POSITION_GROUP'),
            'C_ORDER' => $request->input('C_ORDER'),
            'C_STATUS' => $status,
        );
        $arrResult = $PositionModel->_update($arrParameter, $idPosition);
        return \Response::JSON($arrResult);
    }

    /**
     * Lấy giao diện chinh một nhóm chức vụ
     *
     * @param Request $request
     *
     * @return string view System::Position.edit
     */
    public function edit(Request $request) {
        $PositionGroup = new PositionModel;
        $arrInput = $request->input();
        $idPositionGroup = $arrInput['chk_item_id'];
        $arrData = array();
        $arrData = $PositionGroup->_getSingle($idPositionGroup, 0);
        $arrDep = DB::select("select * from T_USER_POSITION_GROUP");
        $shtml = "";
        foreach ($arrDep as $obj) {
            $selected = '';
            if ($arrData['FK_POSITION_GROUP'] == $obj->PK_POSITION_GROUP) {
                $selected = "selected";
            }
            $shtml .= '<option id="' . $obj->PK_POSITION_GROUP . '" ' . $selected . ' value="' . $obj->PK_POSITION_GROUP . '">' . $obj->C_NAME . '</option>';
        }
        if ($arrData['C_STATUS'] == 'HOAT_DONG') {
            $arrData['checked'] = "checked";
        } else {
            $arrData['checked'] = "";
        }
        $arrData['shtml'] = $shtml;
        return view('Position::Position.edit', $arrData);
    }

    public function delete(Request $request) {
        $arrInput = $request->input();
        $PositionModel = new PositionModel;
        $listposit = $arrInput['listitem'];
        $arrResult = $PositionModel->_delete($listposit);
        return \Response::JSON($arrResult);
    }

}
