<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function Get_Sys_File(){//系統用
	
	return array('SYS_MENU' => '系統目錄', 'SYS_GROUP' => '系統群組', 'SYS_ADMIN' => '系統使用者', 'SYS_TABLES' => '系統資料庫', 'SYS_LOGS' => '系統LOG表', 'SYS_WEBOP' => '系統網站設定', 'SYS_RECAP' => '系統驗證碼串接資料設定');
}

function Get_Model(){//模組
	
	return array('CLASS' => '分類模組', 'TABLE' => '表格模組');
}

function Chk_Login(){//判斷用
	
	return true;
}

function GLOBAL_D(){
	
	global $POST, $GET, $Admin_data, $Sys_Tables_data, $Sys_Tables_Arr, $Now_List;
	
	$GET  = $_GET;
	$POST = $_POST;
	$Admin_data = Get_Admin_data();//取得目前使用者資料
	
	if( $Admin_data['Group_ID'] == 1 || $Admin_data['Admin_Checkbox'] == 1 ){//要有切換資料庫權限
		
		$Sys_Tables_data = Get_Tables_data();//取出資料庫資料
		$Sys_Tables_Arr = Get_Tables_data('', 'Tables_Name1');//取出資料庫中文名稱資料
	}
}

function MATCH_IP( $IP = '' ){//抓取IP
	
	
	if( !empty($IP) ){
		
	   $_IP = $IP;
	}else if( !empty($_SERVER['HTTP_CLIENT_IP']) ){
		
	   $_IP = $_SERVER['HTTP_CLIENT_IP'];
	}else if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ){
		
	   $_IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else if( !empty($_SERVER['HTTP_X_FORWARDED']) ){
		
	   $_IP = $_SERVER['HTTP_X_FORWARDED'];
	}else if( !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) ){
		
	   $_IP = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	}else if( !empty($_SERVER['HTTP_FORWARDED_FOR']) ){
		
	   $_IP = $_SERVER['HTTP_FORWARDED_FOR'];
	}else if( !empty($_SERVER['HTTP_FORWARDED']) ){
		
	   $_IP = $_SERVER['HTTP_FORWARDED'];
	}else{
		
	   $_IP = $_SERVER['REMOTE_ADDR'];
	}
	
	//$_IP = '127.0.0';
	//php 5.2.0 after
	//判斷是否是合法IP 
	//filter_var($ip, FILTER_VALIDATE_IP)
	//判斷是否是合法的IPv4 IP地址 
	//filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
	//判斷是否是合法的公共IPv4地址，192.168.1.1這類的私有IP地址將會排除在外
	//filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE)
	//判斷是否是合法的IPv6地址
	//filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE)
	//判斷是否是public IPv4 IP或者是合法的Public IPv6 IP地址
	//filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
	if( filter_var($_IP, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE) ){//
		
	}else{
		
		$mycurl = new MyCurl();
		
		//只取IP
		$contents = $mycurl->saveToString( "http://www.whatismyip.com.tw/" );
		
		if( !empty($contents) ) {
		
			preg_match( '/<script type="application\/json" id="ip-json">(.*?)<\/script>/is', $contents, $match );
			$JSON = json_decode($match[1]);
			$_IP = $JSON->ip;
		}
		
	}
		
	return $_IP;
}

function MATCH_MENU_DATA( $Menu, $Type = '', $Lv = '' ){//取得目錄的資料
		
	$data = array();
	$db   = new MySQL();
	if( $Type == "ALL" ){
		
		if( !empty($Lv) ){
			
			$db->Where = " WHERE Menu_Lv = '" .$Lv. "'";
		}
		
		$db->query_sql(DB_DataBase.'.'.Menu_DB, "*");
		while( $row = $db->query_fetch() ){
			
			$data[$row['Menu_ID']] = $row;
		}
	}else{
		
		$db->Where = " WHERE Menu_Exec LIKE '" .$db->val_check($Menu). "%' OR Menu_ID = '" .$db->val_check($Menu). "'";
		$db->query_sql(DB_DataBase.'.'.Menu_DB, "*");
		while( $row = $db->query_fetch() ){
			
			$row['Menu_Exec_Name']	= GetUrlVal($row['Menu_Exec'], 'PHP_SELF_NAME');
			$row['Exec_Sub_Name']	= GetUrlVal($row['Menu_Exec'], 'PHP_SELF_SUB_NAME');
			
			$data = $row;
			
			if( $ex[0] == $Menu ) break;
		}
	}
	
	return $data;
}

