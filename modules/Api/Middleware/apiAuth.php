<?php

namespace Modules\Api\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Helpers\Message;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;

class apiAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $url = $request->url();
        $arrUrl = explode('/', $url);
        $sizeArrUrl = sizeof($arrUrl);
        $function = $arrUrl[$sizeArrUrl - 1];
        if ($function == 'getToken') {
            $authUser = explode(' ', $request->header()['authorization'][0])[1];
            $apiUser = env('API_USER', '');
            $apiPassword = env('API_PASSWORD', '');
            $authUser_check = base64_encode($apiUser . ':' . $apiPassword);
            if ($authUser == $authUser_check) {
                return $next($request);
            } else {
                $objMessage = new Message();
                return $objMessage->returnMessage('error', 401, 'Lỗi xác thực');
            }
        } else {
            JWTAuth::parseToken();
            $token = JWTAuth::getToken();
            $user = JWTAuth::parseToken()->authenticate();
            return $next($request);
        }
    }

}
