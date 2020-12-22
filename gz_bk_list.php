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
<html lang="ja">
<head>
    <meta http equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>ブラックリスト</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
</head>
    
<body style="background-color:beige">
    <header class="sticky-top">
        <div class="p-3 mb-2 bg-success text-white" >
            
            <div class ="row">
                <div calss="col-sm" id='toppage'><br>
            <form method="post" name="form1" action="gz.php">
                <input type="hidden" name="top" value="1">
                <a class="white" href="javascript:form1.submit()"><h1>ソリューションシェア</h1></a>
            </form> 
        </div>
                
                                <div class="col clearfix">
                    <div class="col-sm">
                <div class="float-right">
                     <div class ="row">

                         <div id='logon' style='display:none;'><br><a href='gz_logon.php'><button type="button" class="btn btn-light">ログオン</button></a></div>
                <div id='toppage'><br>
            
        </div>
                         <div id='upload' style='display:none;'><br><a href='gz_up.php'><button type="button" class="btn btn-light">スレッド作成</button></a></div>
                         <div id='mypage' style='display:none;'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'><button type="button" class="btn btn-light">マイページ</button></a></div>
        <div id='admin' style='display:none;'><br>
            <form method="post" name="form2" action="gz_admin.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form2.submit()"><button type="button" class="btn btn-light">管理者ページ</button></a>
            </form> 
        </div>
        <form method="post" id='logoff' style='display:none;'><br>
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
    

    <div id='main' class="container-fluid">
        <p id="message"></p>
<?php
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();

            // データベース設定
            require_once("db_init.php");
            $uid = $_SESSION['uid'];
            $ps = $webdb->query("SELECT * FROM `blacklists` WHERE `uid` =  '" . $uid . "'");
            while ($r = $ps->fetch()) {
                $bk_uid = $r['black_uid'];

                $ps_u = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $bk_uid . "'");
                        while ($r_u = $ps_u->fetch()) {
?>
                            <div id='card'>
                                <a href='gz_mypage.php?uid=<?=$bk_uid?>'>【<?php print $r_u['nick'];?>】</a><br>
                            </div>
        <br><br>
<?php
                        }
            }
?>
            <script>
                message.innerHTML = "ブロックしたアカウント一覧";
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
        // ログインしていない
        }else{
?>
            <script>
                message.innerHTML = "ログインが必要";
                // ログオンボタン表示
                logon.style.display = "block";
            </script>
<?php
        }
?>       
    </div>
 
    
</body>
</html>
