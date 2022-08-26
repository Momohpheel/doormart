<?php

namespace App\Repository\Interface\Vendor;


use Illuminate\Http\Request;


interface VendorRepositoryInterface{

    public function updateProfile(array $request);
    public function addWorkingDayTime(array $request);
    public function getWorkingDayTime();
    public function removeWorkingDayTime(int $id);
    public function switchStatus();
}

