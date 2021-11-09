<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TokenController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::attempt($request->only('email', 'password'))){
            return response()->json(Auth::user(), 200);
        }
        return response([
            'message' => ['These credentials do not match out records.'] 
        ],404);


    }


    public function destroy(Request $request)
    {
        Auth::logout();
    }

}