function Get_Admin_data( $ID = '' ){//取得使用者資料
		
	$db 	= new MySQL();
	
	$data 	= array();
	if( !empty($ID) ){
		
		$db->Where = " WHERE adm.Admin_ID = '" .$db->val_check($ID). "'";
	}else{
		
		//$db->Where = " WHERE adm.Admin_ID = '" .$db->val_check($_SESSION['system']['admin_id']). "' AND BINARY adm.Admin_Code = '" .$db->val_check($_SESSION['system']['admin_code']). "'";
		$db->Where = " WHERE adm.Admin_ID = '" .$db->val_check($_SESSION['system']['admin_id']). "'";
	}
	
	$db->query_sql(DB_DataBase.'.'.Admin_DB. ' as adm LEFT OUTER JOIN ' .DB_DataBase.'.'.Group_DB. ' as gro ON adm.Group_ID = gro.Group_ID LEFT OUTER JOIN ' .DB_DataBase.'.'.Tables_DB. ' as tab ON adm.Tables_ID = tab.Tables_ID', 'adm.Admin_ID, adm.Admin_Acc, adm.Admin_Name, adm.Group_ID, adm.Admin_Permissions, adm.Admin_Sdate, adm.Admin_LastLogin, adm.Admin_IP, adm.Admin_Open, adm.Admin_Checkbox, adm.Admin_Code, gro.*, tab.*');
	
	$data = $db->query_fetch();
	
	if( empty($data) || ( $data['Group_ID'] != 1 && $data['Admin_Open'] != 1 && empty($ID) ) ){
		
		$data = array();
	}else{
		
		if( !empty($data['Group_MenuUse']) ){
			
			$data['Menu_Use'] = unserialize($data['Group_MenuUse']);	
		}else{
			
			$data['Menu_Use'] = array();	
		}
	}

	return $data;
}

function Get_Group_data( $ID = '', $Field = '', $Type = '' ){//取得群組資料
	
	global $Admin_data;
	
	$data = array();
	$db = new MySQL();
	
	if( !empty($ID) ){
		
		$db->Where = " WHERE Group_ID = '" .$db->val_check($ID). "'";
		
		$db->query_sql(Group_DB, '*');
		$data = $db->query_fetch();
	}else{
				
		$db->query_sql(Group_DB, '*');
		while( $row = $db->query_fetch() ){
			
			if(	$Type == 'LV' ){
				
				if( $Admin_data['Group_Lv'] > $row['Group_Lv'] ){ continue; }//判斷群組等級，大於則不顯示
			}
			
			if( !empty($Field) ){
				
				$data[$row['Group_ID']] = $row[$Field];
			}else{
								
				$data[$row['Group_ID']] = $row;	
			}
		}
	}
	
	return $data;
}

function Get_Tables_data( $ID = '', $Field = '', $Type = '' ){//取得資料庫資料
	
	global $Admin_data;
	
	$data = array();
	$db = new MySQL();
	
	if( !empty($ID) ){
		
		$db->Where = " WHERE Tables_ID = '" .$db->val_check($ID). "'";
		
		$db->query_sql(Tables_DB, '*');
		$data = $db->query_fetch();
	}else{
		
		if( empty($Type) ){
			
			if( !empty($Field) ){
					
				$data[0] = '系統資料庫';
			}else{
								
				$data[0]['Tables_Name'] = DB_DataBase;	
			}
		}
		
		$db->Where = " WHERE Tables_Open = '1'";
		$db->Order_By = ' ORDER BY Tables_ID ASC';		
		$db->query_sql(Tables_DB, '*');
		while( $row = $db->query_fetch() ){
			
			if( $Type == 'NAMES' ){
				
				$data[$row['Tables_ID']] = $row['Tables_Name']. ' ( ' .$row['Tables_Name1']. ' )';
			}else{
				
				if( !empty($Field) ){
				
					$data[$row['Tables_ID']] = $row[$Field];
				}else{
									
					$data[$row['Tables_ID']] = $row;	
				}
			}
		}
	}
	
	return $data;
}

function Get_Sn_Code(){
	
	global $Menu_Data, $Main_Table, $Main_Key;
	
	$ID = '';
	if( !empty($Menu_Data['Menu_CstSnNum']) ){
		
		$SN = '';
		if( $Menu_Data['Menu_CstSnType'] == 'YYYYMMDD' ){
			
			$SN = $Menu_Data['Menu_CstSnPre'].date('Ymd');
		}else if( $Menu_Data['Menu_CstSnType'] == 'YYMMDD' ){
			
			$SN = $Menu_Data['Menu_CstSnPre'].substr(date('Ymd'), -6);
		}else{
			
			$SN = $Menu_Data['Menu_CstSnPre'];
		}
		
		$ID = GET_NEW_ID($Main_Table, $Main_Key, $SN, $Menu_Data['Menu_CstSnNum']);
	}
	
	return $ID;
}

function GET_NEW_ID( $Sheet, $Field, $Format, $sprintf ){//創建自設格式ID
	
	$db = new MySQL();
	
	$db->Where    = " WHERE " .$Field. " LIKE '" .$Format. "%'";
	$db->Order_By = ' ORDER BY ' .$Field. ' DESC';
	$db->query_sql($Sheet, $Field, 0, 1);

	$row = $db->query_fetch();
	
	if( empty($row) ){
		
		$NEW_ID = $Format.sprintf("%0" .$sprintf. "d", 1);
	}else{
	
		$NEW_ID = $Format.str_pad(substr($row[0], '-'.$sprintf)+1, $sprintf, 0, STR_PAD_LEFT);
	}
	
	return $NEW_ID;
}

function Get_Small_Pic(){//抓取小圖示資料
	
	$content = file_get_contents(SYS_PATH.'config'.DIRECTORY_SEPARATOR.'cfg.smallpic.php');
	//$content = ICONV_CODE('BIG5_TO_UTF8', $content);
	
	$SmallArr  = explode("\n", $content);
	$Arr = array();
	foreach($SmallArr as $val){
		
		$match = explode(",", trim($val));
		$TF = trim($match[1]);
		if( $TF == "true" ){ //true or false
		
			$Arr[trim($match[0])] = trim($match[2]);
		}
	}

	asort($Arr);
	
	return $Arr;
}

