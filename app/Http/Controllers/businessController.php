<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class businessController extends Controller
{

	// Create Products

    public function createBusiness(Request $request)
    {
    	$name =  $request->input('name');
    	$email =  $request->input('email');
    	$registrationNo =  $request->input('registrationNo');

    	$data = [
    		'name' => $name,
    		'email'=> $email,
    		'registrationNo'=> $registrationNo,
    	];

    	DB::table('businesses')->insert($data);

    	 return response()->json(['payload' => $data, "message" => 'data insert successfully.', 'dev_message' => '', "type" => 'SUCCESS', "code" => 1]);
    }

    // Read Business


    public function listBusiness(Request $request)
    {
    	 $listData = DB::table('businesses')->get()->toArray();
    	 return response()->json($listData);
    }
}
