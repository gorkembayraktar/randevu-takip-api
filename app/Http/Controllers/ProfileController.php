<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function edit(Request $request)
    {
        $user = $this->auth($request);


        
    }

    //
}
