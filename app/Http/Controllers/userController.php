<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class userController extends Controller
{

	// Create Products

    public function createUser(Request $request)
    {
    	$name =  $request->input('name');
    	$bio =  $request->input('bio');
    	$email =  $request->input('email');
    	$profile =  $request->file('profile');


	$path = $request->file('profile')->store('uploads/app');

    	$data = [
    		'name' => $name,
    		'email'=> $email,
    		'bio'=> $bio,
    		'profile'=> $profile,
    	];

    	DB::table('users')->insert($data);

    	 return response()->json(['payload' => $data, "message" => 'data insert successfully.', 'dev_message' => '', "type" => 'SUCCESS', "code" => 1]);
    }

   // Read User

    public function listUser(Request $request)
    {
    	 $listData = DB::table('users')->get()->toArray();
    	 return response()->json($listData);
    }

    // User Select Multiple Business

    public function selectBusiness(Request $request)
    {
    		$businessId =  $request->input('businessId');
    	
    	$data = [
    		
    		'businessId'=> $businessId,
     	];


    	DB::table('users')
          ->where('id',$request->input('user_id'))
          ->update($data);

          return response()->json(['payload' => $data, "message" => 'data updated successfully.', 'dev_message' => '', "type" => 'SUCCESS', "code" => 1]);
    }
}
