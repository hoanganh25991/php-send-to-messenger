<?php
require_once "./util.php";
define('HOI_VERIFY_TOKEN', 'hoi_verify_token_is_here');

try{
  handleRequest();
}catch(\Exception $e){
  $currentFolder = __DIR__;
  $logPath = "$currentFolder/history.log";
  $lineEnding  = PHP_EOL;

  $msg = $e->getMessage();
  $log = fopen($logPath, 'a');
  fwrite($log, "$msg$lineEnding");
  fclose($log);

}

function handleRequest(){
  $currentFolder = __DIR__;
  $logPath = "$currentFolder/history.log";
  $lineEnding  = PHP_EOL;

  
  $logPath = "$currentFolder/history.log";
  $log = fopen($logPath, 'a');

  $when        = date('Y-m-d H:i:s');
  $postContent = json_encode($_POST);
  $getContent  = json_encode($_GET);
  $lineEnding  = PHP_EOL;
  $requestBody = file_get_contents('php://input');

  fwrite($log, "[$when]: 
  $postContent
  $getContent
  $requestBody");

  fclose($log);


  $hubVerifyToken = isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : null;
  $isTokenMatched = $hubVerifyToken == HOI_VERIFY_TOKEN;
  if($isTokenMatched){
    $hubChallenge = isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : null;
    echo $hubChallenge;
    
  }

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

  if($requestBody){
    $userAuthInfo = json_decode($requestBody, true, 512, JSON_BIGINT_AS_STRING);
    try{
      $optin = $userAuthInfo['entry']['messaging']['optin'];
      
      $firstTimeAuth = $optin == 'lalala';
      
      if($firstTimeAuth){
        // Send him a message
        $userMessengerId = $userAuthInfo['entry']['messaging']['sender']['id'];
        sendHelloMessage($userMessengerId);
      }
    }catch(\Exception $e){
      $msg = "Not optin case.$lineEnding";
      $msg .= "Exception: $e->getMessage()$lineEnding";
      $logPath = "$currentFolder/history.log";
      $log = fopen($logPath, 'a');
      fwrite($log, "$msg$lineEnding");
      fclose($log);
    }
  }
}
