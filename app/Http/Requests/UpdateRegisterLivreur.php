<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateRegisterLivreur extends FormRequest
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
            'nom' => ['required', 'string', 'min:2','max:80', 'regex:/^[a-zA-Z]+$/'],
            'prenom' => ['required', 'string', 'min:2','max:80', 'regex:/^[a-zA-Z]+$/'],
            'matricule' => 'required|string|max:255',
            //'email' => 'required|string|email|max:255|unique:users',
            'email' => ['required','string', 'email','max:255',Rule::unique('users')->ignore(auth()->user()->id),'regex:/^[a-zA-Z_][\w\.-]*@[a-zA-Z][a-zA-Z.-]+\.[a-zA-Z]{2,4}$/'],
            'genre'=>'required|string|in:homme,femme',
            'numero_tel' => ['required', 'string', 'regex:/^(77|76|75|78|70|33)[0-9]{7}$/',Rule::unique('users')->ignore(auth()->user()->id)],
            // 'password' => 'required|string|min:8',
            'adresse' => ['required', 'string', 'regex:/^[a-zA-Z0-9 ]+$/','max:255'],
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
            'matricule.required'=>'Une matricule doit etre fourni',
            'email.required'=>'Un email doit etre fourni',
            'email.unique'=>'L\'adresse email existe deja',
            // 'password.required'=>'Un mot de passe doit etre fourni',
            'adresse.required'=>'Une adresse doit etre fourni',
            // 'password.min' => 'Le mot de passe doit avoir au moins 8 caractères',
            'genre.required' => 'Le genre doit être fourni',
            'genre.in' => 'Le genre doit être homme ou femme',
            'numero_tel.required' => 'Un numéro de téléphone doit être fourni',
            'numero_tel.regex' => 'Le numéro de téléphone doit être au format correct',
            'numero_tel.unique' => 'Un numéro de téléphone existe deja',
            'email.regex' => 'L\'adresse email doit être au format correct',
            'nom.min' => 'Le nom doit avoir au moins 2 caractères',
            'nom.max' => 'Le nom doit avoir au plus 255 caractères',
            'prenom.min' => 'Le prenom doit avoir au moins 2 caractères',
            'prenom.max' => 'Le prenom doit avoir au plus 80 caractères',
            'prenom.regex' => 'Le prenom doit contenir que des lettres',
            'adresse.regex' => 'L\'adresse doit contenir que des lettres et des chiffres',
            'adresse.max' => 'L\'adresse doit avoir au plus 255 caractères',
            'nom.regex' => 'Le nom doit contenir que des lettres',
        ];
    }
}
