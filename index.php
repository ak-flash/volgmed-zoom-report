<?php
include('load.php'); 
?>
<!DOCTYPE HTML>
<html lang="en">
  <head>
      <title>Получить отчет о посещении лекций</title>
	  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Google Font: Source Sans Pro
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> 
  -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>

  <script>

function downloadPdf(uid,ftype,topic,name,duration) { 
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
			token: 2
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
          <div class="col-8 d-block mx-auto ">
		  

  <div class="login-logo text-center my-4">
    
   <h3>Получить отчет о посещении лекций <b>ВолгГМУ</b></h3> 
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body mx-auto">
	
<?php if ($debug==1) print('<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
  <strong>Ведутся технические работы.</strong> Возможны перебои в работе.
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div>'); ?>
	  
<form action="#" method="POST" class="form-inline mx-3">

<div class="form-group ">

<label for="InputUser">Выберите логин в ZOOM </label>


<select type="email" class="form-control mx-3" id="InputUser" name="user">
<?php
for ($x=1; $x<19; $x++) {
	
	if (isset($_POST['user'])&&$_POST['user']!=""&&(int)$_POST['user']==$x) {
	echo '<option value="'.$x.'" selected>volg***'.$x.'@***.ru</option>';
	
	} else if($x!=2) echo '<option value="'.$x.'">volg***'.$x.'@***.ru</option>';
}
?>

</select>
  </div>
  <div class="form-group">
    <label for="InputDate">Дата: </label>
    <input type="date" class="form-control mx-3" id="InputDate" name="date" value="<?php if (isset($_POST['date'])&&$_POST['date']!="") { echo $_POST['date'];} else echo date("Y-m-d");?>">
  </div>

 
<button type="submit" class="btn btn-primary mx-2">Показать</button>
</div>
</div>
<?php

if (isset($_POST['user'])&&$_POST['user']!="") {
	

switch((int)$_POST['user']){
	case 1:
        $user='volgmed1@mail.ru';
        break;
	case 3:
        $user='volgmed3@mail.ru';
        break;
	case 4:
        $user='volgmed4@mail.ru';
        break;
	case 5:
        $user='volgmed5@mail.ru';
        break;
	case 6:
        $user='volgmed6@bk.ru';
        break;
	case 7:
        $user='volggmu7@mail.ru';
        break;
	case 8:
        $user='volgmed8@bk.ru';
        break;
	case 9:
        $user='volggmu9@bk.ru';
        break;	
	case 10:
        $user='volggmu10@bk.ru';
        break;
	case 11:
        $user='volggmu11@bk.ru';
        break;
	case 12:
        $user='volggmu12@bk.ru';
        break;
	case 13:
        $user='volggmu13@bk.ru';
        break;
	case 14:
        $user='volggmu14@bk.ru';
        break;
	case 15:
        $user='volggmu15@bk.ru';
        break;
	case 16:
        $user='volggmu16@bk.ru';
        break;
	case 17:
        $user='volggmu17@bk.ru';
        break;
	case 18:
        $user='volggmu18@bk.ru';
        break;
}

$date=$_POST['date'];

$result=send_api("/report/users/".$user."/meetings?from=".$date."&to=".$date);
//var_dump($result);

if(isset($result['code'])){

echo '<br><div class="alert alert-danger text-center" style="margin: auto;width:45%;" role="alert"><b>Ошибка:</b> ' . $result['message'].'</div>';	
	
}  else {

echo  '<br><table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col" class=" text-center align-middle">№*</th>
      <th scope="col" class="col-6 text-center align-middle">Название</th>
      <th scope="col" class="col-1 text-center align-middle">Время начала</th>
      <th scope="col" class="col-2 text-center align-middle">Кол-во участников **</th>
	  <th scope="col" class="col-3 text-center align-middle">Скачать отчёт</th>
    </tr>
  </thead> <tbody>';
  
foreach($result['meetings'] as $key=>$value)
{
	
	//$key
	if((int)$value['duration']>25&&(int)$value['participants_count']>10){
		
	

	$timestamp=date("H:i", strtotime($value['start_time'])); 
	
	echo '<tr>
      <th scope="row">'.($key+1).'</th>
      <td>'.$value['topic'].'</td>
      <td class="text-center">'.$timestamp.'</td>
      <td class="text-center">'.$value['participants_count'].'</td>';
	 
	 if($value['user_name']=='') $user_name=$user; else $user_name=$value['user_name'];
	 
	echo '<td class="text-center"><button type="button" class="btn btn-success report_btn" onclick="downloadPdf(\''.urlencode(urlencode($value['uuid'])).'\',1,\''.str_replace("'", "-", $value['topic']).'\',\''.$user_name.'\','.$value['duration'].')">Excel</button>&nbsp;&nbsp;<button type="button" class="btn btn-primary report_btn" onclick="downloadPdf(\''.urlencode(urlencode($value['uuid'])).'\',2,\''.$value['topic'].'\',\''.str_replace("'", "-", $value['topic']).'\','.$value['duration'].')" disabled>Word</button></td>
    </tr>';
	

//$value['uuid']

}
}
echo '<tr class="table-success" style="padding-top:10px;">
      <th colspan="5">Всего конференций: <b>'.$result['total_records'].'</b></th>
       </tr>';
	   
echo ' </tbody>
</table>
<div class="text-justify mb-3 mx-4">* скрыты конференции продолжительностью менее 25 минут и с количеством участников менее 10 человек<br>** данная цифра может не соответствовать реальному количеству участников, здесь указано количество подключений к лекции каждого студента (один студент может быть посчитан несколько раз,если он отключается и заходит снова в конференцию). В файле отчёта будет указано реальное кол-во студентов<br>
! - Для последующей облегчённой автоматической обработки отчётов, рекомендуется студентам придерживаться шаблона имени в конференции:</div>
<div class="alert alert-warning text-center my-2" style="padding-top:15px;margin: auto;width:45%;" role="alert">
<h5>(КурсГруппа Факультет) Ф.И.О.</h5>
</div>
<div class="text-center my-3">Пример: <b>(312 леч) Иванов А.А.</b>  (402 МБФ) Петров С.С.  (214 стом) Сидоров Я.Я. и т.д.</div>';
} 

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

<div class="fixed-bottom">
  ...</div>

</body>
</html>