<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use App\Repository\ThirdParty\AuthService;
use Illuminate\Http\Request;
use App\Trait\Response;

class AuthController extends Controller
{
    use Response;

    public $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }


    public function login()
    {

    }

    public function register()
    {

    }

    public function changePassword()
    {

    }

    public function resetPassword()
    {

    }


    public function forgotPassword()
    {

    }

    public function deleteAccount()
    {

    }

    public function updateProfile()
    {

    }

}