function Select_Option( $arrVal, $defVal = '', $Type = '' ){//選擇自動帶入資料
	
	if( $Type != 'NO_FIRST_OPTION' ){
		
		$tmpStr = '<option value="0">請選擇</option>';
	}
	
	if( is_array($arrVal) ){
		
		foreach( $arrVal as $key => $val ){
			
			$selected = $key == $defVal ? 'selected' : '';
			
			$tmpStr .= '<option value="' .$key. '" ' .$selected. '>' .$val. '</option>';
		}
	}
	
	return $tmpStr;
}

function REMOVE_NDATA( $Arr, $Remove_Arr = array() ){//移除不需要的POST資料
	
	unset($Arr['id']);//移除編號
	unset($Arr['_type']);//移除執行類型
	unset($Arr['Pages']);//移除頁碼
	unset($Arr['Page_Size']);//移除一頁要顯示幾筆
	unset($Arr['SearchKey']);//移除搜尋字串
	unset($Arr['Sort']);//移除排序
	unset($Arr['Field']);//移除排序欄位
	
	if( !empty($Arr['search_field']) ){//移除進階查詢欄位
		
		foreach( $Arr['search_field'] as $key => $field ){
		
			unset($Arr['inquire_'.$field]);
		}
		
		unset($Arr['search_field']);
	}
	
	
	if( !empty($Remove_Arr) ){
		
		foreach( $Remove_Arr as $val ){
			
			unset($Arr[$val]);//移除陣列欄位
		}
	}
	
	return $Arr;
}

function Get_Top_Return( $data ){//取得TOP按鈕是否使用
	
	$Arr = array();
	$Arr['html_add'] = $data['Menu_Add'] == 1 && Menu_Use($data, 'add') ? false : true;
	$Arr['html_edt'] = $data['Menu_Edt'] == 1 && Menu_Use($data, 'edit') ? false : true;
	$Arr['html_del'] = $data['Menu_Del'] == 1 && Menu_Use($data, 'delete') ? false : true;
	
	return $Arr;
}

function ICONV_CODE( $Type, $Str ){//轉碼
	
	if( $Type == 'BIG5_TO_UTF8' ){
		
		if( is_array($Str) ){
			
			foreach( $Str as $key => $val ){
		
				$Str[$key] = iconv("BIG5", "UTF-8", $val);
			}
		}else{
			
			$Str = iconv("BIG5", "UTF-8", $Str);
		}
	}else if( $Type == 'UTF8_TO_BIG5' ){
		
		if( is_array($Str) ){
			
			foreach( $Str as $key => $val ){
		
				$Str[$key] = iconv("UTF-8", "BIG5", $val);
			}
		}else{
			
			$Str = iconv("UTF-8", "BIG5", $Str);
		}
	}
	
	return $Str;
}

function Delete_File( $Path, $File_Name ){//刪除檔案
	
	global $PicSize;
	
	$File = ICONV_CODE( 'UTF8_TO_BIG5', $Path.$File_Name );
	
	if( is_file($File) ){//刪除原圖
		
		unlink($File);
	}
	
	$File = ICONV_CODE( 'UTF8_TO_BIG5', $Path.'sm_'.$File_Name );
	
	if( is_file($File) ){//刪除後台用小圖
		
		unlink($File);
	}

	if( is_array($PicSize) ){
	
		foreach( $PicSize as $Size ){
			
			$File = ICONV_CODE( 'UTF8_TO_BIG5', $Path.$Size.'_'.$File_Name );
		
			if( is_file($File) ){//刪除自製設定圖片大小
				
				unlink($File);
			}
		}
	}
}

function Get_PicSize( $size, $array = array() ){//抓取圖片大小設定
	
	global $PicSize;

	$Arr = array();
	if( $size == 1 ){
		
		$Arr['sm'] = array('w' => 50, 'h' => 50);//後台小圖用
	}else if( $size == 2 ){
		
		$Arr['sm'] = array('w' => 200, 'h' => 200);//後台相簿小圖用
	}
	
	if( is_array($array) ){
		
		foreach( $array as $key => $val ){
			
			if( in_array($key, $PicSize) ){
				
				$Arr[$key] = $val;
			}
		}
	}
	
	return $Arr;
}

function Menu_Use( $Menu_Data, $Type = '1' ){//目錄使用判斷
	
	global $Admin_data;
	
	if( $Type == 1 ){
		
		if( $Menu_Data['Menu_SysAdminUse'] == 1 && $Admin_data['Group_ID'] != 1 ){
			
			return false;
		}
	}else if( $Type == 2 ){
		
		if( !in_array($Menu_Data['Menu_ID'], $Admin_data['Menu_Use']) && $Admin_data['Group_ID'] != 1 ){
		
			return false;
		}
		
		if( !in_array($Menu_Data['Menu_UpMID'], $Admin_data['Menu_Use']) && $Admin_data['Group_ID'] != 1 && $Menu_Data['Menu_Lv'] == 2 ){
			
			return false;
		}
	}else if( $Type == 'add' ){
		
		if( !in_array($Menu_Data['Menu_ID'].'_1', $Admin_data['Menu_Use']) && $Admin_data['Group_ID'] != 1 ){
		
			return false;
		}
	}else if( $Type == 'edit' ){
		
		if( !in_array($Menu_Data['Menu_ID'].'_2', $Admin_data['Menu_Use']) && $Admin_data['Group_ID'] != 1 ){
		
			return false;
		}
	}else if( $Type == 'delete' ){
		
		if( !in_array($Menu_Data['Menu_ID'].'_3', $Admin_data['Menu_Use']) && $Admin_data['Group_ID'] != 1 ){
		
			return false;
		}
	}else if( $Type == 'view' ){
		
		if( !in_array($Menu_Data['Menu_ID'].'_4', $Admin_data['Menu_Use']) && $Admin_data['Group_ID'] != 1 ){
		
			return false;
		}
	}
	
	return true;
}

