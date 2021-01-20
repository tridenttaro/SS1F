<!-- スレッドアップロードページ1 -->

<?php
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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>アップロード画面</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
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
    <div id='main'class="container-fluid">
        <p id="message"></p>
<?php
        // ログオンしている
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();        
            
            // スレッド編集ボタンから遷移
            if (isset($_POST["myb"])) {
                $th_num = $_SESSION['tb'];

                // データベース設定
                require_once("db_init.php");
                $ps = $webdb->query("SELECT * FROM `threads` WHERE `thread_number` = $th_num");
                while ($r = $ps->fetch()) {
                    $title = $r['title'];
                    $text = $r['text'];
                    $image = $r['image'];

                    // セッションに代入(受け渡し用)
                    $_SESSION['th_num'] = $th_num;
?>
                    <form enctype = 'multipart/form-data' action = 'gz_up_set.php' method = 'post'>
                        <input type='hidden' name='edit' value="1"><br>
                        タイトル<br>
                        <input type='text' name='myt' maxlength='30' pattern="\S|\S.*?\S"
                            placeholder='タイトル：最大３０文字' value="<?php print $title; ?>" style="width:80%;" required><br>
                        本文<br>
                        <textarea name='mym' rows='10' maxlength='2040'
                            placeholder='本文：最大２０４０文字' style="width:80%;" required><?php print $text; ?></textarea><br>
                        <input type = 'file' name='myf' accept='image/png, image/jpeg, image/gif'>
                        <p><b>※画像を新たに設定する場合、元の画像は消去されます！</b><br>
                        送信できるのは1mbまでのjpeg画像だけです！<br>
                        また展開後のメモリ消費が多い場合アップロードできません。<br>
                            <input type='submit' value='送信'><br>
                        </p>
                    </form>
<?php
                }
            // スレッド新規作成
            } else {
?>
                <form enctype = 'multipart/form-data' action = 'gz_up_set.php' method = 'post'>
                    <div class="form-group">
                    タイトル<br>
                    <input type='text' name='myt' class="form-control form-control-lg" maxlength='30' pattern="\S|\S.*?\S"
                           placeholder='タイトル：最大３０文字' style="width:80%;" required></div>
                    <div class="form-group">
                    本文<br>
                    <textarea name='mym' rows='10' class="form-control form-control-lg" maxlength='2040'
                              placeholder='本文：最大２０４０文字' style="width:80%;" required></textarea></div><br>
                    <input type = 'file' name='myf' accept='image/png, image/jpeg, image/gif'>
                    <p>送信できるのは1mbまでのjpeg画像だけです！<br>
                    また展開後のメモリ消費が多い場合アップロードできません。<br>
                        <input type='submit' value='送信'><br>
                    </p>
                </form>
                <br><br>
<?php
            }
?>
            <script>
                message.innerHTML = "アップロード画面";
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
        } else {
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
