<?php

namespace App\Http\Controllers;
use App\Http\Resources\CarResource;
use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    
    /**
     * GET api/cars
     * 
     * Get all the cars in the Database.
     * 
     * @response status=200 {"data":[{"id":1,"user_id":13,"vin_number":"5kkt4y594A38e7y7h","plate":"UZK-611","type":"small","fuel":"hybrid","make":"Ford","model":"46","engine":"4","gear_box":"automatic","air_conditioner":1,"color":"red"},{"id":2,"user_id":9,"vin_number":"6ys7t1ej1A6xhv8rc","plate":"ZFD-892","type":"hatchback","fuel":"hybrid","make":"Chrysler","model":"Kingte","engine":"Attiva","gear_box":"automatic","air_conditioner":1,"color":"red"},{"id":3,"user_id":11,"vin_number":"3l2az2zw0Ayhv6nv6","plate":"KLC-009","type":"SUV","fuel":"electric","make":"Innocenti","model":"Beast","engine":"H3","gear_box":"automatic","air_conditioner":1,"color":"red"}]}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function index(){
        return CarResource::collection(Car::all());
    }

}
