<?php
session_start();
$_SESSION = array();
?>

<!DOCTYPE html>
    
<html lang="ja">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>たび写真館ログオン</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
<body style="background-color:lightblue">
    <p class="title_pink">たび写真館</p>
    
<?php
//if (isset($_COOKIE['gz_user'])){
//    print "<p>" . $_COOKIE['gz_user'] . "さんは前回{$_COOKIE['gz_date']}に利用しています</p>";
//    $gu = $_COOKIE['gz_user'];
//} else {
//    print "<p>はじめてのご来場ありがとうございます！</p>";
//    $gu = "";
//}
    print "<p>ご来場ありがとうございます！</p>";
?>

    <a href="./gz_logon_1.php">ログオンはこちらから</a>
    <p></p>
    <a href="./gz.php">ログオンせずに利用(閲覧のみ可能)</a>
</body>
</html>