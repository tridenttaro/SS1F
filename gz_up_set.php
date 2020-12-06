<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http equiv='content-type' content='text/html;charset=utf-8'>
    <title>アップロード完了</title>
</head>
    
<body style='background-color:khaki'>

<?php
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();
    $file = $_FILES['myf'];

    if ($_POST['myn']<>"" && $_POST['mym']<>"" && $_POST['myt']<>"" && $file['size']>0
        && ($file['type']=='image/jpeg' || $file['type']=='image/pjpeg' || $file['type']=='image/png')
        && (strtolower(mb_strrchr($file['name'],'.',FALSE)) == ".jpg")) {

        if ($file['size'] > 1024*1024){
            unlink($file['tmp_name']);
            print "<p>アップするファイルのサイズは1mb以下にしてください<br>
            <a href='gz_up.php'>アップに戻る</a></p>";
            
        }else{
            $ima = date('YmdHis');
            $fn = $ima . $file['name'];
            move_uploaded_file($file['tmp_name'], './gz_img/'.$fn);

            $my_nam = htmlspecialchars($_POST['myn'],ENT_QUOTES);
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

            //サムネイルの表示
            print $file['name'] . "のアップロードに成功！<BR>" .
                 "<IMG SRC='./gz_img/thumb_$fn'>";

            //データベースに追加
            require_once("db_init.php");

            $ps = $webdb->prepare("INSERT INTO `threads` (`uid`, `thread_nick`, `title`, `text`, `ope`, `image`, `date`)
                                VALUES (:v_u, :v_n, :v_t, :v_m, 1, :v_g, :v_d)");
            $ps->bindParam(':v_u', $_SESSION['uid']);
            $ps->bindParam(':v_n', $my_nam);
            $ps->bindParam(':v_t', $my_tit);
            $ps->bindParam(':v_m', $my_mes);
            $ps->bindParam(':v_g', $fn);
            $ps->bindParam(':v_d', $ima);
            $ps->execute();
            print "<p><a href=gz.php>一覧表示へ</a></p>";
        }
    } else if ($_POST['myn']<>"" && $_POST['mym']<>"" && $_POST['myt']<>"") {
        $ima = date('YmdHis');

        $my_nam = htmlspecialchars($_POST['myn'],ENT_QUOTES);
        $my_tit = htmlspecialchars($_POST['myt'],ENT_QUOTES);
        $my_mes = htmlspecialchars($_POST['mym'],ENT_QUOTES);
       
        print "アップロードに成功！";

        //データベースに追加
        require_once("db_init.php");

        $ps = $webdb->prepare("INSERT INTO `threads` (`uid`, `thread_nick`, `title`, `text`, `ope`, `date`)
                            VALUES (:v_u, :v_n, :v_t, :v_m, 1, :v_d)");
        $ps->bindParam(':v_u', $_SESSION['uid']);
        $ps->bindParam(':v_n', $my_nam);
        $ps->bindParam(':v_t', $my_tit);
        $ps->bindParam(':v_m', $my_mes);
        $ps->bindParam(':v_d', $ima);
        $ps->execute();
        print "<p><a href=gz.php>一覧表示へ</a></p>";

    }else{
        print "<p>必ず名前、タイトル、本文を入力してください<br>
                <a href='gz_up.php'>再度アップロード</a></p>";
    }
    
}else{
    session_destroy();
    print "<p>ちゃんとログオンしてね！<br>
            <a href='gz.php'>トップページ</a><br><br>
            <a href='gz_logon.php'>ログオン</a></p>";
}
?>

</body>
</html>
