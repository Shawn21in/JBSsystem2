<?php
$db = new MySQL();

$db->Connect_SQL(DB_Host, DB_UserName, DB_PassWord, DB_DataBase);

//define( "Link_DB", $db->Link_DB );//連結資料庫的連結
?>