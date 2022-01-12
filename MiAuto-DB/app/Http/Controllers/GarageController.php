<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Garage;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GarageResource;
use DB;


class GarageController extends Controller
{
    /**
     * GET api/garages
     * 
     * Retuns all the garages.
     * 
     * @response status=200 {"data":[{"name":"Sten Haselaar","address":"van Dokkumhof 3\n9801TA Westerhaar-Vriezenveensewijk","phone_number":"+41682531929","email":"lveenstra@gmail.com"},{"name":"ut","address":"placeat","phone_number":"+3125544785","email":"hic@gmail.com"}]}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GarageResource::collection(Garage::all());
    }
 
    /**
     * GET api/garages/address/{address}
     * 
     * Search garages by address.
     * 
     * @response status=200 {"data":[{"name":"Sten Haselaar","address":"van Dokkumhof 3\n9801TA Westerhaar-Vriezenveensewijk","phone_number":"+41682531929","email":"lveenstra@gmail.com"}]}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchByAddress($address)
    {
        $garages = DB::table('garages')
            ->where('address', 'like','%'.$address.'%')
            ->get();

        return  GarageResource::collection($garages);
    }


    /**
     * GET api/garage/id
     * 
     * Returns the ID of the garage that you are working.
     * 
     * @response 1
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function getGarageId()
    {
        return Employee::where('user_id',Auth::user()->id)->select('garage_id')->value('garage_id');
    }

    /**
     * GET api/garages/{id}
     * 
     * Search garage by ID.
     * 
     * @response status=200  {"id":1,"user_id":12,"name":"Sten Haselaar","address":"van Dokkumhof 3\n9801TA Westerhaar-Vriezenveensewijk","email":"lveenstra@gmail.com","phone_number":"+41682531929"}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function show($id)
    {
        return Garage::findOrFail($id);
    }

}
