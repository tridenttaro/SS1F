<!--////////////////////////////////////////////////////-->
<!--//テスト用に作ったもの　 使用しません-->
<!--////////////////////////////////////////////////////-->



<?php
session_start();

if (isset($_POST['nick'])) {
    $_SESSION['nick'] = $_POST['nick'];
}
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && $_SESSION['tm'] >= time()-300) {
    $uid = htmlspecialchars($_SESSION['uid'], ENT_QUOTES);
    $nick = htmlspecialchars($_SESSION['nick'], ENT_QUOTES);
    $_SESSION['tm'] = time();
} else {
    $uid = null;
    $nick = null;
}
?>

<!DOCTYPE html>
    
<html lang="ja">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title>ようこそ！　たび写真館</title>
    
</head>
<body style="background-color:lightblue;">
<?php
    if (isset($uid) && isset($nick) && isset($_SESSION['tm'])) {
        
        print "こんにちは" . $nick . "さん。";
        
        // データベースに追加
        require_once("db_init.php");
        
        $ps = $db->prepare("INSERT INTO `table2.1`(`id`, `nick`) VALUES (:v_i, :v_n)");
        $ps->bindParam(':v_i', $uid);
        $ps->bindParam(':v_n', $nick);
        $ps->execute();
        
        
        
    } else if(isset($_SESSION['tm'])) {
        print "セッションの有効期限が切れています。<br>ログインしなおしてください" 
             . "<a href='gz_logon.php'>ログインはこちら</a>";
        
        // テスト
        print "id: " . $_SESSION['uid'] . "nick: " . $_SESSION['nick'] . "tm: " . $_SESSION['tm'];
    } else {
        print "こんにちは名無しさん。";
    }
?>
 

    
</body>
</html>