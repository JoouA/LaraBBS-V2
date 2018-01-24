<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class FounderPermission
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
        if (Auth::check()){
            if (Auth::user()->hasRole('Founder')){
                return $next($request);
            }else{
                return redirect('/',302);
            }
        }else{
            return redirect()->route('login');
        }
    }
}
