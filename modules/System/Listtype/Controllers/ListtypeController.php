<?php

namespace Modules\System\Listtype\Controllers;

use Illuminate\Http\Request;
use Modules\Core\DLT\Library;
use App\Http\Controllers\Controller;
use Modules\System\Listtype\Models\ListtypeModel;
use Modules\Core\DLT\Xml;
use Modules\System\Users\Models\UserModel;
use Modules\System\Users\Models\UnitModel;
use URL;
use DB;

/**
 * Controler xử lý về loại danh mục.
 *
 * @author Duclt
 */
class ListtypeController extends Controller {

    /**
     * Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index() {
        $objLibrary = new Library();
        $arrResult = array();
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'system/Listtype/Js_Listtype.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/bootstrap-confirmation.js,assets/jquery.validate.js', ',', $arrResult);
        $arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $arrResult);
        $data['stringJsCss'] = json_encode($arrResult);
        // lay don vi trien khai
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->get();
        $data['Units'] = $Units;
        return view('Listtype::Listtype.index', $data);
    }

    /**
     * Lấy danh sách danh mục
     *
     * @param Request $request
     *
     * @return string json
     */
    public function loadList(Request $request) {
        $ListtypeModel = new ListtypeModel;
        $currentPage = $request->currentPage;
        $perPage = $request->perPage;
        $search = $request->search;
        $unit = $request->Units;
        $objResult = $ListtypeModel->_getAll($currentPage, $perPage, $search, $unit);
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Listtype::vendor.pagination.default'),
                    'perPage' => $perPage,
        ));
    }

    /**
     * Thêm mới một danh mục
     *
     * @param Request $request
     *
     * @return view
     */
    public function add(Request $request) {
        $objlibrary = new Library();
        $objxml = new Xml();
        $xml_file_name = $request->_filexml;
        //$Units = $request->Units;
        $sxmlFileName = base_path('xml\System\listtype\\'.$xml_file_name);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $p_xml_string_in_db = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        // lay so thu tu lon nhat
        $p_arr_item_value = array();
        $p_arr_item_value['C_ORDER'] = ListtypeModel::count() + 1;
        $p_arr_item_value['C_STATUS'] = 'HOAT_DONG';
        $strrHtml = $objxml->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, $p_xml_string_in_db, $p_arr_item_value);
        $data['strHTML'] = $strrHtml;
        // lay don vi trien khai
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->get();
        $data['data']['id'] = '';
        $data['Units'] = $Units;
        $data['data']['strHTML'] = $strrHtml;
        $data['data']['id'] = '';
        $data['data']['listtype_xml'] = $xml_file_name;
        $data['data']['ownercode'] = $request->Units;
        
        return view('Listtype::listtype.add', $data);
    }

    /**
     * Hiệu chỉnh một loại danh mục
     *
     * @param Request $request
     *
     * @return view
     */
    public function edit(Request $request) {
        $objlibrary = new Library();
        $ListtypeModel = new ListtypeModel;
        $objxml = new Xml();
        $xml_file_name = $request->_filexml;
        $sxmlFileName = base_path('xml\System\listtype\\'.$xml_file_name);
        $pathXmlTagStruct = 'update_object/table_struct_of_update_form/update_row_list';
        $pathXmlTag = 'update_object/update_formfield_list';
        $p_xml_string_in_db = '<?xml version="1.0" encoding="UTF-8"?><root><data_list></data_list></root>';
        $itemid = $request->input('itemId');
        $p_arr_item_value = $ListtypeModel->_getSingle($itemid);
        //dd($p_arr_item_value);
        $strrHtml = $objxml->xmlGenerateFormfield($sxmlFileName, $pathXmlTagStruct, $pathXmlTag, $p_xml_string_in_db, $p_arr_item_value);
        // lay don vi trien khai
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->get();
        $data['Units'] = $Units;
        $data['strHTML'] = $strrHtml;
        $data['data']['strHTML'] = $strrHtml;
        $data['data']['id'] = $itemid;
        $data['data']['listtype_xml'] = $xml_file_name;
        $data['data']['ownercode'] = $p_arr_item_value['C_OWNER_CODE_LIST'];
        return view('Listtype::Listtype.add', $data);
    }

    /**
     * Cập nhật một loại danh mục
     *
     * @param Request $request
     *
     * @return string json
     */
    public function update(Request $request) {
        // Filter du lieu

        $ListtypeModel = new ListtypeModel;
        $listtype_xml = $request->listtype_xml;
        $idlistype = $request->input('listtype_id');
        if ($request->input('C_STATUS') == 'on') {
            $status = 'HOAT_DONG';
        } else {
            $status = 'KHONG_HOAT_DONG';
        }
        $arrParameter = array(
            'C_CODE' => $request->input('C_CODE'),
            'C_NAME' => $request->input('C_NAME'),
            'C_ORDER' => $request->input('C_ORDER'),
            'C_XML_FILE_NAME' => $request->input('C_XML_FILE_NAME'),
            'C_STATUS' => $status,
            'C_OWNER_CODE_LIST' => trim($request->input('ListOwnercode'), ","),
        );
        $arrResult = $ListtypeModel->_update($arrParameter, $idlistype);
        return \Response::JSON($arrResult);
    }

    /**
     * Cập nhật một loại danh mục
     *
     * @param Request $request
     *
     * @return string json
     */
    public function delete(Request $request) {
        $ListtypeModel = new ListtypeModel;
        $listitem = $request->input('listitem');
        $arrResult = $ListtypeModel->_delete($listitem);
        return \Response::JSON($arrResult);
    }

    /**
     * Xuat caches
     *
     * @param Request $request
     *
     * @return string json
     */
    public function exportCaches(Request $request) {
        $options['features'] = SOAP_SINGLE_ELEMENT_ARRAYS;
        $options = array(
            'login' => env('USER_WS_GENERAL', ''),
            'password' => env('PASSWORD_WS_GENERAL', ''),
            'soap_version' => SOAP_1_1,
        );
        $ws = new \SoapClient(env('URL_WS_GENERAL', ''), $options);
        $data = $ws->EFY_ExportCache();
        if ($data['code'] == 200) {
            $arrResult = array('success' => true, 'message' => 'Xuất cache thành công');
        } else {
            $arrResult = array('warning' => true, 'message' => 'Lỗi cập nhật CSDL');
        }
        return \Response::JSON($arrResult);
    }

}
