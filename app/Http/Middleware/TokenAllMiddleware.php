<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class TokenAllMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::get('userinfo')) {
            return redirect('/login');
        } else {
            $userinfo = Session::get('userinfo');
            //            $segment =  \Request::segment(2);
            //            if (!empty($access_control)) {
            //                if ($access_control[$userinfo['user_level_id']][$segment] != "a"){
            //                    return redirect('/'.$segment);
            //                }
            //            } else {
            //                return redirect('');
            //            }
        }
        return $next($request);
    }
}
