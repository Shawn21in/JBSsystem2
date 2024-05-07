<?php
require_once("../../include/inc.config.php");
require_once("../../include/inc.check_login.php");

$_Type  = $POST['_type'];//主執行case
$_Type1 = $POST['_type1'];//副執行選項

if( !empty($_Type) ){
	
	$db 			= new MySQL();
	
	$_TF 			= new TableFun();
	
	$json_array 	= array();
	
	$Menu_Data 		= MATCH_MENU_DATA(FUN);//取得目前目錄資料
	
	$Main_Key  		= $Menu_Data['Menu_TableKey'];//資料表主鍵
	
	$Main_Table 	= $Menu_Data['Menu_TableName'];//目錄使用的資料表
	
	$Main_TablePre  = $Menu_Data['Menu_TablePre'];//資料表前輟
	
	$Main_Key2		= $Menu_Data['Menu_TableKey1'];//擴充資料表主鍵
	
	$Main_Table2 	= $Menu_Data['Menu_TableName1'];//擴充資料表
	
	$Main_TablePre2	= $Menu_Data['Menu_TablePre1'];//擴充資料表前輟
	
	$DATE 			= date('Y-m-d H:i:s');
	
	include('../../include/inc.path.php');
 	
	switch( $_Type ){
		
		case "Table_Re"://表格刷新
		
			$Contents 		= new Contents($POST, $GET);
			
			$_html_content	= $Contents->set_Content_List();
						
			if( $POST['search_type'] == 'excel' ){
				
				$session = md5($Main_Table.time().session_id());
										
				$_SESSION['system']['downloadcode'] = $session;
				
				$db_data = array(
					'DL_Session' => $session,
					'DL_DownLoadInfo' => $_html_content
				);
				
				$db->query_data(Download_DB, $db_data, 'INSERT');
				
				$_html_href		= SYS_URL.$Menu_Data['Menu_Path'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Exec_Name'].".excel.".$Menu_Data['Exec_Sub_Name']."?fun=".FUN."&code=".$session;
				$_html_content  = '';
			}
		break;
			
		case "Table_Edit"://表格編輯畫面
			
			$_ID = $POST['tab_chks'];
			
			$Edit_Arr 	= array();
			if( is_array($_ID) ){

				$Edit_Arr   = $_ID;
			}else{
				
				$Edit_Arr[] = $_ID;
			}
			
			$table_info = $db->get_table_info($Main_Table);//取出資料表欄位的詳細的訊息
			
			$_html_ = array();
			if( !empty($Edit_Arr) ){
				
				foreach( $Edit_Arr as $key => $ID ){
					
					$Sheet = $Main_Table." as a LEFT JOIN web_delivery as b ON a.Orderm_Delivery = b.Delivery_ID";
					
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_sql($Sheet, 'a.*,b.Delivery_Name');
					$row = $db->query_fetch();//取出資料
					
					if( $_Type1 == 'View' && !empty($row) ){
						
						$db->query_sql($Main_Table2, "*");
						$order_list = array();
						while( $brow = $db->query_fetch() ){
		
							$order_list[]	= $brow;
						}
					}
					
					$_html_[] = $row;
				}
			}
			
			if( empty($_html_msg) ){
				
				ob_start();
				if( $_Type1 == 'View' || $_Type1 == 'View1' ){
					
					if( Menu_Use($Menu_Data, 'view') ){
						
						include_once(SYS_PATH.$Menu_Data['Menu_Path'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Exec_Name'].".view.".$Menu_Data['Exec_Sub_Name']);
						
						if( $_Type1 == 'View'){
							
							$_html_boxtype = 3;
						}
					}else{
						
						$_html_msg = '無權限檢視';
					}
				}else{
					
					include_once(SYS_PATH.$Menu_Data['Menu_Path'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Exec_Name'].".edit.".$Menu_Data['Exec_Sub_Name']);
				}
				
				$_html_content = ob_get_contents();
				ob_end_clean();
			}	
		break;
		
		case "Table_Edit_Save"://記錄編輯資料
						
			$ID_Arr = $POST[$Main_Key];

			foreach( $ID_Arr as $key => $val ){

				$Fields = REMOVE_NDATA($POST, array($Main_Key));
				
				$db_data = array();
				
				foreach( $Fields as $key2 => $val2 ){
					
					$Field = Turndecode($key2);
										
					$db_data[$Field] = $val2[$key];
				}
				
				if( !empty($val) && !empty($db_data) ){
					
					if( Menu_Use($Menu_Data, 'edit') ){

						$db->Where = " WHERE " .$Main_Key. " = '" .$val. "'";
						
						$db->query_data($Main_Table, $db_data, 'UPDATE');
						
						if( !empty($db->Error) ){
							
							$_html_msg = '更新失敗';
							break;
						}else{
							
							$_html_msg = '更新完成';
						}
					}else{
						
						$_html_msg = '無權限編輯';
						break;
					}
				}else{
										
					$_html_msg = '無法新增';
					break;
				}
			}

			$_html_eval		= 'Return_Table()';//重新載入返回
			
			$Contents 		= new Contents($POST, $GET);
	
			$_html_content	= $Contents->set_Content_List();
		break;
				
		case "Table_Data_Change"://切換資料
			
			$_ID 	= $POST['id'];
			$_Field	= Turndecode($POST['field']);
			$_Data	= $POST['data'];
			
			$_Key	= $Main_Key;
			$_Table	= $Main_Table;
			
			if( $_Type1 == 'delfile' ){
						
				$_Path_ = $PathFile;		
			}else{
				
				$_Path_	= $Path;
			}
			
			$Data_Arr  = array(
				'Table' => $_Table,
				'Key' 	=> $_Key,
				'ID' 	=> $_ID,
				'Field' => $_Field,
				'Data' 	=> $_Data
			);
			
			$_TF->TableChange($_Type1, $Data_Arr, $Path, false);
			
			if( !empty($_TF->Msg) ){
				
				$_html_msg = $_TF->Msg;
			}else{
				
				$db->Where = " WHERE " .$_Key. " = '" .$db->val_check($_ID). "'";
				$db->query_sql($_Table, '*');
				$row = $db->query_fetch();
					
				$db_data[$_Field] = $_Data;
				
				if( $_Field == $Main_TablePre.'_States' ){
					
					if( !empty($row[$Main_TablePre.'_Success']) ){
						
						$_html_msg = '已完成訂單不能更改狀態';
						break;
						
					//訂單修改為【已出貨】
					}else if( $_Data == '2' ){
						
						$db_data[$Main_TablePre.'_Outdate'] = $DATE;
						$_html_eval = 'Reload()';
					
					//訂單修改為【訂單完成】或 【已取消】
					}else if( $_Data == '3' || $_Data == '10' ){
				
						$db_data['Orderm_Success'] = 1;
					}
				}
				
				$db->query_data($_Table, $db_data, 'UPDATE');
								
				if( $db->Error ){
					
					$_html_msg = '更新失敗';
				}
			}
		break;
										
		case "Table_Return"://表格返回
						
			$json_array = Get_Top_Return($Menu_Data);
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