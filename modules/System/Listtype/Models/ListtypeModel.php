<?php

namespace Modules\System\Listtype\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;
use DB;
use Illuminate\Pagination\Paginator;
use Modules\System\Listtype\Models\ListModel;

class ListtypeModel extends Model
{
    //
	protected $table = 'DLT_LISTTYPE';
	protected $primaryKey = 'PK_LISTTYPE';
	public $timestamps = false;

	public function _getAll($currentPage,$perPage,$search,$unit)
    {
        $query = $this->query();
    	Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
    	if($unit){
    		$query->where('C_OWNER_CODE_LIST', 'LIKE','%' . $unit.'%');
    	}
        if($search){
            $query->where('C_CODE', 'LIKE','%' . $search .'%')->orWhere('C_NAME', 'LIKE','%' . $search .'%');
        }
        return $query->paginate($perPage);      
    }

    public function _getAllbyStatus($status = 'HOAT_DONG')
    {
        return $this->where('C_STATUS', $status)->select('PK_LISTTYPE', 'C_NAME')->get()->toArray();

    }

    public function _getSingle($id,$typeJson = false){
        if($typeJson){
           return $this->where('PK_LISTTYPE', $id)->first()->toJson();
        }else{
           return $this->where('PK_LISTTYPE', $id)->first()->toArray();
        }
    }

    public function _delete($listitem){
        $arrListitem = explode(',', $listitem);
        DB::beginTransaction();
        try {
            $this->whereIn('PK_LISTTYPE',$arrListitem)->delete();
            DB::commit();
            return array('success'=> true,'message' =>Lang::get('System::Listtype.succes'));
        } catch (\Exception $e) {
            DB::rollback();
            return array('success'=> false,'message' => (string) $e->getMessage());
        }       
    }

    public function _update($arrParameter,$idlistype){
        DB::beginTransaction();
        try {
            if($idlistype !==''){
                $this->where('PK_LISTTYPE',$idlistype)->update($arrParameter); 
            }else{
                $this->insert($arrParameter);
            } 
            DB::commit();
            return array('success' => true,'message' => Lang::get('Listtype.succes'));
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false,'message' => (string) $e->getMessage());
        }
    }

    // cac lien ket
    public function ListModel()
    {
        return $this->hasMany('Modules\System\Listtype\Models\ListModel','FK_LISTTYPE');
    }
}
