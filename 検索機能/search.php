<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>検索結果</title>
    <link href="_common/images/favicon.ico" rel="shortcut icon">
<link href="https://fonts.googleapis.com/css?family=M+PLUS+1p:400,500" rel="stylesheet">
<link href="_common/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
        $num_tag =mysql_fetch_array($rc)[mysql_fetch_array($rc)];

    ?><p class="alert alert-success">
        <?php echo "検索結果は".$num_tag ."件でした<br>";
?></p>
    
<?php  
        
    $r = mysql_query("SELECT * FROM `thread` WHERE `".$table_cat."` LIKE '%".$key."%'");
        $num = 0;
    while ($row = mysql_fetch_array($r)){
        $id_rows[] = $row['thread_id']; ?>
        
        <form method="get" action="zyusin.php" name="thread_id">
            <input type=hidden name="thread_id" value="<?php echo $id_rows[$num]; ?>">
        </form>
        <a href="javascript:thread_id<?php if ($num_tag== 1){echo "";}else{
             echo "[".$num."]";}?>.submit()"><?php echo $row['thread_name'] ;?></a><br>
        <?php
        print $row['story'].'<br>';
        $num++;
    }
    mysql_close($d);
?>
    </main>
    
</body>
</html>
