<?php

namespace App\Http\Controllers\API;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletter;
use App\Notifications\EnvoieNewsletter;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newsletters = Newsletter::all();
        return response()->json([
            'status'=>200,
            'status_message'=>'Listes des utilisateurs inscrits au newsletter',
            'data'=>$newsletters
        ]);
    }

    public function inscriptionNewsletter(StoreNewsletter $request)
    {
        try {
            $newsletter = new Newsletter();
            $newsletter->email = $request->email;
            if($newsletter->save()){
                return response()->json([
                    'status'=>200,
                    'status_message'=>'Inscription au newsletter reussie',
                ]);
            }
        }catch(Exception $e){
            return response()->json($e);
        }
    }

    public function envoyerMail(Request $request){
        $newsletters = Newsletter::all();
        $emails = $newsletters->pluck('email');
        $messageContent = $request->input('letter');
        //dd($messageContent);
        //dd($emails);
        foreach($emails as $email) {
           $inscrit = Newsletter::where('email', $email)->first();
            if ($inscrit) {
                $inscrit->notify(new EnvoieNewsletter(['messageContent' => $messageContent]));
           }
        }
        // return response()->json([
        //     'status'=>200,
        //     'status_message'=>'Mail envoye',
        // ]);
        return view('envoieMail', compact('messageContent')); 
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
