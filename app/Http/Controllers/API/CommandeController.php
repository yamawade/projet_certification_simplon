<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Client;
use App\Models\Panier;
use App\Models\Livreur;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Commercant;
use Illuminate\Http\Request;
use App\Models\DetailCommande;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AffecterLivreur;
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
        
        $montantTotal+=$montantTotal*0.1;

        return response()->json([
            'status' => 200,
            'payment_url' => "http://localhost:8000/api/payment?montantTotal={$montantTotal}&commande_id={$commande_id}"
        ]);
        
        //return view('index', compact('montantTotal','commande_id'));
    }
    
    
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $commandes = Commande::with('client')->orderBy('created_at', 'desc')->get();
        try {
            $data = $commandes->map(function ($commande) {
                return [
                    'Id' => $commande->id,
                    'Adresse_Client' => $commande->client->adresse, 
                    'Date_commande' => $commande->created_at,
                    'Etat' => $commande->etat_commande,
                ];
            });
            //dd($data);

            return response()->json([
                'status' => 200,
                'status_message' => 'la liste des commandes',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return response($e)->json($e);
        }
    }

    public function ListerLivreur(){

        $livreurs = Livreur::all();

        $data = $livreurs->map(function ($livreurs) {
            return [
                'Id' => $livreurs->id,
                'Nom' => $livreurs->user->nom,
                'Prenom' => $livreurs->user->prenom,
                'Email' => $livreurs->user->email,
                'Numero' => $livreurs->user->numero_tel,
                'statut' => $livreurs->statut,
                'Adresse' => $livreurs->adresse,
                'Matricule'=> $livreurs->matricule
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
       // dd($request->livreur_id);
        $livreur = Livreur::where('id', $request->livreur_id)->first();
        if(!$livreur) {
            return response()->json([
                'status' => 404,
                'status_message' => 'livreur introuvable',
            ]);
        }
        //dd($livreur);
        if($livreur->statut === 'disponible') {
            $commande->update([
                'livreur_id' => $livreur->id,
                'etat_commande' => 'en_cours',
            ]);
            $livreur->statut = 'indisponible';
            $livreur->save();
            $livreur->user->notify(new AffecterLivreur($commande));
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


    public function listerCommandeClient(){
        $client=Client::where('user_id', Auth::user()->id)->first();
       // dd($client);
        $commandes = Commande::where('client_id', $client->id)->orderBy('created_at', 'desc')->get();
        //dd($commandes);
        $listeCommandes = [];
        foreach ($commandes as $commande) {
            $detailsCommande = DetailCommande::where('commande_id', $commande->id)->get();
            $nombreArticles = 0;
            $montantTotal = 0;

            foreach ($detailsCommande as $detail) {
                $nombreArticles += $detail->nombre_produit;
                $montantTotal += $detail->montant;
            }

            $listeCommandes[] = [
                'Id' => $commande->id,
                'date_commande' => $commande->created_at,
                'nombre_articles' => $nombreArticles,
                'etat_commande' => $commande->etat_commande,
                'montant_total' => $montantTotal,
            ];
        }

        return response()->json([
            'status' => 200,
            'status_message' => 'Liste des commandes du client',
            'data' => $listeCommandes
        ]);
    }


    public function listerVentesCommercant(){
        $commercant=Commercant::where('user_id', Auth::user()->id)->first();
        $produits=Produit::where('commercant_id', $commercant->id)->get();
        //dd($produits);

        foreach ($produits as $produit) {
            $detailsCommande=DetailCommande::where('produit_id', $produit->id)->get();

            foreach($detailsCommande as $vente){
                $listesVentes[] = [
                    'produit_id'=>$vente->produit_id,
                    'nombre_produit'=>$vente->nombre_produit,
                    'nom_produit'=>$vente->produit->nom_produit,
                    'montant'=>$vente->montant,
                    'date_commande'=>$vente->commande->created_at
                ];
    
            }
        }
        //dd($listesVentes);
        return response()->json([
            'status' => 200,
            'status_message' => 'la liste des ventes',
            'data' => $listesVentes
        ]);
    }


    public function listerCommandeAffecterLivreur(){
        $livreur=Livreur::where('user_id', Auth::user()->id)->first();
        $commandeAffecter = Commande::where('livreur_id', $livreur->id)->orderBy('created_at', 'desc')->get();
        //dd($commandeAffecter);
        foreach ($commandeAffecter as $commande) {
            $detailsCommande=DetailCommande::where('commande_id', $commande->id)->get();
            $nombreProduit = 0;
            //dd($detailsCommande);

            foreach ($detailsCommande as $detail) {
                $nombreProduit += $detail->nombre_produit;
            }

            $ListecommandeAffecter[] = [
                'Id' => $commande->id,
                'nom_client'=>$commande->client->user->nom,
                'numero_tel_client'=>$commande->client->user->numero_tel,
                'Adresse_Client' => $commande->client->adresse, 
                'Date_commande' => $commande->created_at,
                'Etat' => $commande->etat_commande,
                'nombre_produit' => $nombreProduit,
                //'montant' => $detailsCommande->montant
            ];
        }
        return response()->json([
            'status' => 200,
            'status_message' => 'la liste des commandes',
            'data' => $ListecommandeAffecter
        ]);
    }


    public function showCommandeClient(Commande $commande){
        $detailsCommande = DetailCommande::where('commande_id', $commande->id)->get();
        $montantTotal = 0;
        $listeArticles = [];
        foreach ($detailsCommande as $detail) {
            $produit = $detail->produit;
          //  dd($produit);
    
            $listeArticles[] = [
                'produit_id' => $produit->id,
                'nom_produit' => $produit->nom_produit,
                'image'=> $produit->image,
                'prix_produit' => $produit->prix,
                // 'quantite' => $detail->nombre_produit,
                // 'montant' => $detail->montant
            ];
        }
    
        return response()->json([
            'status' => 200,
            'status_message' => 'Détails de la commande',
            'data' => $listeArticles
        ]);

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
    public function show(Commande $commande)
    {
        //dd($commande);
        $client = $commande->client;
       // dd($client);

       $detailsCommande = $commande->detailsCommande()->with('produit.commercant')->get();
       //dd($detailsCommande);
       $data = [
            'commande_id' => $commande->id,
            'adresse_client' => $client->adresse,
            'nom_client' => $client->user->prenom.' '.$client->user->nom,
            'numero_tel' => $client->user->numero_tel,
            'date_commande' => $commande->created_at,
            'etat_commande' => $commande->etat_commande,
            'details_commande' => [],
        ];

        $vendeurs = [];

        foreach ($detailsCommande as $detail) {
            $produit = $detail->produit;
            $commercant = $produit->commercant;

            if (!in_array($commercant->adresse, $vendeurs)) {
            
                $vendeurs[] = $commercant->adresse;

                $data['details_commande'][] = [
                    'adresse_vendeur' => $commercant->adresse,
                ];
            }
        }

        // foreach ($detailsCommande as $detail) {
        //     $produit = $detail->produit;
        //     $commercant = $produit->commercant;

        //     $data['details_commande'][] = [
        //         // 'produit_id' => $produit->id,
        //         // 'nom_produit' => $produit->nom_produit,
        //         // 'prix_produit' => $produit->prix,
        //         'adresse_vendeur' => $commercant->adresse, 
        //         // 'quantite' => $detail->nombre_produit,
        //     ];
        // }
        
        //dd($data);
        return response()->json([
            'status' => 200,
            'status_message' => 'Détails de la commande',
            'data' => $data,
        ]);
        
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
