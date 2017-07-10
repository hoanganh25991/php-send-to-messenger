<?php
function sendHelloMessage($messengerId){
  $data = [
      "recipient" => [
        "id" =>  $messengerId
      ],
      "message" => [
          "text" => "hello world"
      ]
  ];

  $curl = curl_init();

  echo json_encode($data);

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

  if ($err) {
      echo "cURL Error #:" . $err;
  } else {
      // echo $response;
    $currentFolder = __DIR__;
    $logPath = "$currentFolder/history.log";
    $lineEnding  = PHP_EOL;
    
    $log = fopen($logPath, 'a');
    fwrite($log, "$response$lineEnding");
    fclose($log);
  }
}