<?php
require_once "./util.php";
define('HOI_VERIFY_TOKEN', 'hoi_verify_token_is_here');

try{
  $response = handleRequest();
  echo $response;
}catch(\Exception $e){
  hoiLog($e->getMessage());
  echo "Sorry, page error";
}

function handleRequest(){
  // Store info data
  storeReqInfo();
  // Handle challenge from facebook
  $challenge = handleFacebookChallenge();
  if($challenge){
    return $challenge;
  }
  // Handle web hook event
  handleWebHook();
  
}

function handleFacebookChallenge(){
  // Handle challenge from facebook
  $hubVerifyToken = isset($_GET['hub_verify_token'])? $_GET['hub_verify_token'] : null;
  $isTokenMatched = $hubVerifyToken == HOI_VERIFY_TOKEN;
  if($isTokenMatched){
    $hubChallenge = isset($_GET['hub_challenge'])? $_GET['hub_challenge'] : null;
    return $hubChallenge;
  }
  return null;
}

function handleWebHook(){
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
  $requestBody = file_get_contents('php://input');
  // Handle web hook event
  // If can not parse as shape of payload
  // Not facebbok web hook event case
  try{
    $userAuthInfo = json_decode($requestBody, true);
    $firstEntryFirstMsg = $userAuthInfo['entry'][0]['messaging'][0];

    hoiLogConversation($firstEntryFirstMsg);

    $isOptIn  = isset($firstEntryFirstMsg['optin']);
    $firstTimeAuth = ($isOptIn == 'lalala');

    if($firstTimeAuth){
      // Send him a message
      hoiLog("Hanlde optin event");

      $userMessengerId = $firstEntryFirstMsg['sender']['id'];

      hoiLogUserMessengerId($userMessengerId);

      sendMessage($userMessengerId, "hello world");
    }
    
  }catch(\Exception $e){
    $msg = 
"Payload not for facebook webhook case
[Exception] $e->getMessage()
";
    hoiLog($msg);
  }
}




