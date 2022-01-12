<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Garage;

class ReservationController extends Controller
{
    

    public function index()
    {
        return new JsonResponse(Auth::user()->reservations, 200);
    }

    public function  getByGarageId($garage_id){
        $garage = Garage::findOrFail($garage_id);
        if($garage){
            $reservation =  $garage->reservations;
            return new JsonResponse($reservation, 200);
        }
        return new JsonResponse("Error not found",404);
      
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'employee_id' => 'required',
            'vin_number' => 'required',
            'description' => 'required',
            'date'=> 'required',
            'startingTime' => 'required',
            'endingTime' => 'required'
        ]);

        if($validator->fails()){
            return new JsonResponse(['errors'=>$validator->messages()],422);
        }else{
            $reservation = Reservation::create($request->all());
            return new JsonResponse($reservation, 200);
        }
    }


    public function updateReservation(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'employee_id' => 'required',
            'vin_number' => 'required',
            'description' => 'required',
            'date'=> 'required',
            'startingTime' => 'required',
            'endingTime' => 'required'
        ]);

        $reservation = Reservation::where($request->id)
        ->update([
            'user_id' => $request->user_id,
            'employee_id' => $request->employee_id,
            'vin_number' => $request->vin_number,
            'description' => $request->description,
            'date'=> $request->date,
            'startingTime' => $request->startingTime,
            'endingTime' => $request->endingTime
        ]);

        return new JsonResponse($reservation,200);

    }
}
