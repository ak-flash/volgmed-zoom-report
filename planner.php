<?php
session_start();

if(!isset($_SESSION['password']))
{
    header('Location: login.php');
    exit;
}

require('load.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Планирование лекций в Zoom</title>
</head>
<link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>

<script>


</script>
<body>
<div class="container col-md-8">
  
  
  <div class="card m-4">
  <div class="card-header text-center ">
        <h4 class="d-inline-block">Запланировать лекцию (конференцию) <b>ВолгГМУ</b> в Zoom</h4><a href="login.php?page_logout" style="margin-left: 20px;"><img src="asserts/img/logout.png" style="height:25px;"></a>
  </div>

  <div class="card-body">
	
<?php if (DEBUG_NOTIFY==1) print('<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
  <strong>Ведутся технические работы.</strong> Возможны перебои в работе.
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div>'); ?>

<div class="alert alert-info text-center alert-dismissible fade show" role="alert">
    <a href="index.php"><strong>Получение отчётов проведённых лекций</strong></a>
</div>

<form action="#" method="POST" class="mx-3">

<div class="row mx-auto">
    <div class="col">

            <div class="form-group ">
                <label for="InputUser">Логин (ZOOM)</label>
                <select type="email" class="form-control mx-3" id="InputUser" name="user" style="width:170px">
                <?php

        //$data = file_get_contents("php://input");
        if(isset($_POST['user'])){ $data->user = htmlspecialchars(strip_tags($_POST['user'])); }
        if(isset($_POST['date'])){ $data->date = htmlspecialchars(strip_tags($_POST['date'])); }
        if(isset($_POST['time'])){ $data->time = htmlspecialchars(strip_tags($_POST['time'])); }
        if(isset($_POST['topic'])){ $data->topic = htmlspecialchars(strip_tags($_POST['topic'])); }
        if(isset($_POST['password'])){ $data->password = htmlspecialchars(strip_tags($_POST['password'])); }

                for ($x=1; $x<=18; $x++) {
                    if (isset($data->user)&&$data->user!=""&&(int)$data->user==$x) {
                    echo '<option value="'.$x.'" selected>volg***'.$x.'@***.ru</option>';
                    } else if($x!=2) echo '<option value="'.$x.'">volg***'.$x.'@***.ru</option>';
                }

                for ($x=30; $x<=78; $x++) {
                    if (isset($data->user)&&$data->user!=""&&(int)$data->user==$x) {
                        echo '<option value="'.$x.'" selected>exam'.$x.'@volgmed.ru</option>';
                    } else
                        echo '<option value="'.$x.'">exam'.$x.'@volgmed.ru</option>';
                }

                for ($x=95; $x<=120; $x++) {
                    if (isset($data->user)&&$data->user!=""&&(int)$data->user==$x) {
                        echo '<option value="'.$x.'" selected>exam'.$x.'@volgmed.ru</option>';
                    } else
                        echo '<option value="'.$x.'">exam'.$x.'@volgmed.ru</option>';
                }

                ?>

                </select>
            </div>
    </div> 
    
    <div class="col">
        <div class="form-group">
            <label for="InputDate">Дата: </label>
            <input type="date" class="form-control mx-3" id="InputDate" name="date" style="width:150px">
        </div>
    </div> 
    
    <div class="col">    
        <div class="form-group">
            <label for="InputDate">Время: </label>
            <input type="time" class="form-control mx-3" id="InputTime" name="time" style="width:100px">
        </div>
    </div>

</div>


<div class="row mt-3 mx-auto">
<div class="col-8">
        <div class="form-group">
            <label for="InputDate">Название: </label>
            <input type="text" class="form-control mx-3" id="InputTopic" name="topic">
        </div>
</div>
<div class="col">
        <div class="form-group">
            <label for="InputDate">Пароль: </label>
            <input type="text" class="form-control mx-3" id="InputPassword" name="password" value="123" style="width:100px;">
        </div>
</div>
</div>

<div class="row">
    <div class="col">
        <button type="submit" class="btn btn-success m-2">Создать</button>
    </div>
</div>

</div>

</div>

</div>

<div class="col-md-7 mx-auto">
<?php



if (isset($data->user)&&$data->user!=""&&(int)$data->user!= 0&&$data->topic!=""&&$data->date!=""&&$data->time!="") {
//$zoom_login_volmed_mailru_ids = [1, 3, 4, 5, 7];
//$zoom_login_volmed_bkru_ids = [6, 8, 9, 10, 7];
//$zoom_login_volmed_bkru_ids = [10, 11];
//for ($x=1; $x<19; $x++) {
//    if(in_array($x, $zoom_login_volmed_mailru_ids)) $zoom_login[$x] = "volgmed1@mail.ru";
//}
if((int)$_POST["user"]>=1&&(int)$_POST["user"]<=18) {

    $ajax_report_token = 'volgmed';

    switch((int)$_POST["user"]){
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

} 

if((int)$_POST["user"]>=30&&(int)$_POST["user"]<=60) {
    $ajax_report_token = 'exam_30-60_95-120';
    $user = 'exam'.(int)$_POST["user"].'@volgmed.ru';
}

if((int)$_POST["user"]>=61&&(int)$_POST["user"]<=78) {
    $ajax_report_token = 'exam_61-78';
    $user = 'exam'.(int)$_POST["user"].'@volgmed.ru';
}

if((int)$_POST["user"]>=95&&(int)$_POST["user"]<=120) {
    $ajax_report_token = 'exam_30-60_95-120';
    $user = 'exam'.(int)$_POST["user"].'@volgmed.ru';
}


$payload = array("topic" => $data->topic,"type" => 2,"start_time"=>$data->date."T".$data->time.":00","duration" => 100,"password" => $data->password,"settings" => array ("join_before_host" => true ,"host_video" => false,"participant_video" => false, "mute_upon_entry" =>  true,"approval_type" =>  3,"audio" => "both", "auto_recording" => "none" ) );

$result=send_api("/users/".$user."/meetings", $payload, $ajax_report_token);
//var_dump($result);

//добавляем лог в конец файла
file_put_contents("logs.txt", date("d/m/Y H:i:s").' Создана ЛЕКЦИЯ: '.$user.' '.$data->topic.' '.$data->date.' '.$data->time.' (IP: '.$_SERVER['REMOTE_ADDR'].')'.PHP_EOL, FILE_APPEND | LOCK_EX);

if(isset($result['code'])){

echo '<br><div class="alert alert-danger text-center" style="margin: auto;width:45%;" role="alert"><b>Ошибка:</b> ' . $result['message'].'</div>';	
	
}  else {

    
    echo "<table class='table table-striped table-responsive-md'>
    <tbody><tr><th>Логин Zoom: </th><td>".$result['host_email']."</td></tr>";
    echo "<tr><th>Название: </th><td>".$result['topic']."</td></tr>";
    echo "<tr><th>Идентификатор конференции: </th><td>".wordwrap($result['id'], 3 , ' ' , true )."</td></tr>";
    
    echo "<tr><th>Ссылка для студентов: </th><td><a href='".$result['join_url']."'>".$result['join_url']."</a></td></tr>";
    echo "<tr><th>Пароль для студентов: </th><td>".$result['password']."</td></tr>";
    
    echo "<tr class='table-danger'><th>Прямая ссылка для начала конференции: </th><td><a href='".$result['start_url']."'>Только для лектора, позволяет запустить конференцию без входа в аккаунт (ввода логина и пароля). Но только с компьютера, с которого была создана конференция.</a></td></tr></tbody>
    </table>";
} 

} else {
    echo '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
    Заполните информацию о лекции.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button></div>';
}
?>

       
    

</div>

  
</body>
</html>