<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRegisterCommercant extends FormRequest
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
            'date_naiss' => 'required|date|before: -18 years',
            'genre'=>'required|string|in:homme,femme',
            'numero_tel' => ['required', 'string', 'regex:/^(77|76|75|78|33)[0-9]{7}$/',Rule::unique('users')->ignore(auth()->user()->id),'regex:/^[a-zA-Z_][\w\.-]*@[a-zA-Z][a-zA-Z.-]+\.[a-zA-Z]{2,4}$/'],           
            'nin' => 'required|string|max:255',
            'email' => ['required','string', 'email','max:255',Rule::unique('users')->ignore(auth()->user()->id)],
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
            'numero_tel.required'=>'Un numero de telephone doit etre fourni',
            'nin.required'=>'Un NIN doit etre fourni',
            'email.required'=>'Un email doit etre fourni',
            'email.unique'=>'L\'adresse email existe deja',
            'date_naiss.required' => 'Une date de naissance doit être fournie',
            'date_naiss.date' => 'La date de naissance doit être une date valide',
            'date_naiss.before' => 'Votre age doit être au moins 18 ans',
            'genre.required' => 'Le genre doit être fourni',
            'genre.in' => 'Le genre doit être homme ou femme',
            'numero_tel.required' => 'Un numéro de téléphone doit être fourni',
            'numero_tel.unique' => 'Un numéro de téléphone existe deja',
            'numero_tel.regex' => 'Le numéro de téléphone doit être au format correct',
            'email.regex' => 'L\'adresse email doit être au format correct',
            'nom.min' => 'Le nom doit avoir au moins 2 caractères',
            'nom.max' => 'Le nom doit avoir au plus 255 caractères',
            'prenom.min' => 'Le prenom doit avoir au moins 2 caractères',
            'prenom.max' => 'Le prenom doit avoir au plus 255 caractères',
        ];
    }
}
