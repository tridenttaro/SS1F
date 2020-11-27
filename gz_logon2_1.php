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
    if (isset($_SESSION['uid'])) {
        // データベース
        require_once("db_init.php");
        $sql = "SELECT * FROM `table2.1` WHERE id = '" . $_SESSION['uid'] . "'";
        $sth = $db->query($sql);
        $sth->execute();
        $count = $sth->rowCount();

        if ($count > 0) {
            // ニックネーム登録済み 
            print "<p>登録済み</p>";

            // sqlの結果を変数へ
            $r = $sth->fetch();
            // セッションにデータベースのニックネームを格納
            $_SESSION['nick'] = $r['nick'];

            // 管理者アカウントの場合
            if ($_SESSION['uid'] == '7XISOdlnLKNpr0bcDhGv5UMxXgq1') {
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
    } else {
        print "<p>ログインが必要なページです</p>";
    }
      
?>
</body>
</html>