<?php
session_start();

// ログオフボタンを押した
if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}

// ブロックボタンを押した
if (isset($_POST["black_uid"])) {
    $black_uid = htmlspecialchars($_POST['black_uid'], ENT_QUOTES, 'UTF-8');
    //データベース設定
    require_once("db_init.php");

    $ps = $webdb->prepare("INSERT INTO `blacklists` (`uid`, `black_uid`) VALUES (:v_u, :v_bu)");
    $ps->bindParam(':v_u', $_SESSION['uid']);
    $ps->bindParam(':v_bu', $_POST['black_uid']);
    $ps->execute();
?>
    <script>
        // 再読み込み
        location.href = "./gz_mypage.php?uid=<?=$get_uid?>'";
    </script>
<?php    
}

// ブロック解除ボタンを押した
if (isset($_POST["white_uid"])) {
    $white_uid = htmlspecialchars($_POST["white_uid"], ENT_QUOTES, 'UTF-8');
    //データベース設定
    require_once("db_init.php");

    $ps = $webdb->prepare("DELETE FROM `blacklists` WHERE `uid` = (:v_u) AND `black_uid` = (:v_bu)");
    $ps->bindParam(':v_u', $_SESSION['uid']);
    $ps->bindParam(':v_bu', $white_uid);
    $ps->execute();
?>
    <script>
        // 再読み込み
        location.href = "./gz_mypage.php?uid=<?=$get_uid?>'";
    </script>
<?php     
}
// URLが正しい
if (isset($_GET['uid'])) {
    $get_uid = htmlspecialchars($_GET['uid'], ENT_QUOTES, 'UTF-8');
    // uidからニックネームを取り出す
    // データベース設定
    require_once("db_init.php");
    $ps = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $get_uid . "'");
    while ($r = $ps->fetch()) {
        $get_nick = $r['nick'];
    }

    
    $flag_bk = 0;
    // ログインしている
    if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
        // ブラックリストに入っているか確認
        $uid = $_SESSION['uid'];
        $ps_bk = $webdb->query("SELECT * FROM `blacklists` WHERE `uid` = '" . $uid . "'");
        while ($r_bk = $ps_bk->fetch()) {
            if ($r_bk['black_uid'] == $get_uid) {
                $flag_bk = 1;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http equiv='content-type' content='text/html;charset=utf-8'>
    <title>マイページ</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel="manifest" href="./manifest.json">
    <script>
        if('serviceWorker' in navigator){
            navigator.serviceWorker.register('./service-worker.js').then(function(){
                console.log("Service Worker is registered!!");
            });
        }
    </script>
</HEAD>
    
<BODY style="background-color:beige">
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
                                
                                <div id='logon' style='display:none;'>
                                    <a href='gz_logon.php'><button type="button" class="btn btn-light"  style='margin-right:1em;'>ログオン</button></a>
                                </div>
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
    <div class="container-fluid">
        <div id="main" class="mx-auto" >
        <p id="message"></p>

        <div id='thlist' style='display:none;'><br>
            <form method="post" name="uplist" action="gz_up_list.php?uid=<?=$get_uid?>">
                <input type="hidden" name="top" value="1">
                <a href="javascript:uplist.submit()">投稿したスレッド一覧</a>
            </form>        
        </div>
        <div id='iilist' style='display:none;'><br>
            <form method="post" name="iine_list" action="gz_iine_list.php?uid=<?=$get_uid?>">
                <input type="hidden" name="top" value="1">
                <a href="javascript:iine_list.submit()">イイネしたスレッド一覧</a>
            </form>        
        </div>
        <div id='comlist' style='display:none;'><br>
            <form method="post" name="com_list" action="gz_com_list.php?uid=<?=$get_uid?>">
                <input type="hidden" name="top" value="1">
                <a href="javascript:com_list.submit()">コメントしたスレッド一覧</a>        
            </form>       
        </div>
        
        
        <div id='bklist' style='display:none;'><br><a href="gz_bk_list.php">ブロックしたアカウント一覧</a></div>
        <div id='chnick' style='display:none;'><br><a href="gz_rename.php">ニックネーム変更</a></div>
        <!-- ブロックボタン -->
        <form method='post' name='black' id='black' style='display:none;'>
            <br><br>
            <input type="hidden" name="black_uid" value="<?=$get_uid?>">
            <a href="javascript:black.submit()" style="background-color:red; color:gold;"
                onclick="return confirm('ブロックしたユーザの投稿、コメントは表示されなくなります。\nよろしいですか?')"><?=$get_nick?>さんをブロックする</a>
        </form>
        <!-- ブロック解除ボタン -->
        <form method='post' name='form3' id='white' style='display:none;'>
            <br><br>
            <input type="hidden" name="white_uid" value="<?=$get_uid?>">
            <a href="javascript:form3.submit()" style="background-color:blue; color:white;"
                onclick="return confirm('ブロックを解除します。よろしいですか?')"><?=$get_nick?>さんのブロックを解除</a>
        </form>
            <br><br>
    </div>
    </div>

<?php
    //------------------------------
    //       hidari UI部分
    //------------------------------
    // ログインしている
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
    // ログオンしていない
    } else {
?>
        <script>
            // ログオンボタン表示
            logon.style.display = "block";
        </script>
<?php
    }
    //------------------------------
    //       main UI部分
    //------------------------------
    // URLが正しい
    if (isset($get_uid)) {
        // ログインしている
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            // 自分のページである
            if ($get_uid == $_SESSION['uid']) {
?>
                    <script>
                        message.innerHTML = 'マイページ';
                        // 投稿したスレッド一覧ボタンを表示
                        thlist.style.display = "block";
                        // イイネしたスレッド一覧ボタンを表示
                        iilist.style.display = "block";
                        // コメントしたスレッド一覧ボタンを表示
                        comlist.style.display = "block";
                        // ニックネーム変更ボタンを表示
                        chnick.style.display = "block";
                        // ブラックリストボタンを表示
                        bklist.style.display = "block";
                    </script>
<?php
            // ブラックリストに入っていない
            } else {
                if ($flag_bk == 0) {    
?>
                    <script>
                        let nick = <?php echo json_encode($get_nick); ?>;
                        message.innerHTML = nick + 'さんのユーザーページ';
                        // 投稿したスレッド一覧ボタンを表示
                        thlist.style.display = "block";
                        // コメントしたスレッド一覧ボタンを表示
                        comlist.style.display = "block";
                        // ブロックボタンを表示
                        black.style.display = "block";
                    </script>
<?php
                // ブラックリストに入っている
                } else {
?>
                    <script>
                        message.innerHTML = 'このユーザはブロックしています。';
                        // ブロック解除ボタンを表示
                        white.style.display = "block";
                    </script>
<?php
                }
            }
        // ログインしていない
        } else {
?>
             <script>
                let nick = <?php echo json_encode($get_nick); ?>;
                message.innerHTML = nick + 'さんのユーザーページ';
                // 投稿したスレッド一覧ボタンを表示
                thlist.style.display = "block";
                // コメントしたスレッド一覧ボタンを表示
                comlist.style.display = "block";
            </script>
<?php
        }
    // 正しく遷移していない
    } else {      
?>      
        <script>
            message.innerHTML = '正しい画面から遷移して下さい';
        </script>
<?php
    }
?>
            

    
    

    
</BODY>
    </HTML>
