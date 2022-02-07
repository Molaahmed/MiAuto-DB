<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Car;
use App\Http\Resources\CarResource;

class ClientCarController extends Controller
{

    /**
     * Display a listing of the resource.
     * 
     * Return all the cars of the client
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()){
            return CarResource::collection(Auth::user()->cars);
        }
        return Abort(401);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'vin_number' => 'required|unique:cars',
            'plate' => 'required',
            'type' => 'required',
            'fuel' => 'required',
            'make' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'gear_box' => 'required',
            'air_conditioner' => 'required',
            'color' => 'required',
        ]);

        if($validator->fails()){
            return new JsonResponse(['errors'=>$validator->messages()],422);
        }else{
            $car = Car::create($request->all());
            return new JsonResponse("Successfully created ", 200);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $car_id)
    {    
        $car = Car::findOrFail($car_id);
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'vin_number' => 'required',
            'plate' => 'required',
            'type' => 'required',
            'fuel' => 'required',
            'make' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'gear_box' => 'required',
            'air_conditioner' => 'required',
            'color' => 'required',
        ]);
        if($validator->fails()){
            return new JsonResponse(['errors'=>$validator->messages()],422);
        }
        else{
            $car->update($request->all());
            return new JsonResponse("Successfully updated ", 200);
        }
    }


    public function show($client_id){
        
        $client = User::findOrFail($client_id);
        if(!$client){
            return new JsonResponse(['error: user' => 'User not found'], 404);
        }
        return  CarResource::collection($client->cars);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($car_id)
    {
        $car = Car::findOrFail($car_id);
        $car->delete();
    }
}
