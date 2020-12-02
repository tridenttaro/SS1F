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
                        <div id='box'>
                                <?=print $r['thread_number'] . "【投稿者:" . $r['thread_nick'] . "】" . $r['date'];?><br>
                                <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
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
                <form method="post" id='logoff'>
                    <button type="submit" name="action" value="logoff" 
                        onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
                </form>
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
