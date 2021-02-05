<?php
session_start();

if(!isset($_SESSION['password']))
{
    header('Location: login.php');
    exit;
}

require('load.php');
?>
<!DOCTYPE HTML>
<html lang="en">
  <head>
      <title>Получить отчет о посещении лекций</title>
	  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap">
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
				//console.log(data);
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
<body>



    <!-- /.login-logo -->
    <div class="card-header text-center">
        <h3 class="d-inline mr-4">
        <b>ВолгГМУ</b>
        </h3>
        <?php $menu = 'report'; include_once('menu.php'); ?>
        <a href="login.php?page_logout" class="float-right my-2" >
            <img src="asserts/img/logout.png" class="h-20">
        </a>
    </div>
    <div class="container-fluid">
<div class="row">
        <div class="col-12 col-md-8 col-xs-auto col-sm-auto d-block mx-auto">     
    <div class="card-body mx-auto text-center">
        
    <?php if (DEBUG_NOTIFY==1) print('<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
    <strong>Ведутся технические работы.</strong> Возможны перебои в работе.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button></div>'); ?>

    <!-- <div class="alert alert-warning text-center">
    Новый функционал: <a href="planner.php"><strong>Планирование лекций в Zoom</strong></a>
    </div> -->

    <h4 class="d-inline-block mb-4 text-center">
        Получить отчет <b>Zoom</b> о посещении лекций
    </h4>  


    <form action="#" method="POST" class="form-inline mx-3">

    <div class="row">

    <div class="col-auto">

    <div class="form-group ml-5">

    <label for="InputUser">Логин в ZOOM </label>


    <select type="email" class="form-control mx-2" id="InputUser" name="user" style="width:170px;">
    <option value="0">Выберите...</option>
    <?php
    for ($x=1; $x<=18; $x++) {
        if (isset($_POST['user'])&&$_POST['user']!=""&&(int)$_POST['user']==$x) {
        echo '<option value="'.$x.'" selected>volg***'.$x.'@***.ru</option>';
        } else if($x!=2) echo '<option value="'.$x.'">volg***'.$x.'@***.ru</option>';
    }

    for ($x=1; $x<=20; $x++) {
        if (isset($_POST['user'])&&$_POST['user']!=""&&(int)$_POST['user']==$x) {
            echo '<option value="'.$x.'" selected>exam'.$x.'@volgmed.ru</option>';
        } else
            echo '<option value="'.$x.'">exam'.$x.'@volgmed.ru</option>';
    }

    for ($x=60; $x<=120; $x++) {
        if (isset($_POST['user'])&&$_POST['user']!=""&&(int)$_POST['user']==$x) {
            echo '<option value="'.$x.'" selected>1exam'.$x.'@volgmed.ru</option>';
        } else
            echo '<option value="'.$x.'">1exam'.$x.'@volgmed.ru</option>';
    }

    ?>

    </select>
    </div>
    
    </div>

    <div class="col-auto"> 
    
    <div class="form-group">
        <label for="InputDate" class="mr-2">Дата: </label>
        <input type="date" class="form-control" id="InputDate" name="date" value="<?php if (isset($_POST['date'])&&$_POST['date']!="") { echo $_POST['date'];} else echo date("Y-m-d");?>">
    </div>
    </div>
    <div class="col-auto">
    <button type="submit" class="btn btn-success">Показать</button>

    </div>

    </div>
    </div>
    <?php

    if (isset($_POST['user'])&&$_POST['user']!=""&&(int)$_POST["user"] != 0) {
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

    if((int)$_POST["user"]>=1&&(int)$_POST["user"]<=20) {
        $ajax_report_token = 'exam_30-60_95-120';
        $user = 'exam'.(int)$_POST["user"].'@volgmed.ru';
    }

    if((int)$_POST["user"]>=60&&(int)$_POST["user"]<=120) {
        $ajax_report_token = 'exam_61-78';
        $user = '1exam'.(int)$_POST["user"].'@volgmed.ru';
    }



    $date=$_POST['date'];

    $result=send_api("/report/users/".$user."/meetings?from=".$date."&to=".$date, "GET", $ajax_report_token);
    //var_dump($result);

    if(isset($result['code'])){

    echo '<br><div class="alert alert-danger text-center" style="margin: auto;width:45%;" role="alert"><b>Ошибка:</b> ' . $result['message'].'</div>';	
        
    }  else {

    echo  '<div class="row d-flex">

    <div class="col-12">
    
    <table class="table">
    <thead class="thead-light">
        <tr>
        <th scope="col" class=" text-center align-middle">№</th>
        <th scope="col" class="col-6 text-center align-middle">Название</th>
        <th scope="col" class="col-1 text-center align-middle">Время начала</th>
        <th scope="col" class="col-2 text-center align-middle">Участников **</th>
        <th scope="col" class="col-3 text-center align-middle">Скачать отчёт</th>
        </tr>
    </thead> <tbody>';
    
    foreach($result['meetings'] as $key=>$value)
    {
        
        //$key
        if((int)$value['duration']>25&&(int)$value['participants_count']>3){
            
        

        $timestamp=date("H:i", strtotime($value['start_time'])); 
        
        echo '<tr>
        <th scope="row">'.($key+1).'</th>
        <td>'.$value['topic'].'</td>
        <td class="text-center">'.$timestamp.'</td>
        <td class="text-center">'.$value['participants_count'].'</td>';
        
        if($value['user_name']=='') $user_name=$user; else $user_name=$value['user_name'];
        if(!empty($value['topic'])) {
            $topic_name = htmlspecialchars_decode($value['topic']);
            $topic_name=str_replace("'", "-", $topic_name);
            $topic_name = str_replace('&quot;', '', $topic_name);
            $topic_name = str_replace('"', '', $topic_name);
        }
        
        
        
        echo '<td class="text-center"><button type="button" class="btn btn-success report_btn" onclick=\'downloadPdf("'.urlencode(urlencode($value['uuid'])).'",1,"'.$topic_name.'","'.$user_name.'",'.$value['duration'].', "'.$ajax_report_token.'");\'>Excel</button>
        </tr>';

        
        //&nbsp;&nbsp;<button type="button" class="btn btn-primary report_btn" onclick="downloadPdf(\''.urlencode(urlencode($value['uuid'])).'\',2,\''.$value['topic'].'\',\''.str_replace("'", "-", $value['topic']).'\','.$value['duration'].')" disabled>Word</button></td>

    //$value['uuid']

    }
    }
    echo '<tr class="table-success" style="padding-top:10px;">
        <th colspan="5">Всего конференций: <b>'.$result['total_records'].'</b></th>
        </tr>';
        
    echo ' </tbody>
    </table>

    <div class="text-justify mb-3 mx-4">* скрыты конференции продолжительностью менее 25 минут и с количеством участников менее 3 человек<br>** данная цифра может не соответствовать реальному количеству участников, здесь указано количество подключений к лекции каждого студента (один студент может быть посчитан несколько раз,если он отключается и заходит снова в конференцию). <b>В файле отчёта будет указано реальное кол-во студентов</b><br>
    ! - Для последующей облегчённой автоматической обработки отчётов, рекомендуется студентам придерживаться шаблона имени в конференции:</div>

    </div>
    
    </div>

    <div class="row mb-3">

    <div class="col-md-auto mx-auto alert alert-warning text-center mb-3" role="alert">
    <h5>(КурсГруппа Факультет) Ф.И.О.</h5>
    </div>
    <div class="col-md-auto mx-auto text-center pr-5">
    Пример: <b>(312 леч) Иванов А.А.</b>  <br>(402 МБФ) Петров С.С. <br> (214 стом) Сидоров Я.Я. и т.д.
    </div>
    
    </div>

    <div class="row">

    <div class="col-12 text-center"><small>Сообщить об ошибке: <img src="asserts/img/support.jpg" alt=""></small></div>

    </div>';
    } 

    } else print('<div class="alert alert-danger text-center w-50 mx-auto">
    <strong>Выберите логин Zoom!</strong><br><small>С которого происходил запуск лекции-конференции</small>
    </div>');
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