<?php

namespace Modules\System\Enterprise\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\System\Enterprise\Models\EnterpriseModel;
use Modules\Core\DLT\Xml;
use Modules\System\Enterprise\Requests\Enterprise as EnterpriseRequest;
use Illuminate\Http\Request;
use DB;
use Excel;
use URL;

/**
 * Lớp xử lý quản trị, nhóm doanh nghiệp
 *
 * @author Duclt
 */
class EnterpriseController extends Controller {

    /**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
    public function index() {
        $objlibrary = new Library();
        $result = array();
        $result = $objlibrary->_getAllFileJavaScriptCssArray('js', 'system/Enterprise/JS_Enterprise.js', ',', $result);
        $result = $objlibrary->_getAllFileJavaScriptCssArray('js', 'assets/chosen/chosen.jquery.min.js,assets/bootstrap-confirmation.js,assets/jquery.validate.js', ',', $result);
        $result = $objlibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css', ',', $result);
        $data['stringJsCss'] = json_encode($result);
        // lay loai danh muc
        return view('Enterprise::Enterprise.index', $data);
    }

    /**
     * Lấy danh sách danh mục đối tượng của một danh mục
     *
     * @param Request $request
     *
     * @return string json
     */
    public function loadList(Request $request) {
        $EnterpriseModel = new EnterpriseModel;
        $currentPage = $request->currentPage;
        $perPage = $request->perPage;
        $search = $request->search;
        // lay danh sach danh muc
        $objResult = $EnterpriseModel->_getAll($currentPage, $perPage, $search);
        return \Response::JSON(array(
                    'Dataloadlist' => $objResult,
                    'pagination' => (string) $objResult->links('Enterprise::vendor.pagination.default'),
                    'perPage' => $perPage,
        ));
    }

    /**
     * Lấy giao diện thêm mới một nhóm doanh nghiệp
     *
     * @param Request $request
     *
     * @return string view System::Enterprise.add
     */
    public function add(Request $request) {
        $data = array();
        $sql = "select C_CODE,C_NAME from T_DLT_LIST where FK_LISTTYPE = 1";
        $arrTinh = DB::select($sql);
        $data['arrTinh'] = $arrTinh;
        return view('Enterprise::Enterprise.add', $data);
    }

    public function import(Request $request) {
        return view('Enterprise::Enterprise.import');
    }

    public function saveimport(Request $request) {
        $unittype = $request->input()['unittype'];
        $objlibrary = new Library();
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/public/attach-file/FILEIMPORT' . time() . '.xlsx';
        move_uploaded_file($_FILES['file']['tmp_name'], $filename);
        $data = Excel::load($filename, function($reader) {
                    
                })->get()->toArray();
        foreach ($data as $value) {
            $matinh = str_replace('Tỉnh ', '', $value['tentinh']);
            $matinh = str_replace('TP ', '', $matinh);
            $matinh = str_replace('Thành Phố ', '', $matinh);
            $matinh = str_replace('Tp ', '', $matinh);
            $matinh = str_replace(' ', '-', $matinh);
            $matinh = $objlibrary->convert_vi_to_en($matinh);
            $ngaycap = $value['ngaycap'];
            $arrNgaycap = explode('/', $ngaycap);
            $ngaycap = $arrNgaycap[2] . '/' . $arrNgaycap[1] . '/' . $arrNgaycap[0];
            $array = array(
                'MASOTHUE' => $value['masothue'],
                'TENCONGTY' => $value['tencongty'],
                'TENGIAODICH' => $value['tengiaodich'],
                'NGAYCAP' => $ngaycap,
                'DIACHITRUSOCHINH' => $value['diachitrusochinh'],
                'SODIENTHOAI' => $value['sodienthoai'],
                'EMAIL' => $value['email'],
                'NGUOIDAIDIEN' => $value['nguoidaidien'],
                'DIACHINGUOIDAIDIEN' => $value['diachinguoidaidien'],
                'NGANHNGHECHINH' => $value['nganhnghechinh'],
                'LINHVUKINHTE' => $value['linhvukinhte'],
                'TENTINH' => $value['tentinh'],
                'MATINH' => $matinh,
            );
            $sql = $objlibrary->_exportsql($array, 'DLT_EnterpriseImportUpdate');
            $a = DB::select($sql);
        }
        unlink($filename);
        return 'Cập nhật thành công';
    }

