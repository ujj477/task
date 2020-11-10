<?php

/*
 * Developer: Ramayan prasad
 * Date: 29-Oct-2018
 * Description: Notifications helper functions
 */

//function saveNotification($data) {
//
//    $Id = DB::table('notifications')->insertGetId($data);
//
//    return $Id;
//}

function sendNotification() {
    
}

function getNotification() {
  if (!empty(session('user_data')['sub_contractors_id']) ) {
    $userId = session('user_data')['sub_contractors_id'];
   //pr($userId);die;
   $userType = 2;
   //pr($userType);die;
   $data = DB::table('notifications')->select('title')->where(['user_id' => $userId,'user_type' => $userType, 'read' => 0])->count();
  } else {
    $userId = session('user_data')['id'];
   //pr($userId);die;
   $userType = session('user_data')['user_type_id'];
   //pr($userType);die;
   $data = DB::table('notifications')->select('title')->where(['user_id' => $userId, 'user_type' => $userType, 'read' => 0])->count();
  }
   //pr($data);die;
   return $data;
}
function listNotification() {
    
    return array();
  if (!empty(session('user_data')['sub_contractors_id']) ) {
    $userId = session('user_data')['sub_contractors_id'];
    //pr($userId);die;
     $userType = 2;
    //pr($userType);die;
    $notifications = DB::table('notifications')->select('title','is_read','id')->where(['user_id' => $userId, 'user_type' => $userType])->orderBy('created_at', 'DESC')->limit('25')->get();
    } else {
    $userId = session('user_data')['id'];
    //pr($userId);die;
    $userType = session('user_data')['user_type_id'];
    //pr($userType);die;
    $notifications = DB::table('notifications')->select('title','is_read','id')->where(['user_id' => $userId, 'user_type' => $userType])->orderBy('created_at', 'DESC')->limit('25')->get();
    }
   //pr($data);die;
   return $notifications;
}