<?php

namespace App\Repository\Interface\Vendor;

use App\Model\Vendor;
use Illuminate\Http\Request;

interface AuthRepositoryInterface{

    public function register(array $request);
    public function login(array $request);
    public function verifyVendorEmail(string $request);
    public function resetPassword(array $request);
    public function checkOtp(array $request);
    public function forgotPassword(array $request);
    public function changePassword(array $request);
    public function logout();

}

