<?php
namespace App\Repository\Rider;

use App\Models\Rider;
use App\Repository\Interface\Rider\OrderRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Trait\Wallet;
use App\Models\Orders;


class OrderRepository implements OrderRepositoryInterface
{

    use Response, Wallet;


    public function getAllRequestedOrders(array $request)
    {

        try
        {

        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
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

