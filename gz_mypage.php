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
    
<BODY style="background-color:beige">
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
    <div id='main'>
<?php
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();
?>
            <h1><?php echo $_SESSION['nick'] ?>さんのマイページ</h1>

            <a href="gz_iine_list.php">イイネ一覧</a><br><br>
            <a href="gz_rename.php">ニックネーム変更</a>
    </div>
    <div id='hidari'>
        <p>
            <a href='gz.php' id='toppage'>トップページ</a><br><br>
            <a href='gz_logon.php' id='logout'>ログオフ</a>
        </p>
    </div>
    
 <?php   
        }else{
            session_destroy();
            print "<P>ちゃんとログオンしてね！<BR>
                    <A HREF='gz.php'>トップページ</A><BR><BR>
                    <A HREF='gz_logon.php'>ログオン</A></P>";
        }
?>
    
</BODY>
</HTML>