function Get_Table_Info( $Sheet, $Field_Key = '', $Field = '', $Where = '', $Order = '', $Arr = array() ){//取得資料表資料
		
	$db 	= new MySQL();
	
	$data 	= $Arr;
	if( !empty($Where) ){
		
		$db->Where = $Where;		
	}
	
	if( !empty($Order) ){
		
		$db->Order_By = $Order;		
	}
	
	$db->query_sql($Sheet, '*');
	while( $row = $db->query_fetch() ){
		
		if( !empty($Field) ){
		
			if( empty($Field_Key) ){
				
				$data[] = $row[$Field];
			}else{
				
				$data[$row[$Field_Key]] = $row[$Field];
			}
		}else{
			
			if( empty($Field_Key) ){
				
				$data[] = $row;	
			}else{
				
				$data[$row[$Field_Key]] = $row;	
			}
		}
	}
	
	if( empty($data) ){
		
		$data = array();
	}
	
	return $data;
}

function Get_Table_Option( $Sheet, $Type = 'ALL', $InType_Arr = array() ){//取得資料表設定資料
			
	$data = array();
	$db = new MySQL();
				
	$table_option_arr = Get_Table_Info(Tables_Option_DB, 'TO_Field', '', " WHERE TO_Name = '" .$db->val_check($Sheet). "'", ' ORDER BY TO_Sort ASC');//取出資料欄位設定資料
	
	if( !empty($table_option_arr) ){
		
		foreach( $table_option_arr as $tofield => $toarr ){
						
			if( $Type == 'IN' ){
				
				if( !$toarr['TO_InShow'] ){ continue; }//跳過不顯示文件
			}else if( $Type == 'OUT' ){
				
				if( !$toarr['TO_OutShow'] ){ continue; }//跳過不顯示文件
			}
			
			if( !empty($InType_Arr) && !in_array($toarr['TO_InType'], $InType_Arr) ){ continue; }//不是想要的欄位種類跳過
			
			$data[$tofield] = $toarr;
		}
	}
	
	return $data;
}

function SendMail( $Maildata, $Subject = '', $Body = '', $Mailto = array(), $MailtoCC = array(), $MailtoBCC = array(), $MailAttch = array() ){//寄送Email
	try {
		$mail = new PHPMailer(true);								//建立物件
		$mail->SetLanguage("zh"); 								//設定語系
		$mail->IsSMTP();										//設定使用SMTP方式寄信
		
		//$mail->SMTPDebug  = 2;								// enables SMTP debug information (for testing)
																// 1 = errors and messages
																// 2 = messages only
										
		if( version_compare(PHP_VERSION, '5.6.0', '>=') ){		//php 5.6 以上要取消peer
			
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
					)
			);
		}
		
		$mail->CharSet 	= "UTF-8"; 								//設定郵件編碼
		$mail->Host 	= $Maildata['WO_StmpHost']; 			//appmsr.hinet.net;設定SMTP主機 
		$mail->Port 	= $Maildata['WO_StmpPort']; 			//設定SMTP埠位，預設為25埠
		
		if( !empty($Maildata['WO_StmpSecure']) ){
			
			$mail->SMTPSecure = strtolower($Maildata['WO_StmpSecure']);		//Gmail的SMTP主機需要使用SSL連線  
		}else if( $mail->Host == 'smtp.gmail.com' ){
			
			$mail->ErrorInfo  = 'GMAIL SMTP 請設定加密方式';
		}
		
		if( empty($mail->ErrorInfo) ){	
			
			if( !empty($Maildata['WO_StmpAuth']) ){
				
				$mail->SMTPAuth = true; 							//設定SMTP需要驗證
				$mail->Username = $Maildata['WO_StmpAcc'];			//設定驗證帳號 
				$mail->Password = $Maildata['WO_StmpPass'];			//設定驗證密碼 
			}
			
			$mail->From 	= $Maildata['WO_SendEmail']; 			//設定寄件者信箱 
			$mail->FromName = $Maildata['WO_SendName']; 			//設定寄件者姓名
			
			$mail->Subject 	= $Subject; 							//設定郵件標題 
			
			//$mail->Body 	= $Body; 								//設定郵件內容
			//$mail->IsHTML(true); 									//設定郵件內容為HTML
			
			$mail->MsgHTML($Body); 	
			
			foreach( $Mailto as $Name => $Email ){
				
				$mail->addAddress($Email, $Name); 					//設定收件者郵件及名稱 (寄送時顯示在收件人欄)
			}
			
			foreach( $MailtoCC as $Name => $Email ){
				
				$mail->addCC($Email, $Name); 						//設定副本收件者郵件及名稱	(寄送時顯示在副本欄)
			}
			
			foreach( $MailtoBCC as $Name => $Email ){
				
				$mail->addBCC($Email, $Name); 						//設定密件副本收件者郵件及名稱 (寄送時看不到email)
			}
			
			foreach( $MailAttch as $Attch ){
				
				$mail->addAttachment($Attch);						//附加檔案
			}
			
			$mail->Send();
			return true;
		}
	} catch (Exception $e) {	
			$db = new MySQL();
			$db->SSD( '', '', $mail->ErrorInfo, '', 'MAILERROR', '' );	
			return false;
	}
	
}

