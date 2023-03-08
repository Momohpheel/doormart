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
                'company_proof' => cloudinary()->upload($request->file('proof')->getRealPath())->getSecurePath(),
                'public_image' => cloudinary()->upload($request->file('public_image')->getRealPath())->getSecurePath(),
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

            // if ($user){

            //     $check = Hash::check($request['password'], $user->password);

            //     if ($check){
            //         if ($user->email_verified_at){
            //             if ($user->admin_verified) {

            //                     $token = $user->createToken('authToken');
            //                     $user['access_token'] =  $token->plainTextToken;
            //                     return $user;

            //             }else{
            //                 return "Admin hasn't verified you yet, plese be patient!";
            //             }
            //         }else{
            //             return "User Email has not been verified";
            //         }

            //     }else{
            //         return "Password or Email incorrect";
            //     }


            // }else{
            //     return $this->error("User not found");

            // }

            if (!$user){
                throw new \Exception("User not found");
            }

            $check = Hash::check($request['password'], $user->password);

            if (!$check){
                throw new \Exception("Password or Email incorrect");
            }

            if (!$user->email_verified_at){
                throw new \Exception("User Email has not been verified");
            }

            if (!$user->admin_verified) {
                throw new \Exception("Admin hasn't verified you yet, plese be patient!");
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

        if (!$user) {
            return "User doesn't exist";
        }

        $user->notify(new VendorForgotPasswordEmail($user));

        return true;



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


        if (!$token) {
            return false;
        }

        DB::table('password_resets')
            ->where("email", "=", $request['email'])
            ->where("token", "=", $request['otp'])
            ->delete();

            return true;
    }



    public function changePassword(array $request)
    {
        $user = Vendor::where('id', auth()->user()->id)->first();
        $check = Hash::check($request['old_password'], $user->password);

        if (!$check) {
            return "Old Password is wrong";
        }

        $user->password = Hash::make($request['new_password']);
        $user->save();

        return $user;


    }

    public function logout()
    {

    }



}

