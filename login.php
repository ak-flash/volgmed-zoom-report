<?php
session_start();

$passwd=["kafedra","boss"];

if(isset($_GET['page_logout']))
{
    unset($_SESSION['password']);
}

if(isset($_POST['submit_pass']) && $_POST['pass'])
{
 if(in_array($_POST['pass'], $passwd)) {
  $_SESSION['password']=$_POST['pass'];
 } else {
  $error="Неверный пароль";
 }
}

if(isset($_SESSION['password'])) {
    if($_SESSION['password']==$passwd[0]) header('Location: index.php');
    if($_SESSION['password']==$passwd[1]) header('Location: all_meetings.php');
} else
{
 ?>
<!DOCTYPE HTML>
<html lang="en">
  <head>
      <title>Получить отчет о посещении лекций</title>
	  <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div id="wrapper">
	<div class="d-flex justify-content-center m-5">
	<div class="card text-center col-sm-4">
	    <div class="card-body">
<form method="post" action="login.php" id="login_form">
  <h3>Вход для сотрудников</h3>
	<div class="input-group">

		<input type="password" name="pass" class="form-control m-2" placeholder="Кодовое слово">
		<input type="submit" name="submit_pass"  class="btn btn-success m-2 w-25" value="Вход">
	</div>
  <p><font style="color:red;"><?php if(isset($error)) echo $error;?></font></p>
 </form>

<?php	
}
?>
</div>
		</div>
	</div>
</div>
</body>
</html>