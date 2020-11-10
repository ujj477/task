<?php

/*
 * Developer: Ramayan prasad
 * Date: 23-Apr-2018
 */

// Last Query
//DB->enableQueryLog();
//$queries = DB::getQueryLog();

function pr($obj) {
    echo '<pre>';
    print_r($obj);
    echo '</pre>';
}

function encode($arg) {
    return base64_encode($arg);
}

function decode($arg) {
    return base64_decode($arg);
}

function getFileExt($fileName = '') {

    if (empty($fileName))
        return false;   

    $exp = explode('.', $fileName);

    if (count($exp) > 0) {
        return end($exp);
    } else {
        return false;
    }
}

function delFile($file) {
    if (file_exists($file)) {
        @unlink($file);
    }
}

function appDateFormat($date = null) {

    if (empty($date))
        $date = date('Y-m-d');

    if (is_numeric($date)) {
        return date(config('constants.APP_DATE_FORMAT'), $date);
    } else {
        return date(config('constants.APP_DATE_FORMAT'), strtotime($date));
    }
}

function generatePatternNumber($id, $numberCount = 8) {
    return str_pad($id, $numberCount, "0", STR_PAD_LEFT);
}

function generateRandomStr($strLength = 8, $prefix = '') {

    $code = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $code .= "abcdefghijklmnopqrstuvwxyz";
    $code .= "0123456789";
    //$code .= '!@#$%^&*()';

    $token = $prefix;
    $max = strlen($code);

    for ($i = 0; $i < $strLength; $i++)
        $token .= $code[random_int(0, $max - 1)];

    return $token;
}

function getPasswordHashed($password) {
    $passwordHash = env('PASSWORD_HASH', true);

    return ($passwordHash == TRUE) ? md5($password) : $password;
}

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
}

function addLogToTable($post) {
    \DB::table('request_log')->insert(['request' => json_encode($post), 'datetime' => time()]);
}

function unsetData($dataArray = array(), $unsetDataArray = array()) {
    return array_diff_key($dataArray, array_flip($unsetDataArray));
}

/* FILE UPLOADS */

function fileUpload($file, $destinationPath = 'uploads', $fileName = null) {

    $uploadTo = config('filesystems.default');  // Get from config folder configuration

    if (empty($file->getClientOriginalName()))
        return '';

    $ext = $file->getClientOriginalExtension();
    if (empty($fileName))
        $fileName = time() . '-' . mt_rand(1000, 9999) . '.' . $ext;


    if ($uploadTo == 'local') {
        // Local uploads
        //echo $destinationPath . $fileName;die;

        $file->move($destinationPath, $fileName);
        @chmod($destinationPath . $fileName, 0777);

        return $fileName;
    } else if ($uploadTo == 's3') {
        // S3 Uploads

        Storage::disk('s3')->put($destinationPath . $fileName, file_get_contents($file), 'public');
        //$fileName = Storage::disk('s3')->url($fileName);

        return $fileName;
    }



    //pr($file);die;
    //$file = $request->file('image');

    /* //Display File Name
      echo 'File Name: '.$file->getClientOriginalName();
      echo '<br>';

      //Display File Extension
      echo 'File Extension: '.$file->getClientOriginalExtension();
      echo '<br>';

      //Display File Real Path
      echo 'File Real Path: '.$file->getRealPath();
      echo '<br>';

      //Display File Size
      echo 'File Size: '.$file->getSize();
      echo '<br>';

      //Display File Mime Type
      echo 'File Mime Type: '.$file->getMimeType();

      //echo $destinationPath .  $file->getClientOriginalName(); */
}

function generateUploadFilePath($type) {

    $uploadTo = config('filesystems.default');  // Get from config folder configuration

    $fileLocation = config('filelocation.' . $type); //subcontractor-profile-pic


    if ($uploadTo == 'local') {
        // Local uploads
        //echo $destinationPath . $fileName;die;

        return env('PROJECT_PATH') . env('UPLOAD_DIR') . $fileLocation;
    } else if ($uploadTo == 's3') {
        // S3
        //$awsRegion = env('AWS_REGION');
        //$bucketName = env('AWS_BUCKET');
        //return 'https://s3.'. $awsRegion .'.amazonaws.com/' . $bucketName . '/';
        return $fileLocation;
    }
}

function getUploadedFileUrl($type, $fileName) {
    
    $uploadTo = config('filesystems.default');  // Get from config folder configuration
     
    $fileLocation = config('filelocation.' . $type); //subcontractor-profile-pic


    if ($uploadTo == 'local') {
        // Local uploads
        //\echo $destinationPath . $fileName;die;
        return url('/') . '/' . env('UPLOAD_DIR') . $fileLocation . $fileName; 
    } else if ($uploadTo == 's3') {
        // S3

        $awsRegion = env('AWS_REGION');
        $bucketName = env('AWS_BUCKET');

        return 'https://s3.' . $awsRegion . '.amazonaws.com/' . $bucketName . '/' . $fileLocation . $fileName;
    }
}

