<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AvisController;
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
Route::get('commentaires/{produit}',[AvisController::class,'getAvisByProduit']);
Route::middleware(['auth:api','client'])->group(function(){
    Route::post('deconnexionClient',[AuthController::class,'logout']);
    Route::post('ajoutProduitPanier/{produit}',[PanierController::class,'ajouterProduitPanier']);
    Route::get('voirPanier',[PanierController::class,'voirPanier']);
    Route::post('retirerProduit/{produit}',[PanierController::class,'retirerProduitPanier']);
    Route::post('passerCommande',[CommandeController::class,'creerCommande']);
    Route::post('faireCommentaire/{produit}',[AvisController::class,'store']);
    Route::post('Commentaire/update/{produit}',[AvisController::class,'update']);
    Route::post('Commentaire/{produit}',[AvisController::class,'destroy']);
    Route::post('signalerProduit/{produit}',[AvisController::class,'signalerProduit']);
    Route::post('modifierInfoClient',[AuthController::class,'modifierInfoClient']);
});

//COMMERCANT
Route::post('registerCommercant',[AuthController::class,'registerCommercant']);
Route::get('produits',[ProduitController::class,'index']);
Route::get('produits/{categorie}',[ProduitController::class,'getProduitsByCategorie']);
Route::post('Detailsproduits/{produit}',[ProduitController::class,'show']);
Route::middleware(['auth:api','commercant'])->group(function(){
    Route::post('deconnexionCommercant',[AuthController::class,'logout']);
    Route::post('produit/create',[ProduitController::class,'store']);
    Route::post('produit/update/{produit}',[ProduitController::class,'update']);
    Route::post('produit/{produit}',[ProduitController::class,'destroy']);
    Route::get('getProduitsByCommercant',[ProduitController::class,'getProduitsByCommercant']);
    Route::post('modifierInfoCommercant',[AuthController::class,'modifierInfoCommercant']);
});

Route::get('categories',[CategorieController::class,'index']);
//ADMIN
Route::middleware(['auth:api','admin'])->group(function(){
    Route::post('registerLivreur',[AuthController::class,'registerLivreur']);
    Route::post('deconnexionAdmin',[AuthController::class,'logout']);
    Route::post('categorie/create',[CategorieController::class,'store']);
    Route::post('categorie/update/{id}',[CategorieController::class,'update']);
    Route::post('categorie/{id}',[CategorieController::class,'destroy']);
    Route::get('commandes',[CommandeController::class,'index']);
    Route::get('ListeProduitSignaler',[AvisController::class,'ListeProduitSignaler']);
    Route::get('ListerLivreur',[CommandeController::class,'ListerLivreur']);
    Route::post('AffecterLivreur/{commande}',[CommandeController::class,'AffecterLivreur']);

});

//LIVREUR
Route::middleware(['auth:api','livreur'])->group(function(){
    Route::post('deconnexionLivreur',[AuthController::class,'logout']);
    Route::post('ChangerStatut',[CommandeController::class,'ChangerStatut']);
});
