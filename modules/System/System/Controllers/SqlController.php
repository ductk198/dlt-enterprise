<?php
namespace Modules\System\System\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\System\System\Helpers\SqlHelper;
use Modules\System\System\Classes\DbFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\System\System\Classes\Connect;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use DB;
/*
    Exec sql
    author : tungnt 23-10-2017
*/
class SqlController extends Controller
{

    public function index() {
		$objLibrary = new Library();
		$SqlHelper = new SqlHelper();
		$arrResult = array();
		$arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js','assets/jquery-ui-1.12.1.custom/jquery-ui.min.js,assets/chosen/chosen.jquery.min.js,assets/bootstrap-confirmation.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,assets/editor/ace/ace.js,assets/editor/php-editor.js,System/System/Js_Sql.js,assets/jquery.validate.js', ',', $arrResult);
		$arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/tree/style.min.css', ',', $arrResult);
        $data['strJsCss'] = json_encode($arrResult);
        $data['alldbs'] = $SqlHelper->getAlldb();
        $data['mode'] = 'data-ace-editor-mode=ace/mode/sqlserver';
        return view("System::Sql.index", $data);
    }

    /**
 	 * Lay case thu muc doi tuong cap 1
 	 * @param Request $request
 	 * @return jSon
 	 */
	public function getall(Request $request){
        $node_level = $request->root;
        $id = $request->id;
		$SqlHelper = new SqlHelper();
        $path_folder = $_SERVER['DOCUMENT_ROOT'];
        $returns = $SqlHelper->getChildByNodeLevel($node_level,$id);
		// du lieu tra ve dang json
		return \Response::JSON($returns);
    }
    
    public function open_script(Request $request){
        $SqlHelper = new SqlHelper();
        $type = $request->type;
        $dbname = $request->dbname;
        $id = $request->id;
        $scripts = $SqlHelper->getScript($id,$type,$dbname);
        $data['mode'] = 'data-ace-editor-mode=ace/mode/sqlserver';
        $data['content'] = $scripts;
        return view("System::Sql.editor", $data);
    }

    public function new_query(Request $request){
        $data['mode'] = 'data-ace-editor-mode=ace/mode/sqlserver';
        $data['content'] = "";
        return view("System::Sql.editor", $data);
    }

    public function open_table(Request $request){
        $SqlHelper = new SqlHelper();
        $type = $request->type;
        $dbname = $request->dbname;
        $id = $request->id;
        $tables = $SqlHelper->getTable($id,$type,$dbname);
        $data['tables'] = $tables;
        $data['name_table'] = $id;
        return view("System::Sql.view_table", $data);
    }


    public function excute(Request $request){
        $code = $request->code;
        $database = $request->db;
        if($code && $database){
            $conn = new Connect($database);
            $Result = $conn->getResult_excute($code);
        }
        if($Result){
            return array('success' => true,'message' => 'Thành công', 'result' =>$Result);
        }else{
            return array('success' => true,'message' => 'Thành công');
        }
    }

    public function excutes(Request $request){
        $code = $request->code;
        $listdb = $request->listdb;
        $listdb = trim($listdb,",");
        $databases = \explode(",",$listdb);
        if($databases){
            foreach($databases as $database){
                if($code && $database){
                    $conn = new Connect($database);
                    $Result = $conn->getResult_excute($code);
                }
            }
        }
        if($Result){
            return array('success' => true,'message' => 'Thành công', 'result' =>$Result);
        }else{
            return array('success' => true,'message' => 'Thành công');
        }
    }

    public function checkpass(Request $request){
        $oldPass = Auth::user()->password;
        $pass = password_verify($request->input('txtpass'), $oldPass);
        $sqlString = $request->sql;
        if($pass){
            $db = $request->listitem;
            $con = $this->sqlconnect($db);
            $stmt = sqlsrv_query( $con, $sqlString );
            if($stmt){
                $Result = array();
                $i= 0;
                while( $ArrResult = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    $Result[$i] = $ArrResult;
                    $i++;
                }
                return array('success' => true,'message' => 'Thành công', 'data' =>$Result);
            }else{
                return array('success' => false,'message' => 'Thực thi không thành công');
            }
        }else{
            return array('success' => false,'message' => 'Rất tiếc,mật khẩu không đúng');
        }
    }

    public function view_excutes(Request $request){
        $SqlHelper = new SqlHelper();
        $code = $request->code;
        $data['alldbs'] = $SqlHelper->getAlldb();
        $data['code'] = $code;
        return view("System::Sql.execSql", $data);
    }

