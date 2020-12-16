<?php
session_start();
// ログオフボタン押下
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
    <title>アップロード画面</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
    
<body style="background-color:beige">
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>

    <div id='hidari'>
        <div id='logon' style='display:none;'><br><a href='gz_logon.php'>ログオン</a></div>

        <div id='toppage'><br>
            <form method="post" name="form1" action="gz.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form1.submit()">トップページ</a>
            </form> 
        </div>
        <div id='upload' style='display:none;'><br><a href='gz_up.php'>アップロードはここ</a></div>
        <div id='mypage' style='display:none;'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'>マイページ</a></div>
        <div id='admin' style='display:none;'><br><br>
            <form method="post" name="form2" action="gz_admin.php">
                <input type="hidden" name="top" value="1">
                <a href="javascript:form2.submit()">管理者ページ</a>
            </form> 
        </div>
        <br><br>
        <form method="post" id='logoff' style='display:none;'>
            <button type="submit" name="action" value="logoff" 
                onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
        </form>
    </div>

    <div id='main'>
        <p id="message"></p>
<?php
        // ログオンしている
        if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
            $_SESSION['tm'] = time();        
            
            // スレッド編集ボタンから遷移
            if (isset($_POST["myb"])) {
                $th_num = $_POST["myb"];

                // データベース設定
                require_once("db_init.php");
                $ps = $webdb->query("SELECT * FROM `threads` WHERE `thread_number` = $th_num");
                while ($r = $ps->fetch()) {
                    $title = $r['title'];
                    $text = $r['text'];
                    $image = $r['image'];
?>
                    <form enctype = 'multipart/form-data' action = 'gz_up_set.php' method = 'post'>
                        <input type='hidden' name='edit' value="<?php print $th_num; ?>"><br>
                        タイトル<br>
                        <input type='text' name='myt' maxlength='30' pattern="\S|\S.*?\S"
                            placeholder='タイトル：最大３０文字' value="<?php print $title; ?>" required><br>
                        本文<br>
                        <textarea name='mym' rows='10' cols='70' maxlength='2040'
                            placeholder='本文：最大２０４０文字' required><?php print $text; ?></textarea><br>
                        <input type = 'file' name='myf' accept='image/png, image/jpeg, image/gif'>
                        <p><b>※画像を新たに設定する場合、元の画像は消去されます！</b><br>
                        送信できるのは1mbまでのjpeg画像だけです！<br>
                        また展開後のメモリ消費が多い場合アップロードできません。<br>
                            <input type='submit' value='送信'><br>
                        </p>
                    </form>
<?php
                }
            // スレッド新規作成
            } else {
?>
                <form enctype = 'multipart/form-data' action = 'gz_up_set.php' method = 'post'>
                    タイトル<br>
                    <input type='text' name='myt' maxlength='30' pattern="\S|\S.*?\S"
                    placeholder='タイトル：最大３０文字' required><br>
                    本文<br>
                    <textarea name='mym' rows='10' cols='70' maxlength='2040'
                    placeholder='本文：最大２０４０文字' required></textarea><br>
                    <input type = 'file' name='myf' accept='image/png, image/jpeg, image/gif'>
                    <p>送信できるのは1mbまでのjpeg画像だけです！<br>
                    また展開後のメモリ消費が多い場合アップロードできません。<br>
                        <input type='submit' value='送信'><br>
                    </p>
                </form>
<?php
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
        } else {
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
