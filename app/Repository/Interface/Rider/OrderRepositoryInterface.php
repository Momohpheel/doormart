<?php

namespace App\Repository\Interface\Rider;

use App\Model\Rider;
use Illuminate\Http\Request;

interface OrderRepositoryInterface{

    public function getAllRequestedOrders();
    public function getAllRiderOrders();
    public function getAllCompletedtedOrders();
    public function getOpenOrders();
    public function dashboard();
    public function getTransactionHistory();
    public function acceptOrder(string $orderId);
    public function declineOrder();



}

