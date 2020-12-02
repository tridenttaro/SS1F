<?php
session_start();

if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
?>
    <script> 
        // 自動的に画面遷移
        location.href = "./gz.php";
    </script>
<?php
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta HTTP EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <title>名前変更</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
<body style="background-color:beige">
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
    <div id='main'>
<?php
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();

            if (isset($_POST['nick']) && $_POST['nick'] != "") {
                $_SESSION['nick'] = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');

                // データベースの設定
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
            print "<P>ちゃんとログオンしてね！<BR>
                    <A HREF='gz.php'>トップページ</A><BR><BR>
                    <A HREF='gz_logon.php'>ログオン</A></P>";
        }
?>
    </div>
    <div id='hidari'>
        <p>
            <a href='gz.php' id='toppage'>トップページ</a><br><br>
            <form method="post" id='logoff'>
                <button type="submit" name="action" value="logoff" 
                    onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
            </form>
        </p>
    </div>
    
 <?php   
        
?>
    
</body>
</html>
