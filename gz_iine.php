<?php
session_start();
$b = $_GET['tran_b'];
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>イイネを送信します</TITLE>
</HEAD>

<BODY STYLE = 'background-color:Lightblue'>
<?php
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();
?>
    <P><?php print $b;?>番の投稿に<U>イイネ！</U>と言いました</P>
    名前を入力してください<BR>
    <FORM ACTION="gz_iine_set.php" METHOD="post">
        名前<BR>
        <INPUT TYPE = "text" NAME = "myn" VALUE = "<?php print $_SESSION['nick']; ?>"><BR>
        <INPUT TYPE = "hidden" NAME = "myb" VALUE="<?php print $b; ?>">
        <INPUT TYPE="submit" VALUE="送信">
    </FORM>

<?php
} else {
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>

</BODY>
</HTML>