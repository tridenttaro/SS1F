<?php
session_start();

if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}

if (isset($_GET['uid'])) {
    $get_uid = htmlspecialchars($_GET['uid'], ENT_QUOTES, 'UTF-8');
}

// 検索機能php部分の読み込み
require_once("search_set.php");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http equiv='Content-Type' content='text/html;charset=UTF-8'>
    <title>イイネ一覧</title>
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
    
<body style='background-color:beige'>
    <header class="sticky-top">
        <div class="p-3 mb-2 bg-success text-white">
            <div class ="row">
                <div calss="col-sm" id='toppage'>
                    <form method="post" name="form1" action="gz.php">
                        <input type="hidden" name="top" value="1">
                        <a class="white" href="javascript:form1.submit()"><h1>ソリューションシェア</h1></a>
                    </form> 
                </div>
                <div class="col clearfix">
                    <div class="col-sm">
                        <div class="float-right">
                            <div class ="row">
                                
                                <div id='logon' style='display:none;'><a href='gz_logon.php'><button type="button" class="btn btn-light">ログオン</button></a></div>
                                <div id='toppage'>
                                    <form method="post" name="top_page" action="gz.php">
                                        <input type="hidden" name="top" value="1">
                                        <a class="white" href="javascript:top_page.submit()">
                                            <button type="button" class="btn btn-light" style='margin-right:1em;'>トップ</button>
                                        </a>
                                    </form>
                                </div>

                                <div id='upload' style='display:none;'><a href='gz_up.php'><button type="button" class="btn btn-light">スレッド作成</button></a></div>
                                <div id='mypage' style='display:none;'><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'><button type="button" class="btn btn-light">マイページ</button></a></div>
                                <div id='admin' style='display:none;'>
                                    <form method="post" name="form2" action="gz_admin.php">
                                        <input type="hidden" name="top" value="1">
                                        <a href="javascript:form2.submit()"><button type="button" class="btn btn-light">管理者ページ</button></a>
                                    </form> 
                                </div>
                                <form method="post" id='logoff' style='display:none;'>
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

    <div id="main" class="container-fluid">
        <p id="message"></p>
