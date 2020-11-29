<?php
session_start();
?>

<!DOCTYPE html>
<HTML lang=ja>
<HEAD>
    <META HTTP EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>たび画像アップロード</TITLE>
</HEAD>
<BODY style='background-color:lightblue'>

<?php
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();
?>

    <P style="color:deeppink;font-size:300%">ソリューションシェア</P>
    投稿よろしくお願いします！
    <FORM ENCTYPE = 'multipart/form-data' ACTION = 'gz_up_set.php' 
          METHOD = 'post'>
        名前<BR>
        <INPUT TYPE='text' NAME='myn' VALUE="<?php print $_SESSION['nick']; ?>"><BR>
        メッセージ<BR>
        <TEXTAREA NAME='mym' ROWS='10' COLS='70'></TEXTAREA><BR>
        <INPUT TYPE = 'file' NAME='myf'>
        <P>送信できるのは1MBまでのJPEG画像だけです！<BR>
            また展開後のメモリ消費が多い場合アップロードできません。<BR>
            <INPUT TYPE='submit' VALUE='送信'><BR>
            <A HREF='gz.php'>一覧表示へ</A>
        </P>
    </FORM>


<?php
}else{
    session_destroy();
    print "<P>アップロードにはログオンが必要です<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
            
</BODY>
</HTML>
