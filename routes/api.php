<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[AuthController::class,'login']);
//CLIENT
Route::post('registerClient',[AuthController::class,'registerClient']);

//COMMERCANT
Route::post('registerCommercant',[AuthController::class,'registerCommercant']);

//ADMIN
Route::middleware(['auth:api','admin'])->group(function(){
    Route::post('registerLivreur',[AuthController::class,'registerLivreur']);

});
