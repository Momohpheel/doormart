<?php
namespace App\Repository\Rider;

use App\Models\Rider;
use App\Repository\Interface\Rider\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Trait\Wallet;


class AuthRepository implements AuthRepositoryInterface
{

    use Response, Wallet;


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

