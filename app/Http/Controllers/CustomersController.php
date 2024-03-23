<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
class CustomersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function get()
    {   
        $data = Customer::select('id', 'fullname', 'email', 'phone', 'note', 'created_at', 'updated_at')->where('isDeleted', 0)->get();
        return [
            "total" => $data->count(),
            "data" => $data
        ];
    }

    public function create(Request $request){

        $data = Customer::create([
            'fullname' => $request->post('fullname'),
            'email' => $request->post('email'),
            'phone' => $request->post('phone'),
            'note' => $request->post('note')
        ]);

        return $data;
    }

    public function edit(Request $request, int $id){
        $customer = Customer::where('id', $id)->where('isDeleted', 0)->first() or abort(404,"Customer not found");
        $customer->fullname = $request->post('fullname');
        $customer->email = $request->post('email');
        $customer->phone = $request->post('phone');
        $customer->note = $request->post('note');
        $customer->save();

        return $customer;
    }

    public function delete(Request $request, int $id){
        $customer = Customer::where('id', $id)->where('isDeleted', 0)->first() or abort(404,"Customer not found");

        $customer->isDeleted = true;
        $status = $customer->save();

        return [
            "success" => $status,
        ];
    }

    //
}
