<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Panier;
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
            return response()->json(['status_code' => 404, 'status_message' => 'Le panier est vide ou n\'existe pas.']);
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
            'status_code' => 200,
            'status_message' => 'Commande créée avec succès',
            'data' => $commande,
        ]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