function CHK_PATH( $Path, $mode = 0775 ){//判斷資料夾
	
	if( !is_dir($Path) ){

		mkdir($Path, $mode, true);
	}
}

function Search_Option( $Option, $Comment ){//查詢設定
		
	$array = array();
	
	//查詢欄位名稱設定
	$array['name'] = $Comment;
	
	//查詢欄位設定種類
	if( $Option['TO_InType'] == 'checkbox' ){
		
		if( !empty($Option['TO_SelStates']) ){
			
			global ${$Option['TO_SelStates']};
			
			$array['type']		= 'select';
			$array['select'] 	= ${$Option['TO_SelStates']};
			
		}else{
			
			$array['type'] = 'text';
		}
	}else if( $Option['TO_InType'] == 'datestart' || $Option['TO_InType'] == 'dateend' || $Option['TO_InType'] == 'datecreat' || $Option['TO_InType'] == 'dateedit' ){
		
		$array['type']		= 'datetime';
		$array['format']	= $Option['TO_TimeFormat'];
	}else if( $Option['TO_InType'] == 'number' ){
		
		$array['type'] = 'between';
	}else{
		
		if( !empty($Option['TO_SelStates']) ){
			
			global ${$Option['TO_SelStates']};
			
			$array['type']		= 'select';
			$array['select'] 	= ${$Option['TO_SelStates']};
		}else{
			
			$array['type'] = 'text';
		}
	}
	
	return $array;
}

function Search_Fun( $Where, $POST, $Search_Option, $Field_As = array() ){//查詢串接
	
	$db = new MySQL();
	
	foreach( $POST['search_field'] as $key => $field ){//先抓出要查詢的欄位
		
		$post_val = $POST['inquire_'.$field];
		
		if( !empty($Field_As[$field]) ){//為了主鍵也能搜尋做的判斷, 例如a.Member_ID
			
			$field = $Field_As[$field].'.'.$field;
		}
		
		if( !empty($post_val) ){//判斷要查詢的欄位是否有值
			
			//foreach( $POST[$field] as $key2 => $val2 ){//開始串接查詢判斷
				
				if( empty($Where) ){
					
					if( $Search_Option[$field]['type'] == 'datetime' || $Search_Option[$field]['type'] == 'between' ){
						
						if( !empty($post_val[0]) && !empty($post_val[1])){
								
							$Where = " WHERE ( " .$db->val_check($field). " BETWEEN '" .$db->val_check($post_val[0]). "' AND '" .$db->val_check($post_val[1]). "' )";
						}else if( !empty($post_val[0]) ){
							
							$Where = " WHERE " .$db->val_check($field). " >= '" .$db->val_check($post_val[0]). "'";
						}else if( !empty($post_val[1]) ){
							
							$Where = " WHERE " .$db->val_check($field). " <= '" .$db->val_check($post_val[1]). "'";
						}
					}else{
						
						$Where = " WHERE " .$db->val_check($field). " LIKE '%" .$db->val_check($post_val[0]). "%'";
					}
				}else{
					
					if( $Search_Option[$field]['type'] == 'datetime' || $Search_Option[$field]['type'] == 'between' ){
						
						if( !empty($post_val[0]) && !empty($post_val[1])){
								
							$Where .= " AND ( " .$db->val_check($field). " BETWEEN '" .$db->val_check($post_val[0]). "' AND '" .$db->val_check($post_val[1]). "' )";
						}else if( !empty($post_val[0]) ){
							
							$Where .= " AND " .$db->val_check($field). " >= '" .$db->val_check($post_val[0]). "'";
						}else if( !empty($post_val[1]) ){
							
							$Where .= " AND " .$db->val_check($field). " <= '" .$db->val_check($post_val[1]). "'";
						}
					}else{
						
						$Where .= " AND " .$db->val_check($field). " LIKE '%" .$db->val_check($post_val[0]). "%'";
					}
				}
			//}
		}
	}

	return $Where;
}

function GetUrlVal( $url, $str ){//抓取網址問號後面裡的某個值
	
	$ex = explode('?', $url);
	
	if( $str == 'PHP_SELF' ){
		
		$ex = explode('/', $ex[0]);
		
		return end($ex);
	}if( $str == 'PHP_SELF_NAME' ){
		
		$ex = explode('/', $ex[0]);
		$ex = explode('.', end($ex));
		
		return $ex[0];
	}else if( $str == 'PHP_SELF_SUB_NAME' ){
		
		$ex = explode('/', $ex[0]);
		$ex = explode('.', end($ex));
		
		return end($ex);
	}else{
		
		$ex = explode('&', $ex[1]);
		
		foreach( $ex as $val ){
			
			$match = explode('=', $val);
			
			if( $match[0] == $str ){
				
				return $match[1];
			}
		}
	}
}

