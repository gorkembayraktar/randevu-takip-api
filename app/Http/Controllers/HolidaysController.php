<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;


class HolidaysController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function get()
    {   
        $data = Holiday::orderBy('id', 'desc')->get();
        return [
            "total" => $data->count(),
            "data" => $data
        ];
    }

    public function find(Request $request, $id)
    {   
        $data = Holiday::where('id', $id)->first() or abort(404,"AppointmentHour not found");
        
        return $data;
    }

    public function create(Request $request)
    {   
        $this->validate($request, [
            'title' => 'required',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:startdate',
            'refresh' => 'required'
        ]);

        /** duplicate check */

        $startdate = date('Y-m-d H:i:s', strtotime( $request->post('startdate')));
        $enddate = date('Y-m-d H:i:s', strtotime( $request->post('enddate')));
      
        $has = Holiday::where('startdate', $startdate )
        ->where('enddate',  $enddate )
        ->first();

        if($has){
            return response()->json([
                'message' => 'Bu tarihler aralığında zaten bir kayıt mevcut.',
            ], 400); 
        }
      

        $data = Holiday::create([
            'title' => $request->post('title'),
            'startdate' =>$startdate ,
            'enddate' => $enddate,
            'refresh' => $request->post('refresh')
        ]);

        return $data;
    }

    public function edit(Request $request, $id)
    {   
        $holiday =  Holiday::where('id', $id)->first() or abort(404,"Holiday not found");

        $this->validate($request, [
            'title' => 'required',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:startdate',
            'refresh' => 'required'
        ]);

        /** duplicate check */

        $startdate = date('Y-m-d H:i:s', strtotime( $request->post('startdate')));
        $enddate = date('Y-m-d H:i:s', strtotime( $request->post('enddate')));
      
        $has = Holiday::where('startdate', $startdate )
        ->where('enddate',  $enddate )
        ->whereNot('id', $holiday->id)
        ->first();


        if($has){
            return response()->json([
                'message' => 'Bu tarihler aralığında zaten bir kayıt mevcut.',
            ], 400); 
        }
      
        $holiday->title = $request->post('title');
        $holiday->startdate =  $startdate;
        $holiday->enddate =  $enddate;
        $holiday->refresh = $request->post('refresh');

        $status = $holiday->save();

        return response()->json(['status' => $status, 'data' => $holiday], $status ? 200 : 500);
    }

    
    public function delete(Request $request, $id){
        $holiday =  Holiday::where('id', $id)->first() or abort(404,"Holiday not found");

        $holiday->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
    //
}
