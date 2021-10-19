<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegistrationController extends Controller
{
    
    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required',
            'address'=> 'required',
            'phone_number'=> 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return abort(406, 'Email exists');
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('garage_client');
         
        return $user;
    }


    //Needs to be implemented
    // public function storeMechanic(Request $request)
    // {
    //     $this->validate(request(), [
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if ($user) {
    //     return abort(406, 'Email exists');
    //     }

    //     $user = User::create(request(['name', 'email', 'password']));
    //     $user->assignRole('mechanic');

    //     return $user;
    // }
}
