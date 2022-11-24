<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Costumer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CostumerController extends Controller
{
    public function all_detail_user()
    {
        $data = Costumer::select('users.id','users.name','users.email','costumers.address','costumers.phone_number','costumers.date_of_birth','costumers.driving_license')->from('costumers')->join('users', 'costumers.id_user', '=', 'users.id')->get();
        return $data;
    }
    public function detail_user($id)
    {
        $data = Costumer::select('users.id','users.name','users.email','costumers.address','costumers.phone_number','costumers.date_of_birth','costumers.driving_license')->from('costumers')->join('users', 'costumers.id_user', '=', 'users.id')->where('users.id', $id)->get();
        return $data;
    }
    public function my_profile()
    {
        $id = auth()->user()->id;
        $data = Costumer::select('users.id','users.name','users.email','costumers.address','costumers.phone_number','costumers.date_of_birth','costumers.driving_license')->from('costumers')->join('users', 'costumers.id_user', '=', 'users.id')->where('users.id', $id)->get();
        return $data;
    }
    public function add_costumer(Request $request)
    {
        $id_user = auth()->user()->id;

        $message = [
            'required' => ':attribute must be filled',
            'integer' => ':attribute must contain an integer',
            'image' => ':attribute must be image',
            'max' => ':attribute must under 5mb'
        ];
        $validate = Validator::make($request->all(), [
            'phone_number' => 'required|integer',
            'address' => 'required',
            'date_of_birth' => 'required',
            'driving_license' => 'required|image|mimes:png,jpg,jpeg|max:5000'
        ], $message);

        if ($validate->fails()) {
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ]);
        }
        $validator = $validate->validate();
 
        if (Costumer::select('id_user')->where('id_user', $id_user)->exists()) {
            return response([
                'status' => 400,
                'message' => 'data already exists'
            ]);
        }else{
            $file = $request->file('driving_license');
            $imagename = round(microtime(true) * 100).'.'.$file->extension();
            $request->file('driving_license')->move(public_path('images/driving_license'), $imagename);

            $data = Costumer::create([
                "id_user" => $id_user,
                "phone_number" => $request->phone_number,
                "address" => $request->address,
                "date_of_birth" => $request->date_of_birth,
                "driving_license" => $imagename,
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
    }
    public function update(Request $request)
    {
        $id = auth()->user()->id;
        $data = $request->all();
        $file = $request->file('driving_license');
        $imagename = round(microtime(true) * 100).'.'.$file->extension();
        if ($request->image != null) {
            $request->file('driving_license')->move(public_path('images/driving_license'), $imagename);
            $olddata = Costumer::find($id);
            $patholdimage = public_path('images/driving_license/'.$olddata->image);
            File::delete($patholdimage);
        }
        $update = Costumer::where('id_user', $id)->update([
            "phone_number" => $request->phone_number,
            "address" => $request->address,
            "date_of_birth" => $request->date_of_birth,
            "driving_license" => $imagename,
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
}
