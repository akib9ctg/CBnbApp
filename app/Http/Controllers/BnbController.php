<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Events;
use App\Models\Properties;
use App\Models\Tags;
use App\Models\PropertiesTags;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class BnbController extends Controller
{
    //Return property_id from Properties table by limit value in parameter
    public function getAllProperties($limit)
    {
        $result=DB::select("select property_id from properties where status='unsorted' order by str_score LIMIT ".$limit);
        return response()->json($result);
    }

    //Return custom object (property and property_tag) data by property id
    public function getPropertyById($property_id)
    {
        $property= Properties::find($property_id);
        $tagValues = $this->getTagByPropertyId($property_id);
        $data = new \stdClass();
        $data->property=$property;
        $data->property_tags=$tagValues->original;
        return response()->json($data);
    }

    //This is a common Endpoint, Data will insert based on the Client Object.
    public function operationFromApp(Request $request)
    {
        $propertyAttributes = array_keys($request->property);
        $eventAttributes=array_keys($request->events);
        $property_id=$request->property['property_id'];
        
        $property= Properties::where('property_id',$request->property['property_id'])->where('status','unsorted')->first();
        if(is_null($property)){
            return response()->json(['message' => 'unsuccessful'], 410);
        }
        foreach($propertyAttributes as $item){
            $property->$item =$request->property[$item];
        }
        $property->save();

        $event = new Events;
        foreach($eventAttributes as $item ){
            $event->$item=$request->events[$item];
        }
        $event->datetime= strtotime(Carbon::now());
        $event->save();

        //Checking client object has property_tag or not
        if(!is_null($request->property_tags)){
            $tagArray=$request->property_tags;
            
            //Insert new tag in Tag table. New tag will sent with Id null from client side.
            foreach ( $tagArray as $index => $item){
                $temp=Tags::where('tag', $item['tag'])->first();
                if(is_null($temp)){
                    $tag =new Tags;
                    $tag->tag=$item['tag'];
                    $tag->save();
                    $tagArray[$index]['id']=$tag->id;

                }

            }
            //Delete all tag from Properties_tag table as TagArray have all previous Tags and newely added Tags.
            $deleted = PropertiesTags::where('property_id', $property_id)->delete();
            
            //Adding all tags in property_tags table
            foreach($tagArray as $item)
            {
                $propertiesTag=new PropertiesTags;
                $propertiesTag->property_id=$property_id;
                $propertiesTag->tag_id=$item['id'];
                $propertiesTag->save();
            }
        }
        
        return response()->json(['message' => 'successful'], 200);
    }
    
    public function getTagByPropertyId($property_id){
        $result = DB::table('properties_tags')
        ->join('tags', 'properties_tags.tag_id', '=', 'tags.tag_id')
        ->select('tags.tag_id','tags.tag')
        ->where('properties_tags.property_id', '=', $property_id)
        ->get();
        return response()->json($result);
    }
    // public function notAvailableAction(Request $request,$property_id, $status)
    // {
    //     if($status==1){
    //         $updateText='declined';
    //     }else if($status==2){
    //         $updateText='conditionally_accepted';
    //     }else if($status==3){
    //         $updateText='accepted';
    //     }
    //     else{
    //         return response()->json(['message' => 'invalid status'], 500);
    //     }
    //     //$result=DB::select("select * from properties where property_id="."$property_id");
    //     $is_success=Properties::where('property_id', $property_id)->where('status','unsorted')->update(['status' => $updateText]);
    //     if($is_success==1){
    //         $event=new Events;
    //         $event->username =$request->username;
    //         $event->property_id =$property_id;
    //         $event->event_type =$request->event_type;
    //         $event->source_page =$request->source_page;
    //         $event->datetime= strtotime(Carbon::now());
    //         $event->save();
    //         return response()->json(['message' => 'successful'], 200);
    //     }
    //     return response()->json(['message' => 'not successful'], 200);
    // }



    // public function revenueAvailableAction(Request $request,$property_id)
    // {
    //     $is_success=Properties::where('property_id', $property_id)->where('status','unsorted')->
    //             update(['status' => 'confirmed', 
    //             'revenue_period'=>$request->revenue_period,
    //             'prop_type'=>$request->prop_type,
    //             'revenue'=>$request->revenue,
    //             'description'=>$request->description
    //         ]);
    //     if($is_success==1)
    //     {
    //         $event=new Events;
    //         $event->username =$request->username;
    //         $event->property_id =$property_id;
    //         $event->event_type =$request->event_type;
    //         $event->source_page =$request->source_page;
    //         $event->datetime= strtotime(Carbon::now());
    //         $event->save();
    //         $tagArray=$request->property_tags;
            
    //         foreach ( $tagArray as $index => $item){
    //             if($item['id']==null){
    //                 $tag =new Tags;
    //                 $tag->tag=$item['tag'];
    //                 $tag->save();
    //                 $tagArray[$index]['id']=$tag->id;
    //             }
                
    //         }
    //         $deleted = PropertiesTags::where('property_id', $property_id)->delete();
            
    //         foreach($tagArray as $item)
    //         {
    //             $propertiesTag=new PropertiesTags;
    //             $propertiesTag->property_id=$property_id;
    //             $propertiesTag->tag_id=$item['id'];
    //             $propertiesTag->save();
    //         }
    //         return response()->json(['message' => 'successful'], 200);
           

    //     }
    //     return response()->json(['message' => 'not successful'], 200);
    // }
        
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