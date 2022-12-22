<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\user;
class AuthController extends Controller
{
    //
    public function register(Request $request){
        $request->validate(['firstname'=>'required|string',
        'lastname'=>'required|string',
        'username'=>'required|string',
        'email'=>'required|unique:user',
        'password'=>'required|string|min:8'

        ]);
         $user = new user([
        'firstname'=>$request->firstname,
        'lastname'=>$request->lastname,
        'username'=>$request->username,
        'email'=>$request->email,
        'password'=>Hash::make($request->password)

         ]);
        $user->save();
        $token = rand(000, 999);
        return response ()->json(['message'=>'registered', $user, $token ],200);
    }
}
