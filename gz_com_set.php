<?php
session_start();
$u = htmlspecialchars($_POST['myn'], ENT_QUOTES);
$p = htmlspecialchars($_POST['myc'], ENT_QUOTES);
$b = htmlspecialchars($_POST['myb'], ENT_QUOTES);
?>

<!DOCTYPE html>
<HTML lang="ja">
<HEAD>
    <META HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=UTF-8'>
    <TITLE>コメントを書き込みました</TITLE>
</HEAD>
    
<BODY STYLE='background-color:Lightblue'>

<?php
if (isset($_SESSION['uid']) && isset($_SESSION['nick']) && isset($_SESSION['tm'])){
    $_SESSION['tm'] = time();
?>

<P><?php print $u; ?>さんは次のようにコメントを書き込みました</P>
<P>【コメント】<BR><?php print $p; ?></P>
<A HREF='gz.php'>一覧表示に戻ります</A>

<?php
    // データベース設定
    require_once("db_init.php");
    $ima = date('YmdHis');
    $ps = $webdb->prepare("INSERT INTO `comments` (`uid`, `thread_number`, `text`, `com_nick`, `date`) 
                        VALUES (:v_u, :v_tn, :v_t, :v_n, :v_d)");
    $ps->bindParam(':v_u', $_SESSION['uid']);
    $ps->bindParam(':v_tn', $b);
    $ps->bindParam(':v_t', $p);
    $ps->bindParam(':v_n', $u);
    $ps->bindParam(':v_d', $ima);
    $ps->execute();
}else{
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz.php'>トップページ</A><BR><BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>

</BODY>
</HTML>