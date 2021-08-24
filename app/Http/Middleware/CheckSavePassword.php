<?php

namespace App\Http\Middleware;

use Closure;

class CheckSavePassword
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
        $curpass = config('pi.save_password');
        if($curpass != $request->pwd){
            return  redirect()->back()->withInput()->with('alert','Password not match !');
        }
        return $next($request);
    }
}
