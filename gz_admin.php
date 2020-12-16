<?php
session_start();
// ログオフボタン押下
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
// 管理者でないとアクセス拒否
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm']) && $_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
    $_SESSION['tm'] = time();
    
    // 検索機能php部分の読み込み
    require_once("search_set.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>ソリューションシェア　管理画面</title>
    <link rel='stylesheet' href='gz_style_file.css' type='text/css'>
</head>
<body style="background-color:gray">
    <div id="ue">
        <p class="title">ソリューションシェア　管理画面</p>
    </div>
    <div id="main">
        <p id="message"></p>
        <h3>ここは管理者のページです</h3>
<?php 
        // 検索機能のHTML部分読み込み
        require_once("search_form.php");
?>        
        <form action="gz_admin_op.php" method="post">
            <br>
            <INPUT TYPE = "submit" VALUE='公開・非公開の送信'>
<?php
            // データベース設定
            require_once("db_init.php");
            $ps_thcoun = $webdb->query("SELECT `thread_number` FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%'");
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
            $ps = $webdb->query("SELECT * FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%'
                                    ORDER BY `date` " .$date_sort." LIMIT ".$this_page."," .$page_num);
            while ($r = $ps->fetch()) {
    
                $tb = $r['thread_number'];
                $to = $r['ope'];
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
?>
                <div id='box'>
                    非公開：<INPUT TYPE = checkbox NAME = check[] VALUE = <?=$tb?> <?php if ($to == 0) { print "CHECKED = checked";} ?> ><br>
                    <?=$r['thread_number']?>
                    【投稿者:<a href='gz_mypage.php?uid=<?=$r['uid']?>'><?=$thread_nick;?></a>】<?=$r['date'];?><br>
                    <p class='iine'>イイネ(<?=$coun_iine?>)</p><hr>
                    <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
                </div>
<?php             
            }
?>
        </form>

        <br><br>
        <!-- ページ遷移 -->
        <ul class="example">
            <?php if ($page != 1){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page - 1) . '">前へ</a>'; ?></li><?php } else { ?>
            <li><div style="color:gray;">前へ</div></li><?php } ?>
            <?php if ($page > 2){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page - 2) . '">'. ($page - 2) .'</a>'; ?></li><?php } ?>
            <?php if ($page > 1){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page - 1) . '">'. ($page - 1) .'</a>'; ?></li><?php } if($coun_th != 0){ ?>
            <li class="this"><?php echo $page.'</a>'; ?></li><?php } 
            if ($page < ceil($coun_th/$page_num )  ){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page + 1) . '">'. ($page + 1) .'</a>'; ?></li><?php }
            if ($page < ceil($coun_th/$page_num ) - 1 ){ ?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page + 2) . '">'. ($page + 2) .'</a>'; ?></li><?php } 
            if (($page != ceil($coun_th/$page_num ))&&($coun_th != 0)) {?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page + 1) . '">次へ</a>'; ?></li><?php } else { ?>
            <li><div style="color:gray;">次へ</div></li><?php } ?>
            
        </ul>
    </div>

    <div id='hidari'>
        <div id='toppage'><br>
            <form method="post" name="form1" action="gz.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form1.submit()">トップページ</a>
            </form> 
        </div>
        <div id='upload'><br><a href='gz_up.php'>アップロードはここ</a></div>
        <div id='mypage'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'>マイページ</a></div>
        <div id='admin'><br><br>
            <form method="post" name="form2" action="gz_admin.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form2.submit()">管理者ページ</a>
            </form> 
        </div>
        <br><br>
        <form method="post" id='logoff'>
            <button type="submit" name="action" value="logoff" 
                onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
        </form>

    </div>

<?php
} else {
    session_destroy();
    print "<P>エラー：アクセス権限がありません。<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
</body>
</html>
