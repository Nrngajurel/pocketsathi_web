<?php

use App\Http\Controllers\Api\ApplyController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HelpController;
use App\Http\Controllers\Api\HelpRequestController;
use App\Models\HelpRequest;
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


Route::prefix('v1/')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);
        // update user location
        Route::post('/update-location', [AuthController::class, 'updateLocation']);


        Route::post('/logout', [AuthController::class, 'logout']);
        Route::resource('category', CategoryController::class);

        // my request and nearby request
        Route::get('requests/my', [HelpRequestController::class, 'myHelpRequest']);
        Route::get('requests/nearby', [HelpRequestController::class, 'nearbyHelpRequest']);


        Route::post('help-request', [HelpRequestController::class, 'storeHelpRequest']);

        Route::post('help-request/{help_request}/complete', [HelpRequestController::class, 'complete']);
        Route::post('help-request/{help_request}/cancel', [HelpRequestController::class, 'cancel']);

        Route::post('apply-help-request', [ApplyController::class, 'store']);
        Route::post('apply/{help_request}/{apply}/reject', [ApplyController::class, 'reject']);
        Route::post('apply/{help_request}/{apply}/accept', [ApplyController::class, 'accept']);


    });
});