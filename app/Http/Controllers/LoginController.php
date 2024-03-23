<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class LoginController extends Controller
{
    /**
     * 
     *
     * @return 
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı adı veya şifre hatalı.',
            ], 401);
        }

        $payload = array(
            'iss' => $_SERVER['HTTP_HOST'],
            'exp' => time()+60 * 60 * 24 , 
            'sub' =>  $user->id,
            'email' =>  $user->email,
         );

        $secretKey = env('JWT_SECRET');

        $token = JWT::encode($payload, $secretKey, 'HS256');


        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    //
}
