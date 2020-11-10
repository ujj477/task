<?php
function encryptText($txt) {
    return hash_hmac('sha256', $txt, 'app[007]');
}

function arrayTrim($arr) {
    foreach ($arr as &$v) {
	if (!is_array($v))
	    $v = trim($v);
    }
    return $arr;
}

function getSessionData() {
    return $request->session()->get('admindata');
}

function prdie($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die;
}

function currentDT() {
    return date('Y-m-d H:i:s');
}

function currentDateShort() {
    return date('Y-m-d');
}

function getExt($file_name='') {
    return substr($file_name,strrpos($file_name,'.')+1,strlen($file_name)-(strrpos($file_name,'.')+1));
}

function getLatLong($address)
{
    $prepAddr = str_replace(' ','+',$address);
    $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
    $output= json_decode($geocode);
    $latitude = $output->results[0]->geometry->location->lat;
    $longitude = $output->results[0]->geometry->location->lng;
    return array("latitude" => $latitude, "longitude" => $longitude );
}

function checkImageExt($filename) {
    $validExt = array('jpg', 'jpeg', 'png', 'gif');
    $ext = getExt($filename);
    if (in_array(strtolower($ext), $validExt))
	return true;
    else
	return false;
}


function trim_post_array($data) {
    $return_arr = array();
    foreach ($data as $key => $val) {
	$return_arr[$key] = trim($val);
	$return_arr[$key] = stripslashes($val);
	$return_arr[$key] = htmlspecialchars($val);
    }
    return $return_arr;
}


function trim_single_value($data) {
    $return1 = trim($data);
    $return2 = stripslashes($return1);
    $return3 = htmlspecialchars($return2);
    return $return3;
}

/*function delFile($file) {
	if(file_exists($file)) {
		unlink($file);
	}
}*/

function addSlash($data) {
	if(is_array($data))	{
		$inf=array();
		foreach($data as $field=>$val) {
			if(!is_array($val))
				$inf[$field]=addslashes($val);
			else
				$inf[$field]=$val;
		}
	}
	else
		$inf=addslashes($data);

	return $inf;
}

function stripSlash($data) {
	if(is_array($data))	{
		$inf=array();
		foreach($data as $field=>$val) {
			if(!is_array($val))
				$inf[$field]=stripslashes($val);
			else
				$inf[$field]=$val;
		}
	}
	else
		$inf=stripslashes($data);

	return $inf;
}




  
function getExtension($str) 
{
    $i = strrpos($str,".");
    if (!$i) { return ""; } 

    $l = strlen($str) - $i;
    $ext = substr($str,$i+1,$l);
    return $ext;
}



/*create number pad by veerender on 02-05-2018*/
function number_pad($val, $type) {
    $num_pad = str_pad($val, $type, "0", STR_PAD_LEFT);
    return $num_pad;
}



// generat randum number by veerender on 07-05-2018*/
function generate_random($length = 16){
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $result='';
    for ($p = 0; $p < $length; $p++)
    {
        $result .= ($p%2) ?  $chars[mt_rand(26, 35)] : $chars[mt_rand(0, 25)];
    }
    return $result;
}

/*
function generateCSV($header, $data, $filename = 'result_file.csv') {
    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $output = fopen("php://output", "w");
    fputcsv($output, $header);
    foreach ($data as $row) {
        fputcsv($output, (array) $row);
    }
    fclose($output);
}*/




/*
* get country list created
* by veerender on 12-06-2018
*/
function get_countries(){
    $countries = DB::table('adsd_country')->select('*')->orderBy('nicename','asc')->get();
    return $countries;
}



/*
* get city list created
* by veerender on 12-06-2018
*/
function get_cities(){
    $countries_code = DB::table('adsd_cities')->select('*')->orderBy('cityName','asc')->get();
    return $countries_code;
}



/*
* get role list created
* by veerender on 13-06-2018
*/
function get_roles(){
    $role_list = array( 'vip_guest' => 'VIP Guest',
                        'guest'     => 'Guest',
                        'media'     => 'Media',
                        'speakers'  => 'Speakers',
                        'moderator' => 'Moderator',
                        'epc_staff' => 'EPC Staff',
                        'volunteers'=> 'Volunteers',
                        'security'  => 'Security',
                        'administartion'  => 'Administartion'
                    );
    
    return $role_list;
}




/*
* get application status
* by veerender on 13-06-2018
*/
function get_application_status(){
    $status = array( 'entered'          => 'Entered',
                    'link_sent'         => 'Link Sent',
                    'form_submitted'    => 'Form Submitted',
                    'hold_incomplete_details'  => 'Hold Incomplete Details',
                    'under_process'     => 'Under Process',
                    'hotel_booked'      => 'Hotel Booked',
                    'completed'         => 'Completed',
                    'cancelled'         => 'Cancelled',
                    'no_reply'          => 'No Reply'
                );
    return $status;
}



