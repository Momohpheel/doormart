<?php
namespace App\Repository\Rider;

use App\Models\Rider;
use App\Repository\Interface\Rider\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Trait\Wallet;


class AuthRepository implements AuthRepositoryInterface
{

    use Response, Wallet;


    public function login(array $request)
    {

        try
        {


            $rider = Rider::where('phone', $request['phone'])->first();

            if ($rider){

                $check = Hash::check($request['password'], $rider->password);

                if ($check){

                            $token = $rider->createToken('authToken');
                            $rider['access_token'] =  $token->plainTextToken;
                            return $rider;


                }else{
                    return "Password or Email incorrect";
                }


            }else{
                return $this->error("Rider not found");

            }

        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }

    }

    public function changePassword(array $request)
    {

        $user = Rider::where('id', auth()->user()->id)->first();
        $check = Hash::check($request['old_password'], $user->password);

        if ($check) {
            $user->password = Hash::make($request['new_password']);
            $user->save();

            return $user;

        }else{
            return "Old Password is wrong";
        }
    }

    public function resetPassword()
    {
        $user = Rider::where('email', $request['email'])->first();
        $user->password = Hash::make($request['password']);
        $user->save();


        return $user;
    }


    public function forgotPassword()
    {
        $user = Rider::where('email', $request['email'])->first();

        if ($user) {
            //token to phone number
            return true;
        }else{
            return "User doesn't exist";
        }
    }

    public function updateProfile(array $request)
    {
        $rider = Rider::where('id', auth()->user()->id)->first();
        $rider->name = isset($request['name']) ? $request['name'] : $rider->name;
        $rider->email = isset($request['email']) ? $request['email'] : $rider->email;
        $rider->phone = isset($request['phone']) ? $request['phone'] : $rider->phone;


        $rider->save();

        return $rider;
    }

    public function setStatus()
    {
        $user = Rider::where('email', $request['email'])->first();

        if ($user) {

            $user->is_available = false;
            $user->save();

            return $user;
        }else{
            return "User doesn't exist";
        }
    }

    public function logout()
    {

    }


}

