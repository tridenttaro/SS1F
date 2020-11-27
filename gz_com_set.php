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
    require_once("db_init.php");
    $ima = date('YmdHis');
    $ps = $db->prepare("INSERT INTO table3 (ban, com, nam, dat) VALUES (:v_b, :v_c, :v_n, :v_d)");
    $ps->bindParam(':v_b', $b);
    $ps->bindParam(':v_c', $p);
    $ps->bindParam(':v_n', $u);
    $ps->bindParam(':v_d', $ima);
    $ps->execute();
}else{
    session_destroy();
    print "<P>ちゃんとログオンしてね！<BR>
            <A HREF='gz_logon.php'>ログオン</A></P>";
}
?>

</BODY>
</HTML>