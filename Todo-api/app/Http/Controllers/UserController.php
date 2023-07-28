<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

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

}
