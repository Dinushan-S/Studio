<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'contact_mobile' => 'required|integer',
            'nic' => 'required|string|unique:users,nic',
            'address' => 'required|string',
            'role_id' => 'required|integer',
            'dob' => 'required',
            'email' => 'required|string|unique:users,email',
            'password'=> 'required|string|confirmed'
        ]);

        $user = User::create([
            'name'=> $fields['name'],
            'contact_mobile' => $fields['contact_mobile'],
            //'contact_landline' => $fields['contact_landline'],
            'nic' => $fields['nic'],
            'address' => $fields['address'],
            'role_id' => $fields['role_id'],
            'dob' => $fields['dob'],
            'email'=> $fields['email'],
            'password'=> bcrypt($fields['password'])
        ]);

        //key for the api token
        $token=$user->createToken('B882DE1F-96A8-4A7B-B076-F95F72643D90')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password'=> 'required|string'
        ]);

        //Check email
        $user=User::where('email','=', $fields['email'])->first();

        //Check Password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Invalid Credentials'
            ],401);
        }
        //key for the api token
        $token=$user->createToken('B882DE1F-96A8-4A7B-B076-F95F72643D90')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return ['message' => 'Logged Out'];
    }

    
}
