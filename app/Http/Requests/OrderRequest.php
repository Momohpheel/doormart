<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class OrderRequest extends FormRequest
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
        //cart id, userid, status, delivery address, logitude, latitude, (rider longitude, latitude),, delivery time,  delivery/pickup
	    //amount, payment_from, delivery amount, rider_id, order_Accepted, dispatcher_confirmed, dispatcher_to_vendor, order_received,
        //order_arrived, order_received
	    //delivery instruction, delivery time,
        return [
            'cart_id' => ['required', 'exists:carts,id'],
            'delivery_address' => ['required', 'string'],
            'deliver_longitude' => ['required', 'string'],
            'deliver_latitude' => ['required', 'string'],
            'delivery_time' => ['required', 'string'],
            'delivery_type' => ['required', 'in:delivery,pickup'],
            'amount' => ['required', 'numeric'],
            'delivery_fee' => ['required', 'numeric'],
            'payment_from' => ['required', 'in:wallet,card'],
            'delivery_instruction' => ['string'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->json([

        ]);
    }
}
