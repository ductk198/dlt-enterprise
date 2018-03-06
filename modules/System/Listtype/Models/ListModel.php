<?php

namespace Modules\System\Listtype\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;
use DB;
use Illuminate\Pagination\Paginator;
use Modules\System\Listtype\Models\ListtypeModel;
use Cache;

class ListModel extends Model {

    protected $table = 'DLT_LIST';
    protected $primaryKey = 'PK_LIST';
    public $timestamps = false;

    public function _getAll($listtype, $currentPage, $perPage, $search, $Unit) {
        $listmodel = ListtypeModel::find($listtype)->ListModel()->orderBy('C_ORDER');
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        if ($Unit) {
            $listmodel->where('C_OWNER_CODE_LIST', 'LIKE', '%' . $Unit . '%');
        }
        if ($search) {
            $listmodel->where('C_CODE', 'LIKE', '%' . $search . '%')
                    ->orWhere('C_NAME', 'LIKE', '%' . $search . '%');
        } else {
            
        }
        return $listmodel->paginate($perPage);
    }

    public function _getAllbyCode($code, $typeJson = false, $arrcolumn) {

        $ListtypeModel = new ListtypeModel();
        $result = $ListtypeModel->where('C_CODE', $code)->get()->toArray();
        $listtype = $result[0]['PK_LISTTYPE'];
        $listmodel = ListtypeModel::find($listtype)->ListModel();
        if ($typeJson) {
            $result = $listmodel->select($arrcolumn)->get()->toJson();
        } else {
            $result = $listmodel->select($arrcolumn)->get()->toArray();
        }
        return $result;
    }

    public function _getAllbyCodeAStatus($code, $typeJson = false, $arrcolumn) {
        $data = array();
        if (!Cache::has($code)) {
            $ListtypeModel = new ListtypeModel();
            $result = $ListtypeModel->where('C_CODE', $code)->get()->toArray();
            if ($result) {
                $listtype = $result[0]['PK_LISTTYPE'];
                $listmodel = ListtypeModel::find($listtype)->ListModel();

                $result = $listmodel->select($arrcolumn)->Where('C_STATUS', 'HOAT_DONG')->get();

                // $value = Cache::get('DM_DON_VI_TRIEN_KHAI');
                // var_dump($value);exit;
                $count = sizeof($result);
                for ($i = 0; $i < $count; $i++) {
                    $temp = array();
                    $temp['C_CODE'] = $result[$i]['C_CODE'];
                    $temp['C_NAME'] = $result[$i]['C_NAME'];
                    if ($result[$i]['xml_data'] != '') {
                        $objXml = simplexml_load_string($result[$i]['C_XML_DATA']);
                        $datalist = (array) $objXml->data_list;
                        foreach ($datalist as $key => $value) {
                            $temp[(string) $key] = (string) $value;
                        }
                    }
                    $data[] = $temp;
                }
                //luu cache
                Cache::forever($code, $data);
                if ($typeJson) {
                    $data = json_encode($data);
                }
            }
        } else {
            $data = Cache::get($code);
        }

        return $data;
    }

    public function _getSinglebyCode($code, $value, $typeJson = false) {

        $ListtypeModel = new ListtypeModel();
        $result = $ListtypeModel->where('C_CODE', $code)->get()->toArray();
        $listtype = $result[0]['PK_LIST'];
        $listmodel = ListtypeModel::find($listtype)->ListModel();
        if ($typeJson) {
            $result = $listmodel->where('C_CODE', $value)->get()->toJson();
        } else {
            $result = $listmodel->where('C_CODE', $value)->get()->toArray();
        }
        return $result;
    }

    public function _update($arrParameter, $listid) {
        DB::beginTransaction();
        try {
            if ($listid) {
                $this->where('PK_LIST', $listid)->update($arrParameter);
            } else {
                $arrCheckDuplicate = $this->where(['FK_LISTTYPE' => $arrParameter['FK_LISTTYPE'], 'C_CODE' => $arrParameter['C_CODE']])->get()->toArray();
                if ($arrCheckDuplicate) {
                    DB::commit();
                    return array('warning' => true, 'message' => Lang::get('System::Listtype.duplicate'));
                } else {
                    $this->C_CODE = $arrParameter['C_CODE'];
                    $this->FK_LISTTYPE = $arrParameter['FK_LISTTYPE'];
                    $this->C_NAME = $arrParameter['C_NAME'];
                    $this->C_ORDER = $arrParameter['C_ORDER'];
                    $this->C_XML_DATA = $arrParameter['C_XML_DATA'];
                    $this->C_STATUS = $arrParameter['C_STATUS'];
                    $this->C_OWNER_CODE_LIST = $arrParameter['C_OWNER_CODE_LIST'];
                    $this->save($arrParameter);
                }
            }
            DB::commit();
            return array('success' => true, 'message' => Lang::get('System::Listtype.succes'));
            // all good
        } catch (\Exception $e) {
            //DB::rollback();
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }

    public function _getSingle($id, $typeJson = false) {
        if ($typeJson) {
            return $this->where('PK_LIST', $id)->first()->toJson();
        } else {
            return $this->where('PK_LIST', $id)->first()->toArray();
        }
    }

    public function _delete($listitem) {
        $arrListitem = explode(',', $listitem);
        DB::beginTransaction();
        try {
            $this->whereIn('PK_LIST', $arrListitem)->delete();
            DB::commit();
            return array('success' => true, 'message' => Lang::get('System::Listtype.succes'));
        } catch (\Exception $e) {
            DB::rollback();
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }

    public function ListtypeModel() {
        return $this->belongsTo('Modules\System\Models\ListtypeModel', 'FK_LISTTYPE');
    }

}
