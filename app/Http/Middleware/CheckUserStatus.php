<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User_login_log;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        //If the status is not active redirect to login
        if (Auth::check() && Auth::user()->active != 1) {

            User_login_log::where('user_id', Auth::user()->id)
                ->latest()
                ->first()
                ->update(['success' => 0]);


            Auth::logout();
            return redirect('/login')->withErrors(['inactive_user' => 'Your account has been suspended']);
        }
        return $response;
    }
}