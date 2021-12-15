<?php

namespace App\Http\Controllers;
use App\Http\Resources\CarResource;
use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
{
    
    public function index(){
        return CarResource::collection(Car::all());
    }

}
