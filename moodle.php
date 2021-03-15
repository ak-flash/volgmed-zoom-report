<?php

session_start();

if(!isset($_SESSION['password']))
{
    header('Location: login.php');
    exit;
}

?>
<!DOCTYPE HTML>
<html lang="en">
  <head>
      <title>Конвертировать вопросы</title>
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


 

</script>

</head>
<body>



    <!-- /.login-logo -->
    <div class="card-header text-center">
        <h3 class="d-inline mr-4">
        <b>ВолгГМУ</b>
        </h3>
        <?php $menu = 'moodle'; include_once('menu.php'); ?>
        <a href="login.php?page_logout" class="float-right my-2" >
            <img src="asserts/img/logout.png" class="h-20">
        </a>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-md-8 col-xs-auto col-sm-auto d-block mx-auto">     
          <div class="card-body text-center">
        

    <!-- <div class="alert alert-warning text-center">
    Новый функционал: <a href="planner.php"><strong>Планирование лекций в Zoom</strong></a>
    </div> -->

    <h4 class="d-inline-block mb-4 text-center">
        Конвертировать  вопросы (в тип "Эссе") для <b>Elearning</b>
    </h4>  


    <form action="#" method="POST" class="form-inline mx-3">
    <div  class="row">
        <div class="col-8">
            <textarea class="form-control" id="InputText" name="InputText" rows="15" cols="45"></textarea>
        </div>
        <div class="col-4 text-left">
           <b>Инструкция:</b> 
           <br>
           Дисциплина - Банк вопросов - Импорт - Формат Moodle XML
          <br>
          <small>Каждый вопрос с новой строки. НЕ должно быть пустых строк (добавится пустой вопрос). Категории для вопросов указывать самостоятельно.</small>
           <br>
           <br> 
            <b>Настройки:</b>
            <div class="form-group"> 
              Балл по умолчанию
              <input type="text" class="form-control ml-3 w-25" name="mark" value="50">
            </div>
            <button type="submit" class="mt-4 btn btn-success">Конвертировать</button>

            <button onclick='document.getElementById("InputText").value="";' class="mt-4 btn btn-danger">
              Очистить
            </button>
        </div>
    </div>
   </form>

   <?php

function file_force_download($file) {
  if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
    readfile($file);

    unlink($file);
    exit;
  }
}

   $xmlTemplate = '<?xml version="1.0" encoding="UTF-8"?>
                    <quiz>
                    ';
   
   
    if (isset($_POST['InputText'])&&$_POST['InputText']!="") {
	
	$inputText = explode(PHP_EOL, $_POST['InputText']);
	
	
	$file = 'tmp/'.uniqid(rand(), true).'.xml';
	
	
	foreach($inputText as $key=>$value)
            { 
				  
				  $xmlTemplate .= '
          <!-- question: 1943368  -->
					  <question type="essay">
						<name>
						  <text>Вопрос '.($key+1).'</text>
						</name>
						<questiontext format="html">
						  <text><![CDATA[<p><span style="font-family: tahoma, arial, helvetica, sans-serif; font-size: medium;">'.($key+1).'. '.$value.'</span></p>]]></text>
						</questiontext>
						<generalfeedback format="html">
						  <text></text>
						</generalfeedback>
						<defaultgrade>50.0000000</defaultgrade>
						<penalty>0.0000000</penalty>
						<hidden>0</hidden>
						<idnumber></idnumber>
						<responseformat>noinline</responseformat>
						<responserequired>1</responserequired>
						<responsefieldlines>15</responsefieldlines>
						<attachments>2</attachments>
						<attachmentsrequired>1</attachmentsrequired>
						<graderinfo format="html">
						  <text></text>
						</graderinfo>
						<responsetemplate format="html">
						  <text></text>
						</responsetemplate>
					  </question>
            ';
                
			}
       
		$xmlTemplate .= '</quiz>';	 

		file_put_contents($file, $xmlTemplate);


    file_force_download($file);
		
    

	}
	
	?>
   
   </div>
   </div>
   </div>
   </div>
   
</body>
</html>
