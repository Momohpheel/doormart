<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        //name, content, images(4), price, category
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'image_1' => ['required', 'image', 'mimes:png,jpg', 'max:2049'],
            'image_2' => ['required', 'image', 'mimes:png,jpg', 'max:2049'],
            'image_3' => ['required', 'image', 'mimes:png,jpg', 'max:2049'],
            'image_4' => ['required', 'image', 'mimes:png,jpg', 'max:2049'],
            'price' => ['required', 'numeric', 'integer'],
            'product_category_id' => ['required', 'numeric', 'exists:product_categories,id'],
        ];
    }
}
