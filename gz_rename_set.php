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

    <div id='hidari'>
        <div id='logon' style='display:none;'><br><a href='gz_logon.php'>ログオン</a></div>

        <div id='toppage'><br><a href='gz.php'>トップページ</a></div>
        <div id='upload' style='display:none;'><br><a href='gz_up.php'>アップロードはここ</a></div>
        <div id='mypage' style='display:none;'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'>マイページ</a></div>
        <div id='admin' style='display:none;'><br><br><a href='gz_admin.php'>管理者ページ</a></div>
        <br><br>
        <form method="post" id='logoff' style='display:none;'>
            <button type="submit" name="action" value="logoff" 
                onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
        </form>
    </div>

    <div id='main'>
        <p id="message"></p>
<?php
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();
?>
            <script>
                // ログオフボタンを表示
                logoff.style.display = "block";
                // アップロードボタンを表示
                upload.style.display = "block";
                // マイページボタンを表示
                mypage.style.display = "block";
            </script>
<?php
        
            // 管理者アカウントである
            if ($_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
?>
                <script>
                    // 管理者ページボタンを表示
                    admin.style.display = "block";
                </script>
<?php   
            }
            
            if (isset($_POST['nick']) && $_POST['nick'] != "") {
                $_SESSION['nick'] = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');

                // データベースの設定
                require_once("db_init.php");
                
                $ps = $webdb->prepare("UPDATE `table2.1` SET `nick` = :v_n WHERE `id` = :v_i");
                $ps->bindParam(':v_i', $_SESSION['uid']);
                $ps->bindParam(':v_n', $_SESSION['nick']);
                $ps->execute();

                print "<h1>ニックネームの変更が完了しました。</h1>";
                print "<h3>現在のニックネーム「" . $_SESSION['nick']  . "」</h3>";
            } else {
                print "<h1>ニックネームの変更に失敗しました。</h1>";
                print "<h3>現在のニックネーム「" . $_SESSION['nick']  . "」</h3>";
            }
        // ログオンしていない
        } else {
?>
            <script>
                message.innerHTML = 'ログインが必要';
                // ログオンボタン表示
                logon.style.display = "block";
            </script>
<?php
        }
?>
    </div>

</body>
</html>
