<!-- ニックネーム変更ページ2 -->

<?php
session_start();

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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http equiv='content-type' content='text/html;charset=UTF-8'>
    <title>名前変更</title>
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
                                <div id='logon' style='display:none;'><a href='gz_logon.php'><button type="button" class="btn btn-light">ログオン</button></a></div>
                                <div id='toppage'>
                                    <form method="post" name="top_page" action="index.php">
                                        <input type="hidden" name="top" value="1">
                                        <a class="white" href="javascript:top_page.submit()">
                                            <button type="button" class="btn btn-light" style='margin-right:1em;'>トップ</button>
                                        </a>
                                    </form>
                                </div>
                                <div id='upload' style='display:none;'><a href='gz_up.php'><button type="button" class="btn btn-light">スレッド作成</button></a></div>
                                <div id='mypage' style='display:none;'><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'><button type="button" class="btn btn-light">マイページ</button></a></div>
                                <div id='admin' style='display:none;'>
                                    <form method="post" name="form2" action="gz_admin.php">
                                        <input type="hidden" name="top" value="1">
                                        <a href="javascript:form2.submit()"><button type="button" class="btn btn-light">管理者ページ</button></a>
                                    </form> 
                                </div>
                                <form method="post" id='logoff' style='display:none;'>
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
<?php
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();
?>
            <script>
                // ログオフボタンを表示
                logoff.style.display = "block";
                // アップロードボタンを表示
                upload.style.display = "block";
                // マイページボタンを表示
                mypage.style.display = "block";
            </script>
<?php
        
            // 管理者アカウントである
            if ($_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
?>
                <script>
                    // 管理者ページボタンを表示
                    admin.style.display = "block";
                </script>
<?php   
            }
            
            if (isset($_POST['nick']) && $_POST['nick'] != "") {
                $nick = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');

                // データベースの設定
                require_once("db_init.php");
                
                $ps = $webdb->prepare("UPDATE `users` SET `nick` = :v_n WHERE `uid` = :v_u");
                $ps->bindParam(':v_u', $_SESSION['uid']);
                $ps->bindParam(':v_n', $nick);
                $ps->execute();

                $_SESSION['nick'] = $nick;

                print "<h1>ニックネームの変更が完了しました。</h1>";
                print "<h3>現在のニックネーム「" . $_SESSION['nick']  . "」</h3>";
            } else {
                print "<h1>ニックネームの変更に失敗しました。</h1>";
                print "<h3>現在のニックネーム「" . $_SESSION['nick']  . "」</h3>";
            }
        // ログオンしていない
        } else {
?>
            <script>
                message.innerHTML = 'ログインが必要';
                // ログオンボタン表示
                logon.style.display = "block";
            </script>
<?php
        }
?>
    </div>
    </div>

</body>
</html>
