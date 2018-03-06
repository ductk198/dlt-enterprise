<?php

namespace Modules\System\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;
use Lang;
use Illuminate\Support\Facades\DB;
use Uuid;

class PositionGroupModel extends Model {

    protected $table = 'USER_POSITION_GROUP';
    protected $primaryKey = 'PK_POSITION_GROUP';
    public $timestamps = false;
    public $incrementing = false;

    public function _getAll($currentPage, $perPage, $search) {
        $query = $this->query()->orderBY('C_ORDER','asc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if ($search) {
            $query->where('C_NAME', 'LIKE', '%' . $search . '%');
        }
        return $query->paginate($perPage);
    }

    function _update($arrParameter, $idPosition) {
        DB::beginTransaction();
        try {
            if ($idPosition !== '' && $idPosition != null) {
                $this->where('PK_POSITION_GROUP', $idPosition)->update($arrParameter);
            } else {
                $arrParameter['PK_POSITION_GROUP'] = Uuid::generate();
                $this->insert($arrParameter);
            }
            DB::commit();
            return array('success' => true, 'message' => Lang::get('Position.succes'));
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }
    public function _getSingle($id, $typeJson = false) {
        if ($typeJson) {
            return $this->where('PK_POSITION_GROUP', $id)->first()->toJson();
        } else {
            return $this->where('PK_POSITION_GROUP', $id)->first()->toArray();
        }
    }
    
     public function _delete($listitem){
        $arrListitem = explode(',', $listitem);
        DB::beginTransaction();
        try {
            $this->whereIn('PK_POSITION_GROUP',$arrListitem)->delete();
            DB::commit();
            return array('success'=> true,'message' =>Lang::get('System::Position.succes'));
        } catch (\Exception $e) {
            DB::rollback();
            return array('success'=> false,'message' => (string) $e->getMessage());
        }       
    }

}
