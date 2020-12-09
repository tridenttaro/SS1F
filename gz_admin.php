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

if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm']) && $_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
    $_SESSION['tm'] = time();
    setcookie ("gz_user", $_SESSION['uid'], time()+60*60*24*365);
    setcookie ("gz_date", date('Y円m月d日H時i分s秒'), time()+60*60*24*365);
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>ソリューションシェア　管理画面</TITLE>
    <LINK REL='stylesheet' HREF='gz_style_file.css' TYPE='text/css'>
</HEAD>
<BODY style="background-color:gray">
    <div id="ue">
        <p class="title">ソリューションシェア　管理画面</p>
    </div>
    <div id="main">
        <p id="message"></p>
        <h3>ここは管理者のページです</h3>
        <FORM ACTION="gz_admin_op.php" METHOD="post">
<?php
            // データベース設定
            require_once("db_init.php");
            $ps = $webdb->query("SELECT * FROM `threads` ORDER BY `thread_number` DESC");
            while ($r = $ps->fetch()) {
                $tb = $r['thread_number'];
                $to = $r['ope'];
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
                    非公開：<INPUT TYPE = checkbox NAME = check[] VALUE = <?=$tb?> <?php if ($to == 0) { print "CHECKED = checked";} ?> ><br>
                    <?=print $r['thread_number']?>
                    【投稿者:<a href='gz_mypage.php?uid=<?=$r['uid']?>'><?=$th_nick;?></a>】<?=$r['date'];?><br>
                    <p class='iine'>イイネ(<?=$coun_iine?>)</p><hr>
                    <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
                </div>
<?php
                
            }
?>
            <INPUT TYPE = "submit" VALUE='公開・非公開の送信'>
        </FORM>
    </div>

    <div id='hidari'>
        <div id='toppage'><br><a href='gz.php'>トップページ</a></div>
        <div id='upload'><br><a href='gz_up.php'>アップロードはここ</a></div>
        <div id='mypage'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'>マイページ</a></div>
        <div id='admin'><br><br><a href='gz_admin.php'>管理者ページ</a></div>
        <br><br>
        <form method="post" id='logoff' style='display:none;'>
            <button type="submit" name="action" value="logoff" 
                onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
        </form>
    </div>

<?php
} else {
    session_destroy();
    print "<P>このページにはアクセス権限がありません。<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
</BODY>
</HTML>
