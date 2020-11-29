<?php
session_start();
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>名前変更</TITLE>
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

            if (isset($_POST['nick']) && $_POST['nick'] != "") {
                $_SESSION['nick'] = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');

                // データベースに追加
                require_once("db_init.php");
                $ps = $db->prepare("UPDATE `table2.1` SET `nick` = :v_n WHERE `id` = :v_i");
                $ps->bindParam(':v_i', $_SESSION['uid']);
                $ps->bindParam(':v_n', $_SESSION['nick']);
                $ps->execute();

                print "<h1>ニックネームの変更が完了しました。</h1>";
                print "<h3>現在のニックネーム「" . $_SESSION['nick']  . "」</h3>";
            } else {
                print "<h1>ニックネームの変更に失敗しました。</h1>";
                print "<h3>現在のニックネーム「" . $_SESSION['nick']  . "」</h3>";
            }
        }else{
            session_destroy();
            print "<P>アップロードにはログオンが必要です<BR>
                    <A HREF='gz_logon.php'>ログオン</A></P>";
        }
?>
    </div>
    <div id='hidari'>
        <p>
            <a href='gz.php' id='toppage'>トップページ</a><br><br>
            <a href='gz_logon.php' id='logout'>ログオフ</a>
        </p>
    </div>
    
 <?php   
        
?>
    
</BODY>
</HTML>
