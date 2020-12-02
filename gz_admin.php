<?php
session_start();
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
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
        <P>ここは管理者のページです</P>
        <FORM ACTION="gz_admin_op.php" METHOD="post">
<?php
            // データベース設定
            require_once("db_init.php");
            $ps = $webdb->query("SELECT * FROM `threads` ORDER BY `thread_number` DESC");
            while ($r = $ps->fetch()) {
                $tg = $r['image'];
                $tb = $r['thread_number'];
                $to = $r['ope'];
                $ii = null;
                $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = $tb");
                $coun_iine = 0;
                while ($r_ii = $ps_ii->fetch()) {
                    $ii = $ii . " " . $r_ii['fav_nick'];
                    $coun_iine++;
                }
                print "<DIV ID='box'>対象" . $r['thread_nick'] . 
                        "<INPUT TYPE = checkbox NAME = check[] VALUE = $tb";
                if ($to == 0) print " CHECKED = checked";

                print ">非公開<BR>
                    {$r['thread_number']}【投稿者:{$r['thread_nick']}】{$r['date']}
                    <BR>" . nl2br($r['text']) . "<BR><A HREF = './gz_img/$tg' TARGET = '_blank'>
                    <IMG SRC='./gz_img/thumb_$tg'></A><BR>";


                $ps_com = $webdb->query("SELECT * FROM `comments` WHERE `thread_number` = $tb");
                $coun = 1;
                while ($r_com = $ps_com->fetch()) {
                    print "<P CLASS='com'>●投稿コメント{$coun}<BR>
                            【{$r_com['com_nick']}さんのメッセージ】{$r_com['date']}<BR>"
                            . nl2br($r_com['text']) . "</P>";
                    $coun++;
                }
                print "</P></DIV>";
            }
?>
            <INPUT TYPE = "submit" VALUE='公開・非公開の送信'>
        </FORM>
    </div>

    <div id='hidari'>
        <p>
            <a href='gz_mypage.php'>マイページ</a><br><br>
            <p><a href = 'gz.php'>通常画面へ(トップページ)</a></p><br><br>
            <a href='gz_logon.php'>ログオフ</a>
        </p>
    </div>

<?php
} else {
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
</BODY>
</HTML>
