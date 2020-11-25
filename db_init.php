<?php
require_once("./gz_data/gz_db_info.php");
$dsn = "mysql:host=$SERV;dbname=$DBNM";
$db = new pdo($dsn, $USER, $PASS);
?>