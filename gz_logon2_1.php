<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログオン画面</title>
    <link rel="manifest" href="./manifest.json">
    <script>
        if('serviceWorker' in navigator){
            navigator.serviceWorker.register('./service-worker.js').then(function(){
                console.log("Service Worker is registered!!");
            });
        }
    </script>
</head>
<body style="background-color:lightblue;">
    <form action="index.php" method="post" id="nickname" style="display:none;">
        <p>ニックネームを設定してください</p>
        <input type="text" name="nick" maxlength='16' pattern="\S|\S.*?\S" required><br>
        <input type="submit" value="決定">
    </form>
<?php
    if (isset($_SESSION['uid'])) {
        // データベース
        require_once("./db_init.php");
        $sql = "SELECT * FROM `users` WHERE `uid`" . "='" . $_SESSION['uid'] . "'";
        
        $sth = $webdb->query($sql);
        $sth->execute();
        $count = $sth->rowCount();

        if ($count > 0) {


            // sqlの結果を変数へ
            $r = $sth->fetch();
            // セッションにデータベースのニックネームを格納
            $_SESSION['nick'] = $r['nick'];

            // 管理者アカウントの場合
            if ($_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
?>
                <script>
                    // ニックネーム項目非表示
                    nickname.style.display = "none";
                    // 自動的に画面遷移
                    location.href = "./gz_admin.php";
                </script>
<?php
            }
?>

            <script>
                // ニックネーム項目非表示
                nickname.style.display = "none";
                // 自動的に画面遷移
                location.href = "./index.php";
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
    } else {
        print "<p>ログオンが必要なページです</p>";
    }
      
?>
</body>
</html>