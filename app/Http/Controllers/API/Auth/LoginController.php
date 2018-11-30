<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * ログイン
     */
    public function login() {
        $credentials = request(['email', 'password']);

        if (! $token = auth("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
    
    /**
     * ログアウト
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * トークンの有効確認
     */
    public function ping()
    {
        //return response()->json(['result' => true]);
        return $this->refresh();
    }

    /**
     * トークンの再発行
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    
    /**
     * トークン付きのレスポンス
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth("api")->factory()->getTTL() * 60
        ]);
    }
}