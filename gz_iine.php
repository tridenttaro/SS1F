<?php
session_start();
$b = $_GET['tran_b'];
if (isset($_SESSION['us']) && $_SESSION['us'] != null && $_SESSION['tm'] >= time() - 300){
    $_SESSION['tm'] = time();
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>イイネを送信します</TITLE>
</HEAD>

<BODY STYLE = 'background-color:khaki'>
    <P><?php print $b; ?>番の投稿に<U>イイネ！</U>と言いました</P>
    名前を入力してください<BR>
    <FORM ACTION="gz_iine_set.php" METHOD="post">
    名前<BR>
    <INPUT TYPE = "text" NAME = "myn" VALUE = "<?php print $_SESSION['us']; ?>"><BR>
    <INPUT TYPE = "hidden" NAME = "myb" VALUE="<?php print $b; ?>">
    <INPUT TYPE="submit" VALUE="送信">
</FORM>

<?php
} else {
    session_destroy();
    print "<p>ちゃんとログオンしてね！<br>
            <a href='gz_logon.php'>ログオン</a></p>";
}
?>

</BODY>
</HTML>