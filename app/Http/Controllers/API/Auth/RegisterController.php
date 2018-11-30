<?php

namespace App\Http\Controllers\API\Auth;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    /**
     * サインアップ
     */
    public function register()
    {
        $data = request(['email', 'password', 'password_confirmation']);

        $validator = Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:12|confirmed',
        ]);
        
        if ($validator->passes()) {
            User::create([
                'email' => $data['email'], 
                'password' => bcrypt($data['password'])
            ]);
            if (! $token = auth("api")->attempt($data)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return $this->respondWithToken($token);
        } else {
            return response()->json($validator->errors(), 400);
        }
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
