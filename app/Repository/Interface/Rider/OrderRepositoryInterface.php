<?php

namespace App\Repository\Interface\Rider;

use App\Model\Rider;
use Illuminate\Http\Request;

interface OrderRepositoryInterface{

    public function getAllRequestedOrders();
    public function getAllRiderOrders(array $request);
    public function getAllCompletedtedOrders(array $request);
    public function getOpenOrders();
    public function dashboard();
    public function getTransactionHistory();
    public function acceptOrder();
    public function declineOrder();


}