/*
* get application status
* by veerender on 13-06-2018
*/
function get_attendee_type(){
    $type = array( 'local'          => 'Local',
                    'international'         => 'International',
                    'vip-local'    => 'VIP Local',
                    'vip-international'  => 'VIP International'
                );
    return $type;
}








// send mail simple
function send_mail($to, $subject, $body, $fromname=''){
    $url = 'https://api.sendgrid.com/';
    $user = 'jeemapp';
    $pass = 'Aa12341234'; 
    $fromName = $fromname!='' ? $fromname : 'relaxApp Team';
        //register_adsd@epc.ae    

    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'to'        => $to,
        'subject'   => $subject,
        'html'      => $body,
        // 'text'      => $body,
        'from'      => 'info@jeem.ae',
        'fromname'  => $fromName,
        );
    
    
    $request =  $url.'api/mail.send.json';
    
// Generate curl request
    $session = curl_init($request);
// Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
// Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
// Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
    // curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
    $response = curl_exec($session);
    curl_close($session);

// pr($params);
// print everything out
    // print_r($response);
}

// send mail simple
function send_mail_attachment($to, $subject, $body, $file, $fromname=''){
    $url = 'https://api.sendgrid.com/';
    $user = 'jeemapp';
    $pass = 'Aa12341234'; 
    $fromName = $fromname!='' ? $fromname : 'relaxApp Team';
        //register_adsd@epc.ae    
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $params = array(
        'api_user'  => $user,
        'api_key'   => $pass,
        'to'        => $to,
        'subject'   => $subject,
        'html'      => $body,
        // 'text'      => $body,
        'from'      => 'info@jeem.ae',
        'fromname'  => $fromName,
        'files[attachment.png]' => @$file,
        );

    //$params['files']['attachment.'.$ext] = $file;
    
    
    $request =  $url.'api/mail.send.json';
    
// Generate curl request
    $session = curl_init($request);
// Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);
// Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
// Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
    // curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
    $response = curl_exec($session);
    curl_close($session);

// pr($params);
// print everything out
    //print_r($response);
}













##################### ROLE MANAGEMENT ####################

/*
function roles($parm='') {
    $userData = Session::get('user_data');
    $query = DB::table('adsd_permission_role as r');
    $query->join('adsd_permissions as p', 'r.permission_id', '=', 'p.id');
    $query->join('adsd_role_category as c', 'r.role_cat_id', '=', 'c.id');
    $query->select('c.cate_name', 'c.display_name');
    if(!empty($parm)){
        $query->where('c.cate_name', $parm);
    }
    $query->where('r.user_id', $userData['id']);
    $permissions=$query->first();
    
    return $permissions;
}



function permissions($parm=array()) {
    // pr($parm);exit;
    $userData = Session::get('user_data');
    $query = DB::table('adsd_permission_role as r');
    $query->join('adsd_permissions as p', 'r.permission_id', '=', 'p.id');
    $query->join('adsd_role_category as c', 'r.role_cat_id', '=', 'c.id');
    $query->select('p.*');
    if(!empty($parm)){
        $query->where('p.name', $parm[1]);
        $query->where('c.cate_name', $parm[0]);
    }
    $query->where('r.user_id', $userData['id']);
    $permissions=$query->get();
    
    return $permissions;
}




function role_categories(){
    $query = DB::table('adsd_role_category')->select('id', 'cate_name', 'display_name')->get();
    $role_categories = array();
    foreach ($query as $key => $value) {
        $value->permissions = get_permistions();
        
        array_push($role_categories, $value);
    }
    
    return $role_categories;
}



function get_permistions(){
    $permistions = DB::table('adsd_permissions')->select('id','name')->get();
    
    return $permistions;
}





function assigned_permissions($cId, $pId, $uId){
    $perm = DB::table('adsd_permission_role')
                    ->select('id')
                    ->where('role_cat_id', $cId)
                    ->where('permission_id', $pId)
                    ->where('user_id', $uId)
                    ->count();
    $assiged = $perm > 0 ? 'checked' : '';
    return $assiged;
}*/




// check phonenumber
function checkPhone($mobileNumber) {
    $mobileNumber = addslashes($mobileNumber);
    $query = DB::table('relax_users')->select('id')->where('phoneNumber', $mobileNumber)->first();
    return !empty($query) ? $query->id : '';
}


