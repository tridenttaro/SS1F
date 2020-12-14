<?php
error_reporting ("E_ALL & -E_NOTICE");
session_start();
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm']) && $_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
    $_SESSION['tm'] = time();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>ソリューションシェア　管理画面</title>
</head>
<body style="background-color:silver">
    
<?php
    // データベース設定
    require_once("db_init.php");
    $n = $webdb->exec("UPDATE `threads` SET `ope` = 1");
    foreach ($_POST['check'] as $a => $b) {
        $n = $webdb->exec("UPDATE `threads` SET `ope` = 0 WHERE `thread_number` = $b");
        print $b . "は非公開です<BR>";
    }
?>
    
    <P><A HREF='gz_admin.php'>管理画面に戻る</A></P>
    <P><A HREF='gz.php'>通常画面に戻る</A></P>

<?php
} else {
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
    
</body>
</html>