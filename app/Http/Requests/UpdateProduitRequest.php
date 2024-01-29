<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProduitRequest extends FormRequest
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
            'nom_produit' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'sometimes|image',
            'quantite' => 'required|integer',
            'prix' => 'required|numeric|min:0|max:99999.99',
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
            'nom_produit.required'=>'Un nom doit etre fourni',
            'description.required'=>'Une description doit etre fourni',
            //'image.required' => 'Une image doit etre fourni',
            'quantite.required' => 'une quantite doit etre fourni',
            'prix.required' => 'Un prix doit etre fourni',
        ];
    }
}
