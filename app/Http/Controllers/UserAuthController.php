<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Repository\Interface\User\AuthRepositoryInterface;
use App\Http\Requests\RegisterUser;
use App\Http\Requests\UserLogin;
use App\Models\Category;
use App\Models\ProductCategory;
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
        try{

            $validated = $request->validated();

            $response = $this->userService->register($validated);

            return $this->success("OTP has been sent", $response, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }
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

    public function getCategories(Request $request)
    {
        try{

           $categories = Category::all();

           return $this->success("Categories", $categories, 200);

        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }

    }

    public function getFoodCategories(Request $request)
    {
        try{

           $category = Category::where('name', 'Food')->first();
           $categories = ProductCategory::where('category_id', $category->id)->get();

           return $this->success("Product Categories", $categories, 200);

        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }

    }


    public function logout()
    {

    }

    public function resendOtp(Request $request)
    {
        try{
            $validated = $request->validate([
                'user_id' => 'required|integer',
            ]);

            $response = $this->userService->resendOtp($validated);

            return $this->success("OTP sent", null, 200);
        }catch(\Exception $e){
            throw new ErrorException($e->getMessage());
        }

    }

}
