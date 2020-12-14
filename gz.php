<?php
session_start();
// ログオフボタン押下
if (isset($_POST["action"]) && $_POST["action"] == "logoff") {
    $_SESSION = array();
    session_destroy();
}

if (isset($_POST['nick']) && $_POST['nick'] != "") {
    $_SESSION['nick'] = htmlspecialchars($_POST['nick'], ENT_QUOTES, 'UTF-8');
}

// 検索対象
if(isset($_POST["search_cat"])){
    $_SESSION['search_cat'] = htmlspecialchars($_POST["search_cat"], ENT_QUOTES, 'UTF-8');
}
if(isset($_SESSION['search_cat'])) {
    $table_cat = $_SESSION['search_cat'];
} else {
    $table_cat = "title";
}

// キーワードの検索範囲
if(isset($_POST["search_method"])){
    $_SESSION['search_method'] = htmlspecialchars($_POST["search_method"], ENT_QUOTES, 'UTF-8');
}
if(isset($_SESSION['search_method'])) {
    $table_method = $_SESSION['search_method']; 
} else {
    $table_method = "and";
}

// 検索結果の順番
if(isset($_POST["search_sort"])){
    $_SESSION['search_sort'] = htmlspecialchars($_POST["search_sort"], ENT_QUOTES, 'UTF-8');     
}
if(isset($_SESSION['search_sort'])) {
    $date_sort = $_SESSION['search_sort'];
} else {
    $date_sort = "";
}
// キーワード検索
if(isset($_POST["search"])){
    $limit = -1;

    $_SESSION['word'] = htmlspecialchars($_POST["search"], ENT_QUOTES, 'UTF-8');

    if ($_SESSION['word'] != "") {
        $keywords = preg_split('/[\p{Z}\p{Cc}]++/u', $_SESSION['word'], $limit, PREG_SPLIT_NO_EMPTY);
    } else {
        $keywords[0] = "";
    }
    
    $_SESSION['search'] = $keywords[0];    

    for($i=1; $i<count($keywords); $i++){
        $_SESSION['search'] = $_SESSION['search']."%' ".$table_method." `".$table_cat."` LIKE '%".$keywords[$i];
    }
}
if (isset($_SESSION['search'])) {
    $key = $_SESSION['search'];
} else {
    $key  = "";
}
// 検索件数
if(isset($_POST["page_num"])){
    $_SESSION['page_num'] = htmlspecialchars($_POST["page_num"], ENT_QUOTES, 'UTF-8');
}
if(isset($_SESSION['page_num'])) {
    $page_num = $_SESSION['page_num'];
} else {
    $page_num = 2;
}
    
     
?>
    
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">    
    <title>ソリューションシェア</title>
    <link rel="stylesheet" href="gz_style_file.css" type="text/css">
</head>
<body style="background-color:beige">
    <div id="ue">
        <p class="title">ソリューションシェア</p>
    </div>
    <div id="main">
        <!-- 検索機能。 -->
        <form method="post" action="">
            <div>
                <!-- キーワード検索 -->
                検索<input type=search name="search" value="<?php if(isset($_SESSION['word'])) {print $_SESSION['word'];}?>">
                <!-- 決定ボタン -->
                <input type="submit" value="検索">
                
                <!-- 折り畳み展開設定 -->
                <div onclick="obj=document.getElementById('open').style; obj.display=(obj.display=='none')?'block':'none';">
                    <a style="cursor:pointer; background-color:white; border:1px solid;">詳細設定</a>
                </div>
                <!-- 折り畳まれ部分 -->
                <div id="open" style="display:none;clear:both;background-color:silver;">
                    <!-- 検索対象 -->
                    <div>
                        検索対象<br>
                        <label><input type=radio name="search_cat" value="title" checked>タイトル検索</label>
                        <label><input type=radio name="search_cat" value="text">本文検索</label><br><br>
                    </div>
                    <!-- キーワードの検索範囲 -->
                    <div>
                        キーワード<br>
                        <label><input type=radio name="search_method" value="and" checked>すべて含む</label>
                        <label><input type=radio name="search_method" value="or">少なくとも1つを含む</label><br><br>
                    </div>
                    <!-- 検索件数 -->
                    <div>
                        表示件数:
                        <select name="page_num">
                            <option value= 2 <?php if(isset($_SESSION['page_num']) && $_SESSION['page_num'] == 2) {print 'selected';} ?>>
                                2件(テスト用)
                            </option>
                            <option value= 5 <?php if(isset($_SESSION['page_num']) && $_SESSION['page_num'] == 5) {print 'selected';} ?>>
                                5件
                            </option>
                            <option value= 10 <?php if(isset($_SESSION['page_num']) && $_SESSION['page_num'] == 10) {print 'selected';} ?>>
                                10件
                            </option>
                        </select>
                    </div>
                </div>
                <!-- // 折り畳まれ部分 -->
                <br><br>
            </div>
            <!-- 検索結果の順番 -->
            <div>
                <select name="search_sort" onchange="submit(this.form)">
                    <option value="DESC" <?php if(isset($_SESSION['search_sort']) && $_SESSION['search_sort'] == 'DESC') {print 'selected';} ?>>
                        新しい順
                    </option>
                    <option value="ASC" <?php if(isset($_SESSION['search_sort']) && $_SESSION['search_sort'] == 'ASC') {print 'selected';} ?>>
                        古い順
                    </option>
                </select> 
            </div>
        </form>
        <!-- // 検索機能。 -->
        <p id="message"></p>
        <p class="iine">(よかったら<u>イイネ！</u>を押してください)</p>
    
