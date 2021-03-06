<!-- スレッドアップロードページ2 -->

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
    <title>アップロード結果</title>
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
    <div id='main' class="container-fluid">
        <p id="message"></p>
<?php
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();            

            $file = $_FILES['myf'];
            if ($_POST['mym']<>"" && $_POST['myt']<>"" && $file['size']>0
                && ($file['type']== 'image/jpeg' || $file['type']=='image/pjpeg')
                && (strtolower(mb_strrchr($file['name'],'.',FALSE)) == ".jpg")) {

                if ($file['size']>1024*1024){
                    unlink($file['tmp_name']);
                    print "<p>アップするファイルのサイズは1MB以下にしてください<br>";
                    
                } else {
                    $ima = date('YmdHis');
                    $fn = $ima . $file['name'];
                    move_uploaded_file($file['tmp_name'], './gz_img/'.$fn);

                    $my_tit = htmlspecialchars($_POST['myt'],ENT_QUOTES);
                    $my_mes = htmlspecialchars($_POST['mym'],ENT_QUOTES);
                    $my_gaz = $fn;
                    //サムネイルの作成
                    $motogazo = @imagecreatefromjpeg("./gz_img/$fn");
                    list($w, $h) = getimagesize("./gz_img/$fn");
                    $new_h = 200;
                    $new_w = $w * 200 / $h;
                    $mythumb = imagecreatetruecolor($new_w, $new_h);
                    imagecopyresized($mythumb, $motogazo, 0, 0, 0, 0,
                                    $new_w,$new_h,$w,$h);
                    imagejpeg($mythumb, "./gz_img/thumb_$fn");

                    // サムネイルの表示
                    print $file['name'] . "のアップロードが完了しました。<BR>" .
                        "<img src='./gz_img/thumb_$fn'>";
                    
                    // スレッドの編集(書き換え)
                    if (isset($_POST['edit'])) {
                        $th_num = $_SESSION['th_num'];
                        
                        //データベースの設定
                        require_once("db_init.php");
                        $ps = $webdb->prepare("UPDATE `threads` SET `title` = (:v_t), `text` = (:v_m), `image` = (:v_g), `update_date` = (:v_ud) 
                                                WHERE `thread_number` = '" . $th_num . "'");
                        $ps->bindParam(':v_t', $my_tit);
                        $ps->bindParam(':v_m', $my_mes);
                        $ps->bindParam(':v_g', $fn);
                        $ps->bindParam(':v_ud', $ima);
                        $ps->execute();

                    // スレッドの新規作成
                    } else {
                        //データベースの設定
                        require_once("db_init.php");
                        $ps = $webdb->prepare("INSERT INTO `threads` (`uid`, `title`, `text`, `ope`, `image`, `date`, `update_date`)
                                            VALUES (:v_u, :v_t, :v_m, 1, :v_g, :v_d, :v_ud)");
                        $ps->bindParam(':v_u', $_SESSION['uid']);
                        $ps->bindParam(':v_t', $my_tit);
                        $ps->bindParam(':v_m', $my_mes);
                        $ps->bindParam(':v_g', $fn);
                        $ps->bindParam(':v_d', $ima);
                        $ps->bindParam(':v_ud', $ima);
                        $ps->execute();
                    }

                    
                }
            } else if ($_POST['mym']<>"" && $_POST['myt']<>"") {
                $ima = date('YmdHis');
                $my_tit = htmlspecialchars($_POST['myt'],ENT_QUOTES);
                $my_mes = htmlspecialchars($_POST['mym'],ENT_QUOTES);
            
                print "アップロードが完了しました。(画像無し)\n\n";

                // スレッドの編集(書き換え)
                if (isset($_POST['edit'])) {
                    $th_num = $_SESSION['th_num'];
                    //データベースの設定
                    require_once("db_init.php");
                    $ps = $webdb->prepare("UPDATE `threads` SET `title` = (:v_t), `text` = (:v_m), `update_date` = (:v_ud) 
                                            WHERE `thread_number` = '" . $th_num . "'");
                    $ps->bindParam(':v_t', $my_tit);
                    $ps->bindParam(':v_m', $my_mes);
                    $ps->bindParam(':v_ud', $ima);
                    $ps->execute();

                // スレッドの新規作成
                } else {
                    //データベースに追加
                    require_once("db_init.php");

                    $ps = $webdb->prepare("INSERT INTO `threads` (`uid`, `title`, `text`, `ope`, `date`, `update_date`)
                                        VALUES (:v_u, :v_t, :v_m, 1, :v_d, :v_ud)");
                    $ps->bindParam(':v_u', $_SESSION['uid']);
                    $ps->bindParam(':v_t', $my_tit);
                    $ps->bindParam(':v_m', $my_mes);
                    $ps->bindParam(':v_d', $ima);
                    $ps->bindParam(':v_ud', $ima);
                    $ps->execute();
                }

            } else {
                print "<p>必ず名前、タイトル、本文を入力してください<br>
                        <a href='gz_up.php'>再度アップロード</a></p>";
            }
?>
            <br><br>
            <form method="post" name="form3" action="index.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form3.submit()">トップページ</a>
            </form>
            <div id='upload'>
                <br>
                <a href='gz_up.php'>再度投稿する</a>
            </div>


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
    <script type="text/javascript" src="js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.js"></script>
    
</body>
</html>