    /**
     * Cập nhật một nhóm doanh nghiệp
     *
     * @param Request $request
     *
     * @return string view System::Enterprise.add
     */
    public function update(EnterpriseRequest $request) {
        $EnterpriseModel = new EnterpriseModel;
        $objlibrary = new Library();
        $arrInput = $request->input();
        if ($request->input('C_STATUS') == 'on') {
            $status = 'HOAT_DONG';
        } else {
            $status = 'KHONG_HOAT_DONG';
        }
        $idEnterprise = $arrInput['PK_DLT_ENTERPRISE'];
        $matinh = str_replace('Tỉnh ', '', $arrInput['TENTINH']);
        $matinh = str_replace('TP ', '', $matinh);
        $matinh = str_replace('Thành Phố ', '', $matinh);
        $matinh = str_replace('Thành phố ', '', $matinh);
        $matinh = str_replace('Tp ', '', $matinh);
        $matinh = str_replace(' ', '-', $matinh);
        $matinh = $objlibrary->convert_vi_to_en($matinh);
        $sql = "select C_CODE from T_DLT_LIST where dbo.f_GetValueOfXMLtag(C_XML_DATA,'url_tinh_tp') = '$matinh'";
        $arrMatinh = DB::select($sql);
        if (isset($arrMatinh[0]))
            $matinh = $arrMatinh[0]->C_CODE;
        $arrParameter = array(
            'MASOTHUE' => $arrInput['MASOTHUE'],
            'TENCONGTY' => $arrInput['TENCONGTY'],
            'TENGIAODICH' => $arrInput['TENGIAODICH'],
            'NGAYCAP' => $arrInput['NGAYCAP'],
            'DIACHITRUSOCHINH' => $arrInput['DIACHITRUSOCHINH'],
            'SODIENTHOAI' => $arrInput['SODIENTHOAI'],
            'EMAIL' => $arrInput['EMAIL'],
            'NGUOIDAIDIEN' => $arrInput['NGUOIDAIDIEN'],
            'DIACHINGUOIDAIDIEN' => $arrInput['DIACHINGUOIDAIDIEN'],
            'NGANHNGHECHINH' => $arrInput['NGANHNGHECHINH'],
            'LINHVUCKINHTE' => $arrInput['LINHVUCKINHTE'],
            'TENTINH' => $arrInput['TENTINH'],
            'MATINH' => $matinh,
            'NGAYNHAP' => \date('Y-m-d H:i:s'),
        );
        $arrResult = $EnterpriseModel->_update($arrParameter, $idEnterprise);
        return \Response::JSON($arrResult);
    }

    /**
     * Lấy giao diện chinh một nhóm doanh nghiệp
     *
     * @param Request $request
     *
     * @return string view System::Enterprise.edit
     */
    public function edit(Request $request) {
        $EnterpriseModel = new EnterpriseModel;
        $arrInput = $request->input();
        $idEnterprise = $arrInput['chk_item_id'];
        $arrData = array();
        $sql = "select C_CODE,C_NAME from T_DLT_LIST where FK_LISTTYPE = 1";
        $arrTinh = DB::select($sql);
        $arrSingle = $EnterpriseModel->_getSingle($idEnterprise, 0);
        $arrData['arrTinh'] = $arrTinh;
        $arrData['arrSingle'] = $arrSingle;
        return view('Enterprise::Enterprise.edit', $arrData);
    }

    public function delete(Request $request) {
        $arrInput = $request->input();
        $EnterpriseModel = new EnterpriseModel;
        $listpositgroup = $arrInput['listitem'];
        $arrResult = $EnterpriseModel->_delete($listpositgroup);
        return \Response::JSON($arrResult);
    }

}
