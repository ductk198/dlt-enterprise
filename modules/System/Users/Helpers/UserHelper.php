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
class UserHelper
{
	public $child_units = array();
	/**
     * Tạo cây thư mục phòng ban, người sử dụng
     *
     * @param string $module : module của menu
     * @param string $menu : menu cha
     * @param boolean $active : là true là active trên form
     * @param string $parrenturl : url parent menu
     *
     * @return string html
     */
	public static function create_tree($unit_user,$root = false,$Position, $user_name,$checkdepartment) {
            //dd($unit_user);
		$return['id'] = $unit_user['PK_UNIT'];
		$return['text'] = $unit_user['C_NAME'];
		$return['a_attr'] = array('type'=>'department');
		if($root){
			if(!$checkdepartment){
				$return['icon'] = 'fa fa-home fa-2x';
			}
            $opened = true;
        }else{
            $opened = false;
        }   
		$return['data'] = array();
		$return['state']  = array(
			"opened" => $opened
		);
		
		$UserModel = new UserModel();
		// lay nguoi su dung trong phong ban
		$users = $UserModel->where("FK_UNIT", $unit_user['PK_UNIT']);
		if($Position <> '') {
			$users = $users->where('FK_POSITION',$Position);
        }
		if(!empty($user_name)) {
			$users = $users->where('C_USERNAME', 'like', '% ' . $user_name . '%');
		}
		$users = $users->orderBy('C_ORDER', 'asc')->get()->toArray();
		if(count($users) > 0) {
                    $i=0;
                    $arrUsers = array();
                    foreach($users as $user){
                        if($user['C_ROLE'] == 'ADMIN_SYSTEM'){
                            $name = "<span style='font-weight: bold;'>".$user['C_NAME']."</span>";
                            $arrUsers[$i]['icon'] = 'fa fa-users fa-hight';
                        }else if($user['C_ROLE'] == 'ADMIN_SYSTEM'){
                            $name = "<span style='color: blue'>".$user['C_NAME']."</span>";
                            $arrUsers[$i]['icon'] = 'fa fa-users fa-hight';
                        }else{
                            $name = "<span style='color: black'>".$user['C_NAME']."</span>";
                            $arrUsers[$i]['icon'] = 'fa fa-user fa-hight';
                        }
                        $arrUsers[$i]['id'] = $user['PK_STAFF'];
                        // lay quyen lien quan
                        $arrUsers[$i]['text'] = $name;
                        
                        $arrUsers[$i]['a_attr'] = array('type'=>'user');
                        $i++;
                    }
                    $return['children'] = $arrUsers;
		}
		// kiem tra xem phong ban co cap con hay khong
		$childrens = UnitModel::where("FK_UNIT", $unit_user['PK_UNIT'])->orderBy('C_ORDER', 'asc')->get()->toArray();
		if(count($childrens) > 0) {
			$j=0;
			foreach($childrens as $children) {
				$return['children'][$j] = UserHelper::create_tree($children,false,$Position, $user_name,$checkdepartment);
				$j++;
			}
		}
		return $return;
	}	

	/**
     * Lấy cây thư mục người dùng và phong ban
     *
     * @param
     *
     * @return array()
     */
	public static function get_tree_user_unit($department,$Position, $user_name) {
		// lay cac don vi cap 1
		if($department !== ''){
			$units = UnitModel::where("PK_UNIT", $department)->orderBy('C_ORDER', 'asc')->get()->toArray();
			$checkdepartment = true;
		}else{
			$checkdepartment = false;
			$units = UnitModel::where("FK_UNIT", NULL)->orderBy('C_ORDER', 'asc')->get()->toArray();
		}
		
		$return = array();
		$i=0;
		foreach($units as $unit){
			if($unit){
				$return[$i] = UserHelper::create_tree($unit,true,$Position, $user_name,$checkdepartment);
				$i++;
			}
		}
		return $return;
	}

	/**
     * Lấy cây thư mục người dùng và phong ban theo dạng checkbox
     *
     * @param
     *
     * @return array()
     */
	public static function get_tree_user_unit_checkbox($arr_sfaff_group) {
		// lay cac don vi cap 1
		$units = UnitModel::where("FK_UNIT", NULL)->orderBy('C_ORDER', 'asc')->get()->toArray();
		$return = array();
		$i=0;
		foreach($units as $unit){
			if($unit){
				$return[$i] = UserHelper::create_tree_checkbox($arr_sfaff_group,$unit,true);
				$i++;
			}
		}
		return $return;
	}

