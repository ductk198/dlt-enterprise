<?php

namespace Modules\Api\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\DLT\Library;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;
use URL;
use JWTAuth;
use JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 *
 * @author Duclt
 */
class UserController extends Controller {

    /**
     * Check users
     *
     * @return json
     */
    public function gettoken(Request $request) {
        $credentials = ['C_USERNAME' => 'userapiefy', 'password' => '123456'];
        // check if isset device token, return infor. else insert to database
        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function getLogin(Request $request) {
        JWTAuth::parseToken();

        // and you can continue to chain methods
        $user = JWTAuth::parseToken()->authenticate();
        dd($user);
    }

}
