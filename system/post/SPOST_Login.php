<?php
require_once("../include/inc.config.php");
//require_once("../include/inc.check_login.php");//登入檔不需要加登入判斷,不然就會進不去

$_Type  = $_POST['_type'];//主執行case
//$_Type1 = $POST['_type1'];//副執行選項

if( !empty($_Type) ){
	
	$db 		= new MySQL();
	
	$json_array = array();
	
	switch( $_Type ){
		
		case "login"://後台登入
			
			if( is_file(SYS_PATH.CONFIG_EXEC) ){
				
				$account  = trim($POST['account']);//登入帳號
				$password = md5(Turnencode(trim($POST['password']), 'password'));//登入密碼
				$Code	  = trim($POST['code']);
				$FormCode = $_SESSION['system']['FormCode'];
				
				if( empty($Code) || empty($FormCode) ){
					
					$_html_msg = "Code Empty";
				}else if( strlen($Code) < 32 || strlen($FormCode) < 32 ){
					
					$_html_msg = "Code Strlen Too Short";
				}else if( $Code != $FormCode ){
					
					$_html_msg = "Code Error";
				}else if( !empty($account) && !empty($password) ){
					//BINARY區分大小寫
					$db->Where = " WHERE BINARY Admin_Acc = '" .$db->val_check($account). "' AND BINARY Admin_Pwd = '" .$db->val_check($password). "'";
					
					$db->query_sql(Admin_DB, "Admin_ID, Admin_Name, Group_ID, Admin_Open");	
					$row = $db->query_fetch();	
					if( $row['Admin_Open'] == 1 || $row['Group_ID'] == 1 ){
						
						$data  = array("Admin_Code" => $Code, "Admin_LastLogin" => "NOW()", "Admin_IP" => MATCH_IP());
						
						$db->Where = " WHERE Admin_ID = '" .$row['Admin_ID']. "'";
						
						$db->query_data(Admin_DB, $data, "UPDATE");
						
						$_SESSION['system']['admin_id']    = $row['Admin_ID'];
						$_SESSION['system']['admin_acc']   = $account;
						$_SESSION['system']['admin_name']  = $row['Admin_Name'];
						$_SESSION['system']['group_id']    = $row['Group_ID'];
						$_SESSION['system']['admin_code']  = $Code;
						
						$_html_msg  = $row['Admin_Name']."歡迎回來";
						$_html_href = MAIN_EXEC;
						break;
					}else{
						
						if( !empty($row) ){
							
							$_html_msg = "帳號未啟用";
						}else{
							
							$_html_msg = "帳號密碼錯誤";
						}
						
						$_html_clear = array( "password", "account" );
					}
				}else{
					
					$_html_msg = "操作錯誤";
				}
				
				$_html_eval = 'Clear_Index()';
			}else{
				
				$_html_eval = 'Reload()';
			}
		break;
		
		case "logout"://後台登出
			
			//session_destroy();
			
			if( !empty($_SESSION['system']['admin_SID']) ) {
			
				$_html_href = 'index.php?shop';
			}else{
			
				$_html_href = 'index.php';
			}
			unset($_SESSION['system']);
			
		break;
		
		case "FormCode"://創建表單驗證碼
			
			$Code = md5("Chun".date("YmdHis")."Wei");
			if( strlen($Code) < 32){
				
				$Code .= $Code;
			}
			
			$_SESSION['system']['FormCode'] = $Code;
			
			$_html_eval = "Login('" .$Code. "')";
		break;
		
		case "checklogin"://檢查登入資訊
			
			if( !empty($Admin_data) ){
		
				if( $Admin_data['Admin_Code'] != $_SESSION['system']['admin_code'] ){
					
					session_destroy();
					$_html_msg = '帳號從其他地方登入( ' .$Admin_data['Admin_IP']. ' )';
					$_html_href = 'index.php';
					
					$db->SSD($Admin_data['Admin_Name'], $Admin_data['Admin_ID'], '帳號被其他地方登入', $_html_msg, 'WARNING', '');
				}
			}else{
		
				$_html_href = 'index.php';
			}
		break;
		
		case "language_chg"://語系切換
			
			$_ID = $POST['id'];
			
			$_SESSION['system']['language'] = $_ID;
			$_html_eval = 'Reload()';
		break;
		
		case "tables_chg"://資料庫切換
			
			$_ID = $POST['id'];
			
			$_SESSION['system']['tables_chg'] = $_ID;
			$_html_eval = 'Reload()';
		break;
		
		case "admin_chg"://管理者切換
			
			if( empty($Admin_data) ){
				
				$_html_msg	= '請先登入';
				$_html_href = 'index.php';
				break;
			}
			
			$Admin_Count = 0;
			if( $Admin_data['Group_ID'] != 1 && $_SESSION['system']['admin_chg'] == 1 ){ 
			
				$db = new MySQL();
					
				$db->Where = " WHERE Group_ID = '1' AND Admin_Code = '" .$db->val_check($_SESSION['system']['admin_code']). "'";
				
				$Admin_Count = $db->query_count(Admin_DB);
			}
			
			if( $Admin_data['Group_ID'] != 1 && $Admin_Count == 0 ){
				
				$_html_msg	= '權限不足';
				$_html_eval = 'Reload()';
			}else{
				
				$_ID = $POST['id'];
				
				$row = Get_Admin_data($_ID);
				
				if( empty($row) ){
					
					$_html_msg	= '無此帳號';
				}else if( empty($row['Admin_Open']) && $row['Group_ID'] != 1 ){
					
					$_html_msg	= '帳號未啟用';
				}else{
					
					$data  = array("Admin_Code" => $Admin_data['Admin_Code']);
						
					$db->Where = " WHERE Admin_ID = '" .$row['Admin_ID']. "'";
					
					$db->query_data(Admin_DB, $data, "UPDATE");
									
					$_SESSION['system']['admin_id']    	= $row['Admin_ID'];
					$_SESSION['system']['admin_acc']   	= $row['Admin_Acc'];
					$_SESSION['system']['admin_name']  	= $row['Admin_Name'];
					$_SESSION['system']['group_id']    	= $row['Group_ID'];
					$_SESSION['system']['admin_code']  	= $Admin_data['Admin_Code'];
					$_SESSION['system']['admin_chg']	= 1;
				}
				$_html_eval = 'Reload()';
			}
		break;
		
		case "config"://建立後台
				
			$server 		= trim($_POST['server']);
			$database 		= trim($_POST['database']);
			$databaseacc 	= trim($_POST['databaseacc']);
			$databasepwd 	= trim($_POST['databasepwd']);
			$sysacc 		= trim($_POST['sysacc']);
			$syspwd 		= md5(Turnencode(trim($_POST['syspwd']), 'password'));
			$admacc 		= trim($_POST['admacc']);
			$admpwd 		= md5(Turnencode(trim($_POST['admpwd']), 'password'));
			
			$Group_MenuUse  = array();
			if( !empty($server) && !empty($database) && !empty($databaseacc) && !empty($databasepwd) && !empty($sysacc) && !empty($syspwd) ){
				
				$db->Error_Html = false;
				$db->Connect_SQL($server, $databaseacc, $databasepwd, $database);
				if( !$db->Link_DB ){
					
					$_html_msg = '連線資料庫資料有誤';
				}else if( $db->get_use_table() != $database ){
					
					$_html_msg = '無權限或資料庫不存在';
				}else{
					
					$contents =
					'<?php
					define( "VERSION", "v2.1" );
					//伺服器位置
					define( "DB_Host", "' .$server. '" );
					//資料庫帳號
					define( "DB_UserName", "' .$databaseacc. '" );
					//資料庫密碼
					define( "DB_PassWord", "' .$databasepwd. '" );
					//資料庫名稱
					define( "DB_DataBase", "' .$database. '" );
					//多網站管理設定, true代表依管理者各別設定網站管理, false代表依預設管理者(Multi_WebUrl_ID)的網站管理資料為主
					define( "Multi_WebUrl", false );
					//預設抓取管理者ID
					define( "Multi_WebUrl_ID", 2 );
					?>';					
					file_put_contents(SYS_PATH.CONFIG_EXEC, $contents);
					
					include(SYS_PATH.CONFIG_EXEC);//載入連結MYSQL資訊
					
					$db->Insert_S = false;

					$Tables_Arr = $db->get_tables();
					
					$TBST = new TablesSetting();					
					
					if( !in_array(Admin_DB, $Tables_Arr)){
						
						$TBST->Options->Create_Admin();
						
						$db_data = array('Admin_Acc' => $sysacc, 'Admin_Pwd' => $syspwd, 'Admin_Name' => '系統管理員', 'Admin_Permissions' => 255, 'Group_ID' => 1, 'Admin_Sdate' => 'now()', 'Admin_Open' => 1, 'Admin_Checkbox' => 1);
						$db->query_data(Admin_DB, $db_data, 'INSERT');
						
						$db_data = array('Admin_Acc' => $admacc, 'Admin_Pwd' => $admpwd, 'Admin_Name' => '一般管理員', 'Group_ID' => 2, 'Admin_Sdate' => 'now()', 'Admin_Open' => 1);
						$db->query_data(Admin_DB, $db_data, 'INSERT');
					}					
					
					if( !in_array(Menu_DB, $Tables_Arr)){
						
						$TBST->Options->Create_Menu();
						
						$SN = substr(date('Ymd'), 2);
						//主目錄
						$sql = "INSERT INTO `" .Menu_DB. "` (`Menu_ID`, `Menu_Name`, `Menu_Lv`, `Menu_Exec`, `Menu_Path`, `Menu_Sort`, `Menu_UpMID`, `Menu_Permissions`, `Menu_Smallpic`, `Menu_TableName`, `Menu_TableName1`, `Menu_TableName2`, `Menu_TableKey`, `Menu_TableKey1`, `Menu_TableKey2`, `Menu_TablePre`, `Menu_TablePre1`, `Menu_TablePre2`, `Menu_OrderBy`, `Menu_ClassMax`, `Menu_Mode`, `Menu_Model`, `Menu_Link`, `Menu_CstSnPre`, `Menu_CstSnType`, `Menu_CstSnNum`, `Menu_Add`, `Menu_Edt`, `Menu_Del`, `Menu_View`, `Menu_Albums_Edt`, `Menu_Albums_Mpc`, `Menu_SysUse`, `Menu_SysAdminUse`) VALUES
('" .$SN.'001'. "', '後台管理', 1, NULL, NULL, 0, NULL, 255, 'fa-gears', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '0', 0, 0, 0, 0, 0, 0, 0, 1, 0),
('" .$SN.'002'. "', '資料庫log檔', 2, 'logList.php', 'php_sys/log', 0, '" .$SN.'001'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, '', 0, 0, 'SYS_LOGS', NULL, NULL, '0', 0, 0, 0, 0, 0, 0, 0, 1, 1),
('" .$SN.'003'. "', '資料庫設定', 2, 'tablesList.php', 'php_sys/tables', 0, '" .$SN.'001'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 1, 0, 'SYS_TABLES', NULL, NULL, '0', 0, 1, 1, 1, 0, 0, 0, 1, 1),
('" .$SN.'004'. "', '目錄設定', 2, 'menuList.php', 'php_sys/menu', 0, '" .$SN.'001'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 1, 0, 'SYS_MENU', NULL, NULL, '0', 0, 1, 1, 1, 0, 0, 0, 1, 0),
('" .$SN.'005'. "', '群組設定', 2, 'groupList.php', 'php_sys/group', 0, '" .$SN.'001'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 1, 0, 'SYS_GROUP', NULL, NULL, '0', 0, 1, 1, 1, 0, 0, 0, 1, 0),
('" .$SN.'006'. "', '管理者設定', 2, 'adminList.php', 'php_sys/admin', 0, '" .$SN.'001'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 1, 0, 'SYS_ADMIN', NULL, NULL, '0', 0, 1, 1, 1, 1, 0, 0, 1, 0),
('" .$SN.'007'. "', '網站管理', 1, NULL, NULL, 0, NULL, 255, 'fa-hdd-o', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '0', 0, 0, 0, 0, 0, 0, 0, 1, 0),
('" .$SN.'008'. "', '郵件參數設定', 2, 'woList.php?type=3', 'php_sys/weboption', 0, '" .$SN.'007'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 1, 0, 'SYS_WEBOP', NULL, NULL, '0', 0, 0, 1, 0, 0, 0, 0, 1, 0),
('" .$SN.'009'. "', '網站基本資料設定', 2, 'woList.php?type=2', 'php_sys/weboption', 0, '" .$SN.'007'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, NULL, 1, 0, 'SYS_WEBOP', NULL, NULL, '0', 0, 0, 1, 0, 0, 0, 0, 1, 0),
('" .$SN.'010'. "', '公司基本資料設定', 2, 'woList.php?type=1', 'php_sys/weboption', 0, '" .$SN.'007'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, '', 1, 0, 'SYS_WEBOP', NULL, '', '0', 0, 0, 1, 0, 0, 0, 0, 1, 0),
('" .$SN.'011'. "', '系統基本功能DEMO', 1, NULL, NULL, 0, NULL, 255, 'fa-cloud', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '0', 0, 0, 0, 0, 0, 0, 0, 0, 1),
('" .$SN.'012'. "', '優惠折扣活動', 2, 'favorList.php', 'demo/favor', 0, '" .$SN.'011'. "', 0, NULL, 'demo_favor', NULL, NULL, 'Favor_ID', NULL, NULL, 'Favor', NULL, NULL, '', 1, 1, NULL, NULL, '', '', 0, 1, 1, 1, 0, 0, 0, 0, 0),
('" .$SN.'013'. "', '訂單功能', 2, 'orderList.php', 'demo/order', 0, '" .$SN.'011'. "', 0, NULL, 'demo_ordermain', 'demo_orderdetail', '', 'Orderm_ID', 'Orderd_ID', '0', 'Orderm', 'Orderd', '', '', 1, 4, NULL, NULL, NULL, '0', 0, 0, 1, 1, 1, 0, 0, 0, 0),
('" .$SN.'014'. "', '會員功能', 2, 'memberList.php', 'demo/member', 0, '" .$SN.'011'. "', 0, NULL, 'demo_member', '', '', 'Member_ID', '0', '0', 'Member', '', '', '', 1, 1, NULL, NULL, 'M', 'YYMMDD', 3, 1, 1, 1, 1, 0, 0, 0, 0),
('" .$SN.'015'. "', '相簿分類功能(內容)', 2, 'albums2List.php', 'demo/albums2', 0, '" .$SN.'011'. "', 0, NULL, 'demo_albums2', 'demo_albumsl2', 'demo_albums2class', 'Achie_ID', 'Achiel_ID', 'Aclass_ID', 'Achie', 'Achiel', 'Aclass', '', 4, 4, NULL, NULL, '', '', 0, 1, 1, 1, 0, 1, 1, 0, 0),
('" .$SN.'016'. "', '相簿分類功能(分類)', 2, 'albums2classList.php', 'demo/albums2', 0, '" .$SN.'011'. "', 0, NULL, 'demo_albums2class', '', '', 'Aclass_ID', '0', '0', 'Aclass', '', '', '', 4, 1, NULL, NULL, 'AC', '', 3, 1, 1, 1, 0, 0, 0, 0, 0),
('" .$SN.'017'. "', '相簿功能', 2, 'albumsList.php', 'demo/albums', 0, '" .$SN.'011'. "', 0, NULL, 'demo_albums', 'demo_albumsl', '', 'Albums_ID', 'Albumsl_ID', '0', 'Albums', 'Albumsl', '', '', 1, 4, NULL, NULL, NULL, '0', 0, 1, 1, 1, 0, 1, 1, 0, 0),
('" .$SN.'018'. "', '基本功能(分管理畫面)', 2, 'settingList.php', 'demo/setting', 0, '" .$SN.'011'. "', 0, NULL, 'demo_setting', '', '', 'Admin_ID', '0', '0', 'Admin', '', '', '', 1, 1, NULL, NULL, NULL, '0', 0, 0, 1, 0, 0, 0, 0, 0, 0),
('" .$SN.'019'. "', '基本分類功能(內容)', 2, 'basic2List.php', 'demo/basic2', 0, '" .$SN.'011'. "', 0, NULL, 'demo_basic2', '', 'demo_basic2class', 'Product_ID', '0', 'Proclass_ID', 'Product', '', 'Proclass', '', 2, 4, NULL, NULL, 'P', 'YYMMDD', 3, 1, 1, 1, 0, 0, 0, 0, 0),
('" .$SN.'020'. "', '基本分類功能(分類)', 2, 'basic2classList.php', 'demo/basic2', 0, '" .$SN.'011'. "', 0, NULL, 'demo_basic2class', '', '', 'Proclass_ID', '0', '0', 'Proclass', '', '', '', 2, 1, NULL, NULL, 'PR', '', 3, 1, 1, 1, 0, 0, 0, 0, 0),
('" .$SN.'021'. "', '基本功能', 2, 'basicList.php', 'demo/basic', 0, '" .$SN.'011'. "', 0, NULL, 'demo_basic', '', '', 'Product_ID', '0', '0', 'Product', '', '', '', 1, 1, NULL, NULL, 'P', 'YYMMDD', 3, 1, 1, 1, 0, 0, 0, 0, 0),
('" .$SN.'022'. "', '設計參數設定', 2, 'woList.php?type=5', 'php_sys/weboption', 0, '" .$SN.'007'. "', 255, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, '', 1, 0, 'SYS_WEBOP', NULL, '', '0', 0, 0, 1, 0, 0, 0, 0, 1, 0),
('" .$SN.'023'. "', '擴充功能', 1, NULL, NULL, 0, NULL, 255, 'fa-gears', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '0', 0, 0, 0, 0, 0, 0, 0, 1, 0)
;";

						$db->query($sql);
						
						for( $i = 1; $i <= 10; $i++ ){
							
							if( $i == 8 ){ continue; }//郵件設定跳過
							
							$Group_MenuUse[] = $SN.sprintf("%03d", $i);;
						}
						
						$Group_MenuUse[] = $SN.'004_2';//目錄編輯
						$Group_MenuUse[] = $SN.'005_1';//群組新增
						$Group_MenuUse[] = $SN.'005_2';//群組編輯
						$Group_MenuUse[] = $SN.'005_3';//群組刪除
						$Group_MenuUse[] = $SN.'006_1';//管理者新增
						$Group_MenuUse[] = $SN.'006_2';//管理者編輯
						$Group_MenuUse[] = $SN.'006_3';//管理者刪除
						$Group_MenuUse[] = $SN.'006_4';//管理者檢視
						$Group_MenuUse[] = $SN.'009_2';//網站編輯
						$Group_MenuUse[] = $SN.'010_2';//公司編輯
					}
					
					if( !in_array(Tables_DB, $Tables_Arr)){
						
						$TBST->Options->Create_Tables();
						
						$db_data = array('Tables_Name' => 'new_sys_demo', 'Tables_Name1' => '基本功能DEMO', 'Tables_Open' => 0);
						$db->query_data(Tables_DB, $db_data, 'INSERT');
					}	
					
					if( !in_array(Web_Option_DB, $Tables_Arr)){
						
						$TBST->Options->Create_WOption();
						
						$sql = "INSERT INTO `sys_web_option` (`Admin_ID`, `WO_Name`, `WO_Addr`, `WO_Addr1`, `WO_Addr2`, `WO_Tel`, `WO_Tel1`, `WO_Fax`, `WO_Email`, `WO_Idn`, `WO_About`, `WO_Title`, `WO_Url`, `WO_FBLink`, `WO_LineLink`, `WO_Description`, `WO_Keywords`, `WO_GMAP`, `WO_MapLat`, `WO_MapLng`, `WO_Open`, `WO_StmpHost`, `WO_StmpPort`, `WO_SendName`, `WO_SendEmail`, `WO_StmpAuth`, `WO_StmpAcc`, `WO_StmpPass`, `WO_StmpSecure`, `WO_AddrName`, `WO_AddrEmail`, `WO_MailSubject`, `WO_MailBody`) VALUES
(1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 'smtp.gmail.com', '465', '小明', 'sfb122405@gmail.com', 1, 'b9413077@gmail.com', 'zdiwuwoiliqzvxeo', 'SSL', '王大明', 'sfb122405@gmail.com', '這是gmail測試寄信', ''這是gmail測試寄信內容'),
(2, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1, 'ms39.hinet.net', '25', '小明', 'bmidp888@gmail.com', 0, '', '', '', '王大明', 'bmidp888@gmail.com', '這是測試寄信', '這是測試寄信內容');";
						
						$db_data = array('Admin_ID' => 1, 'WO_StmpHost' => 'smtp.gmail.com', 'WO_StmpPort' => 465, 'WO_SendName' => '小明', 'WO_SendEmail' => 'sfb122405@gmail.com', 'WO_StmpAuth' => 1, 'WO_StmpAcc' => 'b9413077@gmail.com', 'WO_StmpPass' => 'zdiwuwoiliqzvxeo', 'WO_StmpSecure' => 'SSL', 'WO_AddrName' => '王大明', 'WO_AddrEmail' => 'sfb122405@gmail.com', 'WO_MailSubject' => '這是gmail測試寄信', 'WO_MailBody' => '這是gmail測試寄信內容');
						$db->query_data(Web_Option_DB, $db_data, 'INSERT');
						
						$db_data = array('Admin_ID' => 2, 'WO_StmpHost' => 'smtp.gmail.com', 'WO_StmpPort' => 465, 'WO_SendName' => '小明', 'WO_SendEmail' => 'bmidp888@gmail.com', 'WO_StmpAuth' => 1, 'WO_StmpAcc' => 'bmidp888@gmail.com', 'WO_StmpPass' => 'lfbxmtculcjbblrx', 'WO_StmpSecure' => 'SSL', 'WO_AddrName' => '王大明', 'WO_AddrEmail' => 'bmidp888@gmail.com', 'WO_MailSubject' => '這是測試寄信', 'WO_MailBody' => '這是測試寄信內容');
						$db->query_data(Web_Option_DB, $db_data, 'INSERT');
					}
					
					//驗證碼
					if( !in_array(Recaptcha_DB, $Tables_Arr)){
						
						$TBST->Options->Create_Recaptcha();
						
						$db_data = array(
							'Admin_ID' => 2, 
							'Recaptcha_JS_url' => 'https://www.google.com/recaptcha/api.js?render=', 
							'Recaptcha_API_url' => 'https://www.google.com/recaptcha/api/siteverify', 
							'Recaptcha_SiteKey' => '6Ley38cUAAAAAKT8x7JG2fWCzcv1Gxxutp_DTKRE', 
							'Recaptcha_SecretKey' => '6Ley38cUAAAAAAgHGiteG-gEGLKCkU1SCh9V9VDt'
						);
						
						$db->query_data(Recaptcha_DB, $db_data, 'INSERT');
					}
					
					//建立測試程式資料庫和目錄, 因為權限如果不足會無法建立
					/*$database_demo = $database.'_demo';
					if( !$db->check_database($database_demo) ){
						
						$db->query('CREATE DATABASE ' .$database_demo);
						$db->query('ALTER DATABASE ' .$database_demo. ' CHARACTER SET utf8 COLLATE utf8_general_ci');
						$db->query("GRANT ALL PRIVILEGES ON " .$database_demo. ".* '" .$databaseacc. "'@'localhost'");
					}*/
					
					$TBST->tablename 	= 'web_delivery';
					$TBST->tablepre		= 'Delivery';
					//創建聯絡我們
					if( !in_array($TBST->tablename, $Tables_Arr) && $_POST['tb_delivery'] == 1 ){
						
						$TBST->Options->Create_Delivery($TBST->tablename, $TBST->tablepre);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('delivery');//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						$TBST->Add_Menu_Data('delivery');//新增目錄資料
					}
					
					$TBST->tablename 	= 'web_message';
					$TBST->tablepre		= 'Msg';
					//創建聯絡我們
					if( !in_array($TBST->tablename, $Tables_Arr) && $_POST['tb_message'] == 1 ){
						
						$TBST->Options->Create_Message($TBST->tablename, $TBST->tablepre);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('message');//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						$TBST->Add_Menu_Data('message');//新增目錄資料
					}
					
					$TBST->tablename 	= 'web_member';
					$TBST->tablepre		= 'Member';
					//創建會員資料
					if( !in_array($TBST->tablename, $Tables_Arr) && $_POST['tb_member'] == 1 ){
						
						$TBST->Options->Create_Member($TBST->tablename, $TBST->tablepre);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('member');//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						$TBST->Add_Menu_Data('member');//新增目錄資料
					}
					
					$TBST->tablename 	= 'web_albums';
					$TBST->tablepre		= 'Albums';
					$TBST->tablename1	= $TBST->tablename.'l';
					$TBST->tablepre1	= $TBST->tablepre.'l';
					//創建相簿資料
					if( !in_array($TBST->tablename, $Tables_Arr) && ( $_POST['tb_albums'] == 1 || $_POST['tb_albums'] == 2 ) ){
						
						$tablecname = $_POST['tb_albums'] == 1 ? $TBST->tablename.'class' : '';
						$tablecid 	= $_POST['tb_albums'] == 1 ? $TBST->tablepre.'C_ID' : '';
						$tablecpre 	= $_POST['tb_albums'] == 1 ? $TBST->tablepre.'C' : '';
						
						$TBST->Options->Create_Albums($TBST->tablename, $TBST->tablepre, '', $TBST->tablename1, $TBST->tablepre1, $tablecid);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('albums', $tablecid);//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						if( !in_array($TBST->tablename1, $Tables_Arr) ){
							
							$TBST->Clean_Tables_Option($TBST->tablename1);//清除表格設定檔資料
								
							$field_arr = $TBST->Get_Tables_Option('albumsl');//取得表格設定檔資料
							
							$TBST->Set_Table_Option($TBST->tablename1, $field_arr);//設定欄位的資料
						}
						
						$TBST->Add_Menu_Data('albums', '', 2, $tablecid, $tablecname, $tablecpre);//新增目錄資料
						
						if( !in_array($tablecname, $Tables_Arr) && $_POST['tb_albums'] == 1 ){
							
							$TBST->tablename	= $tablecname;
							$TBST->tablepre		= $tablecpre;
					
							$TBST->Options->Create_Class($TBST->tablename, $TBST->tablepre);
						
							$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
							
							$field_arr = $TBST->Get_Tables_Option('class');//取得表格設定檔資料
							
							$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
							
							$TBST->Add_Menu_Data('class', '相簿分類設定', 2);//創建目錄
						}
					}
										
					$TBST->tablename 	= 'web_product';
					$TBST->tablepre		= 'Product';
					//創建產品資料
					if( !in_array($TBST->tablename, $Tables_Arr) && ( $_POST['tb_product'] == 1 || $_POST['tb_product'] == 2 ) ){
						$tablecname = $_POST['tb_product'] == 1 ? $TBST->tablename.'class' : '';
						$tablecid 	= $_POST['tb_product'] == 1 ? $TBST->tablepre.'C_ID' : '';
						$tablecpre 	= $_POST['tb_product'] == 1 ? $TBST->tablepre.'C' : '';
						
						$TBST->Options->Create_Product($TBST->tablename, $TBST->tablepre, '', $tablecid);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('product', $tablecid);//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						$TBST->Add_Menu_Data('product', '', 2, $tablecid, $tablecname, $tablecpre);//新增目錄資料
						
						if( !in_array($tablecname, $Tables_Arr) && $_POST['tb_product'] == 1 ){
							
							$TBST->tablename	= $tablecname;
							$TBST->tablepre		= $tablecpre;
					
							$TBST->Options->Create_Class($TBST->tablename, $TBST->tablepre);
						
							$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
							
							$field_arr = $TBST->Get_Tables_Option('class');//取得表格設定檔資料
							
							$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
							
							$TBST->Add_Menu_Data('class', '產品分類設定', 2);//創建目錄
						}
					}
					
					$TBST->tablename 	= 'web_qanda';
					$TBST->tablepre		= 'QA';
					//創建常見問題
					if( !in_array($TBST->tablename, $Tables_Arr) && ( $_POST['tb_qanda'] == 1 || $_POST['tb_qanda'] == 2 ) ){
						
						$tablecname = $_POST['tb_qanda'] == 1 ? $TBST->tablename.'class' : '';
						$tablecid 	= $_POST['tb_qanda'] == 1 ? $TBST->tablepre.'C_ID' : '';
						$tablecpre 	= $_POST['tb_qanda'] == 1 ? $TBST->tablepre.'C' : '';
						
						$TBST->Options->Create_QA($TBST->tablename, $TBST->tablepre, '', $tablecid);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('qanda', $tablecid);//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						$TBST->Add_Menu_Data('qanda', '', 2, $tablecid, $tablecname, $tablecpre);//新增目錄資料
						
						if( !in_array($tablecname, $Tables_Arr) && $_POST['tb_qanda'] == 1 ){
							
							$TBST->tablename	= $tablecname;
							$TBST->tablepre		= $tablecpre;
					
							$TBST->Options->Create_Class($TBST->tablename, $TBST->tablepre);
						
							$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
							
							$field_arr = $TBST->Get_Tables_Option('class');//取得表格設定檔資料
							
							$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
							
							$TBST->Add_Menu_Data('class', '問題分類設定', 2);//創建目錄
						}
					}
					
					$TBST->tablename 	= 'web_news';
					$TBST->tablepre		= 'News';
					//創建最新消息
					if( !in_array($TBST->tablename, $Tables_Arr) && ( $_POST['tb_news'] == 1 || $_POST['tb_news'] == 2 ) ){
						
						$tablecname = $_POST['tb_news'] == 1 ? $TBST->tablename.'class' : '';
						$tablecid 	= $_POST['tb_news'] == 1 ? $TBST->tablepre.'C_ID' : '';
						$tablecpre 	= $_POST['tb_news'] == 1 ? $TBST->tablepre.'C' : '';
						
						$TBST->Options->Create_News($TBST->tablename, $TBST->tablepre, '', $tablecid);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('news', $tablecid);//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						$TBST->Add_Menu_Data('news', '', 2, $tablecid, $tablecname, $tablecpre );//新增目錄資料
						
						if( !in_array($tablecname, $Tables_Arr) && $_POST['tb_news'] == 1 ){
							
							$TBST->tablename	= $tablecname;
							$TBST->tablepre		= $tablecpre;
					
							$TBST->Options->Create_Class($TBST->tablename, $TBST->tablepre);
						
							$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
							
							$field_arr = $TBST->Get_Tables_Option('class');//取得表格設定檔資料
							
							$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
							
							$TBST->Add_Menu_Data('class', '消息分類設定', 2);//創建目錄
						}
					}
					
					$TBST->tablename 	= 'web_banner';
					$TBST->tablepre		= 'Banner';
					//創建廣告輪播
					if( !in_array($TBST->tablename, $Tables_Arr) && ( $_POST['tb_banner'] == 1 ) ){
												
						$TBST->Options->Create_Banner($TBST->tablename, $TBST->tablepre);
						
						$TBST->Clean_Tables_Option($TBST->tablename);//清除表格設定檔資料
						
						$field_arr = $TBST->Get_Tables_Option('banner');//取得表格設定檔資料
						
						$TBST->Set_Table_Option($TBST->tablename, $field_arr);//設定欄位的資料
						
						$TBST->Add_Menu_Data('banner');//新增目錄資料
					}
					
					if( !in_array(Group_DB, $Tables_Arr)){
						
						$TBST->Options->Create_Group();
						
						$db_data = array('Group_ID' => 1, 'Group_Name' => '系統管理員', 'Group_Lv' => 0);
						$db->query_data(Group_DB, $db_data, 'INSERT');
						
						$db_data = array('Group_ID' => 2, 'Group_Name' => '一般管理員', 'Group_Lv' => 1, 'Group_MenuUse' => serialize($Group_MenuUse));
						$db->query_data(Group_DB, $db_data, 'INSERT');
					}
					
					$_html_msg 	= '建立完成';
					$_html_eval = 'Reload()';	
				}
			}else{
				
				$_html_msg = '建立資料填寫不完整';	
			}
		break;
	}
	
	ob_end_clean();
	
	$json_array['html_msg']     = $_html_msg ? $_html_msg : '';//訊息
	$json_array['html_href']    = $_html_href ? $_html_href : '';//連結
	$json_array['html_eval']    = $_html_eval ? $_html_eval : '';//確定後要執行的JS
	$json_array['html_content'] = $_html_content ? $_html_content : '';//輸出內容
	$json_array['html_boxtype'] = $_html_boxtype ? $_html_boxtype : '1';//彈出視窗格式1=>只有確定,2=>確定與取消
	$json_array['html_clear']   = $_html_clear ? $_html_clear : '';//清空欄位Array,填欄位ID
	$json_array['html_type'] 	= $_Type ? $_Type : '';//執行類型
	
	echo json_encode( $json_array );
}
?>