<?php
session_start();

if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}

if (isset($_POST['nick']) && $_POST['nick'] != "") {
    $_SESSION['nick'] = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');
}
?>
    
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">    
    <title>ソリューションシェア</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
<body style="background-color:beige">
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
    <div id="main">
        <p id="message"></p>
        <p class="iine">(よかったら<u>イイネ！</u>を押してください)</p>
    
<?php
        // データベース設定
        require_once("db_init.php");
        $ps = $webdb->query("SELECT * FROM `threads` WHERE `ope` = 1 ORDER BY `thread_number` DESC");
        while ($r = $ps->fetch()) {
            $tg = $r['image'];
            $tb = $r['thread_number'];
            $ii = null;
            $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = $tb");
            $coun_iine = 0;
            while ($r_ii = $ps_ii->fetch()) {
                $ii = $ii . " " . $r_ii['fav_nick'];
                $coun_iine++;
            }
?>
            <div id='box'>
                <?=print $r['thread_number'] . "【投稿者:" . $r['thread_nick'] . "】" . $r['date'];?><br>
                <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
            </div>
<?php            
        }
?>
    </div>
    <div id='hidari'>
        <a href='gz_logon.php' id='logon' style='display:none;'>ログオン</a>
        <p>
            <a href='gz_up.php' id='upload' style='display:none;'>アップロードはここ</a><br><br>
            <a href='gz_mypage.php' id='mypage' style='display:none;'>マイページ</a><br><br>
            <a href='gz_admin.php' id='admin' style='display:none;'>管理者ページ</a><br>
            
            <form method="post" id='logoff' style='display:none;'>
                <button type="submit" name="action" value="logoff" 
                    onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
            </form>

            
        </p>
    </div>
     
<?php
    if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
        $_SESSION['tm'] = time();
        setcookie("gz_user", $_SESSION['uid'], time()+60*60*24*365);
        setcookie("gz_date", date('Y年m月d日H字i分s秒'), time()+60*60*24*365);

        // データベースに追加
        require_once("db_init.php");
        $ps = $webdb->prepare("INSERT INTO `users`(`uid`, `nick`) VALUES (:v_u, :v_n)");
        $ps->bindParam(':v_u', $_SESSION['uid']);
        $ps->bindParam(':v_n', $_SESSION['nick']);
        $ps->execute();
?>
        <script>
            // ログオンしている場合の挨拶
            message.innerHTML = 'こんにちは' + '<?php print $_SESSION['nick'] ?>' + 'さん。'; 
            // アップロードボタンを表示
            upload.style.display = "block";
            // ログオフボタンを表示
            logoff.style.display = "block";
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
?>
        <script>
            // ログオンしていない場合のあいさつ
            message.innerHTML = 'こんにちは名無しさん。';
            // ログオンボタン非表示
            logon.style.display = "block";
        </script>
<?php
    }
?> 
</body>

</html>