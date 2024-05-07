<?php
$PicSize 	  = array('S', 'M', 'L');
//資料表設定用
$PicSize_Type = array(
	//key最多30碼
	'_product_' => '產品圖專用',
	'_news_' => '最新消息用',
	'_app_' => 'APP用'
);

/*
新增 S, M, L 三種不同圖片大小, 欲增加第四種請設定$PicSize_Arr 
例如: 
array('S' => 50, 'M' => 100, 'L' => 200)
例如2:
array('S' => 50, 'M' => 100)
*/
$_product_ = array('M' => array('w' => 110, 'h' => 110), 'S' => array('w' => 60, 'h' => 60), );
$_news_ = array('S' => array('w' => 60, 'h' => 60));
$_app_ = array( 'S' => array('w' => 110, 'h' => 80),'M' => array('w' => 320, 'h' => 200) );
?>