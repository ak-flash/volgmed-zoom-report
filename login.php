<?php
session_start();

$passwd=["kafedra","boss"];

if(isset($_POST['submit_pass']) && $_POST['pass'])
{
 if(in_array($_POST['pass'], $passwd)) {
  $_SESSION['password']=$_POST['pass'];
 } else {
  $error="Неверный пароль";
 }
}

if(isset($_GET['page_logout']))
{
 unset($_SESSION['password']);
}
?>

<html>
<head>

</head>
<body>
<div id="wrapper">

<?php
if(isset($_SESSION['password'])) {
    if($_SESSION['password']==$passwd[0]) header('Location: index.php');
    if($_SESSION['password']==$passwd[1]) header('Location: all_meetings.php');
} else
{
 ?>
 <form method="post" action="login.php" id="login_form">
  <h2>Вход для сотрудников</h2>
  <input type="password" name="pass">
  <input type="submit" name="submit_pass" value="перейти">

  <p><font style="color:red;"><?php if(isset($error)) echo $error;?></font></p>
 </form>
 <?php	
}
?>

</div>
</body>
</html>