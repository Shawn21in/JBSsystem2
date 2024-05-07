<?php
require_once(__DIR__ . '/include/class.Main.php');

$QUERY_STRING = $_SERVER['QUERY_STRING'];

if( $QUERY_STRING == 'tablesoption' || $QUERY_STRING == 'tablescreate' ){
	
	require_once("include/inc.config.php");
	require_once("include/inc.check_login.php");
	
	if( $Admin_data['Group_ID'] == 1 ){
	
		if( empty($POST['tablename']) ){//如果資料表名稱的POST是空的 (在使用資料表創建時才會用到)
			
			$Main = new Main();
			$Main->set_head();
			$Main->set_box();
		}
		
		$TBST = new TablesSetting($QUERY_STRING);
	}else{
		
		if( $Json_POST ){
			
			$json_array = array();
		
			$json_array['html_msg']		= $_html_msg;
			$json_array['html_href']    = $_html_href;
			
			echo json_encode( $json_array );
		}else{
			
			header('Location: ' .SYS_URL);
		}
	}
}else if($QUERY_STRING == 'versionrefresh'){
	
	require_once("include/inc.config.php");

	$db = new MySQL();
	$db->Where = " WHERE Admin_ID = '2'";
	$db->query_data( 'sys_web_option' , array('WO_Version' => date('YmdHis')), 'UPDATE');
	
	if(empty($db->Error)){
		echo '<script charset="UTF-8">window.alert("Version Update!")</script>';
		echo '<script type="text/javascript">window.close();</script>';
	}else{
		echo '<script charset="UTF-8">window.alert("fail")</script>';
		echo '<script type="text/javascript">window.close();</script>';
	}
	
}else{
	
	if( !isset($_SESSION) ){
		
		session_start();
	}
	
	unset($_SESSION['system']);
	
	$Main = new Main();
	$Main->set_head();
?>   
	<script type="text/javascript" src="assets/js/sys-index.js"></script>
     
    <body class="login-layout">
<?php
	$Main->set_box();
	
	if( !is_file('sys_config.php') ){
		
		include_once('html'.DIRECTORY_SEPARATOR.'config.html');
	}else{
		
		include_once('html'.DIRECTORY_SEPARATOR.'index.php');
	}
?>
	</body>
</html>
<?php
} 
?>