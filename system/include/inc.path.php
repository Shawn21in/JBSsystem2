<?php
$db 			= new MySQL();

$Now_Table		= $db->get_use_table();

$Path			= IMG_PATH.$Now_Table.DIRECTORY_SEPARATOR.$Main_TablePre.DIRECTORY_SEPARATOR;//圖片實體路徑

$Pathweb		= IMG_URL.$Now_Table.'/'.$Main_TablePre.'/';//圖片網址路徑

$PathFile		= FILE_PATH.$Now_Table.DIRECTORY_SEPARATOR.$Main_TablePre.DIRECTORY_SEPARATOR;//檔案路徑

$PathFileWeb	= FILE_URL.$Now_Table.'/'.$Main_TablePre.'/';//檔案網址

?>