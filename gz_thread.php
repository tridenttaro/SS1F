<?php
session_start();

if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
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
        <a href='gz_logon.php' id='logon' style='display:none;'>ログオン</a>
        <p>
            <a href='gz_up.php' id='upload' style='display:none;'>アップロードはここ</a><br>
            <a href='gz.php' id='toppage'>トップページ</a><br><br>
            <a href='gz_mypage.php' id='mypage' style='display:none;'>マイページ</a><br><br>
            <a href='gz_admin.php' id='admin' style='display:none;'>管理者ページ</a><br>
            
            <form method="post" id='logoff' style='display:none;'>
                <button type="submit" name="action" value="logoff" 
                    onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
            </form>
        </p>
    </div>
    <div id="main">
        <h3>スレッド詳細画面</h3>
        <p id="message"></p>
<?php
        if (isset($_GET['tran_b'])) {
            $b = $_GET['tran_b'];

            // データベース設定
            require_once("db_init.php");

            $ps = $webdb->query("SELECT * FROM `threads` WHERE `thread_number` = $b");
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
                    <?=$r['thread_number']?>【投稿者:<?=$r['thread_nick']?>】<?=$r['date']?>
                    <p class='iine'><a href='gz_iine.php?tran_b=<?=$tb?>'>イイネ!</a> (<?=$coun_iine?>):<?=$ii?></p>
                    <p class='thread_title'><?= $r['title'] ?></p>
                    <?=nl2br($r['text']);?><br>
                    <a href='./gz_img/<?=$tg?>' TARGET='_blank'>
                        <img src='./gz_img/thumb_<?=$tg?>'>
                    </a><br><hr>
                    <p class='com'><a href='gz_com.php?sn=<?=$tb?>'>コメントするときはここをクリック</a></p>          
<?php
                    $ps_com = $webdb->query("SELECT * FROM `comments` WHERE `thread_number` = $tb");
                    $coun = 1;
                    while ($r_com = $ps_com->fetch()) {
?>
                        <p class='com'>●投稿コメント<?=$coun?><br>【<?=$r_com['com_nick']?>さんのメッセージ】
                            <?=$r_com['date']?><br><?=nl2br($r_com['text'])?></p>
<?php
                        $coun++;
                    }
?>
                </div>  
<?php
            }

            if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
                $_SESSION['tm'] = time();
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