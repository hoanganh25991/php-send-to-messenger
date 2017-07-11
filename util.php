<?php
function sendMessage($messengerId, $msg = "hello world"){
  $data = [
    "recipient" => [
      "id" => $messengerId
    ],
    "message" => [
      "text" => $msg
    ]
  ];
  
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://graph.facebook.com/v2.6/me/messages?access_token=EAATxJpXKytIBAEH0x3CcFQITbcfPIZA2b1ArQpm2k1ZAcCmhxYDXhROPthi8SdPAc7ZAEMqr7KQ1FXBPmEX3yiciPNaYfnzKxyc2hmuy2QiOow1FjxK2zKam6TgTVH6CHuUqMy3yd0QjNSIP0vsTLQ4rzbIfnEivvXMqjvWPQZDZD",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: application/json",
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  
  if($err){
    hoiLog("Curl error, no network");
  }else{
    hoiLog($response);
  }
}

function hoiLog($msg){
  // open log
  $currentFolder = __DIR__;
  $logPath = "$currentFolder/history.log";
  $log = fopen($logPath, 'a');

  $when = date('Y-m-d H:i:s');
  $logContent =
"[$when]
$msg
-------------------------
";
  fwrite($log, $logContent);
  fclose($log);
}

function storeReqInfo(){
  $postContent = var_export($_POST);
  $getContent = var_export($_GET);
  $requestBody = file_get_contents('php://input');
  // build msg
  $msg = 
"POST:
$postContent
GET:
$getContent
REQUEST BODY:
$requestBody";

  hoiLog($msg);
}

function hoiLogUserMessengerId($userMessengerId){
  $currentFolder = __DIR__;
  $logPath = "$currentFolder/user_ids.log";
  $logContent = hoiGetContent($logPath);

  $userIds = json_decode($logContent);
  // Set userIds as array if default not yet
  if(!$logContent || is_null($userIds) || !is_array($userIds)){
    $userIds = [];
  }

  // Only add new one when it not exist
  if(!in_array($userMessengerId, $userIds)){
    $userIds[] = $userMessengerId;
  }

  $log = fopen($logPath, 'w');
  fwrite($log, json_encode($userIds));
  fclose($log);
}

function getUserMessengerIds(){
  $currentFolder = __DIR__;
  $logPath = "$currentFolder/user_ids.log";
  $logContent = hoiGetContent($logPath);
  $userIds = json_decode($logContent);
  
  if(is_null($userIds)){
    $userIds = [];
  }
  
  return $userIds;
}

function getUserInfo($userMessengerId){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://graph.facebook.com/v2.6/$userMessengerId?fields=first_name%2Clast_name%2Cprofile_pic%2Clocale%2Ctimezone%2Cgender&access_token=EAATxJpXKytIBAMjKDmMC6nbsTQzPn2L4LJ5fSwp8ALuv1lkvaWypQEuFwnkHKzuTCZCBwg3pdUHt5dspwBcAhZCbfNZAtZBfchWDppQQwDlnk9vUxZAU0Nzv0MIuzXb7pYHirEbTYfDT7wEI0Y6IZAhj5oaLX3g0ZBVQIlZCSQMZBuAZDZD",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "postman-token: dc6ad531-f0db-7925-1c8a-eb0d0029dd3f"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    $msg = "Curl error, no network";
    hoiLog($msg);
    return null;
  } else {
    hoiLog($response);
    /*
     * Payload shape 
    {
    "first_name": "Anh",
    "last_name": "Le Hoang",
    "profile_pic": "https://scontent.xx.fbcdn.net/v/t31.0-1/16463870_1245220882181485_1897488918019384572_o.jpg?oh=0ed9810b9b631c95129e102140fcbe62&oe=59CD82CD",
    "locale": "en_US",
    "timezone": 7,
    "gender": "male"
    }    
     */
    $userInfo = json_decode($response, true);
    
    if(is_null($userInfo)){
      return null;
    }
    
    if(isset($userInfo['first_name'])){
      return $userInfo;
    }
    
    return null;
  }
}

function hoiLogConversation($firstEntryFirstMsg){
  /*
 * Payload shape
 {
 "object":"page",
 "entry":[
     {
         "id":"1582722098684919",
         "time":1499669489107,
         "messaging":[
           {
               "recipient":{
                 "id":"1582722098684919"
               },
               "timestamp":1499669489107,
               "sender":{
                 "id":"1192934707436938"
               },
               "optin":{
                 "ref":"lalala"
               }
           }
         ]
     }
   ]
 }
 */
  $msg = json_encode($firstEntryFirstMsg);
  
  $currentFolder = __DIR__;
  $logPath = "$currentFolder/webhook.log";
  
  $log = fopen($logPath, 'a');
  $when = date('Y-m-d H:i:s');
  $msg = 
"[$when]
$msg
-------------------
";
  fwrite($log, $msg);
  fclose($log);
}

function getWebhookLog(){
  $currentFolder = __DIR__;
  $logPath = "$currentFolder/webhook.log";
  $content = hoiGetContent($logPath);
  
  return $content ? $content : '';
}


function isPost(){
  $isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
  return $isPost;
}


function hoiGetContent($logPath){
  error_reporting(E_ERROR);
  $content = file_get_contents($logPath);
  error_reporting(E_ALL);
  return $content;
}