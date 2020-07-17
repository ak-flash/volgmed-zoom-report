<?php
// получить список конференций
//https://api.zoom.us/v2/report/meetings/Ua6DR6LBSwCSV2eAsyOTIg==/participants?page_size=300
//&next_page_token=nGfun3YTQ9HUuy3xeUCFkyDhijX7u7dNgK2
//"https://api.zoom.us/v2/report/users/volggmu17@bk.ru/meetings?from=2020-04-09&to=2020-04-09"


//var_dump($result);
//echo $result['next_page_token'];

include('load.php');


$result=send_api("https://api.zoom.us/v2/metrics/meetings/nayfAPQwR4i/vxXNeSn0Xw==/participants?page_size=300");
var_dump($result);
?>