<?php
namespace App\Repository\User;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Cart;
use App\Repository\Interface\User\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Models\UserWallet;

class ProductRepository implements ProductRepositoryInterface
{

    use Response;

    public function addToCart(array $request)
    {
        $product = Product::where('id', $request['product_id'])->first();

        $carts = Cart::where('user_id', auth()->user()->id)->get();

        foreach($carts as $cart){
            if ($cart->vendor_id != (int)$request['vendor_id']){
                $cart->delete();
            }
        }

        $cart = new Cart;
        $cart->product_id = (int)$request['product_id'];
        $cart->vendor_id = (int)$request['vendor_id'];
        $cart->category_id = (int)$request['category_id'];
        $cart->user_id = auth()->user()->id;
        $cart->quantity = $request['quantity'];
        $cart->price = (int)$product->price * (int)$request['quantity'];
        $cart->status = 'not paid';
        $cart->save();

        return $cart;
    }

    public function getCart(int $id)
    {
        $items = array();
        $price = 0;
        $deliveryfee = 0;
        $cart = Cart::with(['user','product', 'vendor'])->where('user_id', auth()->user()->id)->where('category_id', $id)->get();

        array_push($items, $cart);

        foreach ($cart as $item){
            $price += $item->price;
            $deliveryfee = $item->delivery_fee;
        }

        $pricecalculate = [
            "total_price" => $price,
            "delivery_fee" => $deliveryfee,
            "total" => $price + $deliveryfee
        ];

        array_push($items, $pricecalculate);
       //array_push($cart, $pricecalculate);


        return $items;
    }

    public function removeSingleProduct(int $id)
    {
        $cart = Cart::with(['user','product', 'vendor'])->where('id', $id)->where('user_id', auth()->user()->id)->first();

        if (!$cart){
            return true;
        }

        $cart->delete();

        return true;
    }


    public function removeAll(int $category_id)
    {
        $carts = Cart::with(['user','product', 'vendor'])->where('user_id', auth()->user()->id)->get();

        if (!$carts){
            return true;
        }

        foreach($carts as $cart){
            if ($cart->category_id == $category_id){
                $cart->delete();
            }

        }


        return true;
    }

    public function incrementQuantity(int $id)
    {
        $cart = Cart::with('product')->where('id', $id)->where('user_id', auth()->user()->id)->first();
        $cart->quantity++;
        $cart->price = $cart->price + (int)$cart->product->price;
        $cart->save();

        return $cart;
    }

    public function decrementQuantity(int $id)
    {
        $cart = Cart::with('product')->where('id', $id)->where('user_id', auth()->user()->id)->first();
        $cart->quantity--;
        $cart->price = $cart->price - (int)$cart->product->price;
        $cart->save();

        return $cart;
    }


    public function setDeliveryFee(array $request)
    {

        $vendor = Vendor::where('id', $request['vendor_id'])->first();

        $distance = $this->twopoints_on_earth($vendor->latitude, $vendor->logitude, $request['latitude'], $request['logitude']);

        $cart = Cart::where('id', $request['cart_id'])->first();

        if ($distance > 1 && $distance < 5) {
            $cart->delivery_fee = 300;
        }else if ($distance > 5 && $distance < 10) {
            $cart->delivery_fee = 800;
        }else if ($distance > 10) {
            $cart->delivery_fee = 1000;
        }

        $cart->save();

        return $cart;

    }



    function twopoints_on_earth($latitudeFrom, $longitudeFrom, $latitudeTo,  $longitudeTo)
    {
        $long1 = deg2rad($longitudeFrom);
        $long2 = deg2rad($longitudeTo);
        $lat1 = deg2rad($latitudeFrom);
        $lat2 = deg2rad($latitudeTo);

        //Haversine Formula
        $dlong = $long2 - $long1;
        $dlati = $lat2 - $lat1;

        $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2);

        $res = 2 * asin(sqrt($val));

        $radius = 6371000;

        $distance = $res*$radius;

        return $distance/1000; //km
    }


    public function payForOrder(array $request)
    {
        $amount = 0;
        $vId = 0;
        $deliveryfee = 0;
        foreach ($request['cart'] as $cart){
            $order = Cart::where('id', $cart['id'])->first();

            $amount += $order->price;
            $vId = $order->vendor_id;

        }

        $vendor = Vendor::where('id', $vId)->first();

        $distance = $this->twopoints_on_earth($vendor->latitude, $vendor->logitude, $request['delivery_latitude'], $request['delivery_longitude']);



        if ($distance > 1 && $distance < 5) {
            $deliveryfee = 300;
        }else if ($distance > 5 && $distance < 10) {
            $deliveryfee = 800;
        }else if ($distance > 10) {
            $deliveryfee = 1000;
        }

        $payment_details = [
            "amount" => $amount + $deliveryfee,
            "email" => auth()->user()->email,
            "tx_ref" => "duka_".rand(),
            "public_key" => config('app.public_key')
        ];
        //amount, tx_ref, public key
        return  $payment_details;
    }


    public function verifyPayment(array $request)
    {
        //tx_ref


    }

    public function payForOrderWallet(array $request)
    {
        $wallet = UserWallet::where('user_id', auth()->user()->id)->first();
        $amount = 0;
        $vId = 0;
        $deliveryfee = 0;
        foreach ($request['cart'] as $cart){
            $order = Cart::where('id', $cart['id'])->first();

            $amount += $order->price;
            $vId = $order->vendor_id;

        }

        $vendor = Vendor::where('id', $vId)->first();

        $distance = $this->twopoints_on_earth($vendor->latitude, $vendor->logitude, $request['delivery_latitude'], $request['delivery_longitude']);



        if ($distance > 1 && $distance < 5) {
            $deliveryfee = 300;
        }else if ($distance > 5 && $distance < 10) {
            $deliveryfee = 800;
        }else if ($distance > 10) {
            $deliveryfee = 1000;
        }

        $total = $amount + $deliveryfee;

        if ((int)$wallet->amount > (int)$total) {
            $wallet->amount = $wallet->amount - $total;
            $wallet->save();


            $details = [
                "amount" => $total,
                "wallet_amount" => $wallet->amount
            ];

            //update order db

            return $details;
        }
        else{
            throw new \Exception("Amount in wallet is insuffecient to complete order");
        }

    }
}



