<?php
include('config.php');

// Turn off all error reporting
if (DEBUG_NOTIFY!=1) error_reporting(0);

function send_api($api_url, $api_data, $ajax_report_token) {

    switch($ajax_report_token){
        case 'volgmed':
            $jwt_zoom_login = JWT_ZOOM_TOKEN_VOLGMED;
            break;
        case 'exam_30-60_95-120':
            $jwt_zoom_login = JWT_ZOOM_TOKEN_EXAM_30_60_95_120;
            break;
        case 'exam_61-78':
            $jwt_zoom_login = JWT_ZOOM_TOKEN_EXAM_61_78;
            break;
    }

$curl = curl_init();

if($api_data=="GET") {
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://".ZOOM_API_ADRESS."/v2".$api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
      "authorization: Bearer ".$jwt_zoom_login,
      "content-type: application/json"
    ),
  ));
} else {
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://".ZOOM_API_ADRESS."/v2".$api_url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode($api_data),
      CURLOPT_HTTPHEADER => array(
      "authorization: Bearer ".$jwt_zoom_login,
      "content-type: application/json"
    ),
  ));
}

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