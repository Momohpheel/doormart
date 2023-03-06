<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateProductRequest extends FormRequest
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
            'name' => ['string'],
            'description' => ['string'],
            'image_1' => ['image', 'mimes:png,jpg', 'max:2049'],
            'image_2' => ['image', 'mimes:png,jpg', 'max:2049'],
            'image_3' => ['image', 'mimes:png,jpg', 'max:2049'],
            'image_4' => ['image', 'mimes:png,jpg', 'max:2049'],
            'price' => ['numeric', 'integer'],
            'product_category_id' => ['numeric', 'exists:product_categories,id'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->json([

        ]);
    }
}
