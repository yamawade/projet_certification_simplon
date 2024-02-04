<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFeedBack extends FormRequest
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
            'numero_tel' => ['required', 'string', 'regex:/^(77|76|75|78|33)[0-9]{7}$/','unique:feddbacks'],           
            'email' => 'required|string|email|max:255|unique:feddbacks',
            'message' => 'required|string|max:510',
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
            'message.required'=>'Un message doit etre fourni',
            'message.max'=>'Le message ne doit pas depasser 510 caractères',
            'email.required'=>'Un email doit etre fourni',
            'email.unique'=>'L\'adresse email existe deja',
            'numero_tel.required'=>'Un numero de telephone doit etre fourni',
            'numero_tel.regex'=>'Le numero de telephone doit etre au format correct',
            'numero_tel.unique'=>'Le numero de telephone existe deja',
            'nom.required'=>'Un nom doit etre fourni',
            'nom.max'=>'Le nom ne doit pas depasser 255 caractères',
        ];
    }
}
