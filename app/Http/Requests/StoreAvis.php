<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreAvis extends FormRequest
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
            'commentaire' => 'required|string|max:510',
            'note' => 'required|integer|max:5|min:1',
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
            'commentaire.required'=>'Un commentaire doit etre fourni',
            'commentaire.max'=>'Le commentaire ne doit pas depasser 510 caractères',
            'note.required'=>'Une note doit etre fournie',
            'note.max'=>'La note ne doit pas depasser 5',
            'note.min'=>'La note doit etre superieure ou egale a 1',
        ];
    }
}
