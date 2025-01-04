<?php

namespace App\Http\Middleware;

use App\Traits\LoggableTrait;
use Closure;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleHasInstructor
{
    use LoggableTrait, ApiResponseHelpers;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (
                Auth::check() && Auth::user()->hasRole('instructor') &&
                Auth::user()->email_verified_at !== null &&
                Auth::user()->status === 'active'
            ) {
                return $next($request);
            }

            return $this->respondForbidden('Bạn không có quyền truy câp vào hệ thống');
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->respondServerError('Có lỗi xảy ra, vui lòng thử lại',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
