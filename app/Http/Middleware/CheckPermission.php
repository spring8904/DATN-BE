<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();

        if (!$user->can($permission)) {
            $roles = $user->getRoleNames();

            if ($roles->contains('student') || $roles->contains('instructor')) {
                Auth::logout();
                session()->flush();

                return redirect()->route('admin.login')->with('error', 'Bạn không có quyền thực hiện chức năng này.');
            }
            abort(403, 'Bạn không có quyền thực hiện chức năng này.');
        }

        return $next($request);
    }
}
