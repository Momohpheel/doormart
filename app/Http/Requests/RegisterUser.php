<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RegisterUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
            'name'  => 'required|string',
            'referral_code'  => 'string',
            'phone' => 'required|numeric|unique:users,phone'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        return response()->json([

        ]);
    }

}
