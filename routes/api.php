<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\GoogleController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Banner\BannerController;
use App\Http\Controllers\API\Category\CategoryController;
use App\Http\Controllers\API\Instructor\CourseController;
use App\Http\Controllers\API\Instructor\ChapterController;

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

    Route::prefix('instructor')
        // ->middleware('roleHasInstructor')
        ->as('instructor.')->group(function () {
        #============================== ROUTE COURSE =============================
        Route::prefix('courses')
            ->as('courses.')
            ->group(function () {
                Route::get('/', [CourseController::class, 'index']);
                Route::get('/{slug}', [CourseController::class, 'getCourseOverView']);
                Route::post('/', [CourseController::class, 'store']);
                Route::put('/{slug}/contentCourse', [CourseController::class, 'updateContentCourse']);
                Route::delete('/{slug}', [CourseController::class, 'deleteCourse']);
            });

        #============================== ROUTE CHAPTER =============================
        Route::prefix('chapters')
            ->as('chapters.')
            ->group(function () {
                Route::post('/', [ChapterController::class, 'storeChapter']);
                Route::put('/{slug}/update-order', [ChapterController::class, 'updateOrderChapter']);
                Route::put('/{slug}/{chapterId}', [ChapterController::class, 'updateContentChapter']);
                Route::delete('/{slug}/{chapterId}', [ChapterController::class, 'deleteChapter']);
            });

        #============================== ROUTE LESSON =============================
        #============================== ROUTE CATEGORY =============================
        Route::prefix('categories')
            ->as('categories.')
            ->group(function () {
                Route::get('/', [CategoryController::class, 'index']);
                Route::post('/', [CategoryController::class, 'store']);
                Route::get('/{id}', [CategoryController::class, 'show']);
                Route::put('/{category}', [CategoryController::class, 'update']);
                Route::delete('/{category}', [CategoryController::class, 'destroy']);
        });
         #============================== ROUTE BANNER =============================
         Route::prefix('banners')
         ->as('banners.')
         ->group(function () {
             Route::get('/', [BannerController::class, 'index']);
             Route::post('/', [BannerController::class, 'store']);
             Route::get('/{id}', [BannerController::class, 'show']);
             Route::put('/{banner}', [BannerController::class, 'update']);
             Route::delete('/{banner}', [BannerController::class, 'destroy']);
     });
    });
});



