<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UserUpdateRequest extends FormRequest
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
            'email' => 'email|unique:users,email',
            'name'  => 'string',
            'region'  => 'string',
            'phone' => 'numeric|unique:users,phone',
            'address' => 'string'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->json([

        ]);
    }
}
