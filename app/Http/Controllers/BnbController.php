<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Events;
use App\Models\Properties;
use App\Models\Tags;
use App\Models\PropertiesTags;
use App\Models\ColdEmails;
use App\Models\EmailInputEvents;
use App\Models\PropertyDocuments;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class BnbController extends Controller
{
    //Return property_id from Properties table by limit value in parameter
    public function getAllProperties($limit)
    {
        $result=DB::select("select property_id from properties where status='unsorted' order by str_score desc LIMIT ".$limit);
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
     //Return property_id and property_address data by search property address search value
     public function getAllPropertyByAddress(Request $request)
     {
        $property_address=$request['property_address'];
        $result=DB::select("select property_id,address_line from properties where address_line like '%".$property_address."%' LIMIT 10");
        return response()->json($result);
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
            $tempPropertyItem=$request->property[$item];
            if($tempPropertyItem==""){
                $tempPropertyItem=null;
            }
            $property->$item =$tempPropertyItem;
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

                }else{
                    $tagArray[$index]['id']=$temp->tag_id;
                }

            }
            //Delete all tag from Properties_tag table as TagArray have all previous Tags and newely added Tags.
            $deleted = PropertiesTags::where('property_id', $property_id)->delete();
            
            //Adding all tags in property_tags table
            foreach($tagArray as $index => $item)
            {
                $propertiesTag=new PropertiesTags;
                $propertiesTag->property_id=$property_id;
                $propertiesTag->tag_id=$tagArray[$index]['id'];
                $propertiesTag->save();
            }
        }
        if($request->document !=null){
            uploadDocuments($request,$property_id);
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
    
    public function getAllTags(){
        $result=DB::select("select * from tags");
        return response()->json($result);
    }
    public function saveEmails(Request $request){
        $email_arr = $request['emails']; 
        $emailInpitEventAttributes=array_keys($request->emailInputEvent);
        $time= strtotime(Carbon::now());
        $event = new EmailInputEvents;
        foreach($emailInpitEventAttributes as $item ){
            $event->$item=$request->emailInputEvent[$item];
        }
        $event->input_time= $time;
        $event->save();
        foreach($email_arr as $email)
        {
            $coldEmails = new ColdEmails;
            $coldEmails->email=$email;
            $coldEmails->email_event_id=$event->id;
            $coldEmails->save();
        }
        return response()->json(['message' => 'successful'], 200);
    }
    public function uploadDocuments(Request $request,$property_id)    {
  
        //$documentName = time().'.'.$request->document->extension();  
     
        $path = Storage::disk('s3')->put('documents', $request->document);
        $path = Storage::disk('s3')->url($path);
  
        /* Store $imageName name in DATABASE from HERE */
        $propertyDocument=new PropertyDocuments;
        $propertyDocument->property_id=$property_id;
        $propertyDocument->document_url=$path;
        $propertyDocument->save();
    }

}