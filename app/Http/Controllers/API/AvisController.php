<?php

namespace App\Http\Controllers\API;

use App\Models\Avis;
use App\Models\Client;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\ProduitSignaler;
use App\Http\Requests\StoreAvis;
use App\Http\Requests\UpdateAvis;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProduitSignaler;

class AvisController extends Controller
{
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
    public function store(StoreAvis $request, Produit $produit)
    {
        try{
            $client = Client::where('user_id', Auth::user()->id)->first();
            $avis = new Avis();
            $avis->produit_id = $produit->id;
            $avis->client_id = $client->id;
            $avis->note = $request->note;
            $avis->commentaire = $request->commentaire;
            if($avis->save()){
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Le commentaire a ete ajouté',
                    'data'=>$avis
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProduitSignaler $produitSignaler)
    {
        $data =[
            'Id' => $produitSignaler->id,
            'Motif' => $produitSignaler->motif,
            'email_commercant' => $produitSignaler->produit->commercant->user->email,
            'nom_commercant' => $produitSignaler->produit->commercant->user->nom,
            'prenom_commercant' => $produitSignaler->produit->commercant->user->prenom,
            'numero_commercant' => $produitSignaler->produit->commercant->user->numero_tel,
            'Client' => $produitSignaler->client->user->prenom.' '.$produitSignaler->client->user->nom,
            'idProduit' => $produitSignaler->produit->id,
            'Produit' => $produitSignaler->produit->nom_produit,
            'Prix' => $produitSignaler->produit->prix,
            'Image' => $produitSignaler->produit->image,
            'Etat' => $produitSignaler->statut
        ];
        return response()->json([
            'status' => 200,
            'status_message' => 'Produit signale',
            'data' => $data
        ]);
        //dd($produitSignaler->produit->nom_produit);
    }

    public function bloquerProduitSignaler(ProduitSignaler $produitSignaler){
        if($produitSignaler->statut === 'pas_bloquer'){
            $produitSignaler->statut = 'bloquer';
            if($produitSignaler->update()){
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Le produit a ete bloquer',
                    'data'=>$produitSignaler
                ]);
            }
        }else{
            $produitSignaler->statut = 'pas_bloquer';
            if($produitSignaler->update()){
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Le produit a ete debloquer',
                    'data'=>$produitSignaler
                ]);
            }
        }
        
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
    public function update(UpdateAvis $request, Produit $produit)
    {
        try{
            $client = Client::where('user_id', Auth::user()->id)->first();
            //dd($client->id);
            $avis = Avis::where('produit_id', $produit->id)->where('client_id', $client->id)->first();
            if($avis->client_id == $client->id){
                $avis->note = $request->note;
                $avis->commentaire = $request->commentaire;
                if($avis->update()){
                    return response()->json([
                        'status'=>200,
                        'status_message'=>'Le commentaire a ete mis à jour',
                        'data'=>$avis
                    ]);
                }
            }else{
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Vous n\'etes pas l\'auteur du commentaire'
                ]);
            }  
        }catch(Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        try{
            $client = Client::where('user_id', Auth::user()->id)->first();
            $avis = Avis::where('produit_id', $produit->id)->where('client_id', $client->id)->first();
            if($avis->client_id == $client->id){
                if($avis->delete()){
                    return response()->json([
                        'status'=>200,
                        'status_message'=>'Le commentaire a ete supprimé',
                        'data'=>$avis
                    ]);
                }
            }else{
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Vous n\'etes pas l\'auteur du commentaire'
                ]);
            }  
        }catch(Exception $e){
            return response()->json($e);
        }
    }

    public function getAvisByProduit(Produit $produit) {
        $avis = Avis::where('produit_id', $produit->id)->with('client')->orderBy('created_at', 'desc')->get();
    
        $data = $avis->map(function ($avis) {
            return [
                'Id' => $avis->id,
                'Commentaires' => $avis->commentaire,
                'Note' => $avis->note,
                'Client' => $avis->client->user->prenom.' '.$avis->client->user->nom,
                'idProduit' => $avis->produit->id,
                'Produit' => $avis->produit->nom_produit,
                'Image' => $avis->produit->image,
                'Date' => $avis->created_at
            ];
        });
    
        return response()->json([
            'status' => 200,
            'status_message' => 'Liste des avis pour le produit',
            'data' => $data
        ]);
    }

    public function signalerProduit(Produit $produit,StoreProduitSignaler $request){
       try {
            $client = Client::where('user_id', Auth::user()->id)->first();
            $produitSignaler = new ProduitSignaler();
            $produitSignaler->produit_id = $produit->id;
            $produitSignaler->client_id = $client->id;
            $produitSignaler->motif = $request->motif;
            if($produitSignaler->save()){
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Le produit a ete signalé',
                    'data'=>$produitSignaler
                ]);
            }
       }catch(Exception $e){
            return response()->json($e);
        }
    }

    public function ListeProduitSignaler(){
        $produitSignaler = ProduitSignaler::with('client','produit')->get();
        $data = $produitSignaler->map(function ($produitSignaler) {
            return [
                'Id' => $produitSignaler->id,
                'Motif' => $produitSignaler->motif,
                'email_commercant' => $produitSignaler->produit->commercant->user->email,
                'Client' => $produitSignaler->client->user->prenom.' '.$produitSignaler->client->user->nom,
                'idProduit' => $produitSignaler->produit->id,
                'Produit' => $produitSignaler->produit->nom_produit,
                'Image' => $produitSignaler->produit->image,
                'Etat' => $produitSignaler->statut
            ];
        });
        return response()->json([
            'status' => 200,
            'status_message' => 'Liste des produit signale',
            'data' => $data
        ]);
    }
    
}
