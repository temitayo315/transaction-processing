<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function registerUser(Request $request){

        return User::create([
           
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }
    
    public function generateToken(Request $request){

        if(!Auth::attempt($request->only(['email', 'password']))){
            
            return response([
                'message' => 'Invalid Credentials'],
                Response::HTTP_UNAUTHORIZED
            );
        }
            
            $user = Auth::user();
            $token = $user->createToken($request->email)->plainTextToken();
            $cookie = cookie('jwt', $token, 60 * 24);
            return response([
                'message' => 'Cookie generated with Token',
                'token'   => $token
            ], Response::HTTP_OK)->withCookie($cookie);
        
    }

    public function balance(Request $request)
    {
        $userId = $request->user()->id;
        $balance = Balance::where('user_id', $userId)->firstOrFail();

        return response()->json(['balance' => $balance->balance], 200);
    }
}