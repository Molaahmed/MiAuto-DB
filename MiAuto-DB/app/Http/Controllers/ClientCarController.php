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
     * GET api/client/cars
     * 
     * Return all the cars of the client that is authenticated
     * 
     * @response {"data":[{"id":1,"user_id":1,"vin_number":"5kkt4y594A38e7y7h","plate":"UZK-611","type":"small","fuel":"hybrid","make":"Ford","model":"46","engine":"4","gear_box":"automatic","air_conditioner":1,"color":"red"},{"id":2,"user_id":1,"vin_number":"6ys7t1ej1A6xhv8rc","plate":"ZFD-892","type":"hatchback","fuel":"hybrid","make":"Chrysler","model":"Kingte","engine":"Attiva","gear_box":"automatic","air_conditioner":1,"color":"red"}]}
     * 
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
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
     * POST api/client/cars
     * 
     * Client post new car in their profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer',
            'vin_number' => 'required|unique:cars',
            'plate' => 'required',
            'type' => 'required',
            'fuel' => 'required',
            'make' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'gear_box' => 'required',
            'air_conditioner' => 'required|boolean',
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


    /**
     * GET api/garage/client/cars/{client_id}
     * 
     * Returns all the cars of the client.
     * 
     * 
     * @response {"data":[{"id":4,"user_id":3,"vin_number":"337vp16w9A7kj9jcp","plate":"IIL-805","type":"sedan","fuel":"diesel","make":"Mazda","model":"Super\u0412 Combi","engine":"PS 160","gear_box":"automatic","air_conditioner":1,"color":"red"},{"id":5,"user_id":3,"vin_number":"1r0pu3w1XAbfn5l30","plate":"RSV-723","type":"SUV","fuel":"hybrid","make":"Adler","model":"Metro","engine":"Freeclimber","gear_box":"manual","air_conditioner":1,"color":"red"}]}
     * 
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
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
