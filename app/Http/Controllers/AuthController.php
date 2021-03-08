<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class AuthController extends Controller
{
    public function requestToken(Request $request){
    $request->validate([
        'login_id'=>'required',
    ]);

    $user = User::with('roles')->where('login_id', $request->login_id)->first();
    if($request->fcm){
        $fcm = User::where('login_id',$request->login_id)->update(['fcm'=>$request->fcm]);
    }


    if (!$user) {
        return response()->json(['message'=>"User Not Found"],404);
    }

    return response()->json(['message'=>"success",'user'=>$user,'token'=>$user->createToken("apitoken")->plainTextToken],200);
       }
       
    //
}