// check checkEmail
function checkEmail($email) {
    $email = addslashes($email);
    $query = DB::table('relax_users')->select('id')->where('email', $email)->first();
    return !empty($query) ? $query->id : '';
}


// email validation
function isEmail($email) {
    //if(filter_var($email, FILTER_VALIDATE_EMAIL))
    if (preg_match("/^([A-Za-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/ ", $email))
        return true;
    else
        return false;
}


function isMobile($mobile) {
    if (preg_match("/^[0-9]/", $mobile))
        return true;
    else
        return false;
}

function notificationCountbyDeviceId($deviceId) {
        $notificationCount = 0;
        $recieverDeviceInfo = DB::table('relax_deviceinfo')->where(['device_id' => $deviceId])->first();
        if($recieverDeviceInfo->user_id)
            $notificationCount = DB::table("relax_notification_log")
                    ->where(['user_id' => $recieverDeviceInfo->user_id, 'is_read' => 0])
                    ->count();

        return $notificationCount;
    }


/* one signal push notification */
function sendOneSinglePushNotification($deviceIds, $heading, $content, $data) {
    
    /*$data = array(
        "foo" => 'bar'
    );

    $content = array(
        "en" => 'English Message'
    );
    
    $heading = array(
        'title' => 'notification title',
        'body' => 'notification description'
    );*/


    $fields = array(
        'app_id' => "7b1897c5-3346-4f0b-94f7-d7408b13dd1a",
        'include_player_ids' => $deviceIds,
        'data' => $data, //array("foo" => "bar"),
        'contents' => $content,
        'headings' => $heading,
    );

    if(count($deviceIds)== 1){
      $badge_count =  notificationCountbyDeviceId($deviceIds[0]);
      if($badge_count >0){
        $fields['ios_badgeCount'] = $badge_count; 
        $fields['ios_badgeType'] = 'SetTo';
      }
    }

    $fields = json_encode($fields);
    
//    print("\nJSON sent:\n");
    //print($fields); die;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function saveNotification($data) {
    return DB::table('relax_notification_log')->insertGetId($data);
}









/*CCAVENUE*/
function ccav_decrypt($encryptedText, $key) {
    $key = ccav_hextobin(md5($key));
    $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
    $encryptedText = ccav_hextobin($encryptedText);
    $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
    return $decryptedText;
}

function ccav_hextobin($hexString) {
    $length = strlen($hexString);
    $binString = "";
    $count = 0;
    while ($count < $length) {
        $subString = substr($hexString, $count, 2);
        $packedString = pack("H*", $subString);
        if ($count == 0) {
            $binString = $packedString;
        } else {
            $binString .= $packedString;
        }

        $count += 2;
    }
    return $binString;
}








/*

function getPasswordHashed($password) {
    $passwordHash = env('PASSWORD_HASH', true);

    return ($passwordHash == TRUE) ? md5($password) : $password;
}
*/


function permissions($role = '', $permission = '') {

    $userData = Session::get('user_data');
//    pr($userData);die;

    if (!empty($userData['permissions'])) {
        if (!empty($userData['permissions'][$role])) {

            //pr($userData['permissions'][$role]);die;

            if (in_array($permission, $userData['permissions'][$role]))
                return $userData['permissions'][$role];
        }
    }

    return array();


}

function role_categories() {
    $query = DB::table('hta_role_category')->select('id', 'cate_name', 'display_name')->get();
    $role_categories = array();
    foreach ($query as $key => $value) {
        $value->permissions = get_permistions();

        array_push($role_categories, $value);
    }

    return $role_categories;
}

function get_permistions() {
    $permistions = DB::table('hta_permissions')->select('id', 'name')->get();

    return $permistions;
}

function assigned_permissions($cId, $pId, $uId) {
    $perm = DB::table('hta_permission_role')
            ->select('id')
            ->where('role_cat_id', $cId)
            ->where('permission_id', $pId)
            ->where('user_type_id', $uId)
            ->count();
    $assiged = $perm > 0 ? 'checked' : '';
    return $assiged;
}



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

/*
function unsetData($dataArray = array(), $unsetDataArray = array()) {
    return array_diff_key($dataArray, array_flip($unsetDataArray));
}
*/

function _UploadFile($request, $fileName, $dir) {
    $file = $request->file($fileName);

    //Move Uploaded File
    $ex = strtolower(str_replace(' ', '_',  time().'_'.rand(1000,9999). '.' . $file->getClientOriginalExtension()));
    $destinationPath = 'uploads/app/public/' . $dir;
    $file->move($destinationPath, $ex);

    @chmod($destinationPath.'/'.$ex, 0777);
    
    return $ex;
}