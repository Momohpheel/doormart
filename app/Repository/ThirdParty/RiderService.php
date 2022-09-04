<?php

namespace App\Repository\ThirdParty;

use App\Models\Rider;

class RiderService{

    public function addRider(array $request)
    {
        $rider = new Rider;
        $rider->name = $request['name'];
        $rider->email = $request['email'];
        $rider->password = Hash::make($request['password']);
        $rider->phone = $request['phone'];
        $rider->agency_id = auth()->user()->id;
        $rider->save();


        return $rider;
    }

    public function updateRider(array $request)
    {
        $rider = Rider::where('id', $request['rider_id'])->where('agency_id', auth()->user()->id)->first();
        $rider->name = isset($request['name']) ? $request['name'] : $rider->name;
        $rider->email = $request['email'] ? $request['email'] : $rider->email;
        $rider->phone = $request['phone'] ? $request['phone'] : $rider->phone;

        $rider->save();


        return $rider;
    }

    public function removeRider(int $id)
    {
        $rider = Rider::where('id', $id)->where('agency_id', auth()->user()->id)->first();

        $rider->delete();

    }

    public function getSingleRider(int $id)
    {
        $rider = Rider::where('id', $id)->where('agency_id', auth()->user()->id)->first();

        return $rider;

    }


    public function getAllRider()
    {
        $riders = Rider::where('agency_id', auth()->user()->id)->get();

        return $riders;
    }


    public function disableRider(int $id)
    {
        $riders = Rider::where('id', $id)->where('agency_id', auth()->user()->id)->first();

        $riders->is_active = false;
        $riders->save();

        return $riders;
    }


    public function getAllRiderHistory(int $id)
    {

    }

    public function getSingleRiderHistory(int $id)
    {

    }

}
