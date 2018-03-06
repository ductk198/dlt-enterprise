<?php

namespace Modules\System\Users\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\Efy\Library;
use Modules\System\Users\Models\UserModel;
use Modules\System\Users\Models\UnitModel;
use Modules\System\Users\Helpers\TreeHelper;
use Modules\System\Users\Helpers\UserHelper;
use Illuminate\Http\Request;
use DB;

/**
 * Lớp xử lý quản trị, phân quyền người sử dụng
 *
 * @author Duclt
 */
class TreeController extends Controller {

    /**
     * Lay case thu muc doi tuong cap 1
     * @param Request $request
     * @return jSon
     */
    public function getunit(Request $request) {
        $unit_id = $root = '';
        if (isset($request->id) && $request->id !== '' && $request->id !== '#') {
            $unit_id = $request->id;
        } else {
            $Units = UnitModel::where("FK_UNIT", '=', NULL)->orderBy('C_ORDER', 'asc')->get(['PK_UNIT'])->toArray();
            if ($_SESSION["role"] == 'ADMIN_SYSTEM') {
                $unit_id = $Units[0]['PK_UNIT'];
            } else {
                $unit_id = $_SESSION["id_unit"];
            }
        }
        // lay root cua don vi
        $root = UserHelper::get_root_by_id($unit_id);
        // du lieu tra ve dang json
        $units = TreeHelper::zend_tree_unit($unit_id, $root[0]->level_root);
        return \Response::JSON($units);
    }

    /**
     * Zend list user va unit
     * @param Request $request
     * @return jSon
     */
    public function zendlist(Request $request) {
        $objTreeHelper = new TreeHelper();
        $users = array();
        $idunit = $request->idunit;
        $node_lv = $request->node_lv;
        $text = $request->text;
        $type = 'user';
        if ($idunit) {
            // lay don vi con
            $Units = UnitModel::where("FK_UNIT", '=', $idunit)->orderBy('C_ORDER', 'asc')->get(['PK_UNIT', 'C_NAME', 'C_ADDRESS', 'C_STATUS', 'C_CODE'])->toArray();
            if ($Units) {
                $type = 'unit';
                $data = $Units;
            } else {
                $data = DB::table('USER_STAFF')
                                ->join('USER_POSITION', 'USER_STAFF.FK_POSITION', '=', 'USER_POSITION.PK_POSITION')
                                ->where("FK_UNIT", '=', $idunit)
                                ->select(['PK_STAFF', 'USER_STAFF.C_NAME', 'C_ROLE', 'C_USERNAME', 'USER_STAFF.C_ORDER', 'C_ADDRESS', 'USER_STAFF.C_STATUS', 'USER_POSITION.C_CODE as C_POSITION'])
                                ->orderBy('USER_STAFF.C_ORDER', 'asc')
                                ->get()->toArray();
            }
        }
        $return['type'] = $type;
        $return['data'] = $data;
        return \Response::JSON($return);
    }

}
