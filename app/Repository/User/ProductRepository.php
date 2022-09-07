<?php
namespace App\Repository\User;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Repository\Interface\User\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Trait\Response;
use App\Trait\Token;
use App\Models\UserWallet;

class ProductRepository implements ProductRepositoryInterface
{

    use Response, Token;

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
        $cart = Cart::with(['user','product', 'vendor'])->where('user_id', auth()->user()->id)->where('category_id', $id)->where('status', 'not paid')->get();

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

        $orderarr = array();
        $response = Http::withHeaders([
            'Authorization' => 'Bearer sk_test_f2a6d1d7f41d7d5e23c4221cf683a56b03ea3a81',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get("https://api.paystack.co/transaction/verify/".$request['txref']);

            //1.verify payment
            //2.chnge cart status to paid
            //3.add cart to order


            if ($response->successful()){

                $body = $response->json();

                if ($body['data']['status'] == "success"){

                    // $trxlog = new TransactionLog;


                    foreach ($request['cart'] as $cart){
                        $bag = Cart::where('id', $cart['id'])->first();
                        $bag->status = "paid";
                        $bag->save();

                        $order = new Order;
                        $order->orderId = $this->orderId();
                        $order->cart_id = $bag->id;
                        $order->user_id = $bag->user_id;
                        $order->vendor_id = $bag->vendor_id;
                        $order->delivery_address = $request['delivery_address'];
                        $order->delivery_latitude = $request['delivery_latitude'];
                        $order->delivery_longitude = $request['delivery_longitude'];
                        $order->delivery_type = $request['delivery_type'];
                        $order->payment_from = $request['payment_from'];
                        $order->delivery_instruction = $request['delivery_instruction'];
                        $order->status = 'paid';

                        $order->save();

                        array_push($orderarr, $order);
                    }



                    return $orderarr;
                   // $amount = $body['data']['amount']/100;

                }else{
                    throw new \Exception("Failed Transaction");
                }
            }else{
                throw new \Exception("Failed Transaction");
            }


    }

    public function payForOrderWallet(array $request)
    {

        $orderarr = array();
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
            //log wallet history
            $wallet->save();

            foreach ($request['cart'] as $cart){
                $bag = Cart::where('id', $cart['id'])->first();
                $bag->status = "paid";
                $bag->save();

                $order = new Order;
                $order->orderId = $this->orderId();
                $order->cart_id = $bag->id;
                $order->user_id = $bag->user_id;
                $order->vendor_id = $bag->vendor_id;
                $order->delivery_address = $request['delivery_address'];
                $order->delivery_latitude = $request['delivery_latitude'];
                $order->delivery_longitude = $request['delivery_longitude'];
                $order->delivery_type = $request['delivery_type'];
                $order->payment_from = 'wallet';
                $order->delivery_instruction = $request['delivery_instruction'];
                $order->status = 'paid';

                $order->save();

                array_push($orderarr, $order);
            }



            return $orderarr;

            // $details = [
            //     "amount" => $total,
            //     "wallet_amount" => $wallet->amount
            // ];

            //update order db

            //return $details;
        }
        else{
            throw new \Exception("Amount in wallet is insuffecient to complete order");
        }

    }
}



