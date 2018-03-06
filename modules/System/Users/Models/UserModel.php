<?php 

namespace Modules\System\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use DB;
use Uuid;

class UserModel extends Model
{
    protected $table = 'USER_STAFF';
    protected $primaryKey = 'PK_STAFF';
    public $incrementing = false;
    public $timestamps = false;
	
	public function _getall($currentPage,$perPage,$search=''){
		 //
    	$query = $this->query();
    	Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if($search){
            $query->where('code', 'LIKE','%' . $search .'%')->orWhere('name', 'LIKE','%' . $search .'%');
        }
        return $query->paginate($perPage); 
	}

    public function permission_group_staff(){
        return $this->hasMany('Modules\Backend\User\Models\GroupStaffModel', 'user_id', 'id');
    }

    public function _updateUser($arrParameter,$id, $groupPermission, $groupProvince, $list_region){
        DB::beginTransaction();
        try {
            if($id){
                //xoa nhom quyen va nhom dia ban di va insert lai
                DB::table('permission_group_staff')->where('user_id', $id)->delete();
                $this->where('id',$id)->update($arrParameter);
            }else{
                //kiem tra trung ten dang nhap
                if($this->where('username', $arrParameter['username'])->first()) {
                    return array('success' => false,'message' => 'Tên đăng nhập đã tồn tại');
                } else {
                    $id = Uuid::generate();
                    $arrParameter['id'] = $id;
                    $this->insert($arrParameter);
                }
            }

            $arrGroupPermission = explode(',', $groupPermission);
            $arrParamsPermission = array();
            foreach($arrGroupPermission as $value) {
                $arrParamsPermission[] = array(
                    'id'                  => Uuid::generate(), 
                    'group_permission_id' => $value, 
                    'user_id'             => $id,
                    );
            }
            DB::table('permission_group_staff')->insert($arrParamsPermission);
            DB::commit();
            return array('success' => true,'message' => 'Thành công');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false,'message' => (string) $e->getMessage());
        }
    }
}
