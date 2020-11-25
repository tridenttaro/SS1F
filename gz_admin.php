<?php
session_start();
if (isset($_SESSION['us']) && $_SESSION['us'] != null && $_SESSION['tm'] >= time() - 300) {
    $_SESSION['tm'] = time();
    setcookie ("gz_user", $_SESSION['us'], time()+60*60*24*365);
    setcookie ("gz_date", date('Y円m月d日H時i分s秒'), time()+60*60*24*365);
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>たび写真館　管理画面</TITLE>
    <LINK REL='stylesheet' HREF='gz_style_file.css' TYPE='text/css'>
</HEAD>
<BODY>
    <P>ここは管理者のページです</P>
    <P><A HREF="gz_logoff.php">ログオフ</A></P>
    <FORM ACTION="gz_admin_op.php" METHOD="post">
<?php
        require_once("db_init.php");
        $ps = $db->query("SELECT * FROM table1 ORDER BY ban DESC");
        while ($r = $ps->fetch()) {
            $tg = $r['gaz'];
            $tb = $r['ban'];
            $to = $r['ope'];
            $ii = null;
            $ps_ii = $db->query("SELECT DISTINCT * FROM table4 WHERE ban = $tb");
            $coun_iine = 0;
            while ($r_ii = $ps_ii->fetch()) {
                $ii = $ii . " " . $r_ii['nam'];
                $coun_iine++;
            }
            print "<DIV ID='box'>対象" . $r['ban'] . 
                    "<INPUT TYPE = checkbox NAME = check[] VALUE = $tb";
            if ($to == 0) print " CHECKED = checked";

            print ">非公開<BR>
                {$r['ban']}【投稿者:{$r['nam']}】{$r['dat']}
                <P CLASS='iine'><A HREF=gz_iine.php?tran_b=$tb>イイネ！</A>
                ($coun_iine):$ii" . "</P><BR>" . nl2br($r['mes'])
                ."<BR><A HREF = './gz_img/$tg' TARGET = '_blank'>
                <IMG SRC='./gz_img/thumb_$tg'></A><BR>
                <P CLASS = 'com'><A HREF = 'gz_com.php?sn=$tb'>
                コメントするときはここをクリック</A></P>";


            $ps_com = $db->query("SELECT * FROM table3 WHERE ban = $tb");
            $coun = 1;
            while ($r_com = $ps_com->fetch()) {
                print "<P CLASS='com'>●投稿コメント{$coun}<BR>
                        【{$r_com['nam']}さんのメッセージ】{$r_com['dat']}<BR>"
                        . nl2br($r_com['com']) . "</P>";
                $coun++;
            }
            print "</P></DIV>";
        }
?>
        <INPUT TYPE = "submit" VALUE='公開・非公開の送信'>
    </FORM>

    <P><A HREF = 'gz_up.php'>画像をアップロードするときはここ</A></P>
    <P><A HREF = 'gz_logoff.php'>ログオフ</A></P>

<?php
} else {
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
</BODY>
</HTML>
