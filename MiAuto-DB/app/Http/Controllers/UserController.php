<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class UserController extends Controller
{

    public function updateProfile(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();

        if(!$user)
        {
            return response([
                'message' => ['No such a user'] 
            ],404);
        }

        $request->validate([
            'name' => 'required',
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
