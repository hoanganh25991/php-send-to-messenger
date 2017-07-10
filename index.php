<?php
define('HOI_VERIFY_TOKEN', 'hoi_verify_token_is_here');

$currentFolder = __DIR__;
$logPath = "$currentFolder/history.log";
$log = fopen($logPath, 'a');
$when = time();
$lineEnding = PHP_EOL;
fwrite($log, "$when$lineEnding");
fclose($log);



$hubVerifyToken = isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : null;
$isTokenMatched = $hubVerifyToken == HOI_VERIFY_TOKEN;
if($isTokenMatched){
  $hubChallenge = isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : null;
  echo $hubChallenge;
  
}
