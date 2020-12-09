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
    <title>アップロード結果</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
    
<body style="background-color:beige">
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>

    <div id='hidari'>
        <div id='logon' style='display:none;'><br><a href='gz_logon.php'>ログオン</a></div>

        <div id='toppage'><br><a href='gz.php'>トップページ</a></div>
        <div id='upload' style='display:none;'><br><a href='gz_up.php'>アップロードはここ</a></div>
        <div id='mypage' style='display:none;'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'>マイページ</a></div>
        <div id='admin' style='display:none;'><br><br><a href='gz_admin.php'>管理者ページ</a></div>
        <br><br>
        <form method="post" id='logoff' style='display:none;'>
            <button type="submit" name="action" value="logoff" 
                onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
        </form>
    </div>

    <div id='main'>
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
                        $th_num = $_POST['edit'];
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
            
                print "アップロードが完了しました。(画像無し)";

                // スレッドの編集(書き換え)
                if (isset($_POST['edit'])) {
                    $th_num = $_POST['edit'];
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
 
    
</body>
</html>
