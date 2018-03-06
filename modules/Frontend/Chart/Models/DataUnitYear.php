<?php

namespace Modules\Backend\Chart\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;
use DB;

class DataUnitYear extends Model
{
    //
    protected $table = 'data_unit_year';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public function _checkexits($unit_id,$year)
    {
    	return $this->where('unit_id', $unit_id)
    				->where('year', $year)
    				//->whereDate('create_date', date('Y-m-d'))
    				->get()->toArray();
    }
    public function _updatedataunit($unit_id,$unit_name,$year,$total,$dataupdate)
    {
    	//xoa ban ghi cu
    	$this->where('unit_id',$unit_id)
    		 ->where('year', $year)
    		 ->delete();
        // cap nhat ban ghi moi
    	$arrParameter =array(
    		'data_json' => $dataupdate,
    		'unit_id' => $unit_id,
    		'unit_name' => $unit_name,
    		'year' => $year,
            'total' => $total,
            'create_date' => date('Y-m-d H:i:s')
    	);	 
    	$this->insert($arrParameter);	 
    }
}