function Turnencode( $Str, $Type = '' ){//鎖碼
	
	$TURN_TF	= true;
	
	$Path 		= SYS_PATH.'config'.DIRECTORY_SEPARATOR.'cfg.turncode.php';
	if( is_file($Path) ){
		
		include($Path);
	}else{
		
		$TURN_TF = false;
	}
		
	if( $TURN_TF ){
		
		$Str = strtr($Str, $TURNCODES, $TURNCODEE);
		
		return $Str;
	}else{
		
		return '';
	}
}

function Turndecode( $Str, $Type = '' ){//解碼
	
	$TURN_TF	= true;
	
	$Path 		= SYS_PATH.'config'.DIRECTORY_SEPARATOR.'cfg.turncode.php';
	if( is_file($Path) ){
		
		include($Path);
	}else{
		
		$TURN_TF = false;
	}
		
	if( $TURN_TF ){
		
		$Str = strtr($Str, $TURNCODEE, $TURNCODES);
		
		return $Str;
	}else{
		
		return '';
	}
}

function Check_Table( $Sheet ){//判斷有無此資料表
	
	$db = new MySQL();
	
	$db->query("SHOW TABLES LIKE '" .$Sheet. "'");
	if( $db->query_rows() == 0 ){
		
		exit('此資料庫, 無此 ' .$Sheet. ' 資料表');
	}
}

function Check_File( $File ){//判斷有無此檔案
	
	if( !is_file($File) ){
		
		exit('無此 ' .basename($File). ' 檔案');
	}
}

function Class_Get( $type = '', $array = array(), $im = false ){//取出分類資料
	
	global $Main_Table3, $Main_Key3, $Main_TablePre3, $Main_maxLv3, $Class_Arr;
	
	$db = new MySQL();
	
	$db->Where = " WHERE " .$Main_TablePre3. "_Lv <= '" .$Main_maxLv3. "'";

	$db->Order_By = ' ORDER BY ' .$Main_TablePre3. '_Lv ASC, ' .$Main_TablePre3. '_Sort DESC, ' .$Main_Key3. ' DESC';
	
	$db->query_sql($Main_Table3, '*' );
	
	$Class_Arr 	= $array;
	$_Class 	= $_Class2 = array();
	while( $row = $db->query_fetch() ){
		
		if( $row[$Main_TablePre3.'_Lv'] == 1 ){
			
			$_Class[$row[$Main_Key3]] = $row;
		}else{
			
			$_Class2[$row[$Main_TablePre3.'_UpMID']][] = $row;
		}
	}
	
	foreach( $_Class as $id => $val ){
		
		if( $type == 'TYPE1' ){
			
			$Class_Arr[$id] = '├─ ' .$val[$Main_TablePre3.'_Name']. ($val[$Main_TablePre3.'_Open']?' (顯)':' (隱)');
		}else{
			
			$Class_Arr[$id] = $val[$Main_TablePre3.'_Name'];
		}
		
		if( $im ){
			
			Class_List( $type, $id, $_Class2, $id );
		}else{
			
			Class_List( $type, $id, $_Class2 );	
		}
	}
	
	return $Class_Arr;
}

function Class_List( $type, $id, $list, $imid = '' ){//列出分類 imid => 將層級的ID串接起來
	
	global $Main_TablePre3, $Main_maxLv3, $Class_Arr;

	if( !empty($list[$id]) ){
		
		foreach( $list[$id] as $val ){
			
			$_ID   = $val[$Main_TablePre3.'_ID'];
			$_Lv   = $val[$Main_TablePre3.'_Lv'];
			$_Name = $val[$Main_TablePre3.'_Name'];
			$_Open = $val[$Main_TablePre3.'_Open'];
			
			$_Str  = '';
			if( $type == 'TYPE1' ){
				
				for( $i = 1; $i < $_Lv; $i++ ){
					
					$_Str .= '　';
				}
				$_Str .= '├─ '.$_Name. ($_Open?' (顯)':' (隱)');
			}else{
				
				$_Str = $_Name;
			}
			
			if( empty($imid) ){
				
				$Class_Arr[$_ID] = $_Str;
			}else{
				
				$_ID2 = $imid.'-'.$_ID;
				$Class_Arr[$_ID2] = $_Str;
			}
									
			if( $_Lv < $Main_maxLv3 ){
				
				Class_List( $type, $_ID, $list, $_ID2 );
			}
		}
	}
}

