<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;


class RegisterRequest extends FormRequest {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'email'    =>  ['required','string','email','max:255',],//ユニークにしたい「Rule::unique('users')」
            'password' => 'confirmed',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $res = response()->json([
            'status' => Response::HTTP_BAD_REQUEST,
            'errors' => $validator->errors(),
        ], Response::HTTP_BAD_REQUEST);
        throw new HttpResponseException($res);
    }

}