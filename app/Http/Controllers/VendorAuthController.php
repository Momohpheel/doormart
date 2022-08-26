<?php

namespace App\Http\Controllers;

use App\Model\Vendor;
use App\Repository\Interface\Vendor\AuthRepositoryInterface;
use App\Http\Requests\RegisterVendor;
use App\Http\Requests\VendorLogin;
use Illuminate\Http\Request;
use App\Trait\Response;

class VendorAuthController extends Controller
{
    use Response;

    public $userService;

    public function __construct(AuthRepositoryInterface $vendorauthrepo)
    {
        $this->vendorService = $vendorauthrepo;
    }


    /**
     * Register User
     *
     * @return \Illuminate\Http\Response
     */
    public function registerVendor(RegisterVendor $request)
    {

        $validated = $request->validated();

        $validated['proof'] = cloudinary()->upload($request->file('proof')->getRealPath())->getSecurePath();
        $validated['public_image'] = cloudinary()->upload($request->file('public_image')->getRealPath())->getSecurePath();

        $response = $this->vendorService->register($validated);

        return $this->success("Registered Successfully - Email has been sent for your to verify", $response, 200);
    }

    /**
     * Log User
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorLogin(VendorLogin $request)
    {
        try{

            $validated = $request->validated();

            $response = $this->vendorService->login($validated);

            return $this->success("Success", $response, 200);

        }catch(Exception $e){
            return $this->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function verify($request)
    {
        $response = $this->vendorService->verifyVendorEmail($request);

        return $this->success("Email verified!",[], 200);
    }


    /**
     *Reset Password
     *
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|string'
        ]);
        $response = $this->vendorService->resetPassword($validated);

        return $this->success("Password Reset Successful!",$response, 200);
    }

    public function checkForgotPasswordOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string'
        ]);
        $response = $this->vendorService->checkOtp($validated);

        return $this->success("OTP!",$response, 200);
    }

    public function forgotPassword(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $response = $this->vendorService->forgotPassword($validated);

        return $this->success("Forgot Password Email sent!",$response, 200);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed'
        ]);

        $response = $this->vendorService->changePassword($validated);

        return $this->success("Change Password Successful!",$response, 200);
    }

    public function logout()
    {
        $response = $this->vendorService->logout();

        return $this->success("User logged out!",true, 200);
    }

}
