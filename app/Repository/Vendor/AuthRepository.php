<?php
namespace App\Repository\Vendor;

use App\Models\Vendor;
use App\Models\OTP;
use App\Repository\Interface\Vendor\AuthRepositoryInterface;
use App\Trait\Response;
use App\Trait\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\EmailVerify;
use App\Notifications\VendorForgotPasswordEmail;
use DB;


class AuthRepository implements AuthRepositoryInterface
{

    use Response, Token;

    public function register(array $request)
    {
        try
        {
            $user = Vendor::create([
                'name'  => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password'  => Hash::make($request['password']),
                'category_id' => $request['category_id'], //restaurant, pharmacy
                'company_proof' => $request['proof'],
                'public_image' => $request['public_image'],
            ]);

            return $user;

        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }


    }


    public function login(array $request)
    {

        try
        {

            $user = Vendor::where('email', $request['email'])->first();

            if ($user){

                $check = Hash::check($request['password'], $user->password);

                if ($check){
                    if ($user->admin_verified) {
                        if ($user->email_verified_at){
                            $token = $user->createToken('authToken');
                            $user['access_token'] =  $token->plainTextToken;
                            return $user;
                        }else{
                            return "User Email has not been verified";
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

            if (!$user){
                return $this->error("User not found");
            }

            $check = Hash::check($request['password'], $user->password);

            if (!$check){
                return "Password or Email incorrect";
            }

            if (!$user->admin_verified) {
                return "Admin hasn't verified you yet, plese be patient!";
            }

            if (!$user->email_verified_at){
                return "User Email has not been verified";
            }

            $token = $user->createToken('authToken');
            $user['access_token'] =  $token->plainTextToken;
            return $user;



        }catch(Exception $e){
            return $this->error($e->getMessage(), 400);
        }

    }

    public function verifyVendorEmail($token)
    {
        $check = EmailVerify::where("token", $token)->first();

        if ($check){

            $user = Vendor::where("email", $check->email)->first();
            $user->email_verified_at = now();
            $user->save();

            $check->delete();
        }


    }


    public function forgotPassword(array $request)
    {
        $user = Vendor::where('email', $request['email'])->first();

        if ($user) {
            $user->notify(new VendorForgotPasswordEmail($user));

            return true;
        }
        // }else{
            return "User doesn't exist";
        // }

    }

    public function resetPassword(array $request)
    {
        $user = Vendor::where('email', $request['email'])->first();
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
        $user = Vendor::where('id', auth()->user()->id)->first();
        $check = Hash::check($request['old_password'], $user->password);

        if ($check) {
            $user->password = Hash::make($request['new_password']);
            $user->save();

            return $user;

        }else{
            return "Old Password is wrong";
        }

    }

    public function logout()
    {

    }



}

