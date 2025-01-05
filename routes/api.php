<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\API\Auth\GoogleController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Posts\PostController;

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
            Route::post('/', [CourseController::class, 'store']);
        });

    #============================== ROUTE CHAPTER =============================

    #============================== ROUTE LESSON =============================


    #============================== ROUTE COURSE =============================
    Route::prefix('posts')->as('posts.')->group( function(){
        Route::get('/', [PostController::class, '']);
    });
});



