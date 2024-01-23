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
use App\Http\Requests\StoreRegisterCommercant;

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
            'type' => 'client'
        ]);

        $client = $user->client()->create([
            'adresse'=>$request->adresse
        ]);

        return response()->json([
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
            'type' => 'commercant'
        ]);

        $client = $user->commercant()->create([
            'ninea'=>$request->ninea,
            'adresse'=>$request->adresse,
            'nin'=>$request->nin
        ]);

        return response()->json([
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
                'message' => 'Connexion echouer',
            ], 401);
        }

        $user = Auth::user();
        if($user->type ==='commercant'){
            return response()->json([
                'message' => 'Salut Commercant',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

        }elseif($user->type ==='client'){
            return response()->json([
                'message' => 'Salut Client',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }elseif($user->type ==='livreur'){
            return response()->json([
                'message' => 'Salut livreur',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }else{
            return response()->json([
                'message' => 'Salut Admin',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
       
    }

    public function registerLivreur(StoreRegisterLivreur $request){
        $user =User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'livreur'
        ]);

        $client = $user->livreur()->create([
            'matricule' => $request->matricule
        ]);

        return response()->json([
            'message' => 'Utilisateur créer avec succes',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Déconnexion réussi',
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
