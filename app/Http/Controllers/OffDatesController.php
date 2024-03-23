<?php

namespace App\Http\Controllers;

use App\Models\OffDate;
use Illuminate\Http\Request;


class OffDatesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function get()
    {   
        $data = OffDate::with('user:id,name,username,email,created_at')->orderBy('id', 'desc')->get();
        return [
            "total" => $data->count(),
            "data" => $data
        ];
    }

    public function find(Request $request, $id)
    {   
        $data = OffDate::where('id', $id)->first() or abort(404,"OffDate not found");
        
        return $data->with('user:id,name,username,email,created_at')->where('id', $data->id)
        ->first();
    }

    public function create(Request $request)
    {   
      
        $this->validate($request, [
            'note' => 'nullable',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:startdate'
        ]);

        /** duplicate check */

        $startdate = date('Y-m-d H:i:s', strtotime( $request->post('startdate')));
        $enddate = date('Y-m-d H:i:s', strtotime( $request->post('enddate')));
      
        $has = OffDate::where('startdate', $startdate )
        ->where('enddate',  $enddate )
        ->first();

     
        if($has){
            return response()->json([
                'message' => 'Bu tarihler aralığında zaten bir kayıt mevcut.',
            ], 400); 
        }
      
      
        $user = $this->auth($request);

        $data = OffDate::create([
            'note' => $request->post('note'),
            'startdate' =>$startdate ,
            'enddate' => $enddate,
            'user_id' => $user->id
        ]);

        return $data->with('user:id,name,username,email,created_at')->where('id', $data->id)
        ->first();
    }

    public function edit(Request $request, $id)
    {   
        $offdate =  OffDate::where('id', $id)->first() or abort(404,"Holiday not found");

        $this->validate($request, [
            'note' => 'nullable',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:startdate'
        ]);

        /** duplicate check */

        $startdate = date('Y-m-d H:i:s', strtotime( $request->post('startdate')));
        $enddate = date('Y-m-d H:i:s', strtotime( $request->post('enddate')));
      
        $has = OffDate::where('startdate', $startdate )
        ->where('enddate',  $enddate )
        ->whereNot('id', $offdate->id)
        ->first();


        if($has){
            return response()->json([
                'message' => 'Bu tarihler aralığında zaten bir kayıt mevcut.',
            ], 400); 
        }
        $user = $this->auth($request);

        $offdate->note = $request->post('note');
        $offdate->startdate =  $startdate;
        $offdate->enddate =  $enddate;
        $offdate->user_id = $user->id;

        $status = $offdate->save();

        return response()->json([
            'status' => $status, 
            'data' => $offdate->with('user:id,name,username,email,created_at')->where('id', $offdate->id)->first()
        ], $status ? 200 : 500);
    }

    
    public function delete(Request $request, $id){
        $OffDate =  OffDate::where('id', $id)->first() or abort(404,"OffDate not found");

        $OffDate->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
    //
}
