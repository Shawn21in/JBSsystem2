<?php
require_once("../include/inc.config.php");
require_once("../include/inc.check_login.php");
require("../include/inc.connect.php");//載入連結MYSQL資訊

$_Type  = $POST['_type'];//主執行case
$_Type1 = $POST['_type1'];//副執行選項

if( !empty($_Type) ){
	
	$db 			= new MySQL();
	
	$_TF 			= new TableFun();
	
	$json_array 	= array();
	
	$Menu_Data 		= MATCH_MENU_DATA(FUN);//取得目前目錄資料
	
	$Main_Key  		= 'Admin_ID';//資料表主健
	
	$Main_Table 	= Web_Option_DB;//目錄使用的資料表
	
	include('../../include/inc.path.php');
	
	switch( $_Type ){
		
		case "Table_Re"://表格刷新
		
			$Contents 		= new Contents($POST, $GET);
	
			$_html_content	= $Contents->set_Content_List();
		break;
		
		case "Table_Edit"://表格編輯畫面
			
			$_ID = $POST['tab_chks'];
			
			$Edit_Arr = array();
			if( is_array($_ID) ){
				
				$Edit_Arr   = $_ID;
			}else{
		
				$Edit_Arr[] = $_ID;
			}
			
			$table_info = $db->get_table_info($Main_Table);//取出資料表欄位的詳細的訊息
											
			$_html_ = array();
			if( !empty($Edit_Arr) ){
				
				foreach( $Edit_Arr as $key => $ID ){
					
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch();//取出資料
					
					$URLweb 	= SYS_URL."assets/css/images/";
					
					$row['WO_LOGO'.'_bUrl'] 		= $URLweb.$row['WO_LOGO'];
					$row['WO_LOGO'.'_sUrl'] 		= $URLweb.'sm_'.$row['WO_LOGO'];
					//
					
					$row['WO_LOGO2'.'_bUrl'] 		= $URLweb.$row['WO_LOGO2'];
					$row['WO_LOGO2'.'_sUrl'] 		= $URLweb.'sm_'.$row['WO_LOGO2'];
					
					$row['WO_FooterLOGO'.'_bUrl'] 	= $URLweb.$row['WO_FooterLOGO'];
					$row['WO_FooterLOGO'.'_sUrl'] 	= $URLweb.'sm_'.$row['WO_FooterLOGO'];
					
					// $row['WO_favicon'.'_bUrl'] 		= $URLweb.$row['WO_favicon'];
					// $row['WO_favicon'.'_sUrl'] 		= $URLweb.$row['WO_favicon'];
					
					$row['WO_ShareIcon'.'_bUrl'] 	= $URLweb.$row['WO_ShareIcon'];
					$row['WO_ShareIcon'.'_sUrl'] 	= $URLweb.'sm_'.$row['WO_ShareIcon'];

					// $row['WO_Banner'.'_bUrl'] 		= $URLweb.$row['WO_Banner'];
					// $row['WO_Banner'.'_sUrl'] 		= $URLweb.'sm_'.$row['WO_Banner'];

					$row['WO_FooterImg'.'_bUrl'] 	= $URLweb.$row['WO_FooterImg'];
					$row['WO_FooterImg'.'_sUrl'] 	= $URLweb.'sm_'.$row['WO_FooterImg'];

					$_html_[] = $row;
				}
			}
			
			$WOtype = GetUrlVal($Menu_Data['Menu_Exec'], 'type');
			
			include_once(SYS_PATH.'config'.DIRECTORY_SEPARATOR.'cfg.woption.php');//載入設定
			
			if( empty($_html_msg) ){
				
				ob_start();
				include_once(SYS_PATH.$Menu_Data['Menu_Path'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Exec_Name'].".edit.".$Menu_Data['Exec_Sub_Name']);
				$_html_content = ob_get_contents();
				ob_end_clean();
			}	
		break;
		
		case "Table_Edit_Save"://記錄編輯資料
						
			$ID_Arr = $POST[$Main_Key];

			foreach( $ID_Arr as $key => $val ){

				$Fields = REMOVE_NDATA($POST, array($Main_Key));
				
				$db_data = array();
				ob_start();

				$db_data['WO_Keywords'] = '';
				
				foreach( $Fields as $key2 => $val2 ){
					
					$Field = Turndecode($key2);

					$db_data[$Field] = $val2[$key];
				}
							
				if( Menu_Use($Menu_Data, 'edit') ){
					
					if( !empty($db_data) ){
						
						$db->Where = " WHERE " .$Main_Key. " = '" .$db->val_check($val). "'";
						$db->query_sql($Main_Table, 'Admin_ID');
						
						if( $db->query_rows() > 0 ){
							
							$db->query_data($Main_Table, $db_data, 'UPDATE');
						}else{
							
							if( Multi_WebUrl ){
								
								$db_data['Admin_ID'] = $Admin_data['Admin_ID'];
							}else{
								
								$db_data['Admin_ID'] = Multi_WebUrl_ID;
							}
							
							$db->query_data($Main_Table, $db_data, 'INSERT');	
							
							$val = $db_data['Admin_ID'];
						}
						
						if( !empty($db->Error) ){
							
							$_html_msg = '更新失敗';
							break;
						}else{
							
							$_html_msg = '更新完成';
						}
					}
				}else{
					
					$_html_msg = '無權限編輯';
					break;
				}
			}
			
			if( !empty($_FILES) && !empty($val) ){//判斷有無上傳圖片
						
				$Path = SYS_PATH."assets".DIRECTORY_SEPARATOR."css".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR;
									
				foreach( $_FILES as $Fkey => $Fval ){
					
					$Field_Name = $Fkey;//欄位名稱
													
					// $_TF->TableUpload($val, 'uploadfile', $Field_Name, $Path, '', false);
					$PicSize_Arr 	= Get_PicSize(1, array());
							
					$_TF->TableUpload($val, 'uploadimg', $Field_Name, $Path, $Field_Name.time(), true, $PicSize_Arr);

					if( !empty($_TF->Msg) ){
						
						$_html_msg = !empty($_html_msg) ? $_html_msg.', '.$_TF->Msg : $_TF->Msg;
						break;
					}
				}
				
				if( empty($_TF->Msg) ){
					
					$_html_msg = !empty($_html_msg) ? $_html_msg.', 上傳完成' : '上傳完成';
				}
			}
				
			if( $Admin_data['Group_ID'] == 1 ){
				
				$_html_eval		= 'Return_Table()';//重新載入返回
				
				$Contents 		= new Contents($POST, $GET);
		
				$_html_content	= $Contents->set_Content_List();
			}else{
				
				$_html_eval		= 'Reload()';//重新載入
			}
		break;
		
		case "Table_Data_Change"://切換資料
			
			$_ID 	= $POST['id'];
			$_Field	= Turndecode($POST['field']);
			$_Data	= $POST['data'];
			
			$_Key	= $Main_Key;
			$_Table	= $Main_Table;
			
			$Data_Arr  = array(
				'Table' => $_Table,
				'Key' 	=> $_Key,
				'ID' 	=> $_ID,
				'Field' => $_Field,
				'Data' 	=> $_Data
			);
			
			$_TF->TableChange($_Type1, $Data_Arr, $Path);
			
			if( !empty($_TF->Msg) ){
				
				$_html_msg = $_TF->Msg;
			}
		break;
										
		case "Table_Return"://表格返回
						
			$json_array = Get_Top_Return($Menu_Data);
		break;
		
		case 'testsend':
			
			$_ID = $POST['id'];
			
			if( $Admin_data['Group_ID'] == 1 ){
				
				$db->Where = " WHERE Admin_ID = '" .$db->val_check($_ID). "'";
			}else{
				
				if( Multi_WebUrl ){
					
					$db->Where = " WHERE Admin_ID = '" .$db->val_check($Admin_data['Admin_ID']). "'";
				}else{
					
					$db->Where = " WHERE Admin_ID = '" .$db->val_check(Multi_WebUrl_ID). "'";
				}
			}
			
			$db->query_sql($Main_Table, "*");
			$maildata = $db->query_fetch();
						
			if( empty($maildata['WO_StmpHost']) ){
				
				$_html_msg = '請設定郵件伺服器(SMTP)';
			}else if( empty($maildata['WO_StmpPort']) ){
				
				$_html_msg = '請設定郵件伺服器(PORT)';
			}else if( empty($maildata['WO_SendName']) ){
				
				$_html_msg = '請設定寄件者名稱';
			}else if( empty($maildata['WO_SendEmail']) ){
				
				$_html_msg = '請設定寄件者Email';
			}else if( $maildata['WO_StmpAuth'] && empty($maildata['WO_StmpAcc']) ){
				
				$_html_msg = '啟用驗證請輸入驗證帳號';
			}else if( $maildata['WO_StmpAuth'] && empty($maildata['WO_StmpPass']) ){
				
				$_html_msg = '啟用驗證請輸入驗證密碼';
			}else if( empty($maildata['WO_AddrName']) ){
				
				$_html_msg = '請設定收件者名稱';
			}else if( empty($maildata['WO_AddrEmail']) ){
				
				$_html_msg = '請設定收件者Email';
			}else if( empty($maildata['WO_MailSubject']) ){
				
				$_html_msg = '請設定信件主題';
			}else if( empty($maildata['WO_MailBody']) ){
				
				$_html_msg = '請設定信件內容';
			}else{
				
				$mailto = array(
					$maildata['WO_AddrName'] => $maildata['WO_AddrEmail']
				);
				
				$mailtocc  = array();
				$mailtobcc = array();
				$mailattch = array();
				/*$mailattch = array(
					'../images/loading_icon.gif'
				);*/
				
				$Send_TF = SendMail($maildata, $maildata['WO_MailSubject'], $maildata['WO_MailBody'], $mailto, $mailtocc, $mailtobcc, $mailattch);
				
				if( $Send_TF ){
					
					$_html_msg = '寄信成功,請至收件人信箱查看';
				}else{
					
					$_html_msg = '寄信失敗,設定內容可能有誤至LOG查看';
				}
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
	$json_array['html_type1'] 	= $_Type1 ? $_Type1 : '';//副執行類型
	
	echo json_encode( $json_array );
}
?>