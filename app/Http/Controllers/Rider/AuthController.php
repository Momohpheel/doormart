<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Repository\Interface\Rider\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;

class AuthController extends Controller
{
    use Response;

    public $service;

    public function __construct(AuthRepositoryInterface $service)
    {
        $this->service = $service;
    }



    public function login(array $request)
    {

        try
        {

        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }

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

    public function updateProfile()
    {

    }

    public function setStatus()
    {

    }

    public function logout()
    {

    }

}
