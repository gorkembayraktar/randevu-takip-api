<?php

namespace App\Http\Controllers;

use App\Models\AppointmentHour;
use Illuminate\Http\Request;


class AppointmentHourController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function get()
    {   
        $data = AppointmentHour::all();
        return [
            "total" => $data->count(),
            "data" => $data
        ];
    }

    public function find(Request $request, $id)
    {   
        $data = AppointmentHour::where('id', $id)->first() or abort(404,"AppointmentHour not found");
        
        return $data;
    }

    public function create(Request $request)
    {   
        $this->validate($request, [
            'day' => 'required',
            'hour' => 'required',
            'active' => 'required'
        ]);

        /** duplicate check */
        /*
        $has = AppointmentHour::where('day', $request->post('day'))->where('hour', $request->post('hour'))->first();
        if($has){
            return response()->json([
                'message' => 'Bu daha önce oluşturuldu.',
            ], 400); 
        }
        */

        $data = AppointmentHour::create([
            'day' => $request->post('day'),
            'hour' => $request->post('hour'),
            'active' => $request->post('active')
        ]);

        return $data;
    }

    public function active(Request $request, $id){
        $this->validate($request, [
            'active' => 'required'
        ]);

        $appointmentHour = AppointmentHour::where('id', $id)->first() or abort(404,"AppointmentHour not found");
        $appointmentHour->active = $request->post('active');
        $status = $appointmentHour->save();

        return response()->json([
            'success' => $status,
        ], $status ? 200 : 500);
    }
    public function delete(Request $request, $id){
        $appointmentHour =  AppointmentHour::where('id', $id)->first() or abort(404,"AppointmentHour not found");

        $appointmentHour->delete();

        return response()->json([
            'success' => true,
        ], 200);
    }
    //
}
