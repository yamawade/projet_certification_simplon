<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRegisterClient extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'date_naiss' => 'required|date|before: -18 years',
            'password' => 'required|string|min:8',
            'genre'=>'required|string|in:homme,femme',
            'numero_tel' => ['required', 'string', 'regex:/^(77|76|75|78|33)[0-9]{7}$/','unique:users'],
        ];
    }
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
            'success'=>false,
            'status_code'=>422,
            'error'=>true,
            'message'=>'Erreur de validation',
            'errorsList'=> $validator->errors()
        ]));
    }

    public function messages(){
        return[
            'nom.required'=>'Un nom doit etre fourni',
            'prenom.required'=>'Un prenom doit etre fourni',
            'adresse.required'=>'Une adresse doit etre fourni',
            'email.required'=>'Un email doit etre fourni',
            'email.unique'=>'L\'adresse email existe deja',
            'password.required'=>'Un mot de passe doit etre fourni',
            'genre.required' => 'Le genre doit être fourni',
            'genre.in' => 'Le genre doit être homme ou femme',
            'numero_tel.required' => 'Un numéro de téléphone doit être fourni',
            'numero_tel.regex' => 'Le numéro de téléphone doit être au format correct',
            'numero_tel.unique' => 'Un numéro de téléphone existe deja',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères',
            'date_naiss.required' => 'Une date de naissance doit être fournie',
            'date_naiss.date' => 'La date de naissance doit être une date valide',
            'date_naiss.before' => 'Votre age doit être au moins 18 ans',
        ];
    }
}
