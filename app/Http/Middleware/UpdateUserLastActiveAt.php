<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //$user = auth()->user();
        //or
        $user = $request->user();
        
        if($user instanceof User){
            $user->forceFill([
                'last_active_at' =>Carbon::now(),
            ])->save();
        }
        
        return $next($request);
    }
}
