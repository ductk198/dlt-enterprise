<?php 

namespace Modules\System\System\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Modules\System\System\Helpers\CodeHelper;
use Modules\System\System\Helpers\UserHelper;
use Illuminate\Http\Request;
use Modules\System\Users\Models\UnitModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\UploadedFile;
use DB;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Duclt
 */
class CodeController extends Controller
{
	private $path_folder = '';
	public $root_name = 'CODE MANAGER';


	function __construct() {
		$this->path_folder = env('FOLDER_CODE', $_SERVER['DOCUMENT_ROOT']);
	}

	public function index() {
		$objLibrary = new Library();
		$objCodeHelper = new CodeHelper();
		$arrResult = array();
		$arrResult = $objLibrary->_getAllFileJavaScriptCssArray('js','assets/jquery-ui-1.12.1.custom/jquery-ui.min.js,assets/chosen/chosen.jquery.min.js,assets/bootstrap-confirmation.js,assets/jstree/jstree.min.js,assets/jstree/jstreetable.js,assets/editor/ace/ace.js,assets/editor/php-editor.js,System/System/Js_Code.js,assets/jquery.validate.js', ',', $arrResult);
		$arrResult = $objLibrary->_getAllFileJavaScriptCssArray('css', 'assets/chosen/bootstrap-chosen.css,assets/tree/style.min.css', ',', $arrResult);
		$data['strJsCss'] = json_encode($arrResult);
        return view("System::Code.index", $data);
    }

 	/**
 	 * Lay case thu muc doi tuong cap 1
 	 * @param Request $request
 	 * @return jSon
 	 */
	public function getall(Request $request){
		$node_level = $request->root;
		$objCodeHelper = new CodeHelper();
		$path_folder = $this->path_folder;
		if($node_level > 1 && $request->id !== $this->root_name){
			$foder_name = $request->id;
			$arrName = explode("\\",$foder_name);
			$returns['icon'] = 'fa fa-folder fa-hight folder-v1';
			$returns['id'] = $request->id;
			$returns['text'] = $arrName[sizeof($arrName)-1];
			$returns['state']['opened'] = 1;
			$path_folder = $path_folder."\\".$foder_name;
			if (is_dir($path_folder)){
				$returns['type'] = 'folder'; 
			}else{
				$returns['type'] = 'file'; 
			}
			$children = $objCodeHelper->dirToArray($node_level,$path_folder,$foder_name);
		}else{
			$returns['icon'] = 'fa fa-home fa-hight';
			$returns['id'] = $this->root_name;
			$returns['text'] = $this->root_name;
			$returns['state']['opened'] = 1;
			$returns['type'] = 'folder'; 
			$children = $objCodeHelper->dirToArray($node_level,$path_folder,"");
		}
		$returns['children'] = $children;
		// du lieu tra ve dang json
		return \Response::JSON($returns);
	}

	public function open_file(Request $request){
		$path_file = $request->path_file;
		$arrName = explode("\\",$path_file);
		$filename = $arrName[sizeof($arrName)-1];
		$extention = explode(".",$filename);
		$type = $extention[sizeof($extention)-1];
		if($type == 'php'){
			$mode = 'data-ace-editor-mode=ace/mode/php';
		}else if($type == 'css'){
			$mode = 'data-ace-editor-mode=ace/mode/css';	
		}else if($type == 'js'){
			$mode = 'data-ace-editor-mode=ace/mode/javascript';
		}else{
			$mode = 'data-ace-editor-mode=ace/mode/html';
		}
		if($path_file){
			$real_path = $_SERVER['DOCUMENT_ROOT']."\\".$path_file;
			$content = file_get_contents($real_path);
		}
		$data['content'] = $content;
		$data['mode'] = $mode;
		$data['path_file'] = $path_file;
		return view('System::Code.open_file', $data);
	}

	public function update_file(){
		
		$root_folder = $this->path_folder;
		$path = $_POST["path"];
		$path_file = $root_folder.$path;
		$content = $_POST["content"];
		// save file
		if(file_put_contents($path_file, $content)){
			return array('success' => true, 'message' => 'Cập nhật thành công', 'path' => $_POST["parent_path"]);
		}else{
			return array('success' => false, 'message' => 'Cập nhật thất bại'); 
		}
	}

	public function add_folder(Request $request){
		$root_folder = $this->path_folder;
		$root_url = $request->parrent_path;
		$name_folder = $request->name_folder;
		$add_folder = $root_folder.$root_url.'\\'.$name_folder;
		if($root_url === $this->root_name){
			if(file_exists($root_folder.'\\'.$name_folder)){
				return 'false';
			}else{
				mkdir($root_folder.'\\'.$name_folder);
				return array('success' => true, 'message' => 'Cập nhật thành công', 'path' => $name_folder);
			}
		}else{
			if(file_exists($add_folder)){
				return 'false';
			}else{
				mkdir($add_folder,0777);
				return array('success' => true, 'message' => 'Cập nhật thành công', 'path' => $name_folder,'url' => $root_url.'\\'.$name_folder);
			}
		}
	}

	public function add_file(Request $request){
		$root_folder = $this->path_folder;
		$root_url = $request->parrent_path;
		$name_file = $request->name_file;
		$add_file = $root_folder.$root_url.'\\'.$name_file;
		 //echo $root_folder.'\\'.$name_file;exit();
		if($root_url === $this->root_name){
			if(file_exists($root_folder.'\\'.$name_file)){
				return 'false';
			}else{
				fopen($root_folder.'\\'.$name_file,'w+');
				return array('success' => true, 'message' => 'Cập nhật thành công', 'path' => $root_url);
			}
		}else{
			if(file_exists($add_file)){
				return 'false';
			}else{
				fopen($add_file,'w+');
				return array('success' => true, 'message' => 'Cập nhật thành công', 'path' => $root_url);
			}
		}
	}

