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
            $uid = $_SESSION['uid'];
            // データベース設定
            require_once("db_init.php");
            $ps = $webdb->query("SELECT * FROM `threads` WHERE `ope` = 1 ORDER BY `thread_number` DESC");
            while ($r = $ps->fetch()) {
                $ps2 = $webdb->query("SELECT `thread_number` FROM `favorites` WHERE `uid` = '" . $uid . "'");

                while ($r2 = $ps2->fetch()) {
                    if ($r['thread_number'] == $r2['thread_number']) {

                        $tg = $r['image'];
                        $tb = $r['thread_number'];
                        $ii = null;
?>
                        <DIV ID='box'>
                            <p> 
                                <?=print $r['thread_number'] . "【投稿者:" . $r['thread_nick'] . "】" . $r['date'];?>
                                <br>
                                <?=nl2br($r['text']);?><br>
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
        print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
    }
?>

</BODY>
</HTML>
