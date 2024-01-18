<?php

namespace App\Http\Controllers\API;

use App\Models\Produit;
use App\Models\Commercant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;

class ProduitController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produit = Produit::all();
        return response()->json([
            'status_code'=>200,
            'status_message'=>'Liste des produits',
            'data'=>$produit
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
    public function store(StoreProduitRequest $request)
    {
        try {
            $produit = new Produit();
            $commercant = Commercant::where('user_id', Auth::user()->id)->first();
            //$user = Auth::user();
            //dd($user->commercant->id);
            $produit->nom_produit = $request->nom_produit;
            $produit->quantite = $request->quantite;
            $produit->prix = $request->prix;
            $produit->description = $request->description;
            $produit->commercant_id = $commercant->id;
            $produit->categorie_id = $request->categorie_id;
            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('images'), $filename);
    
                $produit->image = $filename;
            }

            if($produit->save()){
                return response()->json([
                    'status_code'=>200,
                    'status_message'=>'Le produit a ete ajouté',
                    'data'=>$produit
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Produit $produit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduitRequest $request, Produit $produit)
    {
        try {
            $commercant = Commercant::where('user_id', Auth::user()->id)->first();
            //dd($commercant->id);
            //$user = Auth::user();
            //dd($user->commercant->id);
            if($produit->commercant_id == $commercant->id){
                $produit->nom_produit = $request->nom_produit;
                $produit->quantite = $request->quantite;
                $produit->prix = $request->prix;
                $produit->description = $request->description;
                $produit->categorie_id = $request->categorie_id;
                if ($request->file('image')) {
                    $file = $request->file('image');
                    $filename = date('YmdHi') . $file->getClientOriginalName();
                    $file->move(public_path('images'), $filename);
        
                    $produit->image = $filename;
                }

                if($produit->update()){
                    return response()->json([
                        'status_code'=>200,
                        'status_message'=>'Le produit a ete modifié',
                        'data'=>$produit
                    ]);
                }
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
        try {
            $commercant = Commercant::where('user_id', Auth::user()->id)->first();
            if($produit->commercant_id == $commercant->id){
                if($produit->delete()){
                    return response()->json([
                        'status_code'=>200,
                        'status_message'=>'La produit a ete supprimer',
                        'data'=>$produit
                    ]);
                }
            }
            
       }catch(Exception $e){
        return response()->json($e);
    }
    }
}
