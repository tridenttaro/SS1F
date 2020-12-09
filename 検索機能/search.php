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
    <style>
        .example li {
display: inline;
padding:10px 15px;
border:1px #ccc solid;color:#000053;
border-radius: 5px / 5px;
}


.example .this {background-color:#777;color:#fff;}
    </style>
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
    mysql_select_db("webdb", $d);
     session_start();
        
      
        if(isset($_POST["search_cat"])){ //タイトル検索か失敗談（本文）検索
            if($_POST["search_cat"]=="thread"){
                $_SESSION['search_cat']= "title"; 
            }else{
                $_SESSION['search_cat'] = "text";
            }
        }
        $table_cat = $_SESSION['search_cat']; 
        
        if(isset($_POST["search_method"])){
            if($_POST["search_method"]=="and"){
                $_SESSION['search_method']= "and"; 
            }else{
                $_SESSION['search_method'] = "or";
            }
        }
        $table_method = $_SESSION['search_method']; 
        
        if(isset($_POST["search_sort"])){
                $_SESSION['search_sort']= $_POST["search_sort"];     
        }
        $date_sort = $_SESSION['search_sort'];
        
        if(isset($_POST["search"])){
            $limit = -1;
        
     
            $keywords = preg_split('/[\p{Z}\p{Cc}]++/u', $_POST["search"], $limit, PREG_SPLIT_NO_EMPTY);
            print_r($keywords);
            $_SESSION['search'] = $keywords[0];
            for($i=1; $i<count($keywords); $i++){
                $_SESSION['search'] = $_SESSION['search']."%' ".$table_method." `".$table_cat."` LIKE '%".$keywords[$i];
            }
            echo $_SESSION['search'];
        }
        $key = $_SESSION['search']; 
        

    $rc = mysql_query("SELECT COUNT(*) FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%'");
        $num_tag =mysql_fetch_array($rc)[mysql_fetch_array($rc)]; //num_tagは検索結果件数

    ?><p class="alert alert-success">
        <?php echo "検索結果は".$num_tag ."件でした<br>";
?></p>
    
<?php  
        
        $page_num = 5; //何項目ずつ表示するか
        $page = $_GET["page"];
        $this_page = ($page - 1) * $page_num;
    $r = mysql_query("SELECT * FROM `threads` WHERE `".$table_cat."` LIKE '%".$key."%' ORDER BY `date` " .$date_sort." LIMIT ".$this_page."," .$page_num);
        $num = 0;
    while ($row = mysql_fetch_array($r)){
        $id_rows[] = $row['thread_number']; ?>
        
        <form method="get" action="zyusin.php" name="thread_id">
            <input type=hidden name="thread_id" value="<?php echo $id_rows[$num]; ?>">
        </form>
        <a href="javascript:thread_id<?php if ($num_tag== 1){echo "";}else{
             echo "[".$num."]";}?>.submit()"><?php echo $row['title'] ;?></a><br>
        <?php
        print $row['text'].'<br>';
        $num++;
    }
     
        ?>
        <br><br>
        <ul class="example">
            <?php if ($page != 1){?>
            <li><?php echo '<a href="' . "search.php" . '?page=' . ($page - 1) . '">前へ</a>'; ?></li><?php } ?>
            <?php if ($page > 2){?>
            <li><?php echo '<a href="' . "search.php" . '?page=' . ($page - 2) . '">'. ($page - 2) .'</a>'; ?></li><?php } ?>
            <?php if ($page > 1){?>
            <li><?php echo '<a href="' . "search.php" . '?page=' . ($page - 1) . '">'. ($page - 1) .'</a>'; ?></li><?php } if($num_tag != 0){ ?>
            <li class="this"><?php echo $page.'</a>'; ?></li><?php } 
            if ($page < ceil($num_tag/$page_num )  ){?>
            <li><?php echo '<a href="' . "search.php" . '?page=' . ($page + 1) . '">'. ($page + 1) .'</a>'; ?></li><?php }
            if ($page < ceil($num_tag/$page_num ) - 1 ){ ?>
            <li><?php echo '<a href="' . "search.php" . '?page=' . ($page + 2) . '">'. ($page + 2) .'</a>'; ?></li><?php } 
            if (($page != ceil($num_tag/$page_num ))&&($num_tag != 0)) {?>
            <li><?php echo '<a href="' . "search.php" . '?page=' . ($page + 1) . '">次へ</a>'; ?></li>
            <?php } ?>
            
</ul>
        <?php
    mysql_close($d);?>
    </main>
    
</body>
</html>
