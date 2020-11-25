<?php
session_start();
$u = $_GET['sn'];
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>コメントをどうぞ！</TITLE>
</HEAD>
<BODY STYLE='background-color:lightblue'>

    
    
<?php
if (isset($_SESSION['us']) && $_SESSION['us'] != null && $_SESSION['tm'] >= time()-300){
    $_SESSION['tm'] = time();
?>    
    <P><?php print $u; ?>番の画像に対するコメントをどうぞ！</P>

    <FORM ACTION = "gz_com_set.php" METHOD="post">
        名前<BR>
        <INPUT TYPE = "text" NAME = "myn" VALUE = "<?php print $_SESSION['us']; ?>"><BR>
        コメント<BR>
        <TEXTAREA NAME = "myc" ROWS = "10" COLS = "70"></TEXTAREA><BR>
        <INPUT TYPE = "hidden" NAME = "myb" VALUE = "<?php print $u; ?>">
        <INPUT TYPE = "submit" VALUE = "送信">
    </FORM>
    <P><A HREF = gz.php>一覧表示に戻る</A></P>

<?php
} else {
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>

</BODY>
</HTML>
        