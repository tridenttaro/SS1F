<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>検索結果</title>
    <link href="_common/images/favicon.ico" rel="shortcut icon">
<link href="https://fonts.googleapis.com/css?family=M+PLUS+1p:400,500" rel="stylesheet">
<link href="_common/css/style.css" rel="stylesheet">
</head>
<body>
    <header>
<div class="container">
<h1>Sシェア</h1>
<h2>検索機能お試し</h2>
</div><!-- /.container -->
</header>
    
    <main>
<?php
    $d = mysql_connect("localhost", "root", "") or die("接続失敗");
    mysql_select_db("db", $d);
    $key = $_POST["search"];  
    if($_POST["search_cat"]=="thread"){
        $table_cat = "thread_name";   
    }else{
        $table_cat = "story";
    }
    $rc = mysql_query("SELECT COUNT(*) FROM `thread` WHERE `".$table_cat."` LIKE '%".$key."%'");

    print "検索結果は".mysql_fetch_array($rc)[mysql_fetch_array($rc)]."件でした<br>";
?>
    
<?php
    $r = mysql_query("SELECT * FROM `thread` WHERE `".$table_cat."` LIKE '%".$key."%'");
    while ($row = mysql_fetch_array($r)){
//        print "{$row['thread_id']}{$row['thread_name']}{$row['story']}{$row['thread_created']}{$row['update_time']}<br>";
        print '<a href="http://google.co.jp">'.$row['thread_name'].'</a><br>';
        print $row['story'].'<br>';
    }
    mysql_close($d);
?>
    </main>
    
</body>
</html>