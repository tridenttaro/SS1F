<?php
session_start();

if (isset($_POST['nick'])) {
    $_SESSION['nick'] = $_POST['nick'];
}
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();
    $uid = htmlspecialchars($_SESSION['uid'], ENT_QUOTES);
    $nick = htmlspecialchars($_SESSION['nick'], ENT_QUOTES);
    setcookie("gz_user", $_SESSION['uid'], time()+60*60*24*365);
    setcookie("gz_date", date('Y年m月d日H字i分s秒'), time()+60*60*24*365);
} else {
    $uid = null;
    $nick = null;
}
?>
    
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">    
    <title>ソリューションシェア</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
<body>
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
    <div id="main">
        <p id="message"></p>
        <p class="iine">(よかったら<u>イイネ！</u>を押してください)</p>
    
<?php
        require_once("db_init.php");
        $ps = $db->query("SELECT * FROM table1 WHERE ope=1 ORDER BY ban DESC");
        while ($r = $ps->fetch()) {
            $tg = $r['gaz'];
            $tb = $r['ban'];
            $ii = null;
            $ps_ii = $db->query("SELECT DISTINCT * FROM table4 WHERE ban = $tb");
            $coun_iine = 0;
            while ($r_ii = $ps_ii->fetch()) {
                $ii = $ii . " " . $r_ii['nam'];
                $coun_iine++;
            }
            print "<DIV ID='box'>{$r['ban']}【投稿者:{$r['nam']}】{$r['dat']}
                <p class='iine'><a href=gz_iine.php?tran_b=$tb>イイネ!</a>
                ($coun_iine):$ii" . "</p><br>" . nl2br($r['mes']) .
                "<br><a href='./gz_img/$tg' TARGET='_blank'>
                <img src='./gz_img/thumb_$tg'></a><br>
                <p class='com'><a href='gz_com.php?sn=$tb'>
                コメントするときはここをクリック</a></p>";
            $ps_com = $db->query("SELECT * FROM table3 WHERE ban = $tb");
            $coun = 1;
            while ($r_com = $ps_com->fetch()) {
                print "<p class='com'>●投稿コメント{$coun}<br>
                    【{$r_com['nam']}さんのメッセージ】{$r_com['dat']}<br>"
                    . nl2br($r_com['com']) . "</p>";
                $coun++;
            }
            print "</p></div>";  
        }
?>
    </div>
    <div id='hidari'>
        <a href='gz_up.php'>画像をアップロードするときはここ</a>
        <p>
            <a href='gz_mypage.php' id='mypage' style='display:none;'>マイページ</a><br>
            <a href='gz_logon.php' id='logout' style='display:none;'>ログオフ</a>

            <a href='gz_logon.php' id='login' style='display:none;'>ログオン</a>
        </p>
    </div>
     
<?php
    if (isset($uid) && isset($nick) && isset($_SESSION['tm'])) {
        // データベースに追加
        require_once("db_init.php");

        $ps = $db->prepare("INSERT INTO `table2.1`(`id`, `nick`) VALUES (:v_i, :v_n)");
        $ps->bindParam(':v_i', $uid);
        $ps->bindParam(':v_n', $nick);
        $ps->execute();
?>
        <script>
            // ニックネームを表示
            message.innerHTML = 'こんにちは' + '<?php print $nick ?>' + 'さん。'; 
            // ログアウトボタンを表示
            logout.style.display = "block";
            // マイページボタンを表示
            mypage.style.display = "block";
            // ログインボタンを非表示
            login.style.display = "none";
        </script>
<?php
    } else {
?>
        <script>
            message.innerHTML = 'こんにちは名無しさん。';
            // ログアウトボタンを非表示
            logout.style.display = "none";
            // マイページボタンを非表示
            mypage.style.display = "none";
            // ログインボタンを表示
            login.style.display = "block";
        </script>
<?php
    }
?> 
</body>

</html>