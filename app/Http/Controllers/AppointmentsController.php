<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

use App\Enum\AppointmentEnum;


class AppointmentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function get()
    {   
        $data = Appointment::with('user:id,name,username,email,created_at')->with('customer')
        ->orderBy('id', 'desc')->get();
        return [
            "total" => $data->count(),
            "data" => $data
        ];
    }

    public function find(Request $request, $id)
    {   
   
        $data = Appointment::where('id', $id)->where('isDeleted', 0)->first() or abort(404,"Appointment not found");
        
        return $data;

   
    }

    public function create(Request $request)
    {  
        /*
        create_user_id', 
        'customer_id', 
        'fullname',
        'phone',
        'email',
        'note',
        'date',
        */
      
        $this->validate($request, [
            'customer_id' => 'nullable',
            'ah_id' => 'nullable',
            'note' => 'nullable',
            'date' => 'required|date',
            'fullname' => 'required',
            'phone' => 'nullable',
            'email' => 'nullable',
        ]);

        // ah_id check


        $user = $this->auth($request);

        $customer_id = $request->post('customer_id') ?? 0;
        if($customer_id < 0) $customer_id = 0;

        $create_user_id = $user->id;
        $date = date('Y-m-d H:i:s', strtotime( $request->post('date')));

        do{
            $token = rand(100000000, 9999999999);
        }while(Appointment::where('token', $token)->exists());

        $data = Appointment::create([
            'note' => $request->post('note'),
            'date' =>$date ,
            'ah_id' =>  $request->post('ah_id'),
            'fullname' => $request->post('fullname'),
            'phone' => $request->post('phone'),
            'email' => $request->post('email'),
            'customer_id' => $customer_id,
            'create_user_id' => $create_user_id,
            'token' => $token,
        ]);

        return $data;

    }

    public function edit(Request $request, $id)
    {   
        $this->validate($request, [
            'ah_id' => 'nullable',
            'note' => 'nullable',
            'date' => 'required|date',
            'fullname' => 'required',
            'phone' => 'nullable',
            'email' => 'nullable',
            'status' => 'required'
        ]);


        $values = AppointmentEnum::values();
        if(!in_array($request->post('status'), $values)){
            return response()->json([
                'success' => false,
                'message' => 'status kodu sınırların dışında'
            ], 422);
        }


        $appointment = Appointment::where('id', $id)->first() or abort(404,"Appointment not found");

        // ah_id check
        $user = $this->auth($request);

        $customer_id = $request->post('customer_id') ?? 0;
        if($customer_id < 0) $customer_id = 0;

        $create_user_id = $user->id;
        $date = date('Y-m-d H:i:s', strtotime( $request->post('date')));

        $appointment->note =  $request->post('note');
        $appointment->date = $date;
        $appointment->ah_id = $request->post('ah_id');
        $appointment->fullname = $request->post('fullname');
        $appointment->phone = $request->post('phone');
        $appointment->email = $request->post('email');
        $appointment->status = $request->post('status');

        $appointment->save();
     
        return $appointment;
    }

    
    public function delete(Request $request, $id){
     
        $appointment =  Appointment::where('id', $id)->where('isDeleted', 0)->first() or abort(404,"Appointment not found");

        $appointment->isDeleted = true;
        $appointment->save();

        return response()->json([
            'success' => true,
        ], 200);
       
    }
    //
}
