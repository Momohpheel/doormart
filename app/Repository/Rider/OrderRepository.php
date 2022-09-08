<?php
namespace App\Repository\Rider;

use App\Models\Rider;
use App\Repository\Interface\Rider\OrderRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Trait\Wallet;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;


class OrderRepository implements OrderRepositoryInterface
{

    use Response, Wallet;


    public function getAllRequestedOrders()
    {

        try
        {
            $orders = Order::with(['vendor', 'user'])->where('rider_id', null)->get();


        foreach ($orders as $order) {
            $cartss = array();
            $productss = array();
            $carts = json_decode($order->cart_id);
            $products = json_decode($order->product_id);


            foreach($carts as $cart){
                $cart = Cart::where('id', $cart)->first();
                array_push($cartss, $cart);
            }

            foreach($products as $product){
                $product = Product::where('id', $product)->first();
                array_push($productss, $product);
            }

            $order['carts'] = $cartss;
            $order['products'] = $productss;
        }


        return $orders;

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    public function getAllRiderOrders(array $request)
    {

        try
        {

        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }

    }

    public function getAllCompletedtedOrders(array $request)
    {

        try
        {

        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }

    }

    public function getOpenOrders()
    {

    }

    public function dashboard()
    {

    }


    public function getTransactionHistory()
    {

    }

    public function acceptOrder()
    {

    }

    public function declineOrder()
    {

    }




}

