<?php

require 'vendor/autoload.php';

use \Firebase\JWT\JWT;

$zoom_api_key = 'B_X...';

$zoom_api_secret = 'lZ.....';
		
$token = array(
                "iss" => $zoom_api_key,
                "exp" => "1625138358" // UNIX timestamp until 1 July 2021 г.
            );

echo '<b>ZOOM token:</b> '.JWT::encode($token, $zoom_api_secret);











?>