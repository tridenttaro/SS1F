<?php
session_start();
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>アップロード完了</TITLE>
</HEAD>
    
<BODY style='background-color:khaki'>

<?php
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();
    $file = $_FILES['myf'];

    if ($_POST['myn']<>"" && $_POST['mym']<>"" && $file['size']>0
        && ($file['type']=='image/jpeg' || $file['type']=='image/pjpeg' || $file['type']=='image/png')
        && (strtolower(mb_strrchr($file['name'],'.',FALSE)) == ".jpg")) {

        if ($file['size'] > 1024*1024*10){
            unlink($file['tmp_name']);
            print "<P>アップするファイルのサイズは10MB以下にしてください<BR>
            <A HREF='gz_up.php'>アップに戻る</A></P>";
            
        }else{
            $ima = date('YmdHis');
            $fn = $ima . $file['name'];
            move_uploaded_file($file['tmp_name'], './gz_img/'.$fn);

            $my_nam = htmlspecialchars($_POST['myn'],ENT_QUOTES);
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

            $ps = $db->prepare("INSERT INTO table1 (nam,mes,ope,gaz,dat)
                                VALUES (:v_n,:v_m,1,:v_g,:v_d)");
		    $ps->bindParam(':v_n', $my_nam);
            $ps->bindParam(':v_m', $my_mes);
            $ps->bindParam(':v_g', $fn);
            $ps->bindParam(':v_d', $ima);
            $ps->execute();
            print "<P><A HREF=gz.php>一覧表示へ</A></P>";
        }
    }else{
        print "<P>必ず名前とメッセージを入力しJPEGファイルを選択してください<BR>
                <A HREF='gz_up.php'>再度アップロード</A></P>";
    }
    
}else{
    session_destroy();
    print "<P>アップロードにはログオンが必要です<BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>

</BODY>
</HTML>
