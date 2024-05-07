<?php
$mHost		= '192.168.0.85';
//$mHost		= '211.75.47.37';
// $mHost		= '61.218.134.132';

$mUserName	= 'BMIDP';

// $mPassWord	= 'dj42u03y7';
$mPassWord	= 'BMIDP';

$mDataBase	= 'STOCK世明';
//$mDataBase	= 'STOCK';
// $mDataBase	= 'STOCK123s';

/*$mHost		= '.';

$mUserName	= 'sa';

$mPassWord	= '778899';

$mDataBase	= 'shiming';*/


$this->Connect_SQL($mHost, $mUserName, $mPassWord, $mDataBase);

//define( "Link_MDB", $mdb->Link_DB );//連結資料庫的連結
?>