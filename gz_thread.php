<?php
session_start();

// ログオブボタンを押した
if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}

// コメントを送信した
if (isset($_POST['myn']) && isset($_POST['myc']) && isset($_POST['myb'])) {
    $u = htmlspecialchars($_POST['myn'], ENT_QUOTES);
    $p = htmlspecialchars($_POST['myc'], ENT_QUOTES);
    $b = htmlspecialchars($_POST['myb'], ENT_QUOTES);

    // データベース設定
    require_once("db_init.php");
    $ima = date('YmdHis');
    $ps = $webdb->prepare("INSERT INTO `comments` (`uid`, `thread_number`, `text`, `com_nick`, `date`) 
                        VALUES (:v_u, :v_tn, :v_t, :v_n, :v_d)");
    $ps->bindParam(':v_u', $_SESSION['uid']);
    $ps->bindParam(':v_tn', $b);
    $ps->bindParam(':v_t', $p);
    $ps->bindParam(':v_n', $u);
    $ps->bindParam(':v_d', $ima);
    $ps->execute();

    $_POST = array();
?>
    <script>
        // 再読み込み
        location.href = "./gz_thread.php?uid=<?=$get_num?>'";
    </script>
<?php
    // header("Location: {$_SERVER['PHP_SELF']}");
    // exit;
}

//URLが正しい
if (isset($_GET['tran_b'])) {
    // $_SESSION['thread'] = $_GET['tran_b'];
    $get_num = $_GET['tran_b'];
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
        <h3>スレッド詳細画面</h3>
        <p id="message"></p>
<?php
        // URLが正しい
        if (isset($get_num)) {
            // データベース設定
            require_once("db_init.php");

            $ps = $webdb->query("SELECT * FROM `threads` WHERE `thread_number` = $get_num");
            while ($r = $ps->fetch()) {
                $tg = $r['image'];
                $tb = $r['thread_number'];
                // イイネ関連
                $ii = null;
                $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = $tb");
                $coun_iine = 0;
                while ($r_ii = $ps_ii->fetch()) {
                    $ii = $ii . " " . $r_ii['fav_nick'];
                    $coun_iine++;
                }
                // ブラックリストに入っているか確認
                $flag_bk = 0;
                $uid = $_SESSION['uid'];
                $ps_bk = $webdb->query("SELECT * FROM `blacklists` WHERE `uid` = '" . $uid . "'");
                for ($i = 0; $i < 1; $i++) {
                    while ($r_bk = $ps_bk->fetch()) {
                        // ブロックしているユーザの時
                        if ($r_bk['black_uid'] == $r['uid']) {
                            $flag_bk = 1;
?>
                            <script>
                                message.innerHTML = 'このスレッドは表示できません。投稿ユーザをブロックしている可能性があります。';
                            </script>
<?php
                            goto skip;
                        }
                    }
?>
                    <div id='box'>
                        <?=print $r['thread_number']?>
                        <a href='gz_mypage.php?uid=<?=$r['uid']?>'>【投稿者:<?php print $r['thread_nick'];?>】</a><?$r['date'];?><br>
                        <p class='iine'><a href='gz_iine.php?tran_b=<?=$tb?>'>イイネ!</a> (<?=$coun_iine?>):<?=$ii?></p>
                        <p class='thread_title'><?= $r['title'] ?></p>
                        <?=nl2br($r['text']);?><br>
                        <a href='./gz_img/<?=$tg?>' TARGET='_blank'>
                            <img src='./gz_img/thumb_<?=$tg?>'>
                        </a><br><hr>
<?php
                        $ps_com = $webdb->query("SELECT * FROM `comments` WHERE `thread_number` = $tb ORDER BY `date` DESC");
                        $coun = $ps_com->rowCount();
                        while ($r_com = $ps_com->fetch()) {
?>
                            <p class='com'>●投稿コメント<?=$coun?><br>【<?=$r_com['com_nick']?>さんのメッセージ】
                                <?=$r_com['date']?><br><?=nl2br($r_com['text'])?></p>
<?php
                            $coun--;
                        }
?>
                        <form method="post" id="upcom" style="display:none;">
                            名前<br>
                            <input type = "text" name = "myn" value = "<?php print $_SESSION['nick']; ?>"><br>
                            コメント<BR>
                            <textarea name = "myc" rows = "5" cols = "60" maxlength='250' 
                                placeholder='最大２５０文字' required></textarea><br>
                            <input type = "hidden" name = "myb" value = "<?php print $tb; ?>">
                            <input type="submit" value="送信">
                        </form>
                    </div>  
<?php                        
                }
            }
            skip:
            if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
                $_SESSION['tm'] = time();
?>
                <script>
                    // アップロードボタンを表示
                    upload.style.display = "block";
                    // ログオフボタンを表示
                    logoff.style.display = "block";
                    // マイページボタンを表示
                    mypage.style.display = "block";
                    // コメント入力欄表示
                    upcom.style.display = "block";
                </script>
<?php
                // かつ、ブラックリストに入っていない
                if ($flag_bk == 0) {
?>
                    <script>
                        // ログオンしている場合の挨拶
                        message.innerHTML = 'こんにちは' + '<?php print $_SESSION['nick'] ?>' + 'さん。'; 
                    </script>
<?php
                }
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
                    // ログオンボタン表示
                    logon.style.display = "block";
                </script>
<?php
            }
        } else {
?>
            <script>
                // 正しく遷移していない
                message.innerHTML = '正しい画面から遷移して下さい';
            </script>
<?php
        }
?>
    </div>
</body>

</html>