	public function delete(Request $request){
		$root_folder = $this->path_folder;
		$path = $request->path;
		$remove_path = $root_folder.$path;
		$str = strpos($remove_path,'.');
		if($str != '' && $str != null ){
			unlink($remove_path);
			return array('success' => true, 'message' => 'Xóa thành công');
		}else{
			exec(sprintf("rd /s /q %s", escapeshellarg($remove_path)));
			exec('exit');
			return array('success' => true, 'message' => 'Xóa thành công');
		}
	}

	public function edit(Request $request){
		$root_folder = $this->path_folder;
		$path = $request->path;
		$new_path = $request->new_path;
		$edit_new_path = $root_folder.'\\'.$new_path;
		$edit_path = $root_folder.$path;
		rename($edit_path,$edit_new_path);
		return array('success' => true, 'message' => 'Sửa thành công');
	}

	public function upload_file(){
		return view('System::Code.upload_file');
	}

	
	public function upload(Request $request){
		$path = $request->path_url;
		// echo $path;die();
		// echo $_SERVER['DOCUMENT_ROOT'].'/'.$path;die();
		if (isset($_FILES['files']) && !empty($_FILES['files'])) {
			$no_files = count($_FILES["files"]['name']);
			for ($i = 0; $i < $no_files; $i++) {
				if ($_FILES["files"]["error"][$i] > 0) {
					echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
				} else {
					if($path === $this->root_name){
						if (file_exists($_SERVER['DOCUMENT_ROOT'].'/' . $_FILES["files"]["name"][$i])) {
							$result = array('danger' => true,'message' => 'File đã tồn tại');
						} else {
							move_uploaded_file($_FILES["files"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT'].'/' . $_FILES["files"]["name"][$i]);
							$result = array('success' => true,'message' => 'Tải lên thành công');
						}
					}else{
						if (file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$path. $_FILES["files"]["name"][$i])) {
							$result = array('danger' => true,'message' => 'File đã tồn tại');
						} else {
							move_uploaded_file($_FILES["files"]["tmp_name"][$i], $_SERVER['DOCUMENT_ROOT'].'/'.$path.'/' . $_FILES["files"]["name"][$i]);
							$result = array('success' => true,'message' => 'Tải lên thành công');
						}
					}
					
				}
			}
		} else {
			$result = array('danger' => true,'message' => 'Vui lòng chọn file');
		}
		return $result;
	}

	public function download(Request $request){
		// echo "<pre>";print_r($_SERVER);die();
		$root_folder =$this->path_folder;
		$root_path = substr($request->path,1);
		$nameview =  $request->name;
		// Get real path for our folder
		$rootPath = realpath($root_folder.'\\'.$root_path);
		// Initialize archive object
		$zip = new \ZipArchive();
		$temp = tempnam(sys_get_temp_dir(), 'digit_');
		$t = time();
		$zipfilename = $temp . $t . '.zip';
		$zip->open($zipfilename, \ZipArchive::CREATE);
		// echo url('public/zip');die();
		
		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		if(is_file($rootPath)){
			$zip->addFile($rootPath, $nameview);
		}else{
			$files = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($rootPath),
				\RecursiveIteratorIterator::LEAVES_ONLY
			);
	
			foreach ($files as $name => $file)
			{
				// Skip directories (they would be added automatically)
				if (!$file->isDir())
				{
					// Get real and relative path for current file
					$filePath = $file->getRealPath();
					$relativePath = substr($filePath, strlen($rootPath) + 1);
					// Add current file to archive
					$zip->addFile($filePath, $relativePath);
				}
			}
		}
		// Zip archive will be created only after closing object
		$zip->close();
		header("Content-disposition: attachment; filename=$nameview.zip");
		header('Content-type: application/zip');
		readfile($zipfilename);
	}

	public function export_module(Request $request){
		$UnitRoot = UnitModel::where("FK_UNIT", '=', NULL)->get()->toArray();
		$Units = UnitModel::where("FK_UNIT", '=', $UnitRoot[0]['PK_UNIT'])->get();
		$data['data']['id'] = '';
        $data['Units'] = $Units;
        $data['data']['id'] = '';
        $data['data']['ownercode'] = $request->Units;
		return view('System::Code.export_modules', $data);
	}

	public function zend_unit(Request $request){
		$idunit = $request->unit;
		$Units = UnitModel::where("C_CODE", '=', $idunit)->get();
		if($Units){
			$type = $Units[0]->C_TYPE_GROUP;
		}
		$Units = UnitModel::where("C_CODE", '<>', $idunit)
		->where("C_TYPE_GROUP", '=', $type)
		->get();
		$data['data']['id'] = '';
        $data['Units'] = $Units;
        $data['data']['id'] = '';
        $data['data']['ownercode'] = $request->Units;
		return view('System::Code.zend_unit', $data);
	}

	public function export(Request $request)
    {
        $disk = $_SERVER['DOCUMENT_ROOT'];
        $val = $request->input();
        $source = $val['source'];
        $listdest = $val['destination'];
        $listdest = trim($listdest, ',');
        $arrDest = explode(',', $listdest);
		$project = env('PROJECT_GENERAL');
		if($arrDest){
			foreach ($arrDest as $key => $val) {
				$des = $disk."\\".$project."\modules\\" . $val;
				exec(sprintf("rd /s /q %s", escapeshellarg($des)));
				$exec = 'XCOPY "'.$disk.'\\'.$project.'\modules\\' . $source . '" "' . $des . '" /s /e /y /i';
				exec($exec);
				exec('exit');
			}
		}
    }
	
}