function menu_create_lv( $id, $list, $type = '' ){
	
	global $Admin_data, $Now_List, $Main_TablePre, $Main_maxLv3, $table_info, $menu_lv_states;
	
	$content = '<ul>';
	if( !empty($list[$id]) ){
		
		foreach( $list[$id] as $val ){
			
			$_ID   = $val[$Main_TablePre.'_ID'];
			$_Lv   = $val[$Main_TablePre.'_Lv'];
			$_Name = $val[$Main_TablePre.'_Name'];
			$_Sort = $val[$Main_TablePre.'_Sort'];
			$_Open = $val[$Main_TablePre.'_Open'] ? 1 : 0;
			
			$content .= '<li>';
			
			//if( $_Lv < $Main_maxLv3 ){
				
				$content .= '	<i class="icon fa fa-minus-circle" data-op="1"></i>';
			//}
			
			$content .= '	<input type="hidden" name="' .$Main_TablePre. '_ID[]" value="' .$_ID. '">';
			$content .= '	<input type="hidden" name="' .$Main_TablePre. '_Lv[]" value="' .$_Lv. '">';
			$content .= '	<input type="text" name="' .$Main_TablePre. '_Name[]" value="' .$_Name. '" class="menu_input1 eshow" e-txt="' .$table_info[$Main_TablePre.'_Name']['Comment']. '" msg="請輸入' .$table_info[$Main_TablePre.'_Name']['Comment']. '" maxlength="' .$table_info[$Main_TablePre.'_Name']['Field_Length']. '">';		
			$content .= '	<input type="text" name="' .$Main_TablePre. '_Sort[]" value="' .$_Sort. '" class="menu_input2 eshow" e-txt="' .$table_info[$Main_TablePre.'_Sort']['Comment']. '" input-type="number" input-min="0" input-max="99999" maxlength="' .$table_info[$Main_TablePre.'_Sort']['Field_Length']. '"> ';
			
			if( $type == 'Main' && ( $Admin_data['Admin_Permissions'] == 255 || $Admin_data['Group_ID'] == 1 ) ){
				
				$content .= ' 	<button type="button" class="btn btn-white ' .($val[$Main_TablePre.'_SysUse']==1?"btn-danger":"btn-inverse"). ' btn-sm menu_btn eshow" e-txt="權限">';
                $content .= '		<span>' .$val[$Main_TablePre.'_Permissions']. '</span>';
                $content .= '	</button>';
			}else if( $type == 'showhide' ){
				
				if( (Menu_Use($Now_List, 'edit')) ){ 
				
					$content .= '<button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_display" msh="' .$_Open. '">';
				}else{
					
					$content .= '<button type="button" class="btn btn-white btn-inverse btn-sm menu_btn" disabled>';
				}
                $content .= '		<span class="' .($_Open?'color-blue':'color-red'). '">' .($_Open?'顯示':'隱藏'). '</span>';
                $content .= '	</button>';
			}
						
			if( $_Lv < $Main_maxLv3 ){
				
				$content .= '	<button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_create" mlv="' .($_Lv+1). '" '.(Menu_Use($Now_List, 'edit')&&Menu_Use($Now_List, 'add')?'':'disabled="disabled"'). '>';
				$content .= '		<span>新增第' .$menu_lv_states[($_Lv+1)]. '層</span>';
				$content .= '	</button>';
			}
			
			$content .= '	<button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_edit" '.(Menu_Use($Now_List, 'edit')?'':'disabled="disabled"'). '>';
			$content .= '		<span>編輯</span>';
			$content .= '	</button>';
			
			$content .= '	<button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_del" '.(Menu_Use($Now_List, 'delete')?'':'disabled="disabled"'). '>';
			$content .= '		<span>刪除</span>';
			$content .= '	</button>';
			
			if( $_Lv < $Main_maxLv3 ){
				
				$content .= menu_create_lv( $_ID, $list, $type );
			}
			
			$content .= '</li>';
		}
	}
	$content .= '</ul>';
	
	return $content;
}

function que_menu_create_lv( $id, $list, $equlist = array()){
	
	global $Admin_data, $Now_List, $Main_TablePre, $Main_maxLv3, $table_info, $menu_lv_states;
	
	$content = '<ul>';
	if( !empty($list[$id]) ){
		
		foreach( $list[$id] as $val ){
			
			$_ID   = $val[$Main_TablePre.'_ID'];
			$_Lv   = $val[$Main_TablePre.'_Lv'];
			$_Name = $val[$Main_TablePre.'_Name'];
			$_EQU_ID = $val['EQU_ID'];
			$_Sort = $val[$Main_TablePre.'_Sort'];
			$_Open = $val[$Main_TablePre.'_Open'] ? 1 : 0;
			
			$content .= '<li>';
			
			$content .= '	<i class="icon fa fa-minus-circle" data-op="1"></i>';
			
			$content .= '	<input type="hidden" name="' .$Main_TablePre. '_ID[]" value="' .$_ID. '">';
			$content .= '	<input type="hidden" name="' .$Main_TablePre. '_Lv[]" value="' .$_Lv. '">';
			//$content .= '	<input type="text" name="' .$Main_TablePre. '_Name[]" value="' .$_Name. '" class="menu_input1 eshow" e-txt="裝備名稱" msg="請輸入' .$table_info[$Main_TablePre.'_Name']['Comment']. '" maxlength="' .$table_info[$Main_TablePre.'_Name']['Field_Length']. '">';		
			
			$content .= '	<select id="EQU_ID" name="'.$Main_TablePre.'_Name[]" msg="' .$Msg. '" class="col-xs-5 col-sm-2 padding_none">';
			$content .=			Select_Option($equlist, $_Name );
			$content .= '	</select>';
			
			$content .= '	<input type="text" name="' .$Main_TablePre. '_Sort[]" value="' .$_Sort. '" class="menu_input2 eshow" e-txt="數量" input-type="number" input-min="0" input-max="99999" maxlength="' .$table_info[$Main_TablePre.'_Sort']['Field_Length']. '"> ';
			
			$content .= '	<button type="button" class="btn btn-white btn-inverse btn-sm menu_btn menu_del" '.(Menu_Use($Now_List, 'delete')?'':'disabled="disabled"'). '>';
			$content .= '		<span>刪除</span>';
			$content .= '	</button>';
			
			if( $_Lv < $Main_maxLv3 ){
				
				$content .= menu_create_lv( $_ID, $list, $type );
			}
			
			$content .= '</li>';
		}
	}
	$content .= '</ul>';
	
	return $content;
}