<?php
        // URLが正しい
        if (isset($get_uid)) {
            // かつ、ログインしている
            if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
                $_SESSION['tm'] = time();

                $ph_text = "イイネリスト内の検索";
                // 検索機能のHTML部分読み込み
                require_once("search_form.php");
?>
                <script>
                    message.innerHTML = 'あなたのイイネしたスレッド一覧';
                    // ログオフボタンを表示
                    logoff.style.display = "block";
                    // アップロードボタンを表示
                    upload.style.display = "block";
                    // マイページボタンを表示
                    mypage.style.display = "block";
                </script>
<?php
                // 管理者アカウントの場合
                if ($_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
?>
                    <script>
                        // 管理者ページボタンを表示
                        admin.style.display = "block";
                    </script>
<?php
                }
                // アクセスページの本人のアカウント
                if ($_SESSION['uid'] == $get_uid) {

                    // データベース設定
                    require_once("db_init.php");
                    $ps_thcoun = $webdb->prepare("SELECT * FROM `threads` as `t1` INNER JOIN `favorites` as `t2` ON `t1`.`thread_number` = `t2`.`thread_number` 
                                                    WHERE `t1`.`".$table_cat."` LIKE (:v_k) and `t2`.`uid` = '" . $get_uid . "'");
                    // エスケープ
                    $key = '%'.$key.'%';
                    $key = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
                    $ps_thcoun->bindParam(':v_k', $key, PDO::PARAM_STR);
                    $ps_thcoun->execute();
                    $coun_th = 0;
                    // 検索結果件数
                    while ($r_thcoun = $ps_thcoun->fetch()) {
                        // イイネしたスレッド
                       
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
                    $ps = $webdb->prepare("SELECT `t1`.`thread_number`as`thread_number`, `t1`.`uid`as`uid`, `t1`.`title`as`title`, `t1`.`text`as`text`, `t1`.`ope`as`ope`, `t1`.`date`as`date`
                                            FROM `threads` as `t1` INNER JOIN `favorites` as `t2` ON `t1`.`thread_number` = `t2`.`thread_number` 
                                            WHERE `t1`.`".$table_cat."` LIKE (:v_k) and `t2`.`uid` = '" . $get_uid . "'
                                            ORDER BY `t1`.`date` " .$date_sort." LIMIT ".$this_page."," .$page_num);
                    // エスケープ
                    $ps->bindParam(':v_k', $key, PDO::PARAM_STR);
                    $ps->execute();
                    
                    while ($r = $ps->fetch()) {
                        // イイネしたスレッドを取得
                       
                        $tb = $r['thread_number'];
                        $th_uid = $r['uid'];
                        // ニックネームの取得
                        $ps_nick = $webdb->query("SELECT * FROM `users` WHERE `uid` = '" . $th_uid . "'");
                        while ($r_nick = $ps_nick->fetch()) {
                            $thread_nick = $r_nick['nick'];
                        }
                        // イイネの表示
                        $ps_ii = $webdb->query("SELECT DISTINCT * FROM `favorites` WHERE `thread_number` = $tb");
                        $coun_iine = 0;
                        while ($r_ii = $ps_ii->fetch()) {
                            $coun_iine++;
                        }
                        // ブラックリストに入っているか確認
                        $flag_bk = 0;
                        // セッションが存在している(ログオンしている)
                        if(isset($_SESSION['uid'])) {
                            $uid = $_SESSION['uid'];
                        // セッションが存在していない(ログオンしていない)
                        } else {
                            $uid = 1;
                        }
                        $ps_bk = $webdb->query("SELECT * FROM `blacklists` WHERE `uid` = '" . $uid . "'");
                        while ($r_bk = $ps_bk->fetch()) {
                            // ブロックしているユーザの時
                            if ($r_bk['black_uid'] == $th_uid) {
                                $flag_bk = 1;
                            }
                        }
?>
                        <div class="container-fluid">
                            <div class="card"  style="background-color: lightblue; margin: 1em; padding: 1em">
<?php
                            // ブロックしていないアカウント
                            if ($flag_bk == 0) {
                            
                                // 非公開になっている
                                if ($r['ope'] == 0) {
                                    // print $r['thread_number'] . "【投稿者:****】****-**-**-** **:**:**";
                                    print "<p style='color: red;'>管理者により非公開に設定されています</p>";
                                    // 投稿者本人または管理者
                                    if ($th_uid == $_SESSION['uid'] || $_SESSION['uid'] == 'fkisRnWQAXfzG8cVY0M8k1a91dD2') {
?>
                                        <?php print $r['thread_number']?>
                                        【投稿者:<a href='gz_mypage.php?uid=<?=$r['uid']?>'><?php print $thread_nick ?></a>】<?=$r['date'];?><br>
                                        <p class='iine'>イイネ(<?=$coun_iine?>)</p><hr>
                                        <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
<?php
                                    }
                                // 非公開でない
                                } else {
?>
                                     <div class="card-header"><?php print $r['thread_number']?>
                                    【投稿者:<a href='gz_mypage.php?uid=<?=$r['uid']?>'><?php print $thread_nick ?></a>】<?=$r['date'];?><br>
                                         <p class='iine'>イイネ(<?=$coun_iine?>)</p></div>
                <div class="card-body"style="background-color: white;">
                    <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br></div>
<?php
                                }
                            // ブロックしているアカウント
                            } else {
                                print"<p style='color:red;'>非公開</p>";
                            }
                ?></div>
                        </div>
<?php                  
                    }

?>
                    <br><br>
                    <!-- ページ遷移 -->
                    <ul class="example">
                        <?php if ($page != 1){?>
                        <li><?php echo '<a href="' . "gz_iine_list.php" . '?page=' . ($page - 1) . '&uid=' . $get_uid . '">前へ</a>'; ?></li><?php } else if ($page > 2){?>
                        <li><?php echo '<a href="' . "gz_iine_list.php" . '?page=' . ($page - 2) . '&uid=' . $get_uid . '">'. ($page - 2) .'</a>'; ?></li><?php } ?>
                        <?php if ($page > 1){?>
                        <li><?php echo '<a href="' . "gz_iine_list.php" . '?page=' . ($page - 1) . '&uid=' . $get_uid . '">'. ($page - 1) .'</a>'; ?></li><?php } if($coun_th != 0){ ?>
                        <li class="this"><?php echo $page.'</a>'; ?></li><?php } 
                        if ($page < ceil($coun_th/$page_num )  ){?>
                        <li><?php echo '<a href="' . "gz_iine_list.php" . '?page=' . ($page + 1) . '&uid=' . $get_uid . '">'. ($page + 1) .'</a>'; ?></li><?php }
                        if ($page < ceil($coun_th/$page_num ) - 1 ){ ?>
                        <li><?php echo '<a href="' . "gz_iine_list.php" . '?page=' . ($page + 2) . '&uid=' . $get_uid . '">'. ($page + 2) .'</a>'; ?></li><?php } 
                        if (($page != ceil($coun_th/$page_num ))&&($coun_th != 0)) {?>
                        <li><?php echo '<a href="' . "gz_iine_list.php" . '?page=' . ($page + 1) . '&uid=' . $get_uid . '">次へ</a>'; ?></li><?php } else { ?>
                        <?php } ?> 
                    </ul>
                    <br><br>
<?php
                // 本人のアカウントではない
                } else {
?>
                    <script>
                        message.innerHTML = 'このページは表示できません。本人のアカウントでログオンした場合のみ閲覧できます。';
                    </script>
<?php
                }
            // ログオンしていない
            } else {
?>
                <script>
                    message.innerHTML = 'このページは表示できません。本人のアカウントでログオンした場合のみ閲覧できます。';
                    // ログオンボタン非表示
                    logon.style.display = "block";
                </script>
<?php
            }
        } else {
            // 正しく遷移していない
?>
            <script>
                message.innerHTML = '正しい画面から遷移して下さい';
            </script>
<?php
        }
?>
    </div>

</body>
</html>
