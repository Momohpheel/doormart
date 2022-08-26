<?php
namespace App\Repository\User;

use App\Models\User;
use App\Repository\Interface\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;


class UserRepository implements UserRepositoryInterface
{

    use Response;

    public function updateProfile(array $request)
    {

        $user = User::find(auth()->user()->id);
        $user->email = isset($request['email']) ? $request['email'] : $user->email;
        $user->name = isset($request['name']) ? $request['name'] : $user->name;
        $user->region_id = isset($request['region']) ? $request['region'] : $user->region_id;
        $user->phone = isset($request['phone']) ? $request['phone'] : $user->phone;
        $user->address = isset($request['address']) ? $request['address'] : $user->address;
        $user->save();

        return $user;
    }

    public function getProfile()
    {
        $user = User::with('region')->find(auth()->user()->id);

        return $user;
    }

    public function getVendors()
    {
//based on region and category
    }

    public function getVendorProducts()
    {

    }

    public function likeVendorProducts()
    {

    }

}

