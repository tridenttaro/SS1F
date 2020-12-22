<?php
error_reporting ("E_ALL & -E_NOTICE");
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>ソリューションシェア　管理画面</title>
    <link rel='stylesheet' href='gz_style_file.css' type='text/css'>
</head>
<body style="background-color:silver">
    <div id="ue">
        <p class="title">ソリューションシェア　管理画面</p>
    </div>
    <div id="main">
        <p id="message"></p>
        <h3>非公開設定が完了しました</h3>
    
<?php
    // データベース設定
    require_once("db_init.php");
    $n = $webdb->exec("UPDATE `threads` SET `ope` = 1");
    foreach ($_POST['check'] as $a => $b) {
        $n = $webdb->exec("UPDATE `threads` SET `ope` = 0 WHERE `thread_number` = $b");
        print $b . "は非公開です<BR>";
    }
?>    
        <p><a href='gz.php'>トップページ</a></p>
        <p><a href='gz_admin.php'>管理者ページ</a></p>
        

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