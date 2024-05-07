<?php
//判斷是否是javascript傳直過來
$Json_POST = false;
if( preg_match('/json/i', $_SERVER['HTTP_ACCEPT']) ){
	
	$Json_POST = true;
}

if( !$Login_TF ){

	if( $Json_POST ){
		
		$_html_msg		= '請重新登入';
		$_html_href		= 'index.php';
	}else{
		
		header('Location:' .SYS_URL);
		exit();
	}
}
?>