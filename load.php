<?php
include('config.php');

// Turn off all error reporting
if (DEBUG_NOTIFY!=1) error_reporting(0);



function checkToken($token, $api_url) {

	$curl = curl_init();

	
	curl_setopt_array($curl, array(
	    CURLOPT_URL => ZOOM_API_URL.$api_url,
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



function send_api($api_url, $api_data, $token) {
 
	$token = checkToken($token, $api_url);


    if($token){


        $curl = curl_init();


        if($api_data === "GET") {
          curl_setopt_array($curl, array(
            CURLOPT_URL => ZOOM_API_URL.$api_url,
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
                CURLOPT_URL => ZOOM_API_URL.$api_url,
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
            $error = array(['error', 'msg' => $err]);
            return json_encode($error);
        }

        return json_decode($response,true);

    }

    return false;
}