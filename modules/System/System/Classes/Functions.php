<?php

namespace Modules\System\System\Classes;

use Modules\System\System\Classes\DbFactory;
use Modules\System\System\Classes\AbstractDbClass;

/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class Functions extends AbstractDbClass
{
	public $parent_icon = 'fa fa-square database-lv3 fa-hight';
	public $icon = 'fa fa-file-code-o database-lv4 fa-hight';
	public $id = '';
	public $type = 'function';
	public $parent_type = 'other';
	public $text = 'Functions';
	public $state_opened = true;

	public function setPropertie($id = false, $root = false){
		$this->id = 'function_'.$id;
	}

	public function getScript($id = false, $dbname = false){
		// script
		if($id){
			$conn = new Connect($dbname);
			$sql = "
				SELECT
				definition
			FROM
				sys.sql_modules
			WHERE
			OBJECT_NAME(OBJECT_ID) = '".$id."'
			";
			$Result = $conn->getResult($sql);
			return $Result[0]['definition'];
		}
		return false;
	}

	public function getAll($dbname = false, $name = false){
		$conn = new Connect($dbname);
		$Result = $conn->getResult("SELECT name FROM sys.sql_modules m INNER JOIN sys.objects o ON m.object_id=o.object_id WHERE type_desc like '%function%' order by name");
		return $Result;
	}

	public function getSingle($namedb){
		$table = new Table();
		$allTables = $table->getAll($namedb);
	}

	public function getChildByTree($iddb = false, $root = false, $name = false){
		// get db name
		$objdb = DbFactory::getObjDb('database');
		$resultdb = $objdb->getSingle($iddb);
		$allTables = $this->getAll($resultdb[0]->name);
		$result = array();
		$i=0;
		foreach($allTables as $allTable){
			$result[$i]['children'] = false; 
			$result[$i]['icon'] = $this->icon;
			$result[$i]['id'] = $allTable['name'];
			$result[$i]['text'] = $allTable['name'];
			$result[$i]['type'] = $this->type;
			$result[$i]['dbname'] = $resultdb[0]->name;
			$i++;
		}
		return $result;
	}
}
