<?php

namespace Modules\System\System\Classes;

use Modules\System\System\Classes\Connect;
use Modules\System\System\Classes\DbFactory;
/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class Other
{
	public $parent_icon = 'fa fa-database fa-hight database-lv2';
	public $icon = 'fa fa-square database-lv3 fa-hight';
	public $id = '';
	public $text = '';
	public $type = 'other';
	public $parent_type = 'database';
	public $state_opened = true;

	public function setPropertie($iddb = false, $root = false){
		$this->id = $iddb;
		$objdb = DbFactory::getObjDb('database');
		$resultdb = $objdb->getSingle($iddb);
		$this->text = $resultdb[0]->name;
	}

	public function getScript($id = false){
		// script
	}

	public function getChildByTree($iddb = false, $root = false, $name = false){
		$result = array();
		$icon = $this->icon;
		if($root == 2){
			$result[0]['children'] = true; 
			$result[0]['icon'] = $icon;
			$result[0]['id'] = "table_".$iddb;
			$result[0]['text'] = "Table";
			$result[0]['type'] = $this->type;
			$result[1]['children'] = true; 
			$result[1]['icon'] = $icon;
			$result[1]['id'] = "store_".$iddb;
			$result[1]['text'] = "Store Procedure";
			$result[1]['type'] = $this->type;
			$result[2]['children'] = true; 
			$result[2]['icon'] = $icon;
			$result[2]['id'] = "function_".$iddb;
			$result[2]['text'] = "Functions";
			$result[2]['type'] = $this->type;
			$result[3]['children'] = true; 
			$result[3]['icon'] = $icon;
			$result[3]['id'] = "trigger_".$iddb;
			$result[3]['text'] = "Triggers";
			$result[3]['type'] = $this->type;
		}
		return $result; 
	}
}
