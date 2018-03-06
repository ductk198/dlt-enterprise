<?php 

namespace Modules\System\Login\Controllers;

use Modules\System\Login\Requests\Login as Requests;
use Modules\System\Login\Requests\ChangePasswordRequest;
use App\Http\Controllers\Controller;
use Modules\Core\Efy\Library;
use Illuminate\Support\Facades\Auth;
use Modules\System\Users\Models\UserModel;
use Modules\System\Users\Models\UnitModel;
use Illuminate\Support\Facades\Hash;
use DB;

/**
 * Mô tả về class...
 *
 * @author ...
 */
class LoginController extends Controller
{

	/**
     * khởi tạo dữ liệu mẫu, Load các file js, css của đối tượng
     *
     * @return view
     */
	public function index()
	{
		$data['message'] = '';
		$data['class'] = 'form-control';
		return view("Login::index",$data);
	}

	/**
     * Kiem tra ham checklogin
     *
     * @return redirect url
     */
	public function checklogin(Requests $request)
	{
            $username = $request->username;
            $password = $request->password;
            if(Auth::guard('backend')->attempt(['C_USERNAME'=>$username,'password'=>$password])){
                $user = Auth::user();
                // lay thong tin phong ban cua nguoi dung
                $department = UnitModel::find($user->FK_UNIT);
                $user->department = $department->C_NAME; 
                $_SESSION["role"] = $user->C_ROLE;
                $_SESSION["id_unit"] = $department->FK_UNIT;
                // kiem tra quyen nguoi dung
                if($user->C_ROLE == 'ADMIN_SYSTEM' || $user->C_ROLE == 'ADMIN_OWNER'){
                    Auth::guard('backend')->login($user);
                    //Auth::guard('system')->login($user); 
                    return redirect('/system/users');
                //}else if($user->C_ROLE == 'USER'){
                   // Auth::guard('backend')->login($user);    
                  //  return redirect('admin/chart');
               }else{
                	$data['class'] = 'form-control error';
                	$data['message'] = "Bạn không có quyền đăng nhập!";
                	return view("Login::Login.index",$data);
                   return redirect('admin/login');
                }
            }else{
            	$data['class'] = 'form-control error';
            	$data['message'] = "Sai tên đăng nhập hoặc mật khẩu!";
            	return view("Login::index",$data);
                   // return redirect('admin/login');
            }
	}

	/**
     * Lay danh sach quyen cua nguoi dung
     *
     * @return redirect url
     */
	public function get_listpermission($user_id)
	{
		// lay case module, action, button
		$groups = DB::table('system_user')
		        ->join('permission_group_staff', 'permission_group_staff.user_id', '=', 'system_user.id')
		        ->join('permission_group', 'permission_group.id', '=', 'permission_group_staff.group_permission_id')
		        ->select('permission_group.permission_module','permission_group.permission_action','permission_group.permission_button')
		        ->where('system_user.id',$user_id)
		        ->get();
		$listmodule = '';
		$listaction = '';
		$listbutton = '';
		$listpermission = '';
		foreach($groups as $group){
			if($group->permission_button !== ''){
				$listbutton = $this->check_insertlist($listbutton,$group->permission_button);
			}
			if($group->permission_action !== ''){
				$listaction = $this->check_insertlist($listaction,$group->permission_action);
			}
			if($group->permission_module !== ''){
				$listmodule = $this->check_insertlist($listmodule,$group->permission_module);
			}
		}
		$return['listmodule'] = $listmodule;
		$return['listaction'] = $listaction;
		$return['listbutton'] = $listbutton;
		return $return;
	}

	/**
     * Loại bỏ những quyền thừa 
     *
     * @return string
     */
	public function check_insertlist($listsave,$listitem){
		if($listsave == ''){
			return $listitem;
		}else{
			$listupdate = '';
			$listitems = explode(',', $listitem);
			$lists = explode(',', $listsave);
			foreach($listitems as $listitem){
				$check = true;
				foreach($lists as $list){
					if($listitem == $list){
						$check = false;
					}
				}
				if($check){
					$listupdate .= ','.$listitem;
					
				}
			}
			return $listsave.$listupdate;
		}
	}

	/**
     * Lay danh sach nhom quyen cua nguoi dung
     *
     * @return redirect url
     */
	public function get_listprovince($user_id)
	{
		$provinces = DB::table('system_user')
			        ->join('permission_province_staff', 'permission_province_staff.user_id', '=', 'system_user.id')
			        ->select('permission_province_staff.list_region')
			        ->where('system_user.id',$user_id)
			        ->get();
		$listprovince = '';
		foreach($provinces as $province){
			if($province->list_region !== ''){
				$listprovince .= ",".$province->list_region;
			}
		}
		return trim($listprovince,",");
	}

	/**
     * Thoat
     *
     * @return redirect url
     */
	public function logout(Requests $request)
	{
		session_unset();
		Auth::guard('backend')->logout();
        //Auth::guard('user')->logout();
        return redirect('system/login');
	}

	/**
     * doi mat khau
     *
     * @return view
     */
	public function changePassword(Requests $request)
	{
        return view('Login::changePassword');
	}

	/**
     * cap nhat doi mat khau
     *
     * @return view
     */
	public function updateChangePassword(ChangePasswordRequest $request)
	{
		if(Auth::Check()){
			if (Hash::check($request->old_password, Auth::user()->password)) {
				$id = Auth::user()->id;
				$new_password = Hash::make($request->new_password);
				$arrParameter = array(
					'password' => $new_password
					);
				DB::table('USER_STAFF')->where('id',$id)->update($arrParameter);
				$return = array('success' => true,'message' => 'Thành công');
			} else {
				$return = array('success' => false,'message' => 'Sai mật khẩu');
			}
		} else {
			$return = array('success' => false,'message' => 'Lỗi xác thực');
		}
        return \Response::JSON($return);
	}

}
