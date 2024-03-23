<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;


class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function update(Request $request)
    {
        $user = $this->auth($request);
        $this->validate($request, [
            'username' => 'required',
            'fullname' => 'required',
            'email' => 'required'
        ]);

        $username = $request->post('username');
        $fullname = $request->post('fullname');
        $email = $request->post('email');

        $user->username = $username;
        $user->name = $fullname;
        $user->email = $email;

        $status = $user->save();

        return response()->json(['status' => $status], $status ? 200 : 500);
    }

    public function password_reset(Request $request){
        $user = $this->auth($request);
        $this->validate($request, [
            'password' => 'required|min:5',
            'new-password' => 'required|min:5',
            'new-password2' => 'required|min:5|same:new-password'
        ]);

        $password = $request->post('password');

        if ( !Hash::check($password, $user->password) ) {
            return response()->json([
                'success' => false,
                'message' => 'Şifrenizi doğrulayınız',
            ], 400);
        }

        $new_password = Hash::make($request->post('new-password'));
       
        $user->password = $new_password;
        $status = $user->save();

        return response()->json([
            'success' => $status,
        ], $status ? 200 : 500);
    }

    //
}
