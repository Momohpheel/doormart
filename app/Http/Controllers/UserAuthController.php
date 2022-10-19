<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Repository\Interface\User\AuthRepositoryInterface;
use App\Http\Requests\RegisterUser;
use App\Http\Requests\UserLogin;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Exceptions\ErrorException;

class UserAuthController extends Controller
{
    use Response;

    public $userService;

    public function __construct(AuthRepositoryInterface $userauthrepo)
    {
        $this->userService = $userauthrepo;
    }


    /**
     * Register User
     *
     * @return \Illuminate\Http\Response
     */
    public function registerUser(RegisterUser $request)
    {

        $validated = $request->validated();

        $response = $this->userService->register($validated);

        return $this->success("OTP has been sent", $response, 200);
    }

    /**
     * Log User
     *
     * @return \Illuminate\Http\Response
     */
    public function userLogin(UserLogin $request)
    {
        try{

            $validated = $request->validated();

            $response = $this->userService->login($validated);

            return $this->success("Login", $response, 200);

        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function verify(Request $request)
    {
        try{
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'otp' => 'required|string'
            ]);

            $response = $this->userService->verifyOtp($validated);

            return $this->success("User logged in", ['token' => $response], 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }

    }


    public function logout()
    {

    }

}
