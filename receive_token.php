<?php
require 'vendor/autoload.php';
include_once 'config.php';
// Generation of new ZOOM JWT Token
$zoom_api_key = 'B_X2EUbqT3aFoXDuaQXq_g';
$zoom_api_secret = 'lZx2QoUIyCr5E2P59nEHUrw14iyfDrCVkdCO';
$zoom_api_key_expiration = "1625138358"; // Unix time; until 1 July 2021 г.

use \Firebase\JWT\JWT;

$token = array(
                "iss" => $zoom_api_key,
                "exp" => $zoom_api_key_expiration // UNIX timestamp until 1 July 2021 г.
            );

echo '<b>ZOOM token:</b> '.JWT::encode($token, $zoom_api_secret);