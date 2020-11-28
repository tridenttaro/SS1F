<?php
session_start();
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>イイネ一覧</TITLE>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</HEAD>
    
<BODY style='background-color:lightblue'>
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
<?php
    if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
        $_SESSION['tm'] = time();
?>
        <div id="main">
            <h1>イイネしたスレッド一覧</h1>
            <p id="message"></p>


<?php
            $nick = $_SESSION['nick'];
            require_once("db_init.php");
            $ps = $db->query("SELECT * FROM table1 WHERE ope=1 ORDER BY ban DESC");
            while ($r = $ps->fetch()) {
                $ps2 = $db->query("SELECT ban FROM table4 WHERE nam = '$nick'");
                while ($r2 = $ps2->fetch()) {
                    if ($r['ban'] == $r2['ban']) {

                        $tg = $r['gaz'];
                        $tb = $r['ban'];
                        $ii = null;
?>
                        <DIV ID='box'>
                            <p> 
                                <?=print $r['ban'] . "【投稿者:" . $r['nam'] . "】" . $r['dat'];?>
                                <br>
                                <?=nl2br($r['mes']);?><br>
                                <a href='./gz_img/<?=$tg?>' TARGET='_blank'>
                                    <img src='./gz_img/thumb_<?=$tg?>'>
                                </a><br>
                            </p>
                        </div>
<?php  
                    }
                }
            }
?>
        </div>
        <div id='hidari'>
            <p>
                <a href='gz.php' id='toppage'>トップページ</a><br><br>
                <a href='gz_logon.php' id='logout'>ログオフ</a>
            </p>
        </div>
<?php
    } else {
        session_destroy();
        print "<P>アップロードにはログオンが必要です<BR>
                <A HREF='gz_logon.php'>ログオン</A></P>";
    }
?>

</BODY>
</HTML>
