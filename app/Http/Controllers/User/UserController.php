<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\Interface\User\UserRepositoryInterface;
use App\Trait\Response;

class UserController extends Controller
{
    use Response;
    public $service;

    public function __construct(UserRepositoryInterface $service)
    {
        $this->service = $service;
    }


    public function getVendors()
    {
//based on region and category
    }

    public function getVendorProducts()
    {

    }

    public function likeVendorProducts()
    {

    }
}
