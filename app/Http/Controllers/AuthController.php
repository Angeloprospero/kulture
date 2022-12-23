<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Password;
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
        return response()->json(['error' => 'Unable to login ], 401);
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

    public function passwordreset(Request $request){

        $request->validate([
            'email'=>'required|email',
            'password'=>'required|confirmed',
            'token'=>'required'
        ]);
        //this code is selecting a single user from the database with a matching email address
        $user=user::where('email', [$request->email])->first();
        if(!$user){

            return response()->json(['message' => 'no user with this email address'], 404);
        }
          // Attempt to reset the user's password
    $result = $this->broker()->reset(
        $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
    );

    // If the password reset was successful, return a success response
    if ($result == Password::PASSWORD_RESET) {
        return response()->json([
            'message' => 'Your password has been reset!'
        ], 200);
    }

    // Otherwise, return an error response
    return response()->json([
        'message' => 'There was an error resetting your password. Please try again.'
    ], 500);

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

    This is a PHP script that has various functions related to user authentication.

The register function is responsible for handling new user registration. It validates the incoming request data to ensure that the required fields are present and are in the correct format. It then creates a new user object and sets its properties to the values of the request data. The password is hashed using the Hash::make method before being saved to the database. The function then generates a random token and returns a JSON response with a message and the user and token data.

The login function is responsible for handling user login. It first validates the incoming request data to ensure that the required fields are present and are in the correct format. It then attempts to find a user in the database with the matching email address. If a user is found, it checks if the provided password matches the hashed password in the database using the Hash::check method. If the password does not match, an error is returned. If the password matches, the function returns a JSON response with a message and the user data.

The passwordreset function is responsible for handling password reset requests. It first validates the incoming request data to ensure that the required fields are present and are in the correct format. It then attempts to find a user in the database with the matching email address. If a user is not found, it returns a JSON response with an error message. If a user is found, it attempts to reset the user's password using the broker()->reset method provided by the Laravel framework. If the password reset is successful, it returns a JSON response with a success message. If it is unsuccessful, it returns a JSON response with an error message.

The logout function is responsible for logging the user out. It uses the logout method provided by the Laravel Auth facade to log out the user and then returns a JSON response with a message indicating that the user has been logged out.
        
    
 */    
}
