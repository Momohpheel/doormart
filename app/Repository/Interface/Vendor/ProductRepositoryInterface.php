<?php

namespace App\Repository\Interface\Vendor;

use App\Model\Vendor;
use Illuminate\Http\Request;
use App\Models\Product;

interface ProductRepositoryInterface{

    public function addProduct(array $request);
    public function updateProduct(array $request, int $id);
    public function removeProduct(int $id);
    public function getProducts();
    public function getSingleProduct(int $id);

}

