<?php

namespace App\Repository\Interface\Rider;

use App\Model\Rider;
use Illuminate\Http\Request;

interface AuthRepositoryInterface{

    public function login(array $request);
    public function changePassword(array $request);
    public function resetPassword(array $request);
    public function forgotPassword(array $request);
    public function setStatus();

    public function updateProfile(array $request);
    public function getProfile();
    public function logout();
}

