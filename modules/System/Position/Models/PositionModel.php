<?php

namespace Modules\System\Position\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;
use DB;
use Uuid;
use Lang;

class PositionModel extends Model {

    protected $table = 'USER_POSITION';
    protected $primaryKey = 'PK_POSITION';
    public $timestamps = false;
    public $incrementing = false;

    public function _getAll($currentPage, $perPage, $search) {
//        $query = $this->query()->orderBy('FK_POSITION_GROUP','DESC')->orderBy('C_ORDER','ASC');
        $table = DB::table('USER_POSITION');
        $table->join('USER_POSITION_GROUP', 'USER_POSITION.FK_POSITION_GROUP', '=', 'USER_POSITION_GROUP.PK_POSITION_GROUP')->select('USER_POSITION.*', 'USER_POSITION_GROUP.C_NAME as TEN_NHOM_QUYEN');
        $table->orderBy('FK_POSITION_GROUP','DESC')->orderBy('C_ORDER','ASC');
        if ($search) {
            $table->where('USER_POSITION.C_NAME', 'LIKE', '%' . $search . '%');
        }
        
        $data = $table->get()->toArray();
//        return $table->paginate($perPage);
        return $data;
    }

    public function _update($arrParams, $idPosition) {

        DB::beginTransaction();
        try {
            if ($idPosition !== '' && $idPosition != null) {
                $this->where('PK_POSITION', $idPosition)->update($arrParams);
            } else {
                $arrParams['PK_POSITION'] = Uuid::generate();
                $this->insert($arrParams);
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
            return $this->where('PK_POSITION', $id)->first()->toJson();
        } else {
            return $this->where('PK_POSITION', $id)->first()->toArray();
        }
    }

    public function _delete($listitem) {
        $arrListitem = explode(',', $listitem);
        DB::beginTransaction();
        try {
            $this->whereIn('PK_POSITION', $arrListitem)->delete();
            DB::commit();
            return array('success' => true, 'message' => Lang::get('System::Position.succes'));
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }

}
