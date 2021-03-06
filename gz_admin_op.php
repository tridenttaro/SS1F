<!-- 管理者ページから非公開設定を行った場合に遷移するぺージ -->

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
        location.href = "./index.php";
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
    <link rel='stylesheet' href='css/bootstrap.css' type='text/css'>
    <link rel="manifest" href="./manifest.json">
    <script>
        if('serviceWorker' in navigator){
            navigator.serviceWorker.register('./service-worker.js').then(function(){
                console.log("Service Worker is registered!!");
            });
        }
    </script>
</head>
<body style="background-color:beige">
    <!-- ヘッダー部分 -->
    <header class="sticky-top">
        <div class="p-3 mb-2 bg-success text-white">
            <div class ="row">
                <div calss="col-sm" id='toppage'>
                    <form method="post" name="form1" action="index.php">
                        <input type="hidden" name="top" value="1">
                        <a class="white" href="javascript:form1.submit()"><h1>ソリューションシェア</h1></a>
                    </form> 
                </div>
                <div class="col clearfix">
                    <div class="col-sm">
                        <div class="float-right">
                            <div class ="row">
                                <div id='toppage'>
                                    <form method="post" name="top_page" action="index.php">
                                        <input type="hidden" name="top" value="1">
                                        <a class="white" href="javascript:top_page.submit()">
                                            <button type="button" class="btn btn-light" style='margin-right:1em;'>トップ</button>
                                        </a>
                                    </form>
                                </div>
                                <div id='upload'><a href='gz_up.php'><button type="button" class="btn btn-light">スレッド作成</button></a></div>
                                <div id='mypage'><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'><button type="button" class="btn btn-light">マイページ</button></a></div>
                                <div id='admin'>
                                    <form method="post" name="form2" action="gz_admin.php">
                                        <input type="hidden" name="top" value="1">
                                        <a href="javascript:form2.submit()"><button type="button" class="btn btn-light">管理者ページ</button></a>
                                    </form> 
                                </div>
                                <form method="post" id='logoff'>
                                    <button type="submit" class="btn btn-light" name="action" value="logoff" 
                                        onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
                                </form>
                            </div>
                        </div>                  
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- メイン表示部分 -->
    <div class="container-fluid">
        <div id="main" class="mx-auto" style="background-color: white;">
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
        <p><a href='index.php'>トップページ</a></p>
        <p><a href='gz_admin.php'>管理者ページ</a></p>
        

    </div>
    </div>

<?php
} else {
    session_destroy();
    print "<P>エラー：アクセス権限がありません。<BR>
            <A HREF='index.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>
    
</body>
</html>