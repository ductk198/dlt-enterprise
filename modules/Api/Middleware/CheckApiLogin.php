<?php

namespace Modules\Api\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\System\Helpers\MenuHelper;

class CheckApiLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('backend')->check()) {
            $check_next = false;
            $url = $request->path();
            $module_id = MenuHelper::get_module_by_request($url);
            $listmodule = session('permision_module')['listmodule'];

            $modules = explode(',', $listmodule);
            foreach($modules as $module){
                if($module == $module_id){
                    $check_next = true;
                }
            }
            if($check_next){
                return $next($request);
            }else{
                return redirect('admin/login');
            }   
        }else{
            return redirect('admin/login');
        }  
    }
}