	/**
     * Tạo cây thư mục phòng ban, người sử dụng
     *
     * @param string $module : module của menu
     * @param string $menu : menu cha
     * @param boolean $active : là true là active trên form
     * @param string $parrenturl : url parent menu
     *
     * @return string html
     */
	public static function create_tree_checkbox($arr_sfaff_groups,$unit_user,$root = false) {
		$return['id'] = $unit_user['id'];
		$return['text'] = $unit_user['name'];
		// lay nguoi su dung trong phong ban
		$users = UserModel::where("unit_id", $unit_user['id'])->orderBy('order', 'asc')->get()->toArray();
		if(count($users) > 0) {
			$i=0;
			$arrUsers = array();
			foreach($users as $user){
				$checkslecd = false;
				$arrUsers[$i]['id'] = $user['id'];
				$arrUsers[$i]['text'] = $user['name'];
				if($arr_sfaff_groups){
					foreach($arr_sfaff_groups as $arr_sfaff_group){
						if($arr_sfaff_group['user_id'] == $user['id']){
							$checkslecd = true;
						}
					}
				}
				if($checkslecd){
					$arrUsers[$i]['state'] = array('selected'=>true);
				}
				$i++;
			}
			$return['children'] = $arrUsers;
		}
		// kiem tra xem phong ban co cap con hay khong
		$childrens = UnitModel::where("parent", $unit_user['id'])->orderBy('hierarchy', 'asc')->get()->toArray();
		if(count($childrens) > 0) {
			$j=0;
			foreach($childrens as $children) {
				$return['children'][$j] = UserHelper::create_tree_checkbox($arr_sfaff_groups,$children);
				$j++;
			}
		}
		return $return;
	}

	/**
     * Tạo nhom table checkbox
     *
     * @param string $module : module của menu
     * @param string $menu : menu cha
     * @param boolean $active : là true là active trên form
     * @param string $parrenturl : url parent menu
     *
     * @return string html
     */
	public static function create_html_checkbox($groups) {
		$strHTML = '';
        $strHTML = $strHTML . '<table id="' . $sformFielName . '" class="griddata" width="100%" cellspacing="0" cellpadding="0">';
        $strHTML = $strHTML . '<colgroup><col width="5%"><col width="28%"><col width="5%"><col width="28%"><col width="5%"><col width="29%"></colgroup>';
        $strHTML = $strHTML . '<tr class="header">';
        $strHTML = $strHTML . "<td><input type='checkbox' name='checkall_process_per' onclick='checkallprovince(this)' /></td>";
        $strHTML = $strHTML . '<td colspan="5">Danh sách nhóm địa bàn quản lý</td>';
        $strHTML = $strHTML . '</tr>';
	}
	
	/**
	 * Lay cac don vi con cua minh
	 *
	 * @return array
	 */
	public static function get_tree_user_unit_by_id($unit_id,$Position, $user_name,$root) {
		$return = array();
		// lay cac don vi cap 1
		if($unit_id!== ''){
			$units = UnitModel::where("PK_UNIT", $unit_id)->get(['PK_UNIT','C_NAME'])->toArray();
		}else{
			$root = $root+1;
			$units = UnitModel::where("FK_UNIT", NULL)->get(['PK_UNIT','C_NAME'])->toArray();
			$unit_id = $units[0]['PK_UNIT'];	
		}
		if($root == 1){
			$return['icon'] = 'glyphicon glyphicon-folder-open folder-lv1';
		}elseif($root == 2){
			$return['icon'] = 'glyphicon glyphicon-folder-open folder-lv2';
		}elseif($root == 3){
			$return['icon'] = 'glyphicon glyphicon-folder-open folder-lv3';
		}elseif($root == 4){
			$return['icon'] = 'glyphicon glyphicon-folder-open folder-lv4';
		}elseif($root == 5){
			$return['icon'] = 'glyphicon glyphicon-folder-open folder-lv5';
		}
		// lay tat ca don vi con cua don vi day
		$child_units = UnitModel::where("FK_UNIT", $unit_id)->orderBy('C_ORDER', 'asc')->get(['PK_UNIT','C_NAME'])->toArray();
		$return['id'] = $units[0]['PK_UNIT'];
		$return['text'] = "<span class='js-tree-text'>".$units[0]['C_NAME']."</span>";
		$return['a_attr'] = array('type'=>'department');
		$return['state']  = array(
				"opened" => true
		);
		$i=0;
		$root = $root+1;
		if($child_units){
			// lay phong ban
			foreach($child_units as $child_unit){
				if($child_unit){
					$return['children'][$i] = UserHelper::create_tree_by_unit($child_unit,$Position, $user_name, $root);
					$i++;
				}
			}
		}
		
		return $return;
	}
	
