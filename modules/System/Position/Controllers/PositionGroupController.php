<?php

namespace Modules\System\Position\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\System\Position\Models\PositionGroupModel;
use Modules\Core\DLT\Xml;
use Modules\System\Position\Requests\PositionGroup as PositionRequest;
use Illuminate\Http\Request;
use DB;
use URL;

/**
 * Lớp xử lý quản trị, nhóm chức vụ
 *
 * @author Duclt
 */
class PositionGroupController extends Controller {

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index() {
        $objlibrary = new Library();
        $result = array();
        $result = $objlibrary->_getAllFileJavaScriptCssArray('js', 'system/Position/JS_PositionGroup.js', ',', $result);
        $data['stringJsCss'] = json_encode($result);
        // lay loai danh muc
        //$ListtypeModel  = new PositionModel;
        // $data['arrListTypes'] = $ListtypeModel->_getAllbyStatus();
        return view('Position::PositionGroup.index', $data);
    }

    /**
     * Lấy danh sách danh mục đối tượng của một danh mục
     *
     * @param Request $request
     *
     * @return string json
     */
    public function loadList(Request $request) {
        $PositionGroupModel = new PositionGroupModel;
        $currentPage = $request->currentPage;
        $perPage = $request->perPage;
        $search = $request->search;
        // lay danh sach danh muc
        $objResult = $PositionGroupModel->_getAll($currentPage, $perPage, $search);
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Position::vendor.pagination.default'),
                    'perPage' => $perPage,
        ));
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
        $order = PositionGroupModel::count() + 1;
        $data['order'] = $order;
        return view('Position::PositionGroup.add', $data);
    }

    /**
     * Cập nhật một nhóm chức vụ
     *
     * @param Request $request
     *
     * @return string view System::Position.add
     */
    public function update(PositionRequest $request) {
        $PositionModel = new PositionGroupModel;
        if ($request->input('C_STATUS') == 'on') {
            $status = 'HOAT_DONG';
        } else {
            $status = 'KHONG_HOAT_DONG';
        }
        $idPosition = $request->input('PK_POSITION_GROUP');
        $arrParameter = array(
            'C_CODE' => $request->input('C_CODE'),
            'C_NAME' => $request->input('C_NAME'),
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
        $PositionGroup = new PositionGroupModel;
        $arrInput = $request->input();
        $idPositionGroup = $arrInput['chk_item_id'];
        $arrData = array();
        $arrData = $PositionGroup->_getSingle($idPositionGroup, 0);
        if ($arrData['C_STATUS'] == 'HOAT_DONG') {
            $arrData['checked'] = "checked";
        } else {
            $arrData['checked'] = "";
        }
        return view('Position::PositionGroup.edit', $arrData);
    }

    public function delete(Request $request) {
        $arrInput = $request->input();
        $PositionGroupModel = new PositionGroupModel;
        $listpositgroup = $arrInput['listitem'];
        $arrResult = $PositionGroupModel->_delete($listpositgroup);
        return \Response::JSON($arrResult);
    }
}
