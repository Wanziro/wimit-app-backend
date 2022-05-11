<?php

try {
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.mista.io/sms',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('to' => '0782238638','from' => 'FELIXPRO','unicode' => '0','sms' => 'final testing sms with incorrect phone','action' => 'send-sms'),
  CURLOPT_HTTPHEADER => array(
    'x-api-key: 4917faa3-c2a1-980d-be49-22e0c53d1c9b-38cadf23'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
$x = json_decode($response, true);
echo $x['code'];
} catch (Exception $e) {
  echo $e->getMessage();
  echo $e;
}