<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategorieController;

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

Route::middleware(['auth:api','client'])->group(function(){
    Route::post('deconnexion',[AuthController::class,'logout']);

});

//COMMERCANT
Route::post('registerCommercant',[AuthController::class,'registerCommercant']);

Route::middleware(['auth:api','commercant'])->group(function(){
    Route::post('deconnexion',[AuthController::class,'logout']);

});

//ADMIN
Route::middleware(['auth:api','admin'])->group(function(){
    Route::post('registerLivreur',[AuthController::class,'registerLivreur']);
    Route::post('deconnexion',[AuthController::class,'logout']);
    Route::post('categorie/create',[CategorieController::class,'store']);
    Route::put('categorie/update/{id}',[CategorieController::class,'update']);
    Route::delete('categorie/{id}',[CategorieController::class,'destroy']);
    Route::get('categories',[CategorieController::class,'index']);

});

//LIVREUR
Route::middleware(['auth:api','livreur'])->group(function(){
    Route::post('deconnexion',[AuthController::class,'logout']);

});
