<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv='content-type' content='text/html;charset=utf-8'>
    <title>イイネを送信します</title>
</head>

<body style = 'background-color:lightblue'>
<?php
if (isset($_GET['tran_b'])) {
    if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
        $_SESSION['tm'] = time();
    
        $b = $_GET['tran_b'];
?>
        <p><?php print $b;?>番の投稿に<u>イイネ！</u>と言いました</p>
        名前を入力してください<br>
        <form action="gz_iine_set.php" method="post">
            名前<br>
            <input type = "text" name = "myn" value = "<?php print $_SESSION['nick']; ?>"><br>
            <input type = "hidden" name = "myb" value="<?php print $b; ?>">
            <input type="submit" value="送信">
        </form>

<?php
    } else {
        session_destroy();
        print "<p>ちゃんとログオンしてね！<br>
                <a href='gz.php'>トップページ</a><br><br>
                <a href='gz_logon.php'>ログオン</a></p>";
    }
} else {
    print "<p>正しい画面から遷移してください</p>";
}
?>

</body>
</html>