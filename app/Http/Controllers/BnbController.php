<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Events;
use App\Models\Properties;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class BnbController extends Controller
{
    public function showAllProperties()
    {
        $result=DB::select("select * from properties where status='unsorted' order by str_score");
        return response()->json($result);
    }
    public function notAvailableAction(Request $request,$property_id, $status)
    {
        if($status==1){
            $updateText='declined';
        }else if($status==2){
            $updateText='conditionally_accepted';
        }else if($status==3){
            $updateText='accepted';
        }
        else{
            return response()->json(['message' => 'invalid status'], 500);
        }
        //$result=DB::select("select * from properties where property_id="."$property_id");
        $is_success=Properties::where('property_id', $property_id)->update(['status' => $updateText]);
        if($is_success==1){
            $event=new Events;
            $event->username =$request->username;
            $event->property_id =$property_id;
            $event->event_type =$request->event_type;
            $event->source_page =$request->source_page;
            $event->datetime= strtotime(Carbon::now());
            $event->save();
            return response()->json(['message' => 'successful'], 200);
        }
        return response()->json(['message' => 'not successful'], 200);
    }




    // public function showAllProperties()
    // {
    //     //$result=Properties::all();
    //     $result=DB::select("select property_id from properties where status = 'unsorted' order by str_score");
    //     $result=Properties::where()
    //     Model::where('id', $id)->where('name', $name)->get()
    //     Model::where('id', $id)->orWhere('name', $name)->get()
    //     Model::where('id', $id)->where('name', $name)->orderBy('str_score')


    //     //
        
    //     //return response()->json($result->where('status', 'unsorted'));
    //     return response()->json($result);
    // }
    
 

}