<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Panier;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\PanierProduit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{

    public function ajouterProduitPanier(Produit $produit, Request $request) {
        $user = Auth::user();
        //dd($user->id);
       
        $panier = Panier::firstOrCreate(['user_id' => $user->id]);

       if($produit->quantite <= 0){
            return response()->json([
                'status_message' => 'Stock épuiser',
            ]);
       }else{
            $quantite = $request->input('quantite', 1);

            $produitExiste = $panier->produits()->where('produit_id', $produit->id)->exists();

            if ($produitExiste) {
                $qte=$produitExiste = PanierProduit::where('produit_id', $produit->id)->first();
                $qte->quantite += $request->input('quantite', 1);
                $panier->produits()->updateExistingPivot($produit->id, ['quantite' => $qte->quantite]);
            } else {
                $panier->produits()->attach($produit->id, ['quantite' => $quantite]);
            }

            $produit->decrementerQuantite($quantite);

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le Produit a été ajouté au panier',
                'data' => $panier->produits,
            ]);
        }
        
    }

    public function voirPanier() {
        //$user = Auth::user();
        $user =Client::where('user_id', Auth::user()->id)->first();
        $panier = Panier::where('user_id', $user->id)->first();

        if (!$panier) {
            return response()->json(['status_code' => 404, 'status_message' => 'Le panier est vide ou n\'existe pas.']);
        }

        $produitsPanier = $panier->produits()->withPivot('quantite')->get();

        $listeProduit = [];
        $totalPrix = 0;
        foreach ($produitsPanier as $produit) {
            $prixProduit = $produit->prix * $produit->pivot->quantite;
            $totalPrix += $prixProduit;
            $listeProduit[] = [
                'id' => $produit->id,
                'nom_produit' => $produit->nom_produit,
                'description' => $produit->description,
                'prix' => $produit->prix,
                'quantite' => $produit->pivot->quantite,
            ];
        }
    
        return response()->json([
            'status_code' => 200,
            'status_message' => 'Liste des produits dans le panier',
            'data' => $listeProduit,
            'montant'=>$totalPrix.' FCFA'
        ]);
    }
    
    public function retirerProduitPanier(Produit $produit) {
        $user = Auth::user();
    
        $panier = Panier::where('user_id', $user->id)->first();
    
        if (!$panier) {
            return response()->json(['status_code' => 404, 'status_message' => 'Le panier est vide ou n\'existe pas.']);
        }
    
        $produitExiste = $panier->produits()->where('produit_id', $produit->id)->exists();
    
        if (!$produitExiste) {
            return response()->json(['status_code' => 404, 'status_message' => 'Le produit n\'existe pas dans le panier.']);
        }
    
        // Récupérer la quantité du produit dans le panier avant de le retirer
        $quantiteDansPanier = $panier->produits()->where('produit_id', $produit->id)->first()->pivot->quantite;
    
        $panier->produits()->detach($produit->id);
    
        // Mettre à jour la quantité en stock du produit
        $produit->increment('quantite', $quantiteDansPanier);
    
        return response()->json([
            'status_code' => 200,
            'status_message' => 'Le Produit a été retiré du panier',
            'data' => $panier->produits,
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
