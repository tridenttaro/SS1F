<?php
session_start();


if(isset($_POST['name'])) {
    $_SESSION['uid'] = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
} else {
    $_SESSION['uid'] = null;
}

// アクセス時刻の設定
$_SESSION['tm'] = time();
?>

