<?php
session_start();

if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}

if (isset($_GET['uid'])) {
    $get_uid = $_GET['uid'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>投稿スレッド一覧</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
    
<body style='background-color:lightblue'>
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

    <div id="main">
        <p id="message"></p>
<?php
        // URLが正しい
        if (isset($get_uid)) {

            // データベース設定
            require_once("db_init.php");
            $ps = $webdb->query("SELECT * FROM `threads` WHERE `uid` = '" . $get_uid . "' ORDER BY `thread_number` DESC");
            while ($r = $ps->fetch()) {
                    $tb = $r['thread_number'];
                    $th_uid = $r['uid'];
                    // ニックネームの取得
                    $ps_nick = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $th_uid . "'");
                    while ($r_nick = $ps_nick->fetch()) {
                        $th_nick = $r_nick['nick'];
                    }
                    // イイネの表示
                    $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = $tb");
                    $coun_iine = 0;
                    while ($r_ii = $ps_ii->fetch()) {
                        $coun_iine++;
                    }
?>
                    <div id='box'>
<?php                         
                            if ($r['ope'] == 0) {print "<p style='color: red;'>管理者により非公開に設定されています</p>";}
?>                          
                            <?php print $r['thread_number']?>
                            <a href='gz_mypage.php?uid=<?=$r['uid']?>'>【投稿者:<?=$th_nick;?>】</a><?$r['date'];?><br>
                            <p class='iine'>イイネ(<?=$coun_iine?>)</p><hr>
                            <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
                    </div>
<?php  
            }
?>
            <script>
                // ログオンしていない場合のあいさつ
                message.innerHTML = 'アップロードしたスレッド一覧';
            </script>
<?php
            // かつ、ログインしている
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
                // 管理者アカウントの場合
                if ($_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
?>
                    <script>
                        // 管理者ページボタンを表示
                        admin.style.display = "block";
                    </script>
<?php
                }
            } else {
                // ログオンしていない
?>
                <script>
                    // ログオンボタン非表示
                    logon.style.display = "block";
                </script>
<?php
            }
        } else {
            // 正しく遷移していない
?>
        
            <script>
                message.innerHTML = '正しい画面から遷移して下さい';
            </script>
<?php
        }
?>
    </div>

</body>
</html>
