<?php

namespace App\Http\Controllers;

//use App\Models\Payment;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;
use App\Http\Services\PaytechService;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */

    public function index()
    {

        return view('index');
    }

    public function payment(Request $request)
    {
        # send info to api paytech

        //$validated = $request->validated();
        

        $IPN_URL = 'https://urltowebsite.com';


        // $amount = $validated['price'];
        // $commande = $validated['commande_id'];
        // $code = "47"; // This can be the product id
        $amount = $request->input('price');
        $commande = $request->input('commande_id');

        /*
            The success_url take two parameters, the first one can be product id and
            the one all data retrieved from the form
        */

        return redirect()->route('payment.index', [
            'montant' => $amount,
            'commande_id' => $commande,
        ]);

        $success_url = route('payment.success', ['code' => $code, 
        'data' =>[
            'amount'=>$request->price,
            'commande_id'=>$commande
        ],
        ]);
        $cancel_url = route('payment.index');
        $paymentService = new PaytechService(config('paytech.PAYTECH_API_KEY'),config('paytech.PAYTECH_SECRET_KEY'));


        $jsonResponse = $paymentService->setQuery([
            //'item_name' => $validated['product_name'],
            'commande_id' => $commande,
            'item_price' => $amount,
            'command_name' => "Paiement pour l'achat de via PayTech",
        ])
        ->setCustomeField([
            // 'item_id' => $validated['product_name'], // You can change it by the product id
            'time_command' => time(),
            'ip_user' => $_SERVER['REMOTE_ADDR'],
            'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ])
        ->setTestMode(true) // Change it to false if you are turning in production
        ->setCurrency("xof")
        ->setRefCommand(uniqid()) // You can add the invoice reference to save it to your paytech invoices
        ->setNotificationUrl([
            'ipn_url' => $IPN_URL . '/ipn', //only https
            'success_url' => $success_url,
            'cancel_url' =>  $cancel_url
        ])->send();


        if ($jsonResponse['success'] < 0) {
           // return back()->withErrors($jsonResponse['errors'][0]);
           return 'error';
        } elseif ($jsonResponse['success'] == 1) {
            # Redirection to Paytech website for completing checkout
            $token = $jsonResponse['token'];
            session(['token' => $token]);
            return Redirect::to($jsonResponse['redirect_url']);
        }
    }

    public function success(Request $request, $code)
    {
        // $validated = $_GET['data'];
        // $validated['token'] = session('token') ?? '';

        // // Call the save methods to save data to database using the Payment model

        // $payment = $this->savePayment($validated);

        // session()->forget('token');

        // return Redirect::to(route('payment.success.view', ['code' => $code]));

        $token = session('token') ?? '';
        $data = $request->query('data');

        if (!$token || !$data) {
            return redirect()->route('payment.index')->withErrors('Token ou données manquants');
        }
        // dd( $data );
        $data['token'] = $token;

        $payment = Payment::firstOrCreate([
            'token' => 1,
        ], [
            'amount' => $data['amount'],
            'commande' => $data['commande_id'],
            //'gestion_cycle_id' => $data['gestion_cycle_id'],
        ]);

        if (!$payment) {
            return redirect()->route('payment.index')->withErrors('Échec de la sauvegarde du paiement');
        }

        session()->forget('token');

        return view('succes');
    
    }

    // public function savePayment($data = [])
    // {

    //     # save payment database

    //     /* $payment = Payment::firstOrCreate([
    //         'token' => $data['token'],
    //     ], [
    //         'user_id' => auth()->user()->id,
    //         'product_name' => $data['product_name'],
    //         'amount' => $data['price'],
    //         'qty' => $data['qty']
    //     ]);

    //     if (!$payment) {
    //         # redirect to home page if payment not saved
    //         return $response = [
    //             'success' => false,
    //             'data' => $data
    //         ];
    //     } */


    //     # Redirect to Success page if payment success

    //     // $data['payment_id'] = $payment->id;

    //     /*
    //         You can continu to save onother records to database using Eloquant methods
    //         Exemple: Transaction::create($data);
    //     */

    //     return $response = [
    //         'success' => true, //
    //         'data' => $data
    //     ];
    // }

    public function paymentSuccessView(Request $request, $code)
    {
        // You can fetch data from db if you want to return the data to views

        /* $record = Payment::where([
            ['token', '=', $code],
            ['user_id', '=', auth()->user()->id]
        ])->first(); */

        return view('vendor.paytech.success'/* , compact('record') */)->with('success', 'Félicitation, Votre paiement est éffectué avec succès');
    }

    public function cancel()
    {
        # code...
    }
}