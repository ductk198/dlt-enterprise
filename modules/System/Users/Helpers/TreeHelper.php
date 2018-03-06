<?php

namespace Modules\System\Users\Helpers;

use DB;
use Log;
use Request;

use Illuminate\Support\Facades\Cache;
use Modules\System\Users\Models\UserModel;
use Modules\System\Users\Models\UnitModel;
use Modules\Backend\User\Helpers\PermissionHelper;
use Modules\Backend\User\Helpers\ProvinceHelper;


/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class TreeHelper
{
	/**
	 * Lay cac don vi con cua minh
	 *
	 * @return array
	 */
	public static function zend_tree_unit($unit_id,$node_lv) {
		$_this= new self;
		$return = array();
		$column = ['PK_UNIT','C_NAME','C_CODE','C_ADDRESS','C_STATUS','C_ORDER','C_TYPE_GROUP'];
		// lay cac don vi cap 1
		if($unit_id!== ''){
			$units = UnitModel::where("PK_UNIT", $unit_id)->orderBy('C_ORDER', 'asc')->get($column)->toArray();
		}else{
			$node_lv= $node_lv+1;
			$units = UnitModel::where("FK_UNIT", NULL)->orderBy('C_ORDER', 'asc')->get($column)->toArray();
			$unit_id = $units[0]['PK_UNIT'];	
		}
		if($units[0]['C_STATUS'] == 'HOAT_DONG'){
			$return['icon'] = $_this->incon_by_root($node_lv,true,$units[0]['C_TYPE_GROUP']);
		}else{
			$return['icon'] = $_this->incon_by_root(0,true,$units[0]['C_TYPE_GROUP']);
		}
		
		// lay tat ca don vi con cua don vi day
		$child_units = UnitModel::where("FK_UNIT", $unit_id)->orderBy('C_ORDER', 'asc')->get($column)->toArray();
		$return['id'] = $units[0]['PK_UNIT'];
		$return['node_lv'] = $node_lv;
		$return['name'] = $units[0]['C_NAME'];
		$return['status'] = $units[0]['C_STATUS'];
		$return['code'] = $units[0]['C_CODE'];
		$return['type'] = $units[0]['C_TYPE_GROUP'];
		$return['order'] = $units[0]['C_ORDER'];
		$return['address'] = $units[0]['C_ADDRESS'];
		$return['text'] = "<span class='js-tree-text'>".$units[0]['C_NAME']."</span>";
		$return['a_attr'] = array('type'=>'department');
		$return['state']  = array(
				"opened" => true
		);
		$i=0;
		$node_lv= $node_lv+1;
		if($child_units){
			// lay phong ban
			foreach($child_units as $child_unit){
				if($child_unit){
					$return['children'][$i] = $_this->create_tree_by_unit($child_unit, $node_lv);
					$i++;
				}
			}
		}
		return $return;
	}
	
	public static function create_tree_by_unit($child_unit, $node_lv) {
		$_this = new self;
		// kiem tra don vi co phong ban con khong
		$count = UnitModel::where("FK_UNIT", $child_unit['PK_UNIT'])->count();
		$return['id'] = $child_unit['PK_UNIT'];
		$return['node_lv'] = $node_lv;
		$return['name'] = $child_unit['C_NAME'];
		$return['code'] = $child_unit['C_CODE'];
		$return['type'] = $child_unit['C_TYPE_GROUP'];
		$return['order'] = $child_unit['C_ORDER'];
		$return['status'] = $child_unit['C_STATUS'];
		$return['address'] = $child_unit['C_ADDRESS'];
		$return['text'] = "<span class='js-tree-text'>".$child_unit['C_NAME']."</span>";
		if($child_unit['C_STATUS'] == 'HOAT_DONG'){
			$return['icon'] = $_this->incon_by_root($node_lv,false,$child_unit['C_TYPE_GROUP']);
		}else{
			$return['icon'] = $_this->incon_by_root(0,false,$child_unit['C_TYPE_GROUP']);
		}
		
		if($count > 0){
			$opened = true;
			$return['children'] = true;
		}else{
			$return['children']= false;
		}
		return $return;
	}	
	
	public function incon_by_root($node_lv,$open,$type){
		$icon = '';
		if($open){
			//$status = 'open';
			$status = 'close';
		}else{
			$status = 'close';
		}
		if($node_lv == 1){
			$icon = 'fa fa-home mfa-2x folder-lv'.$node_lv;
		}else if($node_lv == 2){
			$icon = 'fa fa-university fa-hight folder-lv'.$node_lv;
		}else{
			if($type == 'PHUONG_XA'){
				$icon = 'fa fa-university folder-lv'.$node_lv;
			}else{
				$icon = 'fa fa-square  fa-hight folder-lv'.$node_lv;
			}
		}
		return $icon;
	}
	
	public function get_header($node_lv){
		
	}
	
	public function set_header($node_lv,$name){
		
	}
}
