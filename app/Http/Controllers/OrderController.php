<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Car;

class OrderController extends Controller
{
    public function index()
    {
        $data = Order::all();
        return response()->json([
            "message" => "Load data success",
            "data" => $data
        ], 200);
    }

    public function make_order(Request $request)
    {
        $message = [
            'required' => ':attribute must be filled',
            'integer' => ':attribute must contain an integer',
        ];
        $validate = Validator::make($request->all(), [
            'id_costumer' => 'required|integer',
            'id_car' => 'required|integer',
            'pickup_time' => 'required',
            'rental_time' => 'required|integer',
        ], $message);

        if ($validate->fails()) {
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ]);
        }
        $validator = $validate->validate();

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (Order::select('order_code')->where('order_code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        $data = Order::create([
            "order_code" => $code,
            "id_costumer" => $request->id_costumer,
            "id_car" => $request->id_car,
            "pickup_time" => $request->pickup_time,
            "rental_time" => $request->rental_time,
            "status" => "ordered",
        ]);

        if ($data) {
            return response([
                'status' => 201,
                'message' => "data uploaded successfully",
                'data' => $data
            ]);
        }else {
            return response([
                'status' => 400,
                'message' => "data upload failed",
                'data' => null
            ]);
        }
    }

    public function start_rental(Request $request, $id)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (Order::select('return_code')->where('return_code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        $current_date_time = date('Y-m-d H:i:s');
        $update = Order::where('id', $id)->update([
            "start_rental" => $current_date_time,
            "return_code" => $code,
            "status" => "has taken",
        ]);
        if ($update) {
            return response([
                'status' => 200,
                'message' => 'data berhasil diubah',
            ], 200);
        }else{
            return response([
                'status' => 400,
                'message' => 'data gagal diubah',
            ], 400);
        }
    }

    public function end_rental(Request $request, $id)
    {
        $data = Order::find($id);
        $return_code = $data->return_code;
        $start_rental = $data->start_rental;
        $id_car = $data->id_car;
        $input_code = $request->return_code;
        $cardata = Car::find($id_car);
        
        if($return_code === $input_code){
            $current_date_time = Carbon::now()->format('Y-m-d H:s:i');
            $t1 = Carbon::parse($start_rental);
            $t2 = Carbon::parse($current_date_time);
            $diff = $t1->diffInHours($t2);
            $rental_time = 24*$data->rental_time;

            if ($diff > $rental_time){
                $price = round($cardata->price/24*$diff);
                if (substr($price,-3)>499){
                    $total=round($price,-3);
                } else {
                    $total=round($price,-3)+1000;
                } 
                $update = Order::where('id', $id)->update([
                    "end_rental" => $t2,
                    "price" => $total,
                    "status" => "finished",
                ]);
            }else{
                $total = $cardata->price*$data->rental_time;
                $update = Order::where('id', $id)->update([
                    "end_rental" => $t2,
                    "price" => $total,
                    "status" => "finished",
                ]);
            }
            

            if ($update) {
                return response([
                    'status' => 200,
                    'message' => 'rental has been completed',
                    'price' => $total,
                ], 200);
            }else{
                return response([
                    'status' => 400,
                    'message' => 'data gagal diubah',
                ], 400);
            }
        }else{
            return response([
                'status' => 400,
                'message' => 'return code is false',
             ], 400);
        }
    }
}
