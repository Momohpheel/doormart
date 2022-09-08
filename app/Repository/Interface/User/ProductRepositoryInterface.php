<?php

namespace App\Repository\Interface\User;

use App\Model\User;
use Illuminate\Http\Request;

interface ProductRepositoryInterface{

    public function addToCart(array $request);
    public function getCart(int $category_id);
    public function removeSingleProduct(int $id);
    public function removeAll(int $category_id);
    public function setDeliveryFee(array $request);
    public function incrementQuantity(int $id);
    public function decrementQuantity(int $id);
    public function verifyPayment(array $request);
    public function payForOrderWallet(array $request);
    public function payForOrder(array $request);
    public function getSingleOrder(int $id);
    public function getOrders();

}

