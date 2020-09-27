<?php
session_start();

if(!isset($_SESSION['password'])||$_SESSION['password']!='boss')
{
header('Location: login.php');
exit;
}

include('load.php'); 
?>
<!doctype html>
<html lang="en">
  <head>
      <title>Получить отчет о посещении лекций</title>
	  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>

  <script>

function downloadPdf(uid,ftype,topic,name,duration,token) {
$('.modal').modal('show');

$.ajax({
        url: 'report.php',
        method: 'POST',
        data:{
            uid: uid,
			ftype: ftype,
            topic: topic,
			date: $('#InputDate').val(),
			name: name,
			duration: duration,
			token: token
        },
        xhrFields: {
            responseType: 'blob'
        }, 
		success: function (data) {
            var a = document.createElement('a');
            var url = window.URL.createObjectURL(data);
            a.href = url;
            
			if(ftype==1) $file_end='xlsx';
			if(ftype==2) $file_end='pdf';
			
			a.download = $('#InputDate').val()+' Лекция.'+topic+' Cписок студентов.'+$file_end;
            
			document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
			
			$('.modal').modal('hide');
        }
    });

    return false;
}
 
 

</script>

</head>
<body class="">
<div class="row" style="margin-right:0;">
          <div class="col-9 d-block mx-auto">
		  

  <div class="login-logo text-center my-4">
    
   <h3 class="d-inline-block">Получить отчет о лекциях <b>ВолгГМУ</b> за день</h3><a href="login.php?page_logout" style="margin-left: 20px;"><img src="asserts/img/logout.png" style="height:25px;"></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body mx-auto">

