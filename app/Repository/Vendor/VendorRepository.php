<?php
namespace App\Repository\Vendor;

use App\Models\Vendor;
use App\Models\WorkingDayTime;
use App\Repository\Interface\Vendor\VendorRepositoryInterface;
use App\Trait\Response;
use App\Trait\Token;
use Illuminate\Http\Request;


class VendorRepository implements VendorRepositoryInterface
{

    use Response, Token;

    public function updateProfile(array $request)
    {
        $vendor = Vendor::where('id', auth()->user()->id)->first();

        $vendor->address = $request['address'];
        $vendor->min_order = $request['minOrder'];
        $vendor->prepare_time = $request['prepareTime'];
        $vendor->account_number = $request['accountNumber'];
        $vendor->region_id = $request['region'];

        $vendor->save();

        return $vendor;

    }

    public function addWorkingDayTime(array $request)
    {



        $vendor = Vendor::where('id', auth()->user()->id)->first();

        for($i = 0; $i < count($request['time']); $i++){


            $time = WorkingDayTime::where('day', strtolower($request['time'][$i]['day']))->where('vendor_id', $vendor->id)->first();
            if (!$time){

                $time = new WorkingDayTime;

                $time->day = strtolower($request['time'][$i]['day']);
                $time->opening_time = $request['time'][$i]['opening'];
                $time->closing_time = $request['time'][$i]['closing'];
                $time->vendor_id = auth()->user()->id;

                $time->save();

            }else{

                $time->opening_time = $request['time'][$i]['opening'];
                $time->closing_time = $request['time'][$i]['closing'];
                $time->save();

            }

        }

        return $time;
    }


    public function getWorkingDayTime()
    {
        $time = WorkingDayTime::where('vendor_id', auth()->user()->id)->get();

        return $time;
    }

    public function removeWorkingDayTime(int $id)
    {
        $time = WorkingDayTime::where('vendor_id', auth()->user()->id)->where('id', $id)->get();

        $time->save();

        return true;
    }

    public function switchStatus()
    {
        $vendor = Vendor::where('id', auth()->user()->id)->first();

        if  ($vendor->status == 'inactive'){
            $vendor->status = "active";
            $vendor->save();
        }else{
            $vendor->status = "inactive";
            $vendor->save();
        }

        return $vendor->status;
    }



}

