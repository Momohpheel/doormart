<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Repository\Interface\Rider\OrderRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Models\ErrorException;

class OrderController extends Controller
{
    use Response;


    public $service;

    public function __construct(OrderRepositoryInterface $service)
    {
        $this->service = $service;
    }


    public function getAllRequestedOrders(Request $request)
    {
        try
        {
            $response = $this->service->getAllRequestedOrders();

            return $this->success("Open Orders", $response, 200);

        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }



    public function getAllCompletedtedOrders(Request $request)
    {
        try
        {
            $response = $this->service->getAllCompletedtedOrders();

            return $this->success("Completed Orders", $response, 200);

        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }



    public function getAllRiderOrders(Request $request)
    {

        try
        {
            $response = $this->service->getAllRiderOrders();

            return $this->success("Rider Orders", $response, 200);

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }

    }


    public function dashboard()
    {
        try
        {
            $response = $this->service->dashboard();

            return $this->success("Rider Dashboard", $response, 200);

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }


    public function getTransactionHistory()
    {

    }

    public function acceptOrder(string $orderId)
    {
        try
        {
            $response = $this->service->acceptOrder($orderId);

            return $this->success("Order ". $orderId ." Accepted", $response, 200);

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function receiveOrder(string $orderId)
    {
        try
        {
            $response = $this->service->receiveOrder($orderId);

            return $this->success("Order ". $orderId ." Received", $response, 200);

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function orderArrived(string $orderId)
    {
        try
        {
            $response = $this->service->orderArrived($orderId);

            return $this->success("Order ". $orderId ." Arrived", $response, 200);

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function userReceivedOrder(string $orderId)
    {
        try
        {
            $response = $this->service->userReceivedOrder($orderId);

            return $this->success("User received Order - ". $orderId, $response, 200);

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

}
