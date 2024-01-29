<?php

namespace App\Http\Controllers\API;

use App\Models\Avis;
use App\Models\Client;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAvis;
use App\Http\Requests\UpdateAvis;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
