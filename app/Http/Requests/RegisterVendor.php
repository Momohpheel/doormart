<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterVendor extends FormRequest
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
            'name' => ['required', 'string', 'unique:vendors,name'],
            'email' => ['required', 'string', 'email', 'unique:vendors,email'],
            'phone' => ['required', 'numeric'],
            'category_id' => ['required', 'string', 'exists:categories,id'],
             'proof' => ['required', 'file', 'mimes:png,jpg,pdf', 'max:2049'],
             //'proof' => ['required', 'string'],
            'password' => ['required', 'string'],
            'public_image' =>['required', 'image', 'mimes:png,jpg', 'max:2049'],
        ];
    }
}
