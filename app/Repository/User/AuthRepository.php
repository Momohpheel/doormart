<?php
namespace App\Repository\User;

use App\Models\User;
use App\Models\OTP;
use App\Models\UserWallet;
use App\Models\ReferralHistory;
use App\Repository\Interface\User\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use Illuminate\Support\Facades\Http;
use App\Trait\Token;
use App\Trait\Wallet;
use Illuminate\Support\Facades\Mail;
//use App\Mail\RegisterMail;
use App\Notifications\RegisterMail;
use App\Notifications\LoginMail;
use App\Notifications\ResendOtp;


class AuthRepository implements AuthRepositoryInterface
{

    use Response, Token, Wallet;

    public function register(array $request)
    {
        try
        {
            if (isset($request['referral_code'])){
                $refUser = User::where('referral_code', $request['referral_code'])->first();

                if ($refUser){
                    $check = $this->createWallet($refUser->id);
                    $wallet = UserWallet::where('user_id', $refUser->id)->first();
                    $wallet->amount = $wallet->amount + 10;
                    $wallet->save();
                }

            }

            $user = User::create([
                'name'  => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'referral_code' => $this->referralCode(),
            ]);

            $this->sendOtp($user);


            //Mail::to($user->email)->send(new RegisterMail($user));

            $this->createWallet($user->id);

            if (isset($request['referral_code'])){
                //referral history
                $this->storeReferal($user->id, $refUser->id);
            }

            return $user;

        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }


    }



    public function login(array $request)
    {

        try
        {
            $user = User::where('phone', $request['phone'])->first();

            if ($user){
                $this->loginsendOtp($user);

                return $user;
            }

            throw new \Exception("User not found");


        }catch(Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    public function loginsendOtp(User $user)
    {
        $check = OTP::where("user_id", $user->id)->first();

        if ($check){
            $otp = $check->otp;
            $sms = "Hello ".(string)$user->name.", Welcome to Duka! Your OTP is ". (string)$otp. ", please eneter it in 15 minutes. Do not share with anyone!";
            $user->notify(new LoginMail($user, $otp));
            $this->sendSms("234".$user->phone, $sms, 'Duka');
        }else{
            $otp = new OTP;
            $otp->user_id = $user->id;
            $otp->otp = $this->token();
            $otp->save();

            $sms = "Hello ".(string)$user->name.", Welcome to Duka! Your OTP is ". (string)$otp->otp. ", please eneter it in 15 minutes. Do not share with anyone!";
            $user->notify(new LoginMail($user, $otp->otp));
            $this->sendSms("234".$user->phone, $sms, 'Duka');
        }


    }

    public function sendOtp(User $user)
    {
        $check = OTP::where("user_id", $user->id)->first();

        if ($check){
            $otp = $check->otp;
            $sms = "Hello ".(string)$user->name.", Welcome to Duka! Your OTP is ". (string)$otp. ", please eneter it in 15 minutes. Do not share with anyone!";
            $user->notify(new RegisterMail($user, $otp));
            $this->sendSms("234".$user->phone, $sms, 'Duka');
        }else{
            $otp = new OTP;
            $otp->user_id = $user->id;
            $otp->otp = $this->token();
            $otp->save();

            $sms = "Hello ".(string)$user->name.", Welcome to Duka! Your OTP is ". (string)$otp->otp. ", please eneter it in 15 minutes. Do not share with anyone!";
            $user->notify(new RegisterMail($user, $otp->otp));
            $this->sendSms("234".$user->phone, $sms, 'Duka');
        }


    }

    public function resendOtp(array $request)
    {
        $user = User::where('id', $request['user_id'])->first();

        if ($user){
            $check = OTP::where("user_id", $user->id)->first();

            if ($check){
                $otp = $check->otp;
                $sms = "Hello ".(string)$user->name.", Welcome to Duka! Your OTP is ". (string)$otp. ", please eneter it in 15 minutes. Do not share with anyone!";
                $user->notify(new ResendOtp($user, $otp));
                $this->sendSms("234".$user->phone, $sms, 'Duka');
            }else{
                $otp = new OTP;
                $otp->user_id = $user->id;
                $otp->otp = $this->token();
                $otp->save();

                $sms = "Hello ".(string)$user->name.", Welcome to Duka! Your OTP is ". (string)$otp->otp. ", please eneter it in 15 minutes. Do not share with anyone!";
                $user->notify(new ResendOtp($user, $otp->otp));
                $this->sendSms("234".$user->phone, $sms, 'Duka');
            }
        }else{
            throw new \Exception("User not found");
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

    public function verifyOtp(array $request)
    {
        $user = User::where('id', $request['user_id'])->first();

        if ($user){
            $otp = OTP::where("user_id", $user->id)->where("otp", $request['otp'])->first();

            if ($otp){
                $token = $user->createToken('authToken');
                $otp->delete();
                return $token->plainTextToken;
            }else{
               throw new \Exception("Wrong OTP");
            }
        }else{
            return $this->error("User not found");
        }


    }


    public function logout()
    {

    }

    public function storeReferal(int $referred, int $referrer)
    {
        $history = new ReferralHistory;
        $history->referrer = $referrer;
        $history->referred = $referred;
        $history->save();


    }


}

