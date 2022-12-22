<?php

namespace App\Http\Controllers;

use Auth;
use validator;
use App\Models\user;
use PharIo\Manifest\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth as FacadesAuth;
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
    function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:3'
        ]);

        $user_data = array([
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ]
        );
        if(!FacadesAuth::attempt($user_data))
     {
            return response()->json(['message' => 'loggedin']);
     }
     else
     {
      return response()->json('error', 'Wrong Login Details');


     }

    }

    function succeslogin()
    {
     return response ()->json(['message'=>'login' ],200);
    }

    function logout()
    {
     FacadesAuth::logout();
     return response()->json('loggedout');
    }
        
    
     
}
