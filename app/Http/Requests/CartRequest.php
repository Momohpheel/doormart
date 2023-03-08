<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CartRequest extends FormRequest
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
            'product_id' => ['required', 'exists:products,id', 'numeric'],
            'category_id' => ['required', 'exists:categories,id', 'numeric'],
            'vendor_id' => ['required', 'exists:vendors,id', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'user_id' => ['required', 'exists:users,id', 'numeric']
        ];
    }
    public function failedValidation(Validator $validator)
    {
        return response()->json([

        ]);
    }
}
