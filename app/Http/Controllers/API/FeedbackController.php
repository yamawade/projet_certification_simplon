<?php

namespace App\Http\Controllers\API;

use App\Models\Feddback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedBack;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feddback::all();
        return response()->json([
            'status'=>200,
            'status_message'=>'Listes des feedbacks',
            'data'=>$feedbacks
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
    public function store(StoreFeedBack $request)
    {
        try{
            $feedback = new Feddback();
            $feedback->nom = $request->nom;
            $feedback->email = $request->email;
            $feedback->numero_tel = $request->numero_tel;
            $feedback->message = $request->message;
            if($feedback->save()){
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Feedback envoyé avec succès',
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
