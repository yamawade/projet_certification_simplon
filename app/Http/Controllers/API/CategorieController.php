<?php

namespace App\Http\Controllers\API;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategorie;
use App\Http\Requests\UpdateCategorie;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorie = Categorie::all();
        return response()->json([
            'status_code'=>200,
            'status_message'=>'Liste des categories',
            'data'=>$categorie
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
    public function store(StoreCategorie $request)
    {
       try {
            $categorie = new Categorie();
            $categorie->nom_categorie = $request->nom_categorie;
            if($categorie->save()){
                return response()->json([
                    'status_code'=>200,
                    'status_message'=>'La categorie a ete ajouté',
                    'data'=>$categorie
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
    public function update(UpdateCategorie $request, $id)
    {
        try {
            $categorie = Categorie::FindorFail($id);
            $categorie->nom_categorie=$request->nom_categorie;
            if($categorie->update()){
                return response()->json([
                    'status_code'=>200,
                    'status_message'=>'La categorie a ete modifier',
                    'data'=>$categorie
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
       try {
            $categorie = Categorie::FindorFail($id);
            if($categorie->delete()){
                return response()->json([
                    'status_code'=>200,
                    'status_message'=>'La categorie a ete supprimer',
                    'data'=>$categorie
                ]);
            }
       }catch(Exception $e){
        return response()->json($e);
    }

    }
}
