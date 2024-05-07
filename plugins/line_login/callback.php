<?php
require_once('../../include/web.config.php'); //設定值
require_once('config.php'); //設定值

$code 	= $_GET['code'];
$state 	= $_GET['state'];
$session_state = $_SESSION['Line']['_line_state'];

unset($_SESSION['Line']['_line_state']);

if ($session_state !== $state) {

    echo '存取錯誤'; exit;
}

$Line = new LineController();

$access_token = $Line->getAccessToken($code);//取得使用者授權

//setcookie("access_token",$access_token, time()+3600*24*20);//把他記憶20天

$user = $Line->getLineProfile_access_token($access_token);//取得使用者資料

if( !empty($user->userId) ) {
	
	$db = new MySQL();
	$db->Where = "Where Member_Line_id = '".$user->userId."'";
	$db->query_sql( $member_db, 'Member_ID,Member_Line_id,Member_Name');
	
	unset( $_SESSION['Line']);
	
	//有會員資料直接登入
	if( $row = $db->query_fetch() ) {
		
		$_SESSION[$_Website]['website']['member_id']    = $row['Member_ID'];
		
		$db->query_data( $member_db , array('Member_LastLogin' => date('Y-m-d H:i:s') ) , 'UPDATE');
					
		JSAH("LINE登入成功，系統將為您跳轉至主頁面" , "../../index.php");exit;
		
	}else{
	
		$SN = 'M'.substr( date('Ymd') , -6);
		$Member_ID = GET_NEW_ID($member_db, 'Member_ID', $SN, 4);
		
		if( empty($Member_ID) ) {
			
			JSAH("編號產生失敗，請重新操作" , "index.php");exit;
		}else{
		
			$db = new MySQL();
		
			$db_data = array(
			
				'Member_ID'				=> $Member_ID,
				"Member_Line_id" 		=> $user->userId,
				"Member_Name" 			=> $user->displayName,
				'Member_Sdate'			=> date('Y-m-d H:i:s'),
				'Member_Open'			=> 1
			);
			
			$db->query_data( $member_db , $db_data , 'INSERT');
			
			if( !empty($db->Error) ){
	
				JSAH("資料庫異常，請重新操作" , "index.php");exit;
			}else{
				
				$_SESSION[$_Website]['website']['member_id']    = $row['Member_ID'];
				
				JSAH("LINE登入成功，系統將為您跳轉至主頁面" , "../../index.php");exit;
			}
		}
	}
}

