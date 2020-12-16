<?php
session_start();

// ログオフボタンを押した
if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}

// コメントを送信した
if (isset($_POST['myc']) && isset($_POST['myb'])) {
    $p = htmlspecialchars($_POST['myc'], ENT_QUOTES);
    $b = htmlspecialchars($_POST['myb'], ENT_QUOTES);

    // データベース設定
    require_once("db_init.php");
    $ima = date('YmdHis');
    $ps = $webdb->prepare("INSERT INTO `comments` (`uid`, `thread_number`, `text`, `date`) 
                        VALUES (:v_u, :v_tn, :v_t, :v_d)");
    $ps->bindParam(':v_u', $_SESSION['uid']);
    $ps->bindParam(':v_tn', $b);
    $ps->bindParam(':v_t', $p);
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

// イイネ！をした
if (isset($_POST['myb_ii'])) {
    $b_ii = htmlspecialchars($_POST['myb_ii'], ENT_QUOTES);

    // データベース設定
    require_once("db_init.php");
    $ps = $webdb->prepare("INSERT INTO `favorites` (`uid`, `thread_number`) VALUES (:v_u, :v_tn)");
    $ps->bindParam(':v_u', $_SESSION['uid']);
    $ps->bindParam(':v_tn', $b_ii);
    $ps->execute();

    $_POST = array();
?>
    <script>
        // 再読み込み
        location.href = "./gz_thread.php?uid=<?=$get_num?>'";
    </script>
<?php
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

        <div id='toppage'><br>
            <form method="post" name="form1" action="gz.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form1.submit()">トップページ</a>
            </form> 
        </div>
        <div id='upload' style='display:none;'><br><a href='gz_up.php'>アップロードはここ</a></div>
	    <div id='mypage' style='display:none;'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'>マイページ</a></div>
        <div id='admin' style='display:none;'><br><br>
            <form method="post" name="form2" action="gz_admin.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form2.submit()">管理者ページ</a>
            </form> 
        </div>
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
                $th_uid = $r['uid'];
                // ニックネームの取得
                $ps_nick = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $th_uid . "'");
                while ($r_nick = $ps_nick->fetch()) {
                    $th_nick = $r_nick['nick'];
                }
                // イイネの個数取得
                $ii = null;
                $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = $tb");
                $coun_iine = 0;
                while ($r_ii = $ps_ii->fetch()) {
                    $ii = $ii . " " . $th_nick;
                    $coun_iine++;
                }
 
                // ブラックリストに入っているか確認
                $flag_bk = 0;
                // セッションが存在している(ログオンしている)
                if(isset($_SESSION['uid'])) {
                    $uid = $_SESSION['uid'];
                // セッションが存在していない(ログオンしていない)
                } else {
                    $uid = 1;
                }
                $ps_bk = $webdb->query("SELECT * FROM `blacklists` WHERE `uid` = '" . $uid . "'");
                while ($r_bk = $ps_bk->fetch()) {
                    // ブロックしているユーザの時
                    if ($r_bk['black_uid'] == $th_uid) {
                        $flag_bk = 1;
                    }
                }
                
?>
                <div id='box'>
<?php
                    // ブロックしていないアカウントの場合、表示
                    if ($flag_bk == 0) {
                        // 非公開である
                        if ($r['ope'] == 0) {
                            print "<p style='color: red;'>管理者により非公開に設定されています</p>";
                        }
                        // 公開または、非公開だが投稿者本人または管理者
                        if (((isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) && (
                            ($th_uid == $_SESSION['uid']) || ($_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2'))) || $r['ope'] == 1) {
?>
                            <?php print $r['thread_number']?>
                            【投稿者:<a href='gz_mypage.php?uid=<?=$th_uid?>'><?php print $th_nick;?></a>】作成日:<?=$r['date'];?>
                            <div>最終更新:<?=$r['update_date'];?></div>
                            <form method="post" id="upiine" style="display:none;">
                                    <input type = "hidden" name = "myb_ii" value = "<?php print $tb; ?>">
                                    <input type="submit" value="イイネ！" onclick="return confirm('イイネ！します。')">
                            </form>
                            <p class='iine'>イイネ！(<?=$coun_iine?>)</p><hr>
                            <p class='thread_title'><?= $r['title'] ?></p><hr>
                            <?=nl2br($r['text']);?><br>
<?php
                            if (isset($tg) && $tg != "") {
?>
                                <a href='./gz_img/<?=$tg?>' target='_blank'>
                                    <img src='./gz_img/thumb_<?=$tg?>'>
                                </a><br>
<?php
                            }
?>
                            <!-- 編集ボタン -->
                            <form action="gz_up.php" method="post" id="edit" style="display:none;">
                                    <input type = "hidden" name = "myb" value = "<?php print $tb; ?>">
                                    <input type="submit" value="編集する" style="background-color:yellow;">
                            </form>
                            <hr><hr>
<?php
                            // コメントの表示
                            $ps_com = $webdb->query("SELECT * FROM `comments` WHERE `thread_number` = $tb ORDER BY `date` DESC");
                            $coun = $ps_com->rowCount();
                            while ($r_com = $ps_com->fetch()) {
                                $com_uid = $r_com['uid'];
                                // ニックネームの取得
                                $ps_nick_com = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $com_uid . "'");
                                while ($r_nick_com = $ps_nick_com->fetch()) {
                                    $com_nick = $r_nick_com['nick'];
                                }
                                // ブラックリストに入っているか確認
                                $flag_bk_com = 0;
                                // セッションが存在している(ログオンしている)
                                if(isset($_SESSION['uid'])) {
                                    $uid = $_SESSION['uid'];
                                // セッションが存在していない(ログオンしていない)
                                } else {
                                    $uid = 1;
                                }
                                $ps_bk_com = $webdb->query("SELECT * FROM `blacklists` WHERE `uid` = '" . $uid . "'");
                                while ($r_bk_com = $ps_bk_com->fetch()) {
                                    // ブロックしているユーザの時
                                    if ($r_bk_com['black_uid'] == $com_uid) {
                                        $flag_bk_com = 1;
                                    }
                                }
                                // ブロックしていないユーザの時、出力
                                if ($flag_bk_com == 0) {
?>
                                    <p class='com'>●投稿コメント<?=$coun?><br>【<?php print $com_nick?>さんのメッセージ】
                                    <?=$r_com['date']?><br><?=nl2br($r_com['text'])?></p>
<?php   
                                }
                                $coun--;
                            }
?>
                            <!-- コメント入力欄 -->
                            <form method="post" id="upcom" style="display:none;">
                                コメント<BR>
                                <textarea name = "myc" rows = "5" cols = "60" maxlength='250' 
                                    placeholder='最大２５０文字' required></textarea><br>
                                <input type = "hidden" name = "myb" value = "<?php print $tb; ?>">
                                <input type="submit" value="送信">
                            </form>
<?php
                        }
                    // ブロックしている
                    } else {
                        print "<p style='color: red;'>ブロックユーザによる投稿</p>";
                    }
?>
                </div> 
<?php
            }
        
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
                    // イイネボタン表示
                    upiine.style.display = "block";
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
                // ブラックリストに入っている
                } else {
?>
                    <script>
                        message.innerHTML = 'このスレッドは表示できません。投稿ユーザをブロックしている可能性があります。';
                    </script>
<?php
                }

                // 自分が投稿したスレッド
                if ($th_uid == $_SESSION['uid']) {
?>
                    <script>
                        // 編集ボタンを表示
                        edit.style.display = "block";
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