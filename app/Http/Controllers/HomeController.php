<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function login(){
    	if (Auth::guard('admin')->check()) {
           return redirect('admin/home');
        }else{
            return view('backend.Login');
        }
    }
    public function logout(){
        Auth::guard('admin')->logout();
        Auth::guard('user')->logout();
        return redirect('admin/home');
    }
    public function home(){
    	return view('backend.Home');
    }
    public function checklogin(Request $request){
    	$username = $request->username;
    	$password = $request->password;
    	if(Auth::guard('admin')->attempt(['username'=>$username,'password'=>$password])){
            // kiem tra quyen nguoi dung
    		$user = Auth::user();
            if($user->role == 1){
                Auth::guard('user')->login($user);
                Auth::guard('admin')->login($user);         
                return redirect('admin/home');
            }else if($user->role == 2){
                Auth::guard('user')->login($user);         
                return redirect('/');
            }else{
                return redirect('admin/login');
            }
    	}else{
    		return redirect('admin/login');
    	}
    }
}
