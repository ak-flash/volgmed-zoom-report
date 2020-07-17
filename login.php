<?php
session_start();

$passwd="volgmed";

if(isset($_POST['submit_pass']) && $_POST['pass'])
{
 $pass=$_POST['pass'];
 if($pass==$passwd)
 {
  $_SESSION['password']=$pass;
 }
 else
 {
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
if(isset($_SESSION['password'])&&$_SESSION['password']==$passwd)
{
header('Location: all_meetings.php');

}
else
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