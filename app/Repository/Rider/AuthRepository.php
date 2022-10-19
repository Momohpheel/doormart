<?php
namespace App\Repository\Rider;

use App\Models\Rider;
use App\Repository\Interface\Rider\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Trait\Wallet;
use App\Trait\Token;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\RiderOTP as OTP;


class AuthRepository implements AuthRepositoryInterface
{

    use Response, Wallet, Token;


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
                    throw new \Exception("Password or Phone incorrect");
                }


            }else{
                throw new \Exception("Rider not found");

            }

        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    public function sendOtp(Rider $user)
    {

        $check = OTP::where("rider_id", $user->id)->first();

        if ($check){
            $otp = $check->otp;
            $sms = "Hello ".(string)$user->name.",\nYour OTP is ". (string)$otp;
            $this->sendSms("234".$user->phone, $sms, 'Duka');
        }else{
            $otp = new OTP;
            $otp->rider_id = $user->id;
            $otp->otp = $this->token();
            $otp->save();

            $sms = "Hello ".(string)$user->name.",\nYour OTP is ". (string)$otp->otp;
            $this->sendSms("234".$user->phone, $sms, 'Duka');
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
            throw new \Exception( "Old Password is wrong");
        }
    }

    public function checkForgotPasswordOtp(array $request)
    {

            $otp = OTP::where("otp", $request['otp'])->first();

            if ($otp){

                $user = Rider::where('id', $otp->rider_id)->first();

                $otp->delete();

                return $user;
            }else{
               throw new \Exception("Wrong OTP");
            }


    }

    public function sendSms($to, $sms, $from)
    {
        try{

            $api_key = "TLRqTF3e8xMhpV9puHwuoiN1nxYki49hcPTBtK32QP672OygbwVDEzHbep9yTe";

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post('https://api.ng.termii.com/api/sms/send?to='. $to .'&from=Duka&sms='. $sms .'&type=plain&channel=generic&api_key='.$api_key);

           \Log::info($response);
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


    public function resetPassword(array $request)
    {
        $user = Rider::where('phone', $request['phone'])->where('id', $request['rider_id'])->first();
        if ($user){

            $user->password = Hash::make($request['password']);
            $user->save();

            return $user;
        }else{
            throw new \Exception("User not found");
        }



    }


    public function forgotPassword(array $request)
    {
        $user = Rider::where('phone', $request['phone'])->first();

        if ($user) {
            $this->sendOtp($user);

            return true;
        }else{
           throw new \Exception("User doesn't exist");
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

    public function getProfile()
    {
        $rider = Rider::where('id', auth()->user()->id)->first();

        return $rider;
    }

    public function setStatus()
    {
        $user = Rider::where('id', auth()->user()->id)->first();

        if ($user) {

            $user->is_available = false;
            $user->save();

            return $user;
        }else{
            throw new \Exception("User doesn't exist");
        }
    }

    public function logout()
    {

    }


}

