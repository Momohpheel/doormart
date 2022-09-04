<?php

namespace App\Repository\ThirdParty;

use App\Models\Agency as ThirdParty;
use Illuminate\Support\Facades\Hash;

class AuthService{

    public function login(array $request)
    {

        $agency = ThirdParty::where('email', $request['email'])->first();

        if ($agency){

            $check = Hash::check($request['password'], $agency->password);

            if ($check){
                if ($agency->admin_verified) {
                    if ($agency->email_verified_at){
                        $token = $agency->createToken('authToken');
                        $agency['access_token'] =  $token->plainTextToken;
                        return $agency;
                    }else{
                        return "Your Email has not been verified";
                    }
                }else{
                    return "Admin hasn't verified you yet, plese be patient!";
                }

            }else{
                return "Password or Email incorrect";
            }


        }else{
            return $this->error("User not found");

        }



    }

    public function register()
    {
        $agency = new ThirdParty;
        $agency->name = $request['name'];
        $agency->email = $request['email'];
        $agency->password = Hash::make($request['password']);
        $agency->phone = $request['phone'];
        $agency->proof = $request['proof'];
        $agency->save();


        return $agency;
    }


    public function verifyAgencyEmail($token)
    {
        $check = EmailVerify::where("token", $token)->first();

        if ($check){

            $user = ThirdParty::where("email", $check->email)->first();
            $user->email_verified_at = now();
            $user->save();

            $check->delete();
        }


    }


    public function forgotPassword(array $request)
    {
        $user = ThirdParty::where('email', $request['email'])->first();

        if ($user) {
            $user->notify(new AgencyForgotPasswordEmail($user));

            return true;
        }else{
            return "User doesn't exist";
        }

    }

    public function resetPassword(array $request)
    {
        $user = ThirdParty::where('email', $request['email'])->first();
        $user->password = Hash::make($request['password']);
        $user->save();


        return $user;
    }

    public function checkOtp(array $request)
    {
        $token = DB::table('password_resets')
            ->where("email", "=", $request['email'])
            ->where("token", "=", $request['otp'])
            ->get();


        if ($token) {
            DB::table('password_resets')
            ->where("email", "=", $request['email'])
            ->where("token", "=", $request['otp'])
            ->delete();

            return true;
        }else{
            return false;
        }
    }



    public function changePassword(array $request)
    {
        $user = ThirdParty::where('id', auth()->user()->id)->first();
        $check = Hash::check($request['old_password'], $user->password);

        if ($check) {
            $user->password = Hash::make($request['new_password']);
            $user->save();

            return $user;

        }else{
            return "Old Password is wrong";
        }

    }


    public function deleteAccount()
    {
        $user = ThirdParty::where('email', $request['email'])->first();
        $user->delete();
    }

    public function updateProfile(array $request)
    {
        $agency = ThirdParty::where('id', auth()->user()->id)->first();
        $agency->name = isset($request['name']) ? $request['name'] : $agency->name;
        $agency->email = isset($request['email']) ? $request['email'] : $agency->email;
        $agency->phone = isset($request['phone']) ? $request['phone'] : $agency->phone;
        $agency->proof = isset($request['proof']) ? $request['proof'] : $agency->proof;

        $agency->save();

        return $agency;
    }
}
