<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GarageEmployeeAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        //check if the employee is Authorized
        $role = DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->where('users.id',Auth::user()->id)
        ->select('user_role.role_id')->value('role_id');
    
          if($role != 4 && $role != 3 && $role != 2)
          {
             abort(403, 'Access dencied');
          }

          return $next($request);
    }
}
