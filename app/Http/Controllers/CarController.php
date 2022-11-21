<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Car;
use File;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Car::all();
        return response()->json([
            "message" => "Load data success",
            "data" => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute must be filled',
            'integer' => ':attribute must contain an integer',
            'image' => ':attribute must be image',
            'max' => ':attribute must under 5mb'
        ];
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:5000',
            'brand' => 'required',
            'year' => 'required|integer',
            'transmission' => 'required',
            'seats' => 'required|integer',
            'fuel' => 'required',
            'cc' => 'required|integer',
            'description' => 'required',
            'price' => 'required|integer'
        ], $message);

        if ($validate->fails()) {
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ]);
        }
        $validator = $validate->validate();
        $file = $request->file('image');
        $imagename = round(microtime(true) * 100).'.'.$file->extension();
        $request->file('image')->move(public_path('images/cars'), $imagename);

        $data = Car::create([
            "name" => $request->name,
            "image" => $imagename,
            "brand" => $request->brand,
            "year" => $request->year,
            "transmission" => $request->transmission,
            "seats" => $request->seats,
            "fuel" => $request->fuel,
            "cc" => $request->cc,
            "description" => $request->description,
            "price" => $request->price
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Car::find($id);
        if($data){
            return $data;
        }else{
            return ["message" => "Data not found"];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->image != null) {
            $file = $request->file('image');
            $imagename = round(microtime(true) * 100).'.'.$file->extension();
            $request->file('image')->move(public_path('images/cars'), $imagename);
            $olddata = Car::find($id);
            $patholdimage = public_path('images/cars/'.$olddata->image);
            File::delete($patholdimage);
        }
        $update = Car::where('id', $id)->update([
            "name" => $request->name,
            "image" => $imagename,
            "brand" => $request->brand,
            "year" => $request->year,
            "transmission" => $request->transmission,
            "seats" => $request->seats,
            "fuel" => $request->fuel,
            "cc" => $request->cc,
            "description" => $request->description,
            "price" => $request->price
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Car::find($id);
        if($data){
            $pathimage = public_path('images/cars/'.$data->image);
            File::delete($pathimage);
            $data->delete();
            return ["message" => "Delete succes"];
        }else{
            return ["message" => "Data not found"];
        }
    }
}