// SESSION
function checkSesion() {

    //pr(session('user_data'));die;
    if (empty(session('user_data'))) {
        header('location: /');
        exit;
    }
}

function sendSMTPMail($view, $mailData) {
    /* $view = 'mails.set-password';
      $mailData = array(
      'subject' => 'Test',
      'name' => 'Ramayan',
      'email' => 'ramayan@apptology.in',
      'token' => 'test'
      ); */

    \Mail::send($view, $mailData, function ($message) use($mailData) {
        //pr($mailData);die;
        $message->to($mailData['email'])
                ->from(env('MAIL_FROMEMAIL'), env('FROMNAME'))
                ->subject($mailData['subject'] . ' - ' . env('APP_NAME'));
    });
}


function randomOtp() {
    $alphabet = '1234567890';
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}




function generateQrCodeURL($data = array()) {

    $dataString = urlencode(implode('____', $data));

    //$googleApiUrl = 'http://chart.apis.google.com/chart?chs=450x450&cht=qr&chld=L|1&chl='.$dataString;
    $googleApiUrl = 'http://chart.apis.google.com/chart?chs=255x255&cht=qr&chld=L|1&chl=' . $dataString;


    return $googleApiUrl;
}

function getLatLongFromAddress($address){
    
    //$address = 'A-12/3, Phase 1, Naraina Industrial Area, New Delhi - 110028';
    $address = str_replace(' ', '+', $address);
    //echo $address;
    
    //$googleApiKey = 'AIzaSyC47EnCU_VAtFY6PqC6oB2lTEYnGbhA9pA';      // Ramayan google account
    //$googleApiKey = 'AIzaSyA1W0w3fSvwpk5fd_ct3tUihfc3qXZ3tHM';      // Ben google account
    
    $googleApiKey = env('GOOGLE_API_KEY');
    
    $googleLatLongURL = 'https://maps.googleapis.com/maps/api/geocode/json?key='. $googleApiKey .'&address='.$address;
    
    $googleData = callCURL('GET', $googleLatLongURL, false);
    //pr($googleData);die;
    
    $googleData = json_decode($googleData);
    //pr($googleData);die;
    
    if(empty($googleData->results[0])) {
        sleep(0.2);
        getLatLongFromAddress($address);
    }
    
    
    $latitude = !empty($googleData->results[0]->geometry->location->lat) ? $googleData->results[0]->geometry->location->lat : '';
    $longitude = !empty($googleData->results[0]->geometry->location->lng) ? $googleData->results[0]->geometry->location->lng : '';
    
    return [
        'lat' => $latitude,
        'long' => $longitude,
    ];
}


function callCURL($method, $url, $data, $headers=array()) {
    
    // HELP URL: https://www.weichieprojects.com/blog/curl-api-calls-with-php/
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    //echo $url;//die;

    // OPTIONS:
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    
    if(!empty($headers)) {
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $headers = array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        );
    }

    // EXECUTE:
    $result = curl_exec($curl);
    if (!$result) {
        die("Connection Failure");
    }
    curl_close($curl);
    
    return $result;
}

function roles($role = '') {
    $userData = Session::get('user_data');
    //pr($userData);die;
    if (!empty($userData['user_type'])) {
        if ($userData['user_type'] == $role) {
            return $userData['user_type'];
        }
    }

    if (!empty($userData['permissions'])) {
        if (!empty($userData['permissions'][$role])) {
            return $userData['permissions'][$role];
        }
    }

    return array();    
}

//SMS Send
    function SendSms($phone_numbers, $message){
        $SMS_SENDER = urlencode('Join Co.');
        $SMS_USERNAME = 'joincoapi';
        $SMS_PASSWORD = 'joincoapi1';
        $url = "https://api.mpp-sms.com/api/send.aspx?username=joincoapi&password=joincoapi1&language=1&sender=".$SMS_SENDER;
        $message = rawurlencode($message);

        $i= 0;
        $mobiles = "";
        foreach ($phone_numbers as $v) {
            $i++;
            $mobiles .= "965".$v;
            if($i==50){
                $url .= "&mobile=".$mobiles."&message=".$message;
                $send = file_get_contents($url);
                $i = 0;
                $mobiles = "";
            } else {
                $mobiles .= ",";
            }
        }
        if(strlen($mobiles) > 1){
            $mobiles = rtrim($mobiles, ',');
            $url .= "&mobile=".$mobiles."&message=".$message;
            $send = file_get_contents($url);
           // echo $url;
          //print_r($send);  die;
        }
        
      //print_r($send);    
    }



