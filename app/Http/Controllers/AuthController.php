<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\user;
use Auth;
use Illu
use validator;
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
    function checklogin(Request $request)
    {
     $this->validate($request, [
      'email'   => 'required|email',
      'password'  => 'required|alphaNum|min:3'
     ]);

     $user_data = array(
      'email'  => $request->get('email'),
      'password' => $request->get('password')
     );

     if(Auth::attempt($user_data))
     {
      return redirect('main/successlogin');
     }
     else
     {
      return back()->with('error', 'Wrong Login Details');
     }

    }

    function successlogin()
    {
     return view('successlogin');
    }

    function logout()
    {
     Auth::logout();
     return redirect('main');
    }
}
