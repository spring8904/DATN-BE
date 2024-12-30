<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleHasAdmins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        if (
            Auth::check() && (
                Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin')
            ) &&
            Auth::user()->email_verified_at !== null &&
            Auth::user()->status === 'active'
        ) {
            return $next($request);
        }
        return abort(403, 'Bạn không có quyền truy cập vào hệ thống.');
    }
}
