<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Instructor\CourseController;
use App\Http\Controllers\API\Auth\GoogleController;
use App\Http\Controllers\API\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

#============================== ROUTE AUTH =============================
Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('sign-up', [AuthController::class, 'signUp']);
    Route::post('sign-in', [AuthController::class, 'signIn']);
    Route::post('verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::get('google', [GoogleController::class, 'redirectToGoogle']);
    Route::get('google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->as('auth.')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });

#============================== ROUTE COURSE =============================
    Route::prefix('courses')
        ->as('courses.')
        ->group(function () {
            Route::get('/{slug}', [CourseController::class, 'getCourseOverView']);
            Route::post('/', [CourseController::class, 'store']);
            Route::put('/{slug}/contentCourse', [CourseController::class, 'updateContentCourse']);
        });

#============================== ROUTE CHAPTER =============================

#============================== ROUTE LESSON =============================
});



