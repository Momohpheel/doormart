<?php
namespace App\Repository\User;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Region;
use App\Repository\Interface\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;


class UserRepository implements UserRepositoryInterface
{

    use Response;

    public function getRegions(){
        $regions = Region::all();

        return $regions;
    }


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

    public function getVendors(array $request)
    {
        if (isset($request['region_id'])){
            $vendor = Vendor::with('categories')->where('region_id', $request['region_id'])->where('category_id',  $request['category_id'])->where('status', 'active')->where('admin_verified', true)->get();
        }else{
            $vendor = Vendor::with('categories')->where('category_id',  $request['category_id'])->where('status', 'active')->where('admin_verified', true)->get();
        }


        return $vendor;


    }

    public function getVendorProducts(array $request)
    {

        $products = Product::with('productCategory')->where('vendor_id', $request['vendor_id'])->get();

        return $products;
    }

    public function likeVendorProducts()
    {

    }

}

