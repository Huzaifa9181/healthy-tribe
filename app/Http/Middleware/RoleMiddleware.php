<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if the user's role_id is 1
            if ($user->role_id == 1) {
                return $next($request);
            }
        }

        // If the user is not logged in or their role_id is not 1, redirect or abort as needed
        return redirect('/admin/home'); // You can change this to a different route or response as needed
    }
}