    public function zendDb(Request $request){
        $ListtypeHelper = new ListtypeHelper();
        $SqlHelper = new SqlHelper();
        $code = $request->code;
        // get all don vi tham gia he thong
        $UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
        $Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->orderBy('C_ORDER', 'asc')->get();
        $alldbs = $SqlHelper->getAlldb();
        $db_frefix = env('PRE_FIX_DB','');
        $UnitSave = array();
        $i=0;
        foreach($Units as $Unit){
            $check_isset = false;
            $db_check = $db_frefix.$Unit->C_CODE;
            foreach($alldbs as $alldb){
                if($db_check == $alldb->name){
                    $check_isset = true;
                }
            }
            if(!$check_isset){
                $UnitSave[$i]['code'] =  $db_check;
                $UnitSave[$i]['name'] =  $Unit->C_NAME;
                $i++;
            }
        }
        // Duong dan thu muc luu database
        $arrCauhinhs = $ListtypeHelper->_GetAllListObjectByListCode('DM_THAM_SO_HE_THONG');
        foreach($arrCauhinhs as $arrCauhinh){
            if($arrCauhinh['C_CODE'] == 'PATH_SQLSERVER'){
                $path = $arrCauhinh['C_NAME'];
            }
        }
        $data['alldbs'] = $UnitSave;
        $data['code'] = "";
        $data['label'] = "KHỞI TẠO DATABASE";
        $data['path'] = $path;
        return view("System::Sql.CreateDb", $data);
    }

    public function create_db(Request $request){
        $ListtypeHelper = new ListtypeHelper();
        $SqlHelper = new SqlHelper();
        $listdb = $request->listdb;
        $listdb = trim($listdb,",");
        $databases = \explode(",",$listdb);
        // Duong dan thu muc luu database
        $arrCauhinhs = $ListtypeHelper->_GetAllListObjectByListCode('DM_THAM_SO_HE_THONG');
        foreach($arrCauhinhs as $arrCauhinh){
            if($arrCauhinh['C_CODE'] == 'PATH_SQLSERVER'){
                $path = $arrCauhinh['C_NAME'];
            }
        }
        if($databases){
            foreach($databases as $database){
                if($database){
                    $sql_create = $SqlHelper->create_db_temp($database,$path);
                    DB::select($sql_create);
                    //echo "<pre>"; print_r($sql_create); echo "</pre>"; exit;
                    $conn = new Connect($database);
                    $sql = "IF NOT EXISTS (SELECT name FROM sys.filegroups WHERE is_default=1 AND name = N'PRIMARY') 
                    ALTER DATABASE [".$database."] MODIFY FILEGROUP [PRIMARY] DEFAULT
                    GO ";
                    $Result = $conn->getResult_excute($sql);
                }
            }
        }
        return array('success' => true,'message' => 'Thành công');
    }

    public function restoreDb(Request $request){
        $ListtypeHelper = new ListtypeHelper();
        $SqlHelper = new SqlHelper();
        $code = $request->code;
        // get all don vi tham gia he thong
        $alldbs = $SqlHelper->getAlldb();
        $db_frefix = env('PRE_FIX_DB','');
        $UnitSave = array();
        $i=0;
        foreach($alldbs as $alldb){
            $check_isset = false;
            // kiem tra xem table co bang du lieu chua, neu chua thi moi cho phuc hoi
            $conn = new Connect($alldb->name);
            $sql = "select * from sys.tables";
            $Result = $conn->getResult_excute($sql);
            if($Result['message'] == 'error'){
                $UnitSave[$i]['code'] =  $alldb->name;
                $UnitSave[$i]['name'] =  $alldb->name;
                $i++;
            }
        }
        // Duong dan thu muc luu database
        $arrCauhinhs = $ListtypeHelper->_GetAllListObjectByListCode('DM_THAM_SO_HE_THONG');
        foreach($arrCauhinhs as $arrCauhinh){
            if($arrCauhinh['C_CODE'] == 'BACKUP_SQLDATA'){
                $path = $arrCauhinh['C_NAME'];
            }
        }
        $data['alldbs'] = $UnitSave;
        $data['code'] = "";
        $data['label'] = "PHỤC HỒI DATABASE";
        $data['path'] = $path."\\tempdb.bak";
        return view("System::Sql.CreateDb", $data);
    }

    public function update_restoreDb(Request $request){
        $ListtypeHelper = new ListtypeHelper();
        $SqlHelper = new SqlHelper();
        $listdb = $request->listdb;
        $listdb = trim($listdb,",");
        $databases = \explode(",",$listdb);
        // Duong dan thu muc luu database
        $arrCauhinhs = $ListtypeHelper->_GetAllListObjectByListCode('DM_THAM_SO_HE_THONG');
        foreach($arrCauhinhs as $arrCauhinh){
            if($arrCauhinh['C_CODE'] == 'BACKUP_SQLDATA'){
                $restore_path = $arrCauhinh['C_NAME'];
            }
            if($arrCauhinh['C_CODE'] == 'PATH_SQLSERVER'){
                $db_path = $arrCauhinh['C_NAME'];
            }
        }
        $restore_path = $restore_path."\\temp-quanhuyen.bak";
        $sql = "";
        if($databases){
            foreach($databases as $database){
                if($database){
                    // Khoi phuc database
                    $sql .= "<br>".$SqlHelper->restore_db_temp($database,$restore_path,$db_path);
                    //$conn = new Connect(env('DB_DATABASE'));
                    //$Result = $conn->getResult_excute($sql);
                }
            }
        }
        return array('success' => true,'message' => 'Thành công', 'data' => $sql);
    }
}