function MENU_UpMID_DEL( $id ){
	
	global $Main_Key, $Main_Table, $Main_TablePre, $table_option_arr, $Path, $PathFile;;
	
	$db = new MySQL();
	
	$db->Where = " WHERE " .$Main_TablePre. "_UpMID = '" .$id. "'";
	$db->query_sql($Main_Table, $Main_Key);
	
	while( $row = $db->query_fetch() ){
		
		$_html_msg = MENU_UpMID_DEL($row[$Main_Key]);
	}
	
	$db->Where = " WHERE " .$Main_Key. " = '" .$id. "'";
	$db->query_sql($Main_Table, '*');
	$row = $db->query_fetch();
	
	if( !empty($table_option_arr) && empty($_html_msg) ){
		
		foreach( $table_option_arr as $tofield => $toarr ){
														
			if( $toarr['TO_InType'] == 'uploadfile' ){
				
				$Path = $PathFile;
			}
									
			$File_Name = $row[$tofield];
	
			if( !empty($File_Name) ){
				
				$File = ICONV_CODE( 'UTF8_TO_BIG5', $Path.$File_Name );
				
				if( is_file($File) ){//檔案存在
				
					Delete_File($Path, $File_Name);
					
					if( is_file($File) ){//刪除檔案
					
						$_html_msg = $toarr['TO_InType'] == 'uploadimg' ? '編號 ( ' .$row[$Main_Key]. ' ) 圖片刪除失敗' : '編號 ( ' .$row[$Main_Key]. ' ) 檔案刪除失敗';
						break;
					}
				}
			}
		}
	}
	
	if( empty($_html_msg) ){
		
		$db->Where = " WHERE " .$Main_Key. " = '" .$id. "'";
	
		$db->query_delete($Main_Table);
	}
	
	return $_html_msg;
}

function ForMatDate( $Str, $Format = '' ){
	
	if( $Format == 'YYYY-MM-DD HH:mm' && !empty($Str) ){
		
		$Str = date('Y-m-d H:i', strtotime($Str));
	}else if( $Format == 'YYYY-MM-DD HH' && !empty($Str) ){
		
		$Str = date('Y-m-d H', strtotime($Str));
	}else if( $Format == 'YYYY-MM-DD' && !empty($Str) ){
		
		$Str = date('Y-m-d', strtotime($Str));
	}
	
	return $Str;
}

	//GET傳遞參數加密
	function OEncrypt($data, $key){
		
		$key	=	md5($key);
		$x		=	0;
		$len	=	strlen($data);
		$l		=	strlen($key);
		for ($i = 0; $i < $len; $i++){
			
			if ($x == $l){
				
				$x = 0;
			}
			$char .= $key{$x};
			$x++;
		}
		for ($i = 0; $i < $len; $i++){
			
			$str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
		}
		
		$rs = urlencode( base64_encode($str) );
		
		return $rs;
	}
	
	//GET傳遞參數解密
	function ODecrypt($data, $key){
		
		$key = md5($key);
		$x = 0;
		$data = base64_decode($data);
		$len = strlen($data);
		$l = strlen($key);
		for ($i = 0; $i < $len; $i++){
			
			if ($x == $l){
				
				$x = 0;
			}
			$char .= substr($key, $x, 1);
			$x++;
		}
		
		for ($i = 0; $i < $len; $i++){
			
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))){
				
				$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			}else{
				
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return $str;
	}

	function GDC( $Get , $key='' ){
		
		$rs = array();
		
		$code = ODecrypt($Get , $key);
	
		$arr1 = explode( '&' , $code);
		
		foreach( $arr1 as $field ){
			
			$arr2 = explode( '=' , $field);
			$rs[$arr2[0]] = $arr2[1];
		}
		
		return $rs;
	}
	
	//WAF <> 排除
	function TurnSymbol( $input ){
		
		$input = str_replace( '%bm%1%bm%' ,"<", $input);
		$rs = str_replace( '%bm%2%bm%' ,">", $input);
		
		return $rs;
	}
	
	//EXCEL 超過26欄自動轉換成AA
	//帶入參數為欄位數
	function EXCEL_SWITCH_TITLE( $_SN ){
		
		$_First_ACI 	= "";
		
		$_round 		= floor( $_SN / 27 );
		
		$_Less 			= ( $_SN % 27 );
		
		//第二輪AA
		if( $_round >= 1 ){
			
			$_SCHR 			= 64 + $_round;
			$_First_ACI 	=  chr($_SCHR);
			
			$_CHR 			= 65 + $_Less;
		}else{
			
			$_CHR 			= 64 + $_Less;
		}

		$_Second_ACI 	=  chr($_CHR);
		
		$_ACI 			= trim($_First_ACI.$_Second_ACI);
		
		return $_ACI;
	}

	//測試用 印出陣列字串
	function ob_print( $input ){
		
		ob_start();
		print_r($input);
		$rs = ob_get_contents();
		ob_end_clean();
		
		return $rs;	
	}
	
	function GET_AREA_LIST(){
		
		$db = new MySQL();
		$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
		$db->query_sql("web_area", '*');
		$row = $db->query_fetch();//取出資料
	}
?>