<?php
    foreach (ZOOM_ACCOUNTS as $id => $account) {
        if (isset($_POST['accountId']) && $_POST['accountId']!="" && (int)$_POST['accountId'] === $id) {
            echo '<option value="'.$id.'" selected>'.$account['login'].'</option>';
        } else
            echo '<option value="'.$id.'">'.$account['login'].'</option>';
    }