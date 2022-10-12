<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Repository\Interface\Rider\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Exceptions\ErrorException;

class AuthController extends Controller
{
    use Response;

    public $service;

    public function __construct(AuthRepositoryInterface $service)
    {
        $this->service = $service;
    }



    public function login(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'phone' => 'required|numeric',
                'password' => 'required'
            ]);

            $response = $this->service->login($validated);

            return $this->success("Rider logged in", $response, 200);

        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }

    }

    public function changePassword(Request $request)
    {
        try{
            $validated = $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed'
            ]);

            $response = $this->service->changePassword($validated);

            return $this->success("Rider password changed", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try{
            $validated = $request->validate([
                'phone' => 'required',
                'password' => 'required',
            ]);

            $response = $this->service->resetPassword($validated);

            return $this->success("Password Reset Successful", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }


    public function forgotPassword(Request $request)
    {
        try{
            $validated = $request->validate([
                'phone' => 'required',
            ]);

            $response = $this->service->forgotPassword($validated);

            return $this->success("Token sent to Reset Password", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try{
            $validated = $request->validate([
                'phone' => 'string|nullable',
                'name' => 'string|nullable',
                'email' => 'string|nullable',
            ]);

            $response = $this->service->updateProfile($validated);

            return $this->success("Profile Update Successful", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function getProfile(Request $request)
    {
        try{


            $response = $this->service->getProfile();

            return $this->success("Rider Profile", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function setStatus()
    {
        try{
            $response = $this->service->setStatus();

            return $this->success("Status saved", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    public function logout()
    {

    }

}
