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

            $allOrders = $this->getOrders($orders);

            return $allOrders;

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function getAllRiderOrders()
    {
        try
        {
            $orders = Order::with(['vendor', 'user'])->where('rider_id', auth()->user()->id)->where('order_status', 'ongoing')->get();

            $ongoing = $this->getOrders($orders);

            return $ongoing;

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }


    }

    public function getAllCompletedtedOrders()
    {
        try
        {
            $orders = Order::with(['vendor', 'user'])->where('rider_id', auth()->user()->id)->where('order_status', 'completed')->get();

            $completed = $this->getOrders($orders);

            return $completed;

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }


    }

    public function getSingleOrder($orderId)
    {
        try
        {
            $order = Order::with(['vendor', 'user'])->where('orderId', $orderId)->where('rider_id', auth()->user()->id)->where('order_status', 'completed')->first();

            $single = $this->getOrders($order);

            return $single;

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function getOrders($orders)
    {
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

    }

    public function dashboard()
    {
        $completed = $this->getAllCompletedtedOrders();

        $orders = Order::with(['vendor', 'user'])->where('rider_id', auth()->user()->id)->where('order_status', 'completed')->latest()->limit(4)->get();

        $data =  [
            "delivered_orders" => count($completed),
            "distance_covered" => 0,
            "recent_orders" => $orders
        ];


        return $data;
    }


    public function getTransactionHistory()
    {

    }

    public function acceptOrder(string $id)
    {
        try{
            $order = Order::where('orderId', $id)->first();

            if ($order->rider_id == null && $order->order_status == 'pending'){
                $order->rider_id = auth()->user()->id;
                $order->order_status = 'ongoing';
                $order->rider_accepted_order = true;
                $order->save();

                return $order;
            }

            throw new \Exception("Order has been assigned to another");

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    public function receiveOrder(string $id)
    {
        try{
            $order = Order::where('orderId', $id)->first();


            if (($order->rider_id == auth()->user()->id) && ($order->order_status == 'ongoing')){

                $order->rider_received_order = true;
                $order->save();

                return $order;
            }

            throw new \Exception("Something's wrong somewhere");

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    public function orderArrived(string $id)
    {
        try{
            $order = Order::where('orderId', $id)->first();


            if (($order->rider_id == auth()->user()->id) && ($order->order_status == 'ongoing')){
                $order->order_arrived = true;
                $order->save();
                return $order;
            }

            throw new \Exception("Something's wrong somewhere");


        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    public function userReceivedOrder(string $id)
    {
        try{
            $order = Order::where('orderId', $id)->first();


            if (($order->rider_id == auth()->user()->id) && ($order->order_status == 'ongoing')){

                $order->user_received_order = true;
                $order->order_status = 'completed';
                $order->save();

                return $order;
            }

            throw new \Exception("Something's wrong somewhere");


        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }

    }


}

