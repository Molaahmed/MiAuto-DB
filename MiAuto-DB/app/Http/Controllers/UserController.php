<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function User()
    {
        $role_id = DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->select('user_role.role_id')->value('role_id');

        return DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->join('roles','roles.id','=','user_role.role_id')
        ->where('users.id',Auth::user()->id)
        ->select('users.*','roles.name as role')
        ->get();
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();

        if(!$user)
        {
            return response([
                'message' => ['User not found'] 
            ],404);
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required',
            'address'=> 'required',
            'phone_number'=> 'required'
        ]);
        
        $user->update($request->all());

        return new JsonResponse('Updated successful', 200);
    }
}
