<?php
print '<pre>';
print "HTTP リクエスト(GET メソッド)で送信されたデータ\n";
var_dump($_GET);
print "HTTP リクエスト(POST メソッド)で送信されたデータ\n";
var_dump($_POST);
print "HTTP リクエストで送信されたデータ\n";
var_dump($_REQUEST);
print '</pre>';