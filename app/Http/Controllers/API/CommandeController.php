<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Client;
use App\Models\Panier;
use App\Models\Livreur;
use App\Models\Commande;
use Illuminate\Http\Request;
use App\Models\DetailCommande;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{

    public function creerCommande(){
        $user = Client::where('user_id', Auth::user()->id)->first();
        $panier = Panier::where('user_id', $user->id)->first();
    
        if (!$panier) {
            return response()->json(['status' => 404, 'status_message' => 'Le panier est vide ou n\'existe pas.']);
        }
    
        $commande = Commande::create([
            'date_commande' => now(),
            'client_id' => $user->id,
        ]);
       
        $produitsPanier = $panier->produits()->withPivot('quantite')->get();
    
        foreach ($produitsPanier as $produit) {
            DetailCommande::create([
                'commande_id' => $commande->id,
                'produit_id' => $produit->id,
                'montant' => $produit->pivot->quantite * $produit->prix,
                'nombre_produit' => $produit->pivot->quantite,
            ]);
        }
    
        // Détachez les produits après les avoir ajoutés à la commande
        $panier->produits()->detach();
    
        return response()->json([
            'status' => 200,
            'status_message' => 'Commande créée avec succès',
            'data' => $commande,
        ]);
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

    public function ListerLivreur(){

        $livreurs = User::where('type', 'livreur')->get();

        $data = $livreurs->map(function ($livreurs) {
            return [
                'Id' => $livreurs->id,
                'Nom' => $livreurs->nom,
                'Prenom' => $livreurs->prenom,
                'Email' => $livreurs->email,
                'numero_tel' => $livreurs->numero_tel,
                'statut' => $livreurs->livreur->statut
            ];
        });
        try {
           
            return response()->json([
                'status' => 200,
                'status_message' => 'la liste des livreurs',
                'data' =>$data
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
