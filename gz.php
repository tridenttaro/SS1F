<?php
session_start();
// ログオフボタン押下
if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}
// 初回ログイン時、ニックネーム設定
if (isset($_POST['nick']) && $_POST['nick'] != "") {
    $_SESSION['nick'] = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');
}

// 検索機能php部分の読み込み
require_once("search_set.php");
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
        // 検索機能のHTML部分読み込み
        require_once("search_form.php");
 
        
        // データベース設定
        require_once("db_init.php");
        $ps_thcoun = $webdb->query("SELECT `thread_number` FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%' and `ope` = 1");
        $coun_th = 0;
        // 検索結果件数
        while ($r_thcoun = $ps_thcoun->fetch()) {
            $coun_th++;
        }
        print "<p>検索結果は" . $coun_th . "件でした<br>";

        // 何項目ずつ表示するか
        if (isset($_GET["page"]) && !(isset($_POST["search"])) ) {
            $page = $_GET["page"];    
        } else {
            $page = 1;
        }
        
        $this_page = ($page - 1) * $page_num;
        // 検索結果の表示
        $ps = $webdb->query("SELECT * FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%' and `ope` = 1 ORDER BY `date` " .$date_sort." LIMIT ".$this_page."," .$page_num);
        while ($r = $ps->fetch()) {
            $id_rows[] = $r['thread_number'];

            $tb = $r['thread_number'];
            $th_uid = $r['uid'];
            // ニックネームの取得
            $ps_nick = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $th_uid . "'");
            while ($r_nick = $ps_nick->fetch()) {
                $thread_nick = $r_nick['nick'];
            }
            // イイネの個数取得
            $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = '" . $tb . "'");
            $coun_iine = 0;
            while ($r_ii = $ps_ii->fetch()) {
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
            // ブロックしていないアカウントの場合、表示
            if ($flag_bk == 0) {
?>
                <div id='box'>
                    <?php print $r['thread_number']?>
                    【投稿者:<a href='gz_mypage.php?uid=<?=$r['uid']?>'><?php print $thread_nick ?></a>】<?=$r['date'];?><br>
                    <p class='iine'>イイネ(<?=$coun_iine?>)</p><hr>
                    <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
                </div>
<?php            
            }
        }        
?>
        <br><br>
        <!-- ページ遷移 -->
        <ul class="example">
            <?php if ($page != 1){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page - 1) . '">前へ</a>'; ?></li><?php } else { ?>
            <li><div style="color:gray;">前へ</div></li><?php } ?>
            <?php if ($page > 2){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page - 2) . '">'. ($page - 2) .'</a>'; ?></li><?php } ?>
            <?php if ($page > 1){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page - 1) . '">'. ($page - 1) .'</a>'; ?></li><?php } if($coun_th != 0){ ?>
            <li class="this"><?php echo $page.'</a>'; ?></li><?php } 
            if ($page < ceil($coun_th/$page_num )  ){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page + 1) . '">'. ($page + 1) .'</a>'; ?></li><?php }
            if ($page < ceil($coun_th/$page_num ) - 1 ){ ?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page + 2) . '">'. ($page + 2) .'</a>'; ?></li><?php } 
            if (($page != ceil($coun_th/$page_num ))&&($coun_th != 0)) {?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page + 1) . '">次へ</a>'; ?></li><?php } else { ?>
            <li><div style="color:gray;">次へ</div></li><?php } ?>
            
        </ul>
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
        <div id='admin' style='display:none;'><br><br><a href='gz_admin.php'>管理者ページ</a></div>
        <br><br>
        <form method="post" id='logoff' style='display:none;'>
            <button type="submit" name="action" value="logoff" 
                onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
        </form>

    </div>
     
<?php
    // ログインしている
    if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
        $_SESSION['tm'] = time();
        // setcookie("gz_user", $_SESSION['uid'], time()+60*60*24*365);
        // setcookie("gz_date", date('Y年m月d日H字i分s秒'), time()+60*60*24*365);
        // ユーザを追加
        // データベースの設定
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