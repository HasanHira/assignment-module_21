<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function UserRegistration(Request $request){

        try{
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone'=>$request->input('phone'),
                'password'=>$request->input('password')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successful'
            ]);
        }
        catch (Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong'
            ]);
        }

    }

    function UserLogin(Request $request){

        $count = User::where('email', '=', $request->input('email'))
        ->where('password', '=', $request->input('password'))
        ->count();

        if($count == 1){
            //Issu token
            $token = JWTToken::CreateToken($request->input('email'));

            return response()->json([
                'status' => 'success',
                'message' => 'User Login Successful',
                'token' => $token
            ]);
        } else {

            return response()->json([
                'status' => 'fail',
                'message' => 'unauthorize'
            ]);
        }
    }

    function SendOTPCode(Request $request){

        $email = $request->input('email');
        $otp = rand(1000,9999);
        $count = User::where('email', '=', $email)->count();

        if($count == 1){
            Mail::to($email)->send(new OTPMail($otp));

            User::where('email', '=', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => '4 digits otp code has been sent to your email.'
            ]);
        }
        else {

            return response()->json([
                'status' => 'fail',
                'message' => 'unauthorize'
            ]);
        }

    }

    function VerifyOTP(Request $request){

        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();

        if($count == 1){
            User::where('email', '=', $email)->update(['otp' => '0']);

            $token = JWTToken::TokenForResetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP Verify Successful',
                'token' => $token
            ]);
        }
        else{
            return response()->json([
                'status' => 'fail',
                'message' => 'unauthorize'
            ]);
        }

    }

    function ResetPassword(Request $request){

        try{
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', '=', $email)->update(['password' => $password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reset Request Successful'
            ]);
        }
        catch (Exception $e){
            return response()->json([
                'status' => 'fail',
                'message' => 'Reset Failed'
            ]);
        }

    }

}
