<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class productsController extends Controller
{

	// Create Products

    public function createProducts(Request $request)
    {
    	$name =  $request->input('name');
    	$mrp =  $request->input('mrp');
    	$description =  $request->input('description');
    	$userId =  $request->input('userId');
    	
    	//image upload

    	if($files=$request->file('images')){
        foreach($files as $file){
        	$path = 'uploads/app';
            $names=$file->getClientOriginalName();
             $file->move($path,$names);
            $images[]=$names;

        }

       }

        
    	
    	$data = [
    		'name' => $name,
    		'mrp'=> $mrp,
    		'description'=> $description,
    		'userId'=> $userId,
    		'images'=> implode(",",$images),
    	];

    	DB::table('products')->insert($data);

    	 return response()->json(['payload' => $data, "message" => 'data insert successfully.', 'dev_message' => '', "type" => 'SUCCESS', "code" => 1]);
    }

    // Read Products

     public function listProducts(Request $request)
    {
    	 $listData = DB::table('products')->get()->toArray();
    	 return response()->json($listData);
    }

    // Update Products

    public function updateProducts(Request $request)
    {

    	$name =  $request->input('name');
    	$mrp =  $request->input('mrp');
    	$description =  $request->input('description');
    	$userId =  $request->input('userId');
    	if($files=$request->file('images')){
        foreach($files as $file){
        	$path = 'uploads/app';
            $names=$file->getClientOriginalName();
            // $file->move('image',$names);
            $file->move($path,$names);
            $images[]=$names;

        }

       }
    	

    	$data = [
    		'name' => $name,
    		'mrp'=> $mrp,
    		'description'=> $description,
    		'userId'=> $userId,
    		'images'=> implode(",",$images),
    	];


    	DB::table('products')
          ->where('id',$request->input('product_id'))
          ->update($data);

          return response()->json(['payload' => $data, "message" => 'data updated successfully.', 'dev_message' => '', "type" => 'SUCCESS', "code" => 1]);
    }

    // Update Delete
    
    public function deleteProducts(Request $request)
    {
    	$id = $request->input('products_id');

    	 $resultData = DB::table('products')->where("id", $id)->delete();
    	 return response()->json(['payload' => $resultData, "message" => 'deleted successfully.', 'dev_message' => '', "type" => 'SUCCESS', "code" => 1]);
    }


}
