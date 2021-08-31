<?php
include('config.php');

// Turn off all error reporting
if (DEBUG_NOTIFY!=1) error_reporting(0);

$error_message = '';

$jwt_zoom_login = array(
    'volgmed' => JWT_ZOOM_TOKEN_VOLGMED, 
	'exam_100' => JWT_ZOOM_TOKEN_EXAM_100,
	'exam_30-60_95-120' => JWT_ZOOM_TOKEN_EXAM_30_60_95_120,
);


function checkToken($token_alias, $api_url) {
	
	global $jwt_zoom_login;
    global $error_message;

	$curl = curl_init();

	$token = $jwt_zoom_login[$token_alias];

	
	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://".ZOOM_API_ADRESS."/v2".$api_url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => array(
	  "authorization: Bearer ".$token,
	  "content-type: application/json"
	),
	));

	$response_temp = curl_exec($curl);
	$result_temp = json_decode($response_temp,true);

    if (DEBUG === 1) {
        echo 'Запрос: <b>'.$response_temp.'</b>';
    }


	curl_close($curl);
		
	if(isset($result_temp['code'])){
        return false;
	} else {
		return $token;
	}
	


}



function send_api($api_url, $api_data, $token_alias) {
	global $jwt_zoom_login;
 
	$token = checkToken($token_alias, $api_url);

	//while ($token === false || count($jwt_zoom_login) > 0) {
	//	$token = checkToken(0, $api_url);
	//}

	
if($token === false && count($jwt_zoom_login) > 0) {
	unset($jwt_zoom_login[$token_alias]);
	foreach ($jwt_zoom_login as $key => $value) {
		$token = checkToken($key, $api_url);

		if($token){
			break;
			}
	}
}

if($token){



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
      "authorization: Bearer ".$token,
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
      "authorization: Bearer ".$token,
      "content-type: application/json"
    ),
  ));
}

$response = curl_exec($curl);

//var_dump($response);

$err = curl_error($curl);

curl_close($curl);


if ($err) {
	echo '<br><div class="card">
    <div class="card-body mx-auto"><br><div class="alert alert-danger text-center" role="alert"><b>Ошибка:</b> ' . $err.'</div>';
} else {
	$result=json_decode($response,true);

	return $result;

}
}
}
?>