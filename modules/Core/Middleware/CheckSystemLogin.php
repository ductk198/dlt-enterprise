<?php

namespace Modules\Core\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\System\Helpers\MenuHelper;

class CheckSystemLogin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (isset($_SESSION["role"]) && ($_SESSION["role"] == 'ADMIN_OWNER' || $_SESSION["role"] == 'ADMIN_SYSTEM')) {
            return $next($request);
        } else {
            return redirect('system/login');
        }
    }

}
