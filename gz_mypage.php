<?php
session_start();
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>マイページ</TITLE>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</HEAD>
    
<BODY style='background-color:lightblue'>
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
    <h1><?php echo $_SESSION['nick'] ?>さんのマイページ</h1>

<?php
    if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();

    
    }else{
    session_destroy();
    print "<P>アップロードにはログオンが必要です<BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
    }
?>

</BODY>
</HTML>
