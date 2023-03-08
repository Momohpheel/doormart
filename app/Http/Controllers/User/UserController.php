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


    public function getRegions()
    {
        $response = $this->service->getRegions();

        return $this->success("Regions", $response, 200);
    }

    /**
     *
     * get vendors based on region and category
     */
    public function getVendors(Request $request)
    {
        $validated = $request->validate([
            "region_id" => "numeric|nullable",
            "category_id" => "required|numeric"
        ]);

        $response = $this->service->getVendors($validated);

        return $this->success("Vendors", $response, 200);

    }


    public function getVendorProducts(Request $request)
    {
        $validated = $request->validate([
            "vendor_id" => "numeric|required",
        ]);

        $response = $this->service->getVendorProducts($validated);

        return $this->success("Vendor Product", $response, 200);
    }

    public function likeVendorProducts()
    {

    }
}
