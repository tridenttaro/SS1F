<?php
session_start();
?>

<!DOCTYPE html>
<html lang=ja>
<head>
    <meta http equiv='content-type' content='text/html;charset=utf-8'>
    <title>アップロード画面</title>
</head>
<body style='background-color:lightblue'>

<?php
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
    $_SESSION['tm'] = time();
?>

    <p style="color:deeppink;font-size:300%">ソリューションシェア</p>
    投稿画面
    <form enctype = 'multipart/form-data' action = 'gz_up_set.php' 
          method = 'post'>
        名前<br>
        <input type='text' name='myn' value="<?php print $_SESSION['nick']; ?>"><br>
        タイトル<br>
        <input type='text' name='myt' maxlength='30' 
            placeholder='タイトル：最大３０文字' required><br>
        本文<br>
        <textarea name='mym' rows='10' cols='70' maxlength='2040' 
            placeholder='本文：最大２０４０文字' required></textarea><br>
        <input type = 'file' name='myf'>
        <p>送信できるのは1mbまでのjpeg画像だけです！<br>
            また展開後のメモリ消費が多い場合アップロードできません。<br>
            <input type='submit' value='送信'><br>
            <a href='gz.php'>一覧表示へ</a>
        </p>
    </form>


<?php
}else{
    session_destroy();
    print "<p>アップロードにはログオンが必要です<br>
            <a href='gz.php'>トップページ</a><br><br>
            <a href='gz_logon.php'>ログオン</a></p>";
}
?>
            
</body>
</html>
