<?php

namespace App\Http\Middleware;

use App\Traits\LoggableTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleHasInstructor
{
    use LoggableTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (
                Auth::check() && (Auth::user()->hasRole('instructor') || Auth::user()->hasRole('super_admin')
                    || Auth::user()->hasRole('admin')
                ) &&
                Auth::user()->email_verified_at !== null &&
                Auth::user()->status === 'active'
            ) {
                return $next($request);
            }

            return response()->json([
                'message' => 'Bạn không có quyền truy cập vào hệ thống',
            ], Response::HTTP_FORBIDDEN);
        } catch (\Exception $e) {
            $this->logError($e);

            return response()->json([
                'message' => 'Có lỗi xảy ra, vui lòng thử lại',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
