<?php

namespace App\Http\Controllers;

use App\Events\NewUserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
class AuthController extends Controller
{
    protected $secretKey;

    public function __construct(){
        $this->secretKey = env('APP_KEY');
    }
    public function register(Request $request){
        $fields=$request->all();

        $errors=Validator::make($fields,[
            'email' =>'required|email|unique:users,email',
            'password'=>'required|min:8|max:15',
        ]);
        if($errors->fails()){
            return response($errors->errors()->all(),422);
        }
        $user=User::create([
            'email'=>$fields['email'],
            'password'=>bcrypt($fields['password']),
            'isValidEmail'=>User::IS_INVALID_EMAIL,
            'remember_token'=> $this->generateRandomCode()
        ]);

        NewUserCreated ::dispatch($user);
        return response(['message'=> 'User Created',$user],200);
    }

    public function validateEmail($token){
       $user= User::where('remember_token',$token)->first();
        if(!$user){

            return  redirect('/login')->with('error',"User Token was not found");
        }


        $user->update(['isValidEmail'=>User::IS_VALID_EMAIL]);
        return  redirect('/login')->with('success',"Email Verified Successfully .");

    }
    function generateRandomCode(){
        $code = Str::random(10) . time();
        return $code;
    }

    public function login(Request $request){
        $fields=$request->all();

        $errors=Validator::make($fields,[
            'email' =>'required|email',
            'password'=>'required',
        ]);
        if($errors->fails()){
            return response($errors->errors()->all(),422);
        }
        $user=User::where('email',$fields['email'])->first();
        if(!is_null($user)){
            if(intval($user->isValidEmail)!==User::IS_VALID_EMAIL){
                NewUserCreated ::dispatch($user);
                return response(['message'=>'We have sent you an email with verification!']);

            }
        }
        if(!$user || !Hash::check($fields['password'],$user->password)){
            return response(['message'=>'email or password Invalid','isLoggedIn'=>true],422);
        }
        $token=$user->createToken($this->secretKey)->plainTextToken;
        return response(
            [
                'user'=>$user,
                'message'=>'loggedin',
                'token'=>$token,
                'isLoggedIn'=>true

            ],200
        );
    }
}
