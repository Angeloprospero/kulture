<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Validation\ValidationException;
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
        /*  //attempt to login
        if(FacadesAuth::attempt(['email' => $request->email, 'password' => $request->password])){
        //if successful, return the authenticated user
        return response()->json(FacadesAuth::user());
        }
        //if not successful, return an error
        return response()->json(['error' => 'Unable to login with provided credentials'], 401);
        }*/
       
        $user = user::where('email', $request->email)->first();
        if (!$user)
            return "doesnt exist";
        if (!Hash::check($request->password, $user->password))
            ;
        if (!$user)
            return "do not exist";
        if (!Hash::check($request-> password, $user->password)) {
            throw ValidationException::withMessages(["message" => "wrong details"]);
        }
        return response()->json(['message' => 'user logged in',$user],200);

    }

    public function passwordreset(){

    }


    public function logout(Request $request)
    {
        FacadesAuth::logout();
        return response()->json('loggedout');
    }







        /*
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

    
        
    
 */    
}
