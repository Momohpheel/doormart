<?php

namespace App\Repository\Interface\User;

use App\Model\User;
use Illuminate\Http\Request;

interface AuthRepositoryInterface{

    public function register(array $request);
    public function login(array $request);
    public function verifyOtp(array $request);
}

