<?php

namespace Modules\System\Enterprise\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;
use Lang;
use Illuminate\Support\Facades\DB;
use Uuid;

class EnterpriseModel extends Model {

    protected $table = 'DLT_ENTERPRISE';
    protected $primaryKey = 'PK_DLT_ENTERPRISE';
    public $timestamps = false;
    public $incrementing = false;

    public function _getAll($currentPage, $perPage, $search) {
        $query = $this->query()->orderBY('NGAYNHAP', 'asc');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if ($search) {
            $query->where('C_NAME', 'LIKE', '%' . $search . '%');
        }
        return $query->paginate($perPage);
    }

    function _update($arrParameter, $idEnterprise) {
        DB::beginTransaction();
        try {
            if ($idEnterprise !== '' && $idEnterprise != null) {
                $this->where('PK_DLT_ENTERPRISE', $idEnterprise)->update($arrParameter);
            } else {
                $arrParameter['PK_DLT_ENTERPRISE'] = Uuid::generate();
                $this->insert($arrParameter);
            }
            DB::commit();
            return array('success' => true, 'message' => Lang::get('Enterprise.succes'));
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }

    public function _getSingle($id, $typeJson = false) {
        if ($typeJson) {
            return $this->where('PK_DLT_ENTERPRISE', $id)->first()->toJson();
        } else {
            return $this->where('PK_DLT_ENTERPRISE', $id)->first()->toArray();
        }
    }

    public function _delete($listitem) {
        $arrListitem = explode(',', $listitem);
        DB::beginTransaction();
        try {
            $this->whereIn('PK_DLT_ENTERPRISE', $arrListitem)->delete();
            DB::commit();
            return array('success' => true, 'message' => Lang::get('System::Enterprise.succes'));
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }

}
