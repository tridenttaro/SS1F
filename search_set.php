<!-- 検索機能のphp部分 -->

<?php
// トップページボタン押下
if (isset($_POST["top"])) {
    unset($_SESSION["word"]);
    unset($_SESSION['search']);
    unset($_SESSION["search_cat"]);
    unset($_SESSION["search_method"]);
    unset($_SESSION["search_sort"]);
    unset($_SESSION["page_num"]);
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
    $date_sort = "DESC";
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
// キーワード検索
if(isset($_POST["search"])){
    $limit = -1;

    $_SESSION['word'] = htmlspecialchars($_POST["search"], ENT_QUOTES, 'UTF-8');

    if ($_SESSION['word'] != "") {
        $keywords = preg_split('/[\p{Z}\p{Cc}]++/u', $_SESSION['word'], $limit, PREG_SPLIT_NO_EMPTY);
    } else {
        $keywords[0] = "";
    }
    
    $_SESSION['search'] = htmlspecialchars($keywords[0], ENT_QUOTES, 'UTF-8');    

    for($i=1; $i<count($keywords); $i++){
        $_SESSION['search'] = $_SESSION['search']."%' ".$table_method." `".$table_cat."` LIKE '%".htmlspecialchars($keywords[$i], ENT_QUOTES, 'UTF-8');
    }  
}
if (isset($_SESSION['search'])) {
    $key = htmlspecialchars($_SESSION['search'], ENT_QUOTES, 'UTF-8');
} else {
    $key  = "";
}

?>