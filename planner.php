<?php
session_start();

if (!isset($_SESSION['password'])) {
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

<body>

<!-- /.login-logo -->
<div class="card-header text-center">
    <h3 class="d-inline mr-4">
        <b>ВолгГМУ</b>
    </h3>
    <?php $menu = 'planner';
    include_once('menu.php'); ?>
    <a href="login.php?page_logout" class="float-right my-2">
        <img src="asserts/img/logout.png" class="h-20">
    </a>
</div>

<div class="card-body mx-auto text-center">

    <?php if (DEBUG_NOTIFY == 1) print('<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
  <strong>Ведутся технические работы.</strong> Возможны перебои в работе.
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button></div>'); ?>

    <h4 class="d-inline-block mb-4 text-center">
        Запланировать лекцию (конференцию) в <b>Zoom</b>
    </h4>

    <div class="container col-md-8">

        <form action="#" method="POST" class="mx-3">

            <div class="row mx-auto">
                <div class="col">
                    <div class="form-group text-left">
                        <label for="InputUser">Логин (ZOOM)</label>
                        <select type="email" class="form-control mx-3" id="InputUser" name="accountId"
                                style="width:200px">
                            <?php
                            include_once 'list_of_accounts.php';
                            ?>

                        </select>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group text-left">
                        <label for="InputDate">Дата: </label>
                        <input type="date" class="form-control mx-3" id="InputDate" name="date" style="width:150px" value="<?=$_POST['date'] ?>">
                    </div>
                </div>

                <div class="col">
                    <div class="form-group text-left">
                        <label for="InputTime">Время: </label>
                        <input type="time" class="form-control mx-3" id="InputTime" name="time" style="width:100px">
                    </div>
                </div>

            </div>


            <div class="row mt-3 mx-auto">
                <div class="col-8">
                    <div class="form-group text-left">
                        <label for="InputTopic">Название: </label>
                        <input type="text" class="form-control mx-3" id="InputTopic" name="topic">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group text-left">
                        <label for="InputPassword">Пароль: </label>
                        <input type="text" class="form-control mx-3" id="InputPassword" name="password" value="123"
                               style="width:100px;">
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



<div class="col-md-7 mx-auto">
    <?php


    if (isset($_POST['date']) && isset($_POST['accountId']) && isset($_POST['time']) && isset($_POST['topic']) && @$_POST['topic'] != '' && isset($_POST['password'])) {

        $date = htmlspecialchars(strip_tags($_POST['date']));
        $time = htmlspecialchars(strip_tags($_POST['time']));
        $topic = htmlspecialchars(strip_tags($_POST['topic']));
        $password = htmlspecialchars(strip_tags($_POST['password']));

        $account = ZOOM_ACCOUNTS[(int)$_POST['accountId']];

        $payload = array("topic" => $topic, "type" => 2, "start_time" => $date . "T" . $time . ":00", "duration" => 100, "password" => $password, "settings" => array("join_before_host" => true, "host_video" => false, "participant_video" => false, "waiting_room" => false, "mute_upon_entry" => true, "approval_type" => 3, "audio" => "both", "auto_recording" => "none"));

        $result = send_api("/users/" . $account['login'] . "/meetings", $payload, $account['token']);
        //var_dump($result);

        //добавляем лог в конец файла
        file_put_contents("logs.txt", date("d/m/Y H:i:s") . ' Создана ЛЕКЦИЯ: ' . $account['login'] . ' ' . $topic . ' ' . $date . ' ' . $time . ' (IP: ' . $_SERVER['REMOTE_ADDR'] . ')' . PHP_EOL, FILE_APPEND | LOCK_EX);

        if (isset($result['code'])) {

            echo '<br><div class="alert alert-danger text-center" style="margin: auto;width:45%;" role="alert"><b>Ошибка:</b> ' . $result['message'] . '</div>';

        } else {
            $zoomId = wordwrap($result['id'], 3, ' ', true);

            echo "<table class='table table-striped table-responsive-md mb-4'>
        <tbody><tr><th>Логин Zoom: </th><td>" . $result['host_email'] . "</td></tr>";
            echo "<tr><th>Название: </th><td>" . $result['topic'] . "</td></tr>";
            echo "<tr><th>Идентификатор конференции: </th><td>" .$zoomId. "
<button type='button' class='btn btn-info ml-4' onclick='copyToClipboard(\"".$zoomId."\")'>Скопировать</button>
</td></tr>";

            echo "<tr><th>Ссылка для студентов: </th><td>
<div class='d-flex'>
    <a href='" . $result['join_url'] . "' class='pr-4'>" . $result['join_url'] . "</a>
    <button type='button' class='btn btn-info' onclick='copyToClipboard(\"".$result['join_url']."\")'>Скопировать</button>
</div>


</td></tr>";
            echo "<tr><th>Пароль для студентов: </th><td>" . $result['password'] . "</td></tr>";

            //echo "<tr class='table-danger'><th>Прямая ссылка для начала конференции: </th><td><a href='".$result['start_url']."'>Только для лектора, позволяет запустить конференцию без входа в аккаунт (ввода логина и пароля). Но только с компьютера, с которого была создана конференция.</a></td></tr>";

            echo "</tbody></table>";
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

<script>
    function copyToClipboard(text) {
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    }
</script>
</body>
</html>