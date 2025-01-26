<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkNotificationAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('notification_id')) {
            $notification = $request->user()->notifications()->find($request->get('notification_id'));

            if ($notification && $notification->unread()) {
                $notification->markAsRead();
            }
        }

        return $next($request);
    }
}