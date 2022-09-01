<?php

namespace App\Repository\Interface\User;

use App\Model\User;
use Illuminate\Http\Request;

interface ProductRepositoryInterface{

    public function addToCart(array $request);
    public function getCart();
    public function removeSingleProduct();
    public function removeAll();
    public function orderProduct();

}