<?php
 
        
        // データベース設定
        require_once("db_init.php");
        $ps_thcoun = $webdb->query("SELECT `thread_number` FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%' and `ope` = 1");
        $coun_th = 0;
        // 検索結果件数
        while ($r_thcoun = $ps_thcoun->fetch()) {
            $coun_th++;
        }
        print "<p>検索結果は" . $coun_th . "件でした<br>";

        // 何項目ずつ表示するか
        if (isset($_GET["page"])) {
            $page = $_GET["page"];    
        } else {
            $page = 1;
        }
        
        $this_page = ($page - 1) * $page_num;
        $ps = $webdb->query("SELECT * FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%' and `ope` = 1 ORDER BY `date` " .$date_sort." LIMIT ".$this_page."," .$page_num);

        while ($r = $ps->fetch()) {
            $id_rows[] = $r['thread_number'];

            $tg = $r['image'];
            $tb = $r['thread_number'];
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
            // ブロックしていないアカウントの場合、表示
            if ($flag_bk == 0) {
?>
                <div id='box'>
                    <?php print $r['thread_number']?>
                    【投稿者:<a href='gz_mypage.php?uid=<?=$r['uid']?>'><?php print $thread_nick ?></a>】<?=$r['date'];?><br>
                    <p class='iine'>イイネ(<?=$coun_iine?>)</p><hr>
                    <a href='gz_thread.php?tran_b=<?=$tb?>' class='thread_title'><?= $r['title'] ?></a><br>
                </div>
<?php            
            }
        }        
?>
        <br><br>
        <!-- ページ遷移 -->
        <ul class="example">
            <?php if ($page != 1){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page - 1) . '">前へ</a>'; ?></li><?php } else { ?>
            <li><div style="color:gray;">前へ</div></li><?php } ?>
            <?php if ($page > 2){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page - 2) . '">'. ($page - 2) .'</a>'; ?></li><?php } ?>
            <?php if ($page > 1){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page - 1) . '">'. ($page - 1) .'</a>'; ?></li><?php } if($coun_th != 0){ ?>
            <li class="this"><?php echo $page.'</a>'; ?></li><?php } 
            if ($page < ceil($coun_th/$page_num )  ){?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page + 1) . '">'. ($page + 1) .'</a>'; ?></li><?php }
            if ($page < ceil($coun_th/$page_num ) - 1 ){ ?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page + 2) . '">'. ($page + 2) .'</a>'; ?></li><?php } 
            if (($page != ceil($coun_th/$page_num ))&&($coun_th != 0)) {?>
            <li><?php echo '<a href="' . "gz.php" . '?page=' . ($page + 1) . '">次へ</a>'; ?></li><?php } else { ?>
            <li><div style="color:gray;">次へ</div></li><?php } ?>
            
        </ul>
    </div>
    <div id='hidari'>
        <div id='logon' style='display:none;'><br><a href='gz_logon.php'>ログオン</a></div>
        
        <div id='toppage'><br><a href='gz.php'>トップページ</a></div>
        <div id='upload' style='display:none;'><br><a href='gz_up.php'>アップロードはここ</a></div>
        <div id='mypage' style='display:none;'><br><a href='gz_mypage.php?uid=<?=$_SESSION['uid']?>'>マイページ</a></div>
        <div id='admin' style='display:none;'><br><br><a href='gz_admin.php'>管理者ページ</a></div>
        <br><br>
        <form method="post" id='logoff' style='display:none;'>
            <button type="submit" name="action" value="logoff" 
                onclick="return confirm('ログオフします。よろしいですか?')">ログオフ</button>
        </form>

    </div>
     
<?php
    // ログインしている
    if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])) {
        $_SESSION['tm'] = time();
        setcookie("gz_user", $_SESSION['uid'], time()+60*60*24*365);
        setcookie("gz_date", date('Y年m月d日H字i分s秒'), time()+60*60*24*365);
        // ユーザを追加
        // データベースの設定
        require_once("db_init.php");
        $ps = $webdb->prepare("INSERT INTO `users`(`uid`, `nick`) VALUES (:v_u, :v_n)");
        $ps->bindParam(':v_u', $_SESSION['uid']);
        $ps->bindParam(':v_n', $_SESSION['nick']);
        $ps->execute();
?>
        <script>
            // ログオンしている場合の挨拶
            message.innerHTML = 'こんにちは' + '<?php print $_SESSION['nick'] ?>' + 'さん。'; 
            // アップロードボタンを表示
            upload.style.display = "block";
            // ログオフボタンを表示
            logoff.style.display = "block";
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
    } else {
?>
        <script>
            // ログオンしていない場合のあいさつ
            message.innerHTML = 'こんにちは名無しさん。';
            // ログオンボタン非表示
            logon.style.display = "block";
        </script>
<?php
    }
?> 
</body>

</html>