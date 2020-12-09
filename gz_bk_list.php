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
    <meta http equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>ブラックリスト</title>
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

            // データベース設定
            require_once("db_init.php");
            $uid = $_SESSION['uid'];
            $ps = $webdb->query("SELECT * FROM `blacklists` WHERE `uid` =  '" . $uid . "'");
            while ($r = $ps->fetch()) {
                $bk_uid = $r['black_uid'];

                $ps_u = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $bk_uid . "'");
                        while ($r_u = $ps_u->fetch()) {
?>
                            <div id='box'>
                                <a href='gz_mypage.php?uid=<?=$bk_uid?>'>【<?php print $r_u['nick'];?>】</a><br>
                            </div>
<?php
                        }
            }
?>
            <script>
                message.innerHTML = "ブロックしたアカウント一覧";
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
        // ログインしていない
        }else{
?>
            <script>
                message.innerHTML = "ログインが必要";
                // ログオンボタン表示
                logon.style.display = "block";
            </script>
<?php
        }
?>       
    </div>
 
    
</body>
</html>
