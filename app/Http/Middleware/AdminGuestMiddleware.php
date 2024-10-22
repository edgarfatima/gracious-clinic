<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminGuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            // Redirect based on user role
            if ($user->role === 'admin') {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->role === 'doctor') {
                return redirect()->intended('doctor/patient');
            } elseif ($user->role === 'staff') {
                return redirect()->intended('staff/appointment');
            } else {
                return redirect()->route('admin.login')->with('error', 'Unauthorized role');
            }
        }

        return $next($request);
    }
}