<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'password'=>'required|confirmed',
        ]);
        //create user
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);

        $user->save();
        
        //create Token  //token is a word make you can acess to api
        $token=$user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);

        //"token": "1|OMuWq3EEWgn8s0fGkpfacmHdsBS1DkiidVyYy5PR"
        
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'User logged out']);
    }

    public function login(Request $request){
        //check email 
        $user=User::where('email',$request->email)->first();
        //check password
        //401 when we login worng again and again
        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['message'=>'bad login'],401);
        }
        
        //create Token  //token is a word make you can acess to api
        $token=$user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);
    }
}
