<?php
require_once(__DIR__."/include/web.config.php");

$json_array = array();

$_type = $_POST['_type'];

if( !empty($_type) && empty($_html_msg) ){
	
	$db		= new MySQL();
	$SC 	= new ShopCart();
	
	
	if( !empty( $_MemberData['Member_ID'] ) ) {
		
		$Order_ID = GET_OrderSn( $_MemberData['Member_ID'] );
		
	}else if( !empty($_SESSION[$_Website]['website']['order_Sn']) ){
	
		$Order_ID = $_SESSION[$_Website]['website']['order_Sn'];
	}
	
	switch( $_type ){
		

		case "add_cart":
	
			$Pro_ID    		= $_POST['id'];
			$href_type  	= $_POST['href_type'];
			$Pro_Unit   	= $_POST['unit'];
			
			if( is_numeric($_POST['count']) ){
				
				$Pro_Count = $_POST['count'] > 0 ? $_POST['count'] : 1;
			}else{
				
				$_html_msg 	= '請輸入數字0-9';
				break;
			}
			
			if( empty($Order_ID) ){
				
				$_data = $SC->Creat_OrderID();//創建訂單號碼

				if( $_data['states'] == 1 ){
					
					$_html_msg = $_data['message'];
					break;
				}else{
					
					$_SESSION[$_Website]['website']['order_Sn'] = $_data['id'];
					$Order_ID = $_data['id'];
				}
			}
			
			if( empty($_html_msg) ) {
				
				//檢查訂單是否有重複產品並加入購物車
				$_data = $SC->Check_OrderPro( $Order_ID, $Pro_ID, $Pro_Count, $Pro_Unit);
				
				if( $_data['states'] != 0 ){
				
					$_html_msg 	= $_data['message'];
					
				}else{
					
					if( $href_type == 'NTC' ) 
					{
						$_html_href = 'cart1.php';	
					}else{
						$_html_eval = 'Reload()';
					}
					
					$_html_msg 	= $_data['message'];
				}
			}
		break;
		
		case "chg_cart":
			
			$Pro_ID		= $_POST['id'];
			$Pro_Count	= $_POST['count'];
			$Pro_Unit	= $_POST['unit'];
						
			$db->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "' AND Product_ID = '" .$Pro_ID. "' AND Ordertd_Unit = '" .$Pro_Unit. "'";
			$db->query_data($SC->Tabletd, array('Ordertd_Count' => $Pro_Count), 'UPDATE');
			
			if( !empty($db->Error) ){
				
				$_html_msg 	= '數量更新失敗';
			}else{
				
				$db = new MySQL();
				$db->Where = "Where Ordertm_Sn = '".$Order_ID."'";
				$db->Where .= " AND Delivery_ID = '202'";
				$db->query_sql( $SC->Tabletm, 'Delivery_ID');
				
				if( $row = $db->query_fetch('','assoc') ){
		
					$_Limit = $SC->GET_DELIVERY_LIMIT();
					$_Sub	= $SC->GET_ORDER_SUB($Order_ID);
					
					if( $_Sub > $_Limit ) {
					
						$db = new MySQL();
						$db->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "'";
						$db->query_data($SC->Tabletm, array('Delivery_ID' => 201), 'UPDATE');
						
						$_html_msg 	= '您的訂單金額超出貨到付款上限金額，請選擇其他付款方式。';
					}
				}
			}
			
			$_html_eval = 'Reload()';
		break;
		
		case "del_cart":
			
			$Pro_ID		= $_POST['id'];
			$Pro_Unit	= $_POST['unit'];
			
			$_html_eval = 'Reload()';
			
			if( !empty($Order_ID) ){
				
				$db->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "' AND Product_ID = '" .$Pro_ID. "' AND Ordertd_Unit = '" .$Pro_Unit. "'";
				$db->query_delete($SC->Tabletd);
				
				if( !empty($db->Error) ){
					
					$_html_msg 	= '刪除失敗';
				}
			}
		break;
		
		//儲存訂單
		case "save_cart":
			
			global $_MemberData,$member_db;
			
			if( empty($Order_ID) || !isset($Order_ID) ) {
				
				$_html_msg 	= '操作錯誤';
				$_html_href = 'product.php';
				break;
			}
			
			$Value = array();
			
			
			if( empty($_MemberData) ) {
				
				$Value['reg_email'] 		= trim($_POST['reg_email']);	//會員帳號
				$Value['reg_pwd']			= trim($_POST['reg_pwd']);		//會員密碼
				$Value['reg_name']			= trim($_POST['reg_name']);		//會員名稱
				$Value['reg_mobile'] 		= trim($_POST['reg_mobile']);	//會員電話
				$Value['reg_city'] 			= trim($_POST['reg_city']);		//縣市
				$Value['reg_county'] 		= trim($_POST['reg_county']);	//區域
				$Value['reg_address'] 		= trim($_POST['reg_address']);	//地址	
			}
			
			
			$Value['recevice_name'] 	= trim($_POST['recevice_name']);	//收件人姓名
			$Value['recevice_mobile'] 	= trim($_POST['recevice_mobile']);	//收件人聯絡電話
			$Value['recevice_city'] 	= trim($_POST['recevice_city']);	//收件人縣市
			$Value['recevice_county'] 	= trim($_POST['recevice_county']);	//收件人區域
			$Value['recevice_address'] 	= trim($_POST['recevice_address']);	//收件人地址
			$Value['recevice_content'] 	= trim($_POST['recevice_content']);	//備註
			
			//判斷空值
			foreach( $Value as $key => $val ){
				
				if( $key == 'recevice_content' ) {
					
					continue;
				}else if( empty($val) ) {
					
					$_html_msg 	= '資料填寫不完全';
					break;
				}
			}
			
			//非會員-先建立會員
			if( empty($_html_msg) && empty($_MemberData) ){
				
				$SN = date('Ymd');
				$SN = 'M'.substr($SN, -6);
				$Member_ID = GET_NEW_ID($member_db, 'Member_ID', $SN, 4);
				
				if( empty($Member_ID) ) {
					
					$_html_msg 	= '會員編號取得失敗，請重新操作!';
					break;
				}	
				
				$pwd = md5(Turnencode(trim($Value['reg_pwd']), 'password'));
				
				$_MemberData = array(
					'Member_ID'			=> $Member_ID,
					'Member_Acc'		=> $Value['reg_email'],
					'Member_Pwd' 		=> $pwd,
					'Member_Name' 		=> $Value['reg_name'],
					'Member_Email' 		=> $Value['reg_email'],
					'Member_Mobile'		=> $Value['reg_mobile'],
					'Member_City'		=> $Value['reg_city'],
					'Member_County'		=> $Value['reg_county'],
					'Member_Address'	=> $Value['reg_address'],
					'Member_Open'		=> 1,
					'Member_Sdate'		=> date('Y-m-d H:i:s', time()), //$sdate
				);
								
				$db->query_data( $member_db , $_MemberData, 'INSERT');
								
				if( !empty($db->Error) ){
						
					$_html_msg 	= '會員建立失敗, 請重新操作';
					break;
				}else{
					
					$_SESSION[$_Website]['website']['member_id'] = $Member_ID;
				}
			}
			
			//先儲存訂單資訊
			if( empty($_html_msg) ) {
				
				$db_data = array();
				$db_data['Member_ID'] 		= $_MemberData['Member_ID'];
				$db_data['Ordertm_Email'] 	= $_MemberData['Member_Email'];
				$db_data['Ordertm_RName'] 	= $Value['recevice_name'];
				$db_data['Ordertm_RCity'] 	= $Value['recevice_city'];
				$db_data['Ordertm_RCounty'] = $Value['recevice_county'];
				$db_data['Ordertm_RAddr'] 	= $Value['recevice_address'];
				$db_data['Ordertm_RMobile'] = $Value['recevice_mobile'];
				$db_data['Ordertm_Note'] 	= $Value['recevice_content'];
				
				$db->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "'";
				$db->query_data("web_ordertmain", $db_data, 'UPDATE');
				
				if( !empty($db->Error) ){
						
					$_html_msg 	= '訂單資料更新失敗';
					break;
					
				}
				
				//更新暫存購物車編號
				$db->Where = " WHERE Ord_Sn = '" .$Order_ID. "'";
				$db->query_data("web_ordert_sn", array('Ord_UID' => $_MemberData['Member_ID']), 'UPDATE');
				
			}
			
			//轉正式訂單
			if( empty($_html_msg) ) {
				
				$_data = $SC->Creat_OrderMain();
				
				if( $_data['states'] == 'success' ) {
					
					$_html_href = 'cart3.php?c='.OEncrypt('msg=success&time'.time() , 'cart3');
					
																
					$FromName = !empty($_setting_['WO_SendName']) ? $_setting_['WO_SendName'] : '本網站';
					
					//收件人姓名 收件人信箱
					$mailto = array(
						$_MemberData['Member_Name'] => $_MemberData['Member_Email']
					);
												
					$sbody = get_MailBody('custom_order',$_data['id']);
					
					//寄給客戶
					$Send_TF = SendMail($_setting_, '您在 '.$FromName. ' 訂單內容, 此信件為系統發出信件，請勿直接回覆', $sbody, $mailto);
					
					//------------------------------------------------------
					
					$mailto = array(
							$FromName => $_setting_['WO_Email']
						);
						
					$sbody = get_MailBody('admin_order',$_data['id']);
					
					//寄給管理者
					$Send_TF = SendMail($_setting_,  $FromName. ' 有一筆新的訂單內容, 此信件為系統發出信件，請勿直接回覆', $sbody, $mailto);
					
				}else{
					
					$_html_msg 	= '會員資料建立成功，訂單創建失敗，請登入會員後重新操作';
					$_html_href = 'login.php';
					break;
				}
			}


		break;

		case "cancel_order":
			
			$Order_ID = $_POST['id'];
			
			$SC = new ShopCart();
			
			if( !empty($Order_ID) ){
			
				$db->Where = "Where Orderm_ID ='".$Order_ID."'";
				$db->Where .= " AND Orderm_States = '0'";
				$db->Where .= " AND Orderm_card5no ISNULL";
				$db->Where .= " AND Orderm_payTime ISNULL";
				
				$db->query_sql( $SC->Tablem, 'Orderm_ID');
				$count = $db->query_rows(); //結果筆數

				//判斷訂單狀態為未受理才能取消
				if($count > 0){
						
					$db->Where = "Where Orderm_ID ='".$Order_ID."'";
					$db->query_data($SC->Tablem, array('Orderm_States' => '3'), 'UPDATE');

					if( !empty($db->Error)){
						
						$_html_msg 	= '訂單取消失敗';
						break;
					}else{
						
						$_html_eval = 'Reload()';
						$_html_msg 	= '您已成功取消訂單';
					}

				}else{
					
					$_html_msg 	= '訂單取消失敗，目前的訂單狀態無法取消訂單，如欲取消訂單請電洽客服人員。';
					
				}
				
			}else{
				
				$_html_msg 	= '操作錯誤';
				break;
			}
		break;
		
		case "chg_delivery":
			
			$Order_ID 		= $_POST['id'];
			$delivery 		= $_POST['delivery'];
			$SC 			= new ShopCart();
			$Delivery_Arr 	= $SC->GET_DELIVERY_LIST( $Order_ID );//取得付款方式	
			
			
			if( !empty($Delivery_Arr[$delivery]) ){
				
				$db->Where = " WHERE Ordertm_Sn = '" .$Order_ID. "'";
				$db->query_data($SC->Tabletm, array('Delivery_ID' => $delivery), 'UPDATE');
				
				if( !empty($db->Error) ){
					
					$_html_msg 	= '更新失敗';
				}
			}else{
			
				$_html_msg 	= '操作錯誤，請重新操作';
			}
			
			$_html_eval = 'Reload()';
			
		break;

	}
}else if( empty($_html_msg) ){
	
	$_html_msg 	= '操作錯誤';
	$_html_href = 'index.php';
}

ob_end_clean(); 

$json_array['html_msg']     = $_html_msg ? $_html_msg : '';//訊息
$json_array['html_href']    = $_html_href ? $_html_href : '';//連結
$json_array['html_eval']    = $_html_eval ? $_html_eval : '';//確定後要執行的JS
$json_array['html_content'] = $_html_content ? $_html_content : '';//輸出內容

	
echo json_encode( $json_array );
?>