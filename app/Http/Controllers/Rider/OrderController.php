<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Repository\Interface\Rider\OrderRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;

class OrderController extends Controller
{
    use Response;


    public $service;

    public function __construct(OrderRepositoryInterface $service)
    {
        $this->service = $service;
    }


    public function getAllRiderOrders(array $request)
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
