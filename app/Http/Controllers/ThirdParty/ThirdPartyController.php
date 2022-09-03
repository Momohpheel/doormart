<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use App\Repository\ThirdParty\RiderService;
use App\Repository\ThirdParty\WalletService;
use Illuminate\Http\Request;
use App\Trait\Response;

class ThirdPartyController extends Controller
{
    use Response;


    public $service;
    public $wallet;

    public function __construct(RiderService $service, WalletService $wallet)
    {
        $this->service = $service;
        $this->wallet = $wallet;
    }


    /**
     * Rider Ops
     */

    public function addRider()
    {

    }

    public function updateRider()
    {

    }

    public function removeRider()
    {

    }

    public function getSingleRider()
    {

    }


    public function getAllRider()
    {

    }

    public function getAllRiderHistory()
    {

    }

    public function getSingleRiderHistory()
    {

    }






    /**
     *
     * Wallet Ops
     */


    public function addMoney()
    {

    }

    public function withdrawMoney()
    {

    }

    public function getWalletBalance()
    {

    }

}
