<?php

namespace Modules\System\System\Classes;

use Modules\System\System\Classes\AbstractDbClass;
use DB;
use Log;
use Request;

/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class Database extends AbstractDbClass
{
	public $parent_icon = 'fa fa-cubes fa-hight database-lv1';
	public $icon = 'fa fa-database fa-hight database-lv2';
	public $id = 'Server';
	public $type = 'database';
	public $parent_type = 'root';
	public $text = 'SQL MANAGER';
	public $state_opened = true;

	public function setPropertie($id = false, $root = false){
		
	}

	public function getScript($id = false, $dbname = false){
		// script
	}

	public function getAll($dbname = false, $name = false) { 
		$Dbs = DB::select("SELECT * 
		FROM master.dbo.sysdatabases  WHERE name != 'master' and name != 'tempdb' and name != 'model' and name != 'msdb' order by name");
		return $Dbs;
	} 

	public function getSingle($id) { 
		$Dbs = DB::select("SELECT * 
		FROM master.dbo.sysdatabases  WHERE dbid = ".$id." ");
		return $Dbs;
	}

	public function getChildByTree($id = false, $root = false, $name = false){
		$result = array();
		$Dbs = $this->getAll();
		$i=0;
		foreach ($Dbs as $Db) 
		{ 
			 $pos = strpos($Db->name, "ReportServer");
			 if ($pos === false) {
				 $result[$i]['children'] = true; 
				 $result[$i]['icon'] = $this->icon;
				 $result[$i]['id'] = $Db->dbid;
				 $result[$i]['text'] = $Db->name;
				 $result[$i]['type'] = 'database';
				 $result[$i]['dbname'] = $Db->name;
				 $i++;
			 }
		}
		return $result; 
	}
}
