<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログイン画面</title>
</head>
<body style="background-color:lightblue;">
    <form action="gz.php" method="post" id="nickname" style="display:none;">
        <p>ニックネームを決めてください</p>
        <input type="text" name="nick" size="10" required><br>
        <input type="submit" value="決定">
    </form>
    
    
    
    
    
<?php
    // データベース確認
    $a = "test";
//    print "<p>{$_SESSION['uid']}</p>";
    
    require_once("db_init.php");
    $sql = "SELECT * FROM `table2.1` WHERE id = '" . $_SESSION['uid'] . "'";
    $sth = $db->query($sql);
    $sth->execute();
    $count = $sth->rowCount();
    
//    print "<p>{$count}</p>";
    if ($count > 0) {
        // ニックネーム登録済み 
        print "<p>登録済み</p>";
        
        
        $r = $sth->fetch();
        // セッションにデータベースのニックネームを格納
        $_SESSION['nick'] = $r['nick'];
?>
        <script>
            // ニックネーム項目非表示
            nickname.style.display = "none";
            // 自動的に画面遷移
            location.href = "./gz.php";
        </script>
<?php
    } else {
        // ニックネーム未登録
        print "<p>未登録</p>";
?>
        <script>
            // ニックネーム項目表示
            nickname.style.display = "block";
        </script>
<?php
    }
      
?>
</body>
</html>