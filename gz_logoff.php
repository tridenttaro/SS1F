<!DOCTYPE html>

<?php
session_start();

$_SESSION = array();
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 3600, '/');
}
session_destroy();
?>

<html lang="ja">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<title>ご利用ありがとうございました</title>
</head>
<body>
<p style="color: red"> 愛鳥獣写真館momo (=^・・^=)</p>
<p>またのご来場お待ちしております<br>
<a href="gz_logon.php">再度ログオンするときはここから</a></p>
</body>
</html>