<?php
error_reporting ("E_ALL & -E_NOTICE");
session_start();
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE style="color:white">ソリューションシェア　管理画面</TITLE>
</HEAD>
<BODY style="background-color:black">
    
<?php
    require_once("db_init.php");
    $n = $db->exec("UPDATE table1 SET ope = 1");
    foreach ($_POST['check'] as $a => $b) {
        $n = $db->exec("UPDATE table1 SET ope = 0 WHERE ban = $b");
        print $b . "は非公開です<BR>";
    }
?>
    
    <P><A HREF='gz_admin.php'>管理画面に戻る</A></P>
    <P><A HREF='gz.php'>通常画面に戻る</A></P>

<?php
} else {
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
    
</BODY>
</HTML>