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

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }



    public function getAllRiderOrders(Request $request)
    {

        try
        {

        }catch(Exception $e){
            throw new ErrorException($e->getMessage());
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
