<?php
session_start();
// ログオフボタン押下
if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
?>
    <script> 
        // 自動的に画面遷移
        location.href = "./index.php";
    </script>
<?php
}
// 管理者でないとアクセス拒否
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm']) && $_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
    $_SESSION['tm'] = time();
    
    // 検索機能php部分の読み込み
    require_once("search_set.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv='content-type' content='text/html;charset=UTF-8'>
    <title>ソリューションシェア　管理画面</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css">
    <link rel="manifest" href="./manifest.json">
    <script>
        if('serviceWorker' in navigator){
            navigator.serviceWorker.register('./service-worker.js').then(function(){
                console.log("Service Worker is registered!!");
            });
        }
    </script>
</head>
<body style="background-color:beige">
    <header class="sticky-top">
        <div class="p-3 mb-2 bg-success text-white">
            <div class ="row">
                <div calss="col-sm" id='toppage'>
                    <form method="post" name="form1" action="index.php">
                        <input type="hidden" name="top" value="1">
                        <a class="white" href="javascript:form1.submit()"><h1>ソリューションシェア</h1></a>
                    </form> 
                </div>
                <div class="col clearfix">
                    <div class="col-sm">
                        <div class="float-right">
                            <div class ="row">
                                
                                <div id='toppage'>
                                    <form method="post" name="top_page" action="index.php">
                                        <input type="hidden" name="top" value="1">
                                        <a class="white" href="javascript:top_page.submit()">
                                            <button type="button" class="btn btn-light" style='margin-right:1em;'>トップ</button>
                                        </a>
                                    </form>
                                </div>

                                <div id='upload'><a href='gz_up.php'><button type="button" class="btn btn-light">スレッド作成</button></a></div>
                                <div id='mypage'><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'><button type="button" class="btn btn-light">マイページ</button></a></div>
                                <div id='admin'>
                                    <form method="post" name="form2" action="gz_admin.php">
                                        <input type="hidden" name="top" value="1">
                                        <a href="javascript:form2.submit()"><button type="button" class="btn btn-light">管理者ページ</button></a>
                                    </form> 
                                </div>
                                <form method="post" id='logoff'>
                                    <button type="submit" class="btn btn-light" name="action" value="logoff" 
                                        onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
                                </form>
                            </div>
                        </div>                  
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid">
        <div id="main" class="mx-auto" style="background-color: white;">
        <p id="message"></p>
        <h2>ここは管理者のページです</h2>
<?php 
        $ph_text = "全スレッドの検索";
        // 検索機能のHTML部分読み込み
        require_once("search_form.php");
?>        
        <!-- 公開・非公開設定 -->
        <form action="gz_admin_op.php" method="post">
            <br>
            <INPUT TYPE = "submit" VALUE='公開・非公開の送信'>
<?php
            // データベース設定
            require_once("db_init.php");
            $ps_thcoun = $webdb->prepare("SELECT `thread_number` FROM `threads` WHERE `".$table_cat."` LIKE (:v_k)");
            // エスケープ
            $key = '%'.$key.'%';
            $key = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
            $ps_thcoun->bindParam(':v_k', $key, PDO::PARAM_STR);
            $ps_thcoun->execute();

            $coun_th = 0;
            // 検索結果件数
            while ($r_thcoun = $ps_thcoun->fetch()) {
                $coun_th++;
            }
            print "<p>検索結果は" . $coun_th . "件でした<br>";
    
            // 何項目ずつ表示するか
            if (isset($_GET["page"]) && !(isset($_POST["search"])) ) {
                $page = htmlspecialchars($_GET["page"], ENT_QUOTES, 'UTF-8');    
            } else {
                $page = 1;
            }
            
            $this_page = ($page - 1) * $page_num;

            // 検索結果の表示
            $ps = $webdb->prepare("SELECT * FROM `threads` WHERE `".$table_cat."` LIKE (:v_k)
                                    ORDER BY `date` " .$date_sort." LIMIT ".$this_page."," .$page_num);
            // エスケープ
            $ps->bindParam(':v_k', $key, PDO::PARAM_STR);
            $ps->execute();
            while ($r = $ps->fetch()) {
    
                $tb = $r['thread_number'];
                $to = $r['ope'];
                $th_uid = $r['uid'];
                // ニックネームの取得
                $ps_nick = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $th_uid . "'");
                while ($r_nick = $ps_nick->fetch()) {
                    $thread_nick = $r_nick['nick'];
                }
                // イイネの個数取得
                $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = '" . $tb . "'");
                $coun_iine = 0;
                while ($r_ii = $ps_ii->fetch()) {
                    $coun_iine++;
                }
?>
                <div class="container-fluid">
                <div class="card"  style="background-color: lightblue; margin: 1em; padding: 1em" >
                    <div class="card-header">非公開：<INPUT TYPE = checkbox NAME = check[] VALUE = <?=$tb?> <?php if ($to == 0) { print "CHECKED = checked";} ?> ></INPUT><br>
                    <?=$r['thread_number']?>
                    【投稿者:<a href='gz_mypage.php?uid=<?=$r['uid']?>'><?=$thread_nick;?></a>】<?=$r['date'];?><br>
                        <p class='iine'>イイネ(<?=$coun_iine?>)</p><hr></div><div class="card-body"style="background-color: white;">
                    <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br></div>
                </div>
                    </div>
<?php             
            }
?>
                </form>

        <br><br>
        <!-- ページ遷移 -->
        <ul class="example">
            <?php if ($page != 1){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page - 1) . '">前へ</a>'; ?></li><?php } else { ?>
            <?php } ?>
            <?php if ($page > 2){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page - 2) . '">'. ($page - 2) .'</a>'; ?></li><?php } ?>
            <?php if ($page > 1){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page - 1) . '">'. ($page - 1) .'</a>'; ?></li><?php } if($coun_th != 0){ ?>
            <li class="this"><?php echo $page.'</a>'; ?></li><?php } 
            if ($page < ceil($coun_th/$page_num )  ){?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page + 1) . '">'. ($page + 1) .'</a>'; ?></li><?php }
            if ($page < ceil($coun_th/$page_num ) - 1 ){ ?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page + 2) . '">'. ($page + 2) .'</a>'; ?></li><?php } 
            if (($page != ceil($coun_th/$page_num ))&&($coun_th != 0)) {?>
            <li><?php echo '<a href="' . "gz_admin.php" . '?page=' . ($page + 1) . '">次へ</a>'; ?></li><?php } else { ?>
            <?php } ?>
            
        </ul>
        <br><br>
    </div>
    </div>

    

<?php
} else {
    session_destroy();
    print "<P>エラー：アクセス権限がありません。<BR>
            <A HREF='index.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>

</body>
</html>
