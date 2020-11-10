<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
use Helpers;

class userController extends Controller
{

	// Create Products


public function login_view()
{   
return view('login');  
}



public function login(Request $request)
{ 
        $useremail = $request->input('email');
        $userpassword = $request->input('password');

       
        $user = DB::table('users')
        ->where('email', '=', $useremail)
        ->where('type', '=','ADMIN')
        ->first();


        if(isset($user)){
        if(md5($userpassword) == $user->password){
            // if(Hash::check($userpassword, $user->password)){
        $request->session()->put('UserData', $user);

        return redirect("userList");
        } 
        else{

        session()->flash('loginError', 'Email or Password is incorrect');
        return redirect("login");
        }
        }

} 


public function logout(){
            Session::flush();
            Session::forget('UserData');
            return redirect('login');
        }

        public function add_register(){  

           return view('register');
               }  


    public function save_register(Request $request)
    {
    	$name =  $request->input('name');
    	$bio =  $request->input('bio');
    	$email =  $request->input('email');
    	// $password =  $request->input('password');
         $password = md5($request->input('password'));
        $phone =  $request->input('phone');
       


	   	$data = [
    		'name' => $name,
    		'email'=> $email,
    		'bio'=> $bio,
    		'password'=> $password,
            'phone'=> $phone,
    	];

    	DB::table('users')->insert($data);
        if($data){
    	 return redirect("login");
        }else{
            echo "ERROR";
        }
    }

   // Read User

    public function listUser()
    {
        $listData = DB::table('users')->get()->toArray();
        return view('userList', compact('listData'));
    }

    // public function ajaxListUser(Request $request)
    // {
    // 	 $listData = DB::table('users')->get()->toArray();
    // 	 // return response()->json($listData);

    //      return view('student/user-detail', compact('query')); 
    // }

    // User Select Multiple Business

   
}
