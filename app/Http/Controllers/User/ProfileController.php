<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Repository\Interface\User\UserRepositoryInterface;
use App\Trait\Response;

class ProfileController extends Controller
{
    use Response;
    public $service;

    public function __construct(UserRepositoryInterface $service)
    {
        $this->service = $service;
    }

    public function updateProfile(UserUpdateRequest $request)
    {
        $validated = $request->validated();

        $response = $this->service->updateProfile($validated);

        return $this->success("Updated User Profile", $response, 200);
    }

    public function getProfile()
    {
        $response = $this->service->getProfile();

        return $this->success("User Profile", $response, 200);
    }

}
