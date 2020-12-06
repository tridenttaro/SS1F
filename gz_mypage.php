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
        <p id="message"></p>
        <a href="gz_up_list.php?uid=<?=$get_uid?>" id='thlist' style='display:none;'>投稿したスレッド一覧</a><br><br>
        <a href="gz_iine_list.php?uid=<?=$get_uid?>" id='iilist' style='display:none;'>イイネしたスレッド一覧</a><br><br>
        <a href="gz_com_list.php?uid=<?=$get_uid?>" id='comlist' style='display:none;'>コメントしたスレッド一覧</a><br><br>
        <a href="gz_rename.php" id='chnick' style='display:none;'>ニックネーム変更</a>
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
<?php
    // URLが正しい
    if (isset($get_uid)) {
        // uidからニックネームを取り出す
        $get_nick = "";
        // データベース設定
        require_once("db_init.php");
        $ps = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $get_uid . "'");
        while ($r = $ps->fetch()) {
            $get_nick = $r['nick'];
        }
?>
        <script>
            let nick = <?php echo json_encode($get_nick); ?>;
            message.innerHTML = nick + 'さんのユーザーページ';
            // 投稿したスレッド一覧ボタンを表示
            thlist.style.display = "block";
            // コメントしたスレッド一覧ボタンを表示
            comlist.style.display = "block";
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
            // かつ、自分のユーザーページ
            if ($get_uid == $_SESSION['uid']) {
?>
                <script>
                    message.innerHTML = 'マイページ';
                    // イイネしたスレッド一覧ボタンを表示
                    iilist.style.display = "block";
                    // ニックネーム変更ボタンを表示
                    chnick.style.display = "block";
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
            

    
    

    
</BODY>
</HTML>