<?php if (DEBUG_NOTIFY==1) print('<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
  <strong>Ведутся технические работы.</strong> Возможны перебои в работе.
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div>'); ?>

<div class="alert alert-success text-center" role="alert"><b>Резервный сервер:</b> <a href="http://volgmed2.ak-vps.tk/"><b>volgmed2.ak-vps.tk</b></a></div>

	  
<form action="#" method="POST" class="form-inline mx-3">

<div class="form-group">



  <div class="form-group">
    <label for="InputDate">Интервал дат с </label>
    <input type="date" class="form-control mx-3" id="InputDate" name="date" value="<?php if (isset($_POST['date'])&&$_POST['date']!="") { echo $_POST['date'];} else echo date("Y-m-d");?>">
	по <input type="date" class="form-control mx-3" id="InputDateEnd" name="date_end" value="<?php if (isset($_POST['date_end'])&&$_POST['date_end']!="") { echo $_POST['date_end'];} else echo date("Y-m-d");?>">
  </div>
</div>
 
<button type="submit" class="btn btn-primary mx-5">Показать</button>
</div>

<?php 
if (isset($_POST['date'])&&$_POST['date']!=""&&$_POST['date_end']!="") {

//добавляем лог в конец файла
file_put_contents("logs.txt", date("d/m/Y H:i:s").' !!! Получен СПИСОК лекций: c '.$_POST['date'].' по '.$_POST['date_end'].' (IP: '.$_SERVER['REMOTE_ADDR'].')'.PHP_EOL, FILE_APPEND | LOCK_EX);

$date=$_POST['date'];
$date_end=$_POST['date_end'];

$total_num=0;
$x=0;

while ($x++<=91) {


if($x<=17) {

    $ajax_report_token = 'volgmed';

switch($x){
	case 1:
        $user='volgmed1@mail.ru';
        break;
	case 2:
        $user='volgmed3@mail.ru';
        break;
	case 3:
        $user='volgmed4@mail.ru';
        break;
	case 4:
        $user='volgmed5@mail.ru';
        break;
	case 5:
        $user='volgmed6@bk.ru';
        break;
	case 6:
        $user='volggmu7@mail.ru';
        break;
	case 7:
        $user='volgmed8@bk.ru';
        break;
	case 8:
        $user='volggmu9@bk.ru';
        break;	
	case 9:
        $user='volggmu10@bk.ru';
        break;
	case 10:
        $user='volggmu11@bk.ru';
        break;
	case 11:
        $user='volggmu12@bk.ru';
        break;
	case 12:
        $user='volggmu13@bk.ru';
        break;
	case 13:
        $user='volggmu14@bk.ru';
        break;
	case 14:
        $user='volggmu15@bk.ru';
        break;
	case 15:
        $user='volggmu16@bk.ru';
        break;
	case 16:
        $user='volggmu17@bk.ru';
        break;
	case 17:
        $user='volggmu18@bk.ru';
        break;
  }

} else if($x <= (60-12)) { // skip 12 beetwen exam17 and exam30
    $ajax_report_token = 'exam_30-60_95-120';
    $user='exam'.($x+12).'@volgmed.ru';
} else if($x <= (78-12)) { // skip 0 beetwen exam60 and exam61
    $ajax_report_token = 'exam_61-78';
    $user = 'exam'.($x+12).'@volgmed.ru';
} else if($x <= (120-28)) { // skip 28 beetwen exam78 and exam95
    $ajax_report_token = 'exam_30-60_95-120';
    $user='exam'.($x+28).'@volgmed.ru';
}



$result=send_api("/report/users/".$user."/meetings?from=".$date."&to=".$date_end, "GET",$ajax_report_token);

if(isset($result['code'])) 
{

echo '</div><br><div class="alert alert-danger text-center" style="margin: auto;width:45%;" role="alert"><b>Ошибка:</b> ' . $result['message'].'</div>';
break;	
	
}  else {

echo  '<br><table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col" class="text-center align-middle">№</th>
      <th scope="col" class="col-4 text-center align-middle">Название</th>
      <th scope="col" class="col-2 text-center align-middle">Время</th>
      <th scope="col" class="col-1 text-center align-middle">Кол-во * участников </th>
	  <th scope="col" class="col-2 text-center align-middle">Длительность</th>
	  <th scope="col" class="col-3 text-center align-middle">Скачать отчёт</th>
    </tr>
  </thead> <tbody>';


echo '<tr>
      <th colspan="6">Отчёт по пользователю: <b>'.$user.'</b></th>
       </tr>';
 $num=0; 
foreach($result['meetings'] as $key=>$value)
{
	
//if((int)$value['duration']>25&&(int)$value['participants_count']>15){
		date_default_timezone_set('Europe/Volgograd');
	$timestamp_s=date("H:i", strtotime($value['start_time'])); 
	$timestamp_e=date("H:i", strtotime($value['end_time'])); 
	$datestamp=date("d.m", strtotime($value['start_time']));
	
	$duration = $value['duration'];
	
	echo '<tr>
      <th scope="row">'.($key+1).'</th>
      <td>'.$value['topic'].'</td>';
      
	  echo '<td class="text-center">'.$timestamp_s.' - '.$timestamp_e;
	  if($date!=$date_end) echo ' <font size="2">('.$datestamp.')</font>';
      
	  if($value['user_name']=='') $user_name=$user; else $user_name=$value['user_name'];
	  
	  echo '</td><td class="text-center">'.$value['participants_count'].'</td>
	  <td class="text-center">'.$value['duration'].' мин. ';
		
		if($duration>60) 
    {
      $hours = floor($duration / 60);
  		$minutes = $duration % 60;
  		printf('(%02d:%02d)', $hours, $minutes);
		}
		
	  	echo '</td><td class="text-center"><button type="button" class="btn btn-success report_btn" onclick="downloadPdf(\''.urlencode(urlencode($value['uuid'])).'\',1,\''.str_replace("'", "-", $value['topic']).'\',\''.$user_name.'\','.$value['duration'].', \''.$ajax_report_token.'\')">Excel</button> <!--- &nbsp;&nbsp;<button type="button" class="btn btn-primary report_btn" onclick="downloadPdf(\''.urlencode(urlencode($value['uuid'])).'\',2,\''.$value['topic'].'\',\''.str_replace("'", "-", $value['topic']).'\','.$value['duration'].')" disabled>Word</button> ---></td>
    </tr>';
	
	$num++;
		$total_num++;
		
	//}
		
	
	
	
	


//$value['uuid']

}

echo '<tr>
      <th colspan="6">Конференций: <b>'.$num.'</b></th>
       </tr>';	



}
} 

echo '<tr class="table-success" style="padding-top:15px;">
      <th colspan="4" class="text-center h4">Всего конференций за день: <b>'.$total_num.'</b></th><td class="text-center" colspan="2"><!-- Общий отчёт:&nbsp;&nbsp;<button type="button" class="btn btn-success report_btn" onclick="downloadPdf(0,3,0)" disabled>Excel</button>&nbsp;&nbsp;<button type="button" class="btn btn-primary report_btn" onclick="downloadPdf(0,4,0)" disabled>Word</button> --!> </td>
       </tr></tbody>
</table>
<div class="text-justify mb-3 mx-4">* - данная цифра может не соответствовать реальному количеству участников, здесь указано количество подключений к лекции каждого студента (один студент может быть посчитан несколько раз,если он отключается и заходит снова в конференцию). <b>В файле отчёта будет указано реальное кол-во студентов</b><br>
! - Для последующей облегчённой автоматической обработки отчётов, рекомендуется студентам придерживаться шаблона имени в конференции:
<div class="alert alert-warning text-center my-2" style="padding-top:15px;margin: auto;width:45%;" role="alert">
<h5>(КурсГруппа Факультет) Ф.И.О.</h5>
</div>
<div class="text-center my-3">Пример: <b>(312 леч) Иванов А.А.</b>  (402 МБФ) Петров С.С.  (214 стом) Сидоров Я.Я. и т.д.</div>';

}

?>
</div></div>

<div class="modal fade bd-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog h-100 d-flex flex-column justify-content-center my-0">
        <div class="d-flex justify-content-center">
		<div class="spinner-border text-success" style="width: 4rem; height: 4rem;" role="status">
  <span class="sr-only">Загрузка...</span>
</div>
</div>
    </div>
</div>


</body>
</html>