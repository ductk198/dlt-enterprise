<?php 

namespace Modules\Backend\Main\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class ReportModel extends Model
{
    protected $table = 'KNTC_INFO_REPORT';
    protected $primaryKey = "PK_REPORT";
    public $timestamps = false;
    public $incrementing = false;
	
	public function getall(){
		 //
    }
    
    public function _delete($listitem){
        $arrListitem = explode(',', $listitem);
        DB::beginTransaction();
        try {
            $this->whereIn('PK_REPORT', $arrListitem)->delete();
            DB::commit();
            return array('success' => true, 'message' => 'Xóa thành công');
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false, 'message' => (string)$e->getMessage());
        }
    }
}