	public static function create_tree_by_unit($child_unit,$Position, $user_name, $root) {
		// kiem tra don vi co phong ban con khong
		$count = UnitModel::where("FK_UNIT", $child_unit['PK_UNIT'])->count();
		$return['id'] = $child_unit['PK_UNIT'];
		$return['text'] = "<span class='js-tree-text'>".$child_unit['C_NAME']."</span>";
		if($root == 1){
			$return['icon'] = 'glyphicon glyphicon-folder-close folder-lv1';
		}elseif($root == 2){
			$return['icon'] = 'glyphicon glyphicon-folder-close folder-lv2';
		}elseif($root == 3){
			$return['icon'] = 'glyphicon glyphicon-folder-close folder-lv3';
		}elseif($root == 4){
			$return['icon'] = 'glyphicon glyphicon-folder-close folder-lv4';
		}elseif($root == 5){
			$return['icon'] = 'glyphicon glyphicon-folder-close folder-lv5';
		}
		if($count > 0){
			$opened = true;
			$return['children'] = true;
		}else{
			$return['children']= false;
		}
		return $return;
	}	
	
	public static function get_user_by_child_unit($parent_unit,$search) {
		$query  = ' WITH ret AS( ';
		$query .= ' SELECT  PK_UNIT ';
		$query .= ' FROM  T_USER_UNIT ';
		$query .= " WHERE   PK_UNIT = '".$parent_unit."' ";
		$query .= ' UNION ALL ';
		$query .= ' SELECT  t.PK_UNIT ';
		$query .= ' FROM    T_USER_UNIT t INNER JOIN ';
		$query .= ' ret r ON t.FK_UNIT = r.PK_UNIT ';
		$query .= ' ) ';
		$query .= ' SELECT  PK_STAFF,B.C_NAME,B.C_STATUS,B.C_ROLE,C_USERNAME,B.C_ORDER,C.C_CODE as C_POSITION FROM ret A inner join T_USER_STAFF B on A.PK_UNIT = B.FK_UNIT ';
		$query .= ' inner join T_USER_POSITION C on B.FK_POSITION = C.PK_POSITION ';
		$query .= " WHERE   PK_UNIT <> '".$parent_unit."' ";
		if($search !== ''){
			$query .= " AND  (B.C_NAME like '%".$search."%' ";
			$query .= " OR  B.C_USERNAME like '%".$search."%') ";
		}
		return DB::select($query);
	}
	
	public static function get_unit_by_child_unit($parent_unit,$search) {
		$query  = ' WITH ret AS( ';
		$query .= ' SELECT  PK_UNIT ';
		$query .= ' FROM  T_USER_UNIT ';
		$query .= " WHERE   PK_UNIT = '".$parent_unit."' ";
		$query .= ' UNION ALL ';
		$query .= ' SELECT  t.PK_UNIT ';
		$query .= ' FROM    T_USER_UNIT t INNER JOIN ';
		$query .= ' ret r ON t.FK_UNIT = r.PK_UNIT ';
		$query .= ' ) ';
		$query .= ' SELECT  * from ret A inner join T_USER_UNIT B on A.PK_UNIT = B.PK_UNIT ';
		$query .= " WHERE A.PK_UNIT <> '".$parent_unit."' ";
		if($search !== ''){
			$query .= " AND  (B.C_NAME like '%".$search."%' ";
			$query .= " OR  B.C_CODE like '%".$search."%') ";
		}
		return DB::select($query);
	}
	
	public static function get_root_by_id($id_unit) {
		$query  = ' WITH ret AS( ';
		$query .= ' select PK_UNIT, FK_UNIT ';
		$query .= ' from T_USER_UNIT ';
		$query .= " where PK_UNIT = '".$id_unit."' ";
		$query .= ' union all ';
		$query .= ' select C.PK_UNIT, C.FK_UNIT ';
		$query .= ' from T_USER_UNIT c ';
		$query .= ' join ret p on C.PK_UNIT = P.FK_UNIT  ';
		$query .= ' ) ';
		$query .= ' SELECT  count(*) as level_root from ret ';
		return DB::select($query);
	}
}
