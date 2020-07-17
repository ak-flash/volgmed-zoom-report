<?php

// Настройки
$debug=1;

date_default_timezone_set('Europe/Volgograd');


// Turn off all error reporting
if ($debug!=1) error_reporting(0);

function send_api($api_url) {
$curl = curl_init();

$api_adress="api.zoom.us";
//$api_adress='52.202.62.238';

$api_secret="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJCX1gyRVVicVQzYUZvWER1YVFYcV9nIiwiZXhwIjoiMTU5MzU2MTYwMCJ9.tWiNX5fDxq-nWMXx2lh9o8M_bu5Qra5MDGjyhHi3_Tc";

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://".$api_adress."/v2".$api_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_DNS_USE_GLOBAL_CACHE => false,
  CURLOPT_DNS_CACHE_TIMEOUT => 2,
  CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer ".$api_secret,
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	  echo '<br><div class="card">
    <div class="card-body mx-auto"><br><div class="alert alert-danger text-center" role="alert"><b>Ошибка:</b> ' . $err.'</div><br><div class="alert alert-success text-center" role="alert"><b>Попробуйте резервный сервер:</b> <a href="http://volgmed.ak-vps.tk/"><b>volgmed.ak-vps.tk</b></a></div></div></div>';
} else {
  $result=json_decode($response,true);

return $result;


}
}
?>