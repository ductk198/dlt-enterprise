<?php

namespace Modules\Backend\User\Helpers;

use DB;
use Log;
use Request;

use Illuminate\Support\Facades\Cache;
use Modules\Backend\User\Models\GroupModel;
use Modules\Backend\User\Models\GroupStaffModel;

/**
 * Helper hỗ trợ module user.
 *
 * @author Duclt
 */
class PermissionHelper
{
	
	/**
     * Lấy danh sách các quyền từ một user
     *
     * @param string $user_id : id user
     *
     * @return string string
     */
	public static function get_permission($user_id){
		$permission = '';
		if($user_id){
			$groups = DB::table('permission_group_staff')
            ->join('permission_group', 'permission_group.id', '=', 'permission_group_staff.group_permission_id')
            ->select('permission_group.name')
            ->where('permission_group_staff.user_id',$user_id)
            ->get()->toArray();
            foreach($groups as $group){
            	$permission  .= ", ".$group->name;
            }
		}
		return trim($permission,",");
	}
}
