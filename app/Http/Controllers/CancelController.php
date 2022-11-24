<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancel;
use App\Models\Order;
use Auth;

class CancelController extends Controller
{
    public function cancel(Request $request)
    {
        $id_order = $request->id_order;
        if (Cancel::select('id_order')->where('id_order', $id_order)->exists()) {
            return response([
                'status' => 400,
                'message' => 'data already exists'
            ]);
        }else{
            $id_user = auth()->user()->id;
            $data = Cancel::create([
                "id_order" => $id_order,            
                "canceling_person" => $id_user,            
                "reason" => $request->reason,            
            ]);

            $update = Order::where('id', $id_order)->update([
                "status" => "canceled",
            ]);

            if ($data) {
                return response([
                    'status' => 201,
                    'message' => "cancel successfully",
                    'data' => $data
                ]);
            }else {
                return response([
                    'status' => 400,
                    'message' => "cancel failed",
                    'data' => null
                ]);
            }
        }
        
    }
    public function reason($id)
    {
        $data = Cancel::where('id_order', $id)->first();
        if($data){
            return response([
                    'status' => 201,
                    'data' => $data
                ]);
        }else{
            return response([
                    'status' => 404,
                    'message' => "not found",
                ]);
        }
    }
}
