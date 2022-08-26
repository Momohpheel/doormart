<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VendorProfileRequest;
use App\Http\Requests\WorkingDayRequest;
use App\Repository\Interface\Vendor\VendorRepositoryInterface;
use App\Trait\Response;

class ProfileController extends Controller
{
    use Response;

    public $service;

    public function __construct(VendorRepositoryInterface $service)
    {
        $this->service = $service;
    }


    public function dashboard()
    {

    }

    public function updateProfile(VendorProfileRequest $request)
    {
        $validated = $request->validated();

        $response = $this->service->updateProfile($validated);

        return $this->success("Vendor Profile Updated", $response, 200);
        //address, min order, opening time, tag, time to prepare, rating, region, accoun nummber
    }

    public function addWorkingDayTime(WorkingDayRequest $request)
    {
        $validated = $request->validated();

        $response = $this->service->addWorkingDayTime($validated);

        return $this->success("Added Working DayTime", $response, 200);

    }

    public function getWorkingDayTime()
    {

        $response = $this->service->getWorkingDayTime();

        return $this->success("Working DayTime", $response, 200);

    }

    public function removeWorkingDayTime($id)
    {
        $response = $this->service->removeWorkingDayTime($id);

        return $this->success("Removed Working DayTime", $response, 200);

    }

    public function switchStatus()
    {
        $response = $this->service->switchStatus();

        return $this->success("Status switched", $response, 200);
    }
}
