<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PanierController;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\CommandeController;
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
    Route::post('deconnexionClient',[AuthController::class,'logout']);
    Route::post('ajoutProduitPanier/{produit}',[PanierController::class,'ajouterProduitPanier']);
    Route::get('voirPanier',[PanierController::class,'voirPanier']);
    Route::post('retirerProduit/{produit}',[PanierController::class,'retirerProduitPanier']);
    Route::post('passerCommande',[CommandeController::class,'creerCommande']);
});

//COMMERCANT
Route::post('registerCommercant',[AuthController::class,'registerCommercant']);
Route::get('produits',[ProduitController::class,'index']);
Route::middleware(['auth:api','commercant'])->group(function(){
    Route::post('deconnexionCommercant',[AuthController::class,'logout']);
    Route::post('produit/create',[ProduitController::class,'store']);
    Route::post('produit/update/{produit}',[ProduitController::class,'update']);
    Route::delete('produit/{produit}',[ProduitController::class,'destroy']);

});

//ADMIN
Route::middleware(['auth:api','admin'])->group(function(){
    Route::post('registerLivreur',[AuthController::class,'registerLivreur']);
    Route::post('deconnexionAdmin',[AuthController::class,'logout']);
    Route::post('categorie/create',[CategorieController::class,'store']);
    Route::put('categorie/update/{id}',[CategorieController::class,'update']);
    Route::delete('categorie/{id}',[CategorieController::class,'destroy']);
    Route::get('categories',[CategorieController::class,'index']);
    Route::get('commandes',[CommandeController::class,'index']);

});

//LIVREUR
Route::middleware(['auth:api','livreur'])->group(function(){
    Route::post('deconnexionLivreur',[AuthController::class,'logout']);

});
