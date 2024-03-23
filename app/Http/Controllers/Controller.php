<?php

namespace App\Http\Controllers;


use Laravel\Lumen\Routing\Controller as BaseController;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    public function auth(Request $request){
        $decoded = $request->attributes->get('decoded');

        if (!$decoded || !isset($decoded->sub)) {
            //return abort(401, 'Yetkisiz erişim.');

            response()->json(['message' => 'You must login', 'status' => false], 401)->send();

            die;
        }

        return User::find($decoded->sub); // 'sub' claim'i kullanıcı ID'sini tutar
    }
}
