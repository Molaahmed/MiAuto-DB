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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GarageResource::collection(Garage::all());
    }
 
    /**
     * Store a newly created resource in storage.
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

    public function getGarageId()
    {
        return Employee::where('user_id',Auth::user()->id)->select('garage_id')->value('garage_');
    }

    public function show($id)
    {
        return Garage::findOrFail($id);
    }

}
