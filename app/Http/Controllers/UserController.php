<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
class UserController extends Controller
{
    //Return property_id from Properties table by limit value in parameter
    public function addNewUser(Request $request)
    {
        $userAttributes = array_keys($request->user);
        $user = new Users;
        foreach($userAttributes as $item ){
            $user->$item=$request->user[$item];
        }
        $user->created_at= strtotime(Carbon::now());
        $user->token=Crypt::encryptString($request->first_name.$request->last_name.$request->created_at);
        $user->updated_at= strtotime(Carbon::now());
        $user->save();
        return response()->json(['message' => 'successful', 'token'=>$user->token], 200);
    }
    public function updateUser(Request $request)
    {
        $user = Users::find($request->user_id);
        $userAttributes = array_keys($request->user);
        foreach($userAttributes as $item ){
            $user->$item=$request->users[$item];
        }
        $user->updated_at= strtotime(Carbon::now());
        $user->save();
        return response()->json(['message' => 'successful'], 200);
    }
    public function loginUser(Request $request)
    {
        $value =Users::where('token', $request->token)->where('is_active', 1)->count();
        //return response()->json($user);
        if($value>0){
            return response()->json(['message' => 'successful'], 200);
        }
        else{
            return response()->json(['message' => 'unsuccessful'], 401);
        }
    }


}