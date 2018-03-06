<?php

namespace Modules\System\Listtype\Controllers;

use Illuminate\Http\Request;
use Modules\Core\DLT\Library;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Modules\System\Listtype\Models\ListModel;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Listtype\Models\ListtypeModel;
use Modules\Core\DLT\Xml;
use URL;
use DB;
use Illuminate\Support\Facades\Cache;

/**
 * Controler xử lý về đối tượng danh mục.
 *
 * @author Duclt
 */
class ListController extends Controller {

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index() {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'system/listtype/Js_List.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/bootstrap-confirmation.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        // lay loai danh muc
        $ListtypeModel = new ListtypeModel;
        $data['arrListTypes'] = $ListtypeModel->_getAllbyStatus();
        // lay don vi trien khai
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->get();
        $data['Units'] = $Units;
        return view('Listtype::List.index', $data);
    }

    /**
     * Lấy danh sách danh mục đối tượng của một danh mục
     *
     * @param Request $request
     *
     * @return string json
     */
    public function loadList(Request $request) {
        $ListModel = new ListModel;
        $currentPage = $request->currentPage;
        $perPage = $request->perPage;
        $search = $request->search;
        $listtype = $request->listtype;
        $Units = $request->Units;
        $ListtypeModel = ListtypeModel::find($listtype);
        $xml_filename = 'quan_tri_doi_tuong_danh_muc.xml';
        if ($ListtypeModel->C_XML_FILE_NAME !== '') {
            $checkpathfile = base_path("xml\System\list") . '\\' . $ListtypeModel->C_XML_FILE_NAME;
            if (file_exists($checkpathfile)) {
                $xml_filename = $ListtypeModel->C_XML_FILE_NAME;
            }
        }
        $xmlFilePath = URL::to('/xml/System/list/' . $xml_filename);
        // lay danh sach danh muc
        $objResult = $ListModel->_getAll($listtype, $currentPage, $perPage, $search, $Units);
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Listtype::vendor.pagination.default'),
                    'perPage' => $perPage,
                    'xmlFilePath' => $xmlFilePath
        ));
    }

    /**
     * Thêm mới một loại danh mục đối tượng
     *
     * @param Request $request
     *
     * @return view
     */
    public function add(Request $request) {
        $objlibrary = new Library();
        $ListtypeModel = new ListtypeModel;
        $objxml = new Xml();
        $arrListtype = $ListtypeModel->_getSingle($request->listtype);
        $xml_file_name = 'quan_tri_doi_tuong_danh_muc.xml';
        if (isset($arrListtype['C_XML_FILE_NAME']) && $arrListtype['C_XML_FILE_NAME'] !== '') {
            $xml_file_name = $arrListtype['C_XML_FILE_NAME'];
        }
        $sxmlFileName = base_path('xml\System\list\\' . $xml_file_name);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $p_xml_string_in_db = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        $p_arr_item_value = array();
        $p_arr_item_value['C_ORDER'] = ListModel::where('FK_LISTTYPE', $request->input('listtype'))->count() + 1;
        $strrHtml = $objxml->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, $p_xml_string_in_db, $p_arr_item_value);
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->get();
        $data = array(
            'strrHTML' => $strrHtml,
            'oldorder' => $p_arr_item_value['C_ORDER'],
            'listtype_id' => $request->listtype,
            'idlist' => ''
        );
        $data['data']['id'] = '';
        $data['Units'] = $Units;
        $data['data']['ownercode'] = $request->Units;
        return view('Listtype::List.add', $data);
    }

    /**
     * Hiệu chỉnh một danh mục đối tượng
     *
     * @param Request $request
     *
     * @return view
     */
    public function edit(Request $request) {
        $objXml = new Xml();
        $objlibrary = new Library();
        $ListModel = new ListModel;
        $ListtypeModel = new ListtypeModel;
        $itemid = $request->input('itemId');
        $arrListtype = $ListtypeModel->_getSingle($request->listtype);
        $xml_file_name = 'quan_tri_doi_tuong_danh_muc.xml';
        if (isset($arrListtype['C_XML_FILE_NAME']) && $arrListtype['C_XML_FILE_NAME'] !== '') {
            $checkpathfile = base_path("xml\System\list") . '\\' . $arrListtype['C_XML_FILE_NAME'];
            if (file_exists($checkpathfile)) {
                $xml_file_name = $arrListtype['C_XML_FILE_NAME'];
            }
        }
        $sxmlFileName = base_path('xml\System\list\\' . $xml_file_name);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $p_xml_string_in_db = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        $p_arr_item_value = $ListModel->_getSingle($itemid);
//        dd($pathXmlTagStruct);
        $strrHtml = $objXml->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, 'C_XML_DATA', $p_arr_item_value);
        $data = array(
            'strrHTML' => $strrHtml,
            'oldorder' => $p_arr_item_value['C_ORDER'],
            'listtype_id' => $request->listtype,
            'idlist' => $itemid
        );
        $data['data']['ownercode'] = $p_arr_item_value['C_OWNER_CODE_LIST'];
        return view('Listtype::List.add', $data);
    }

    /**
     * Cập nhật một danh mục đối tượng
     *
     * @param Request $request
     *
     * @return string json
     */
    public function update(Request $request) {
        $arrInput = $request->input();
        $ListModel = new ListModel;
        $idlist = $request->input('idlist');
        if ($request->input('C_STATUS') == 'on') {
            $status = 'HOAT_DONG';
        } else {
            $status = 'KHONG_HOAT_DONG';
        }
        $arrParameter = array(
            'C_CODE' => $request->input('C_CODE'),
            'FK_LISTTYPE' => $request->input('listtype'),
            'C_NAME' => $request->input('C_NAME'),
            'C_ORDER' => $request->input('C_ORDER'),
            'C_XML_DATA' => $request->input('stringxml'),
            'C_STATUS' => $status,
            'C_OWNER_CODE_LIST' => $request->input('ListOwnercode')
        );

        //$this->export($request->listtype);
        $arrResult = $ListModel->_update($arrParameter, $idlist);
        if ($arrInput['oldorder'] != $arrInput['C_ORDER']) {
            DB::select("EXEC DLT_UpdateListOrder '" . $arrInput['listtype'] . "','" . $arrInput['C_ORDER'] . "','" . $arrInput['oldorder'] . "','" . $arrInput['C_CODE'] . "'");
        }
        return \Response::JSON($arrResult);
    }

    /**
     * Xóa một danh mục đối tượng
     *
     * @param Request $request
     *
     * @return string json
     */
    public function delete(Request $request) {
        $ListModel = new ListModel;
        $listitem = $request->input('listitem');
        //xuat caches
//        $this->export($request->listtype);
        $arrResult = $ListModel->_delete($listitem);

        return \Response::JSON($arrResult);
    }

    /**
     * Xuat caches
     *
     * @param Request $request
     *
     * @return string json
     */
    public function exportCache(Request $request) {
        if ($this->export($request->listtype) == true) {
            return array('success' => true, 'message' => 'Xuất cache thành công');
        } else {
            return array('success' => false, 'message' => 'Không có dữ liệu');
        }
    }

    public function export($listtype_id) {
        $arrResult = DB::table('system_listtype as a')
                        ->join('system_list as b', 'a.id', '=', 'b.listtype_id')
                        ->where('a.id', $listtype_id)
                        ->select('b.code', 'b.name', 'b.xml_data', 'a.code as list_type_code')
                        ->get()->toArray();
        if ($arrResult) {
            $count = sizeof($arrResult);
            for ($i = 0; $i < $count; $i++) {
                $temp = array();
                $temp['code'] = $arrResult[$i]->code;
                $temp['name'] = $arrResult[$i]->name;
                if ($arrResult[$i]->xml_data != '') {
                    $objXml = simplexml_load_string($arrResult[$i]->xml_data);
                    $datalist = (array) $objXml->data_list;
                    foreach ($datalist as $key => $value) {
                        $temp[(string) $key] = (string) $value;
                    }
                }
                $data[] = $temp;
            }
            $listtype_code = $arrResult[0]->list_type_code;
            Cache::forget($listtype_code);
            Cache::forever($listtype_code, $data);
            return true;
        } else {
            return false;
        }
    }

}
