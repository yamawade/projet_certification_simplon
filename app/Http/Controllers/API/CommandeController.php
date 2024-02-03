<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Client;
use App\Models\Panier;
use App\Models\Livreur;
use App\Models\Produit;
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
        //dd($user->id);
        
        foreach ($request->input('panier') as $produit) {
            $produitExist = Produit::where('id', $produit['produit_id'])->where('quantite', '>', 0)->first();

            if (!$produitExist || $produit['nombre_produit'] > $produitExist->quantite) {
                return response()->json(['status' => 400, 'status_message' => 'Quantité de produit insuffisante.']);
            }

        }

        $commande = Commande::create([
            'date_commande' => now(),
            'client_id' => $user->id,
        ]);

        $commande_id = $commande->id;
        $montantTotal = 0;
        //dd($request->input('panier'));
        foreach ($request->input('panier') as $produit) {
            DetailCommande::create([
                'commande_id' => $commande->id,
                'produit_id' => $produit['produit_id'],
                'nombre_produit' => $produit['nombre_produit'],
                'montant' => $produit['montant'],
            ]);
            
            Produit::where('id', $produit['produit_id'])->decrement('quantite', $produit['nombre_produit']);
    
            // Ajouter le montant du produit au montant total
            $montantTotal += $produit['montant'];
        }
        
        return view('index', compact('montantTotal','commande_id'));
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
                'Numero' => $livreurs->numero_tel,
                'statut' => $livreurs->livreur->statut,
                'Adresse' => $livreurs->livreur->adresse,
                'Matricule'=> $livreurs->livreur->matricule
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
