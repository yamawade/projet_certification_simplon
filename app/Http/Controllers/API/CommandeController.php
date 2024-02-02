<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Panier;
use App\Models\Livreur;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\DetailCommande;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;

class CommandeController extends Controller
{
    private $paymentController;

    public function __construct(PaymentController $paymentController)
    {
        $this->paymentController = $paymentController;
    }

    public function creerCommande(Request $request)
    {
        $user = Client::where('user_id', Auth::user()->id)->first();
        $panier = Panier::where('user_id', $user->id)->first();
    
        if (!$panier) {
            return response()->json(['status' => 404, 'status_message' => 'Le panier est vide ou n\'existe pas.']);
        }
    
        $commande = Commande::create([
            'date_commande' => now(),
            'client_id' => $user->id,
        ]);
        $commande_id=$commande->id;
       // dd($commande);
        $produitsPanier = $panier->produits()->withPivot('quantite')->get();
    
        $montantTotal = 0;
    
        foreach ($produitsPanier as $produit) {
            $montantProduit = $produit->pivot->quantite * $produit->prix;
            $montantTotal += $montantProduit;
    
            DetailCommande::create([
                'commande_id' => $commande->id,
                'produit_id' => $produit->id,
                'montant' => $montantProduit,
                'nombre_produit' => $produit->pivot->quantite,
            ]);
        }
    
        $panier->produits()->detach();
    
        // if ($request->expectsJson()) {
        //     // Si la requête provient d'Insomnia, retournez une réponse JSON
        //     return response()->json(['montantTotal' => $montantTotal, 'commande_id' => $commande_id]);
        // } else {
        //     // Si la requête provient du navigateur, redirigez vers la vue
        //     return view('index', compact('montantTotal', 'commande_id'));
        // }
    //     // Utilisez la redirection appropriée
    //     //return redirect()->route('payment.index', compact('montantTotal', 'commande_id'));
        return view('index', compact('montantTotal','commande_id'));
       //return redirect()->away(route('payment.index', ['montantTotal' => $montantTotal, 'commande_id' => $commande_id]));
    }
    
    
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $commandes = Commande::where('etat_commande', 'en_attente')->orderBy('created_at', 'desc')->get();
        try {

            return response()->json([
                'status' => 200,
                'status_message' => 'la liste des commandes',
                'data' => $commandes
            ]);
        } catch (Exception $e) {
            return response($e)->json($e);
        }
    }

    public function ListerLivreurDisponible(){

        $livreurs = Livreur::where('statut', 'disponible')->get();
        try {

            return response()->json([
                'status' => 200,
                'status_message' => 'la liste des livreurs disponible',
                'data' => $livreurs
            ]);

        } catch (Exception $e) {
            return response($e)->json($e);
        }
    }

    public function AffecterLivreur(Commande $commande,Request $request){

        $livreur = Livreur::where('id', $request->livreur_id)->first();
        if($livreur->statut === 'disponible') {
            $commande->update([
                'livreur_id' => $livreur->id,
                'etat_commande' => 'en_cours',
            ]);
            $livreur->statut = 'indisponible';
            $livreur->save();
            return response()->json([
                'status' => 200,
                'status_message' => 'livreur affecté',
                'data' => $commande
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'status_message' => 'livreur non disponible',
            ]);
        }
    }

    public function ChangerStatut(){
        $user = Livreur::where('user_id', Auth::user()->id)->first();
        $commande = Commande::where('livreur_id', $user->id)->where('etat_commande', 'en_cours')->first();
        if($user->statut === 'indisponible') {
            $user->statut = 'disponible';
            $user->save();
            $commande->update([
                'livreur_id' => $user->id,
                'etat_commande' => 'terminer',
            ]);
            return response()->json([
                'status' => 200,
                'status_message' => 'livreur disponible',
                'data' => $user
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'status_message' => 'livreur non disponible',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
