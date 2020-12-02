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
<HTML lang="ja">
<HEAD>
    <META HTTP EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>名前変更</TITLE>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</HEAD>
    
<BODY style="background-color:beige">
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
    <div id='main'>
<?php
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();
?>
            <h1>ニックネームの変更</h1>
            <h3>現在のニックネーム「<?php echo $_SESSION['nick'] ?>」</h3>

            <form action="gz_rename_set.php" method="post">
                <p>
                    <label>ニックネーム</label>
                    <input type="text" name="nick">
                </p>

                <input type="submit" value="編集する">
            </form>

            
    </div>
    <div id='hidari'>
        <p>
            <a href='gz.php' id='toppage'>トップページ</a><br><br>
            <form method="post" id='logoff'>
                <button type="submit" name="action" value="logoff" 
                    onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
            </form>
        </p>
    </div>
    
 <?php   
        }else{
            session_destroy();
            print "<P>ちゃんとログオンしてね！<BR>
                    <A HREF='gz.php'>トップページ</A><BR><BR>
                    <A HREF='gz_logon.php'>ログオン</A></P>";
        }
?>
    
</BODY>
</HTML>
