<?php
session_start();


if(isset($_POST['name'])) {
    $uid = $_POST['name'];
} else {
    $uid = null;
}
//if(isset($_POST['uid'])) {
//    $uid = $_POST['uid'];
//} else {
//    $uid = null;
//}



$_SESSION['uid'] = $uid;
// アクセス時刻の設定
$_SESSION['tm'] = time();
?>

