<?php

namespace Modules\System\System\Classes;

use Modules\System\System\Classes\Connect;
use Modules\System\System\Classes\DbFactory;

/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class Table extends AbstractDbClass
{
	public $parent_icon = 'fa fa-square database-lv3 fa-hight';
	public $icon = 'fa fa-table database-lv4 fa-hight';
	public $id = '';
	public $type = 'table';
	public $parent_type = 'other';
	public $text = 'Table';
	public $state_opened = true;

	public function setPropertie($id = false, $root = false){
		$this->id = 'table_'.$id;
	}

	public function getScript($id = false, $dbname = false){
		// script
	}

	public function getInfor($id = false, $dbname = false){
		// script
		if($id){
			$conn = new Connect($dbname);
			$sql = "
			SELECT ORDINAL_POSITION, COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH
					, IS_NULLABLE
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = '".$id."' 
			";
			$Result = $conn->getResult($sql);
			return $Result;
		}
		return false;
	}

	public function getAll($dbname = false, $name = false){
		$conn = new Connect($dbname);
		$Result = $conn->getResult("select * from sys.tables where name <> 'sysdiagrams' ");
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
