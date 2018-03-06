<?php
namespace Modules\System\System\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class PerMissionModel extends Model
{
    protected $table = "EFYLIB_PERMISSION_RELATE";
    protected $primaryKey = "PK_PERMISSION_RELATE";
    public $timestamps = false;
    public $incrementing = false;

    public function _delete($listitem){
        $arrListitem = explode(',', $listitem);
        DB::beginTransaction();
        try {
            $this->whereIn('PK_PERMISSION_RELATE', $arrListitem)->delete();
            DB::commit();
            return array('success' => true, 'message' => 'Xóa thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false, 'message' => (string)$e->getMessage());
        }
       }
}