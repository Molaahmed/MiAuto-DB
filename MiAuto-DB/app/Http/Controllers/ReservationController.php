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
    


    /**
     * GET api/reservations/{garage_id}
     * 
     * Get all the reservation per garage.
     * 
     * @response [{"id":1,"user_id":3,"garage_id":1,"vin_number":"5kkt4y594A38e7y7h","description":"Accusamus dolores officia totam dolorem doloribus eaque consequatur tempora. Sunt sed magni et omnis earum deleniti. Voluptatem officia et mollitia expedita qui.","date":"1991-05-09","startingTime":"16:59:01","endingTime":"17:24:57"},{"id":2,"user_id":4,"garage_id":1,"vin_number":"1r0pu3w1XAbfn5l30","description":"Cupiditate in vel commodi rerum unde. Vel exercitationem consequatur iusto aperiam dolor maiores sunt modi. At molestiae numquam tempora vel velit ipsam iure explicabo.","date":"2013-11-26","startingTime":"23:35:23","endingTime":"18:23:15"},{"id":3,"user_id":5,"garage_id":1,"vin_number":"6ys7t1ej1A6xhv8rc","description":"Optio commodi modi itaque eius. Et non ratione est excepturi id. Iusto tempore est velit adipisci ad in.","date":"2000-04-10","startingTime":"16:20:45","endingTime":"04:34:26"},{"id":4,"user_id":2,"garage_id":1,"vin_number":"337vp16w9A7kj9jcp","description":"Tenetur voluptatem atque unde et veniam qui est tempora. Quia numquam sed dicta earum quia velit similique sint. Veritatis dolores ut quis magni quidem suscipit dolorem natus.","date":"1996-01-25","startingTime":"11:26:08","endingTime":"22:20:34"}]
     * 
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function  getByGarageId($garage_id){
        $garage = Garage::findOrFail($garage_id);
        if($garage){
            $reservation =  $garage->reservations;
            return new JsonResponse($reservation, 200);
        }
        return new JsonResponse("Error not found",404);
      
    }


    /**
     * POST api/reservation
     * 
     * Creating reservation on specific garage.
     * This endpoint is used by Clients, Garage Manager, Admin.
     * 
     * @response status=200  {"user_id":1,"garage_id":1,"vin_number":"1r0pu3w1XAbfn5l30","description":"Optio nihil quaerat quaerat. Est sunt eos neque mollitia consectetur sit reiciendis. Accusamus et eum ad rerum.","date":"1990-07-26","startingTime":"01:21:29","endingTime":"00:21:57","id":6}
     * @response status=422  {"errors":{"user_id":["The user id field is required."],"garage_id":["The garage id field is required."],"vin_number":["The vin number field is required."],"description":["The description field is required."],"date":["The date field is required."],"startingTime":["The starting time field is required."],"endingTime":["The ending time field is required."]}}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'garage_id' => 'required',
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
}
