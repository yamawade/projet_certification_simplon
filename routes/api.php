<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AvisController;
use App\Http\Controllers\API\PanierController;
use App\Http\Controllers\API\ProduitController;
use App\Http\Controllers\API\CommandeController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\NewsletterController;

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
    Route::post('passerCommande',[CommandeController::class,'creerCommande']);
    Route::post('faireCommentaire/{produit}',[AvisController::class,'store']);
    Route::post('Commentaire/update/{produit}',[AvisController::class,'update']);
    Route::delete('Commentaire/{produit}',[AvisController::class,'destroy']);
    Route::post('signalerProduit/{produit}',[AvisController::class,'signalerProduit']);
    Route::post('modifierInfoClient',[AuthController::class,'modifierInfoClient']);
    Route::get('showClient',[AuthController::class,'showClient']);
    Route::get('listeCommande',[CommandeController::class,'listerCommandeClient']);
});

Route::get('payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/checkout', [PaymentController::class, 'payment'])->name('payment.submit');
Route::get('ipn', [PaymentController::class, 'ipn'])->name('paytech-ipn');
Route::get('payment-cancel', [PaymentController::class, 'cancel'])->name('paytech.cancel');
Route::get('payment-success/{code}', [PaymentController::class, 'success'])->name('payment.success');
Route::get('payment/{code}/success', [PaymentController::class, 'paymentSuccessView'])->name('payment.success.view');

//COMMERCANT
Route::post('registerCommercant',[AuthController::class,'registerCommercant']);
Route::get('produits',[ProduitController::class,'index']);
Route::get('produits/{categorie}',[ProduitController::class,'getProduitsByCategorie']);
Route::post('Detailsproduits/{produit}',[ProduitController::class,'show']);
Route::middleware(['auth:api','commercant'])->group(function(){
    Route::post('deconnexionCommercant',[AuthController::class,'logout']);
    Route::post('produit/create',[ProduitController::class,'store']);
    Route::post('produit/update/{produit}',[ProduitController::class,'update']);
    Route::delete('produit/{produit}',[ProduitController::class,'destroy']);
    Route::get('getProduitsByCommercant',[ProduitController::class,'getProduitsByCommercant']);
    Route::post('modifierInfoCommercant',[AuthController::class,'modifierInfoCommercant']);
    Route::get('showCommercant',[AuthController::class,'showCommercant']);
    Route::get('listeVentes',[CommandeController::class,'listerVentesCommercant']);
});

Route::get('categories',[CategorieController::class,'index']);
//ADMIN
Route::middleware(['auth:api','admin'])->group(function(){
    Route::post('registerLivreur',[AuthController::class,'registerLivreur']);
    Route::post('deconnexionAdmin',[AuthController::class,'logout']);
    Route::post('categorie/create',[CategorieController::class,'store']);
    Route::post('categorie/update/{id}',[CategorieController::class,'update']);
    Route::delete('categorie/{id}',[CategorieController::class,'destroy']);
    Route::get('commandes',[CommandeController::class,'index']);
    Route::get('ListeProduitSignaler',[AvisController::class,'ListeProduitSignaler']);
    Route::get('ListerLivreur',[CommandeController::class,'ListerLivreur']);
    Route::post('AffecterLivreur/{commande}',[CommandeController::class,'AffecterLivreur']);
    Route::get('ListeUtilisateur',[AuthController::class,'ListeUtilisateur']);
    Route::get('ListeNewsletter',[NewsletterController::class,'index']);
    Route::post('envoyerMail',[NewsletterController::class,'envoyerMail']);
    Route::get('ListeFeedback',[FeedbackController::class,'index']);
    Route::get('voirFeedback/{feedback}',[FeedbackController::class,'show']);
    Route::get('Detailscommandes/{commande}',[CommandeController::class,'show']);
    Route::get('voirProduitSignaler/{produitSignaler}',[AvisController::class,'show']);
    Route::get('bloquerProduitSignaler/{produitSignaler}',[AvisController::class,'bloquerProduitSignaler']);
});

//LIVREUR
Route::middleware(['auth:api','livreur'])->group(function(){
    Route::post('deconnexionLivreur',[AuthController::class,'logout']);
    Route::post('ChangerStatut',[CommandeController::class,'ChangerStatut']);
    Route::post('modifierPasswordLivreur',[AuthController::class,'modifierPasswordLivreur']);
});

//NEWSLETTER
Route::post('inscriptionNewsletter',[NewsletterController::class,'inscriptionNewsletter']);

//FEEDBACK
Route::post('faireFeedback',[FeedbackController::class,'store']);

