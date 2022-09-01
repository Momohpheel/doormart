<?php

namespace App\Repository\Interface\User;

use App\Model\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface{

    public function updateProfile(array $request);
    public function getProfile();
    public function getVendors(array $request);
    public function getVendorProducts(array $request);
    public function likeVendorProducts();


}

