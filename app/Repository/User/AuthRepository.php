<?php
namespace App\Repository\User;

use App\Models\User;
use App\Models\OTP;
use App\Models\UserWallet;
use App\Models\ReferralHistory;
use App\Repository\Interface\User\AuthRepositoryInterface;
use Illuminate\Http\Request;
use App\Trait\Response;
use App\Trait\Token;
use App\Trait\Wallet;


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

            $this->createWallet($user->id);

            //referral history
            $this->storeReferal($user->id, $refUser->id);

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
                $this->sendOtp($user);

                return $user;
            }

            return $this->error("User not found");


        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }

    }

    public function sendOtp(User $user)
    {
        $check = OTP::where("user_id", $user->id)->first();

        if ($check){
            $otp = $check->otp;
        }else{
            $otp = new OTP;
            $otp->user_id = $user->id;
            $otp->otp = $this->token();
            $otp->save();
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

