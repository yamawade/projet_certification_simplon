<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreLogin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreRegisterClient;
use App\Http\Requests\StoreRegisterLivreur;
use App\Http\Requests\UpdateRegisterClient;
use App\Http\Requests\StoreRegisterCommercant;
use App\Http\Requests\UpdateRegisterCommercant;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registerClient','registerCommercant']]);
    }

    public function registerClient(StoreRegisterClient $request){
        $user =User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'genre'=>$request->genre,
            'numero_tel'=>$request->numero_tel,
            'type' => 'client'
        ]);

        $client = $user->client()->create([
            'adresse'=>$request->adresse,
            'date_naiss'=>$request->date_naiss,
        ]);

        return response()->json([
            'status'=>200,
            'message' => 'Utilisateur créer avec succes',
            'user' => $user
        ]);
    }

    public function registerCommercant(StoreRegisterCommercant $request){
        $user =User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'numero_tel'=>$request->numero_tel,
            'genre'=>$request->genre,
            'type' => 'commercant'
        ]);

        $client = $user->commercant()->create([
            'ninea'=>$request->ninea,
            'adresse'=>$request->adresse,
            'nin'=>$request->nin,
            'date_naiss'=>$request->date_naiss,
        ]);

        return response()->json([
            'status'=>200,
            'message' => 'Utilisateur créer avec succes',
            'user' => $user
        ]);
    }

    public function login(StoreLogin $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        
        if (!$token) {
            return response()->json([
                'status'=>401,
                'message' => 'Connexion échouée',
            ]);
        }else{
            $user = Auth::user();
            if($user->type ==='commercant'){
                return response()->json([
                    'status'=>200,
                    'message' => 'Salut Commercant',
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
    
            }elseif($user->type ==='client'){
                return response()->json([
                    'status'=>200,
                    'message' => 'Salut Client',
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }elseif($user->type ==='livreur'){
                return response()->json([
                    'status'=>200,
                    'message' => 'Salut livreur',
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }else{
                return response()->json([
                    'status'=>200,
                    'message' => 'Salut Admin',
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }    
        }

    }

    public function registerLivreur(StoreRegisterLivreur $request){
        $user =User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'numero_tel'=>$request->numero_tel,
            'genre'=>$request->genre,
            'type' => 'livreur'
        ]);

        $client = $user->livreur()->create([
            'matricule' => $request->matricule,
            'adresse' => $request->adresse,
        ]);

        return response()->json([
            'status'=>200,
            'message' => 'Utilisateur créer avec succes',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status'=>200,
            'message' => 'Déconnexion réussi',
        ]);
    }


    public function modifierInfoClient(UpdateRegisterClient $request){
        //$client = Client::where('user_id', Auth::user()->id)->first();
        try {
            $user = Auth::user();
            // dd($user);
             $user->update([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'genre'=>$request->genre,
                'numero_tel'=>$request->numero_tel,
                'type' => 'client'
             ]);
     
             $client = $user->client()->update([
                'adresse'=>$request->adresse,
                'date_naiss'=>$request->date_naiss,
             ]);
     
             return response()->json([
                 'status'=>200,
                 'message' => 'Utilisateur mis à jour avec succes',
                 'user' => $user
             ]);
        }catch(Exception $e){
            return response()->json($e);
        }

    }

    public function modifierInfoCommercant(UpdateRegisterCommercant $request){
        try {
            $user = Auth::user();
        //dd($user);
        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'numero_tel'=>$request->numero_tel,
            'genre'=>$request->genre,
            'type' => 'commercant'
        ]);

        $commercant = $user->commercant()->update([
            'ninea'=>$request->ninea,
            'adresse'=>$request->adresse,
            'nin'=>$request->nin,
            'date_naiss'=>$request->date_naiss,
        ]);

        return response()->json([
            'status'=>200,
            'message' => 'Utilisateur mis à jour avec succes',
            'user' => $user
        ]);
    
        }catch(Exception $e){
            return response()->json($e);
        }
    }


    public function ListeUtilisateur(){
        $users = User::where('type', 'client')->orWhere('type', 'livreur')->orWhere('type', 'commercant')->get();
        return response()->json([
            'status'=>200,
            'users' => $users
        ]);
    }

    public function showClient(){
        $client = Auth::user()->client;
        return response()->json([
            'status'=>200,
            'client' => [
                'nom'=>$client->user->nom,
                'prenom'=>$client->user->prenom,
                'email'=>$client->user->email,
                'genre'=>$client->user->genre,
                'adresse'=>$client->adresse,
                'date_naiss'=>$client->date_naiss,
                'numero_tel'=>$client->user->numero_tel
            ]
        ]);
    }

    public function showCommercant(){
        $commercant = Auth::user()->commercant;
        return response()->json([
            'status'=>200,
            'commercant' => [
                'nom'=>$commercant->user->nom,
                'prenom'=>$commercant->user->prenom,
                'email'=>$commercant->user->email,
                'genre'=>$commercant->user->genre,
                'ninea'=>$commercant->ninea,
                'adresse'=>$commercant->adresse,
                'nin'=>$commercant->nin,
                'date_naiss'=>$commercant->date_naiss,
                'numero_tel'=>$commercant->user->numero_tel
            ]
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
