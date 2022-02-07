<?php
require('load.php');


if (isset($_GET['meetingId']) && isset($_GET['accountId'])) {
    echo getZoomStartUrl($_GET['meetingId'], $_GET['accountId']);
}

if (isset($_GET['token'])) {

    $decoded = decodeTokenForZoomStartUrl($_GET['token']);


    $account = ZOOM_ACCOUNTS[(int)$decoded->account_id];

    $result = send_api("/meetings/" . $decoded->meeting_id, "GET", $account['token']);

    echo "<script>location.href='".$result['start_url']."';</script>";
}




