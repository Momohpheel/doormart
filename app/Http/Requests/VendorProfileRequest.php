<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class VendorProfileRequest extends FormRequest
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
            'address' => ['string'],
            'minOrder' => ['numeric'],
            'prepareTime' => ['string'],
            'region' => ['integer', 'exists:regions,id'],
            'accountNumber' => ['string', 'min:10', 'max:10'],
            ''
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->json([

        ]);
    }
}
