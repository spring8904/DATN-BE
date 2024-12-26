<?php

use App\Http\Controllers\API\CloudinaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// API TEST Base không sử dụng

Route::prefix('v2')->group(function () {
    Route::post('cloudinary', [CloudinaryController::class, 'upload']);
    Route::post('destroy', [CloudinaryController::class, 'delete']);
    Route::post('upload-mux', [CloudinaryController::class, 'mux']);
    Route::get('get-duration/{assetId}', [CloudinaryController::class, 'getVideoDuration']);
    Route::delete('delete-mux/{assetId}', [CloudinaryController::class, 'deleteVideoFromMux']);
});
