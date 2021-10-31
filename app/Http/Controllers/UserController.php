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
        $result=Users::where('email',$request->user['email'])->count();
        if($result>0){
            return response()->json(['message' => 'email already exist'], 200); 
        }
        else{
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
    }
    public function updateUser(Request $request)
    {
        $user = Users::find($request->user_id);
        $userAttributes = array_keys($request->user);
        foreach($userAttributes as $item ){
            $user->$item=$request->user[$item];
        }
        $user->updated_at= strtotime(Carbon::now());
        $user->save();
        return response()->json(['message' => 'successful'], 200);
    }
    public function loginUser(Request $request)
    {
        $user =Users::where('token', $request->token)->where('is_active', 1)->first();
        //return response()->json($user);
        if(!is_null($user)){
            $data = new \stdClass();
            $data->user=$user;
            return response()->json($data, 200);
        }
        else{
            return response()->json(['message' => 'unsuccessful'], 401);
        }
    }
    public function getUserById($id)
    {
        $user =Users::find($id);
        return response()->json($user);
    }
    public function getAllUser()
    {
        $result=DB::select("select * from users");
        return response()->json($result);
    }

}