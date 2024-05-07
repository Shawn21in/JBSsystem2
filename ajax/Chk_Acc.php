<?php 
require_once("../include/web.config.php");

$account		= trim($_POST['reg_email']);
	
$_html_msg = '';

if( empty($account) ) {
	
	$_html_msg 	= '請輸入帳號！';
}
	
		
if( empty($_html_msg) ){
	
	$db = new MySQL();
	$db->Where = " Where Member_Acc = '".$account."'";										
	$count = $db->query_count('web_member');
			
	if( $count != 0 ){
			
		$_html_msg 	= '該帳號已被註冊';
	}
}

$result['Msg'] 		= $_html_msg;

$json = json_encode($result);
print_r($json);

?>