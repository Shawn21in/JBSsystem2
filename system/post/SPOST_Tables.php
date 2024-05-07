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
	
	$Main_Key  		= 'Tables_ID';//資料表主健
	
	$Main_Table 	= Tables_DB;//目錄使用的資料表
	
	$Main_TablePre  = 'Tables';//資料表前輟
	
	switch( $_Type ){
		
		case "Table_Re"://表格刷新
		
			$Contents 		= new Contents($POST, $GET);
	
			$_html_content	= $Contents->set_Content_List();
		break;
	
		case "Table_Del"://表格資料刪除
			
			$_html_msg  = "";
			$_ID		= $POST['tab_chks'];
									
			$Del_Arr 	= array();
			if( is_array($_ID) ){

				$Del_Arr   = $_ID;
			}else{
				
				$Del_Arr[] = $_ID;
			}
			
			foreach( $Del_Arr as $key => $ID ){
				
				$row = Get_Tables_data($ID);//取出資料庫資料
				
				if( $Admin_data['Group_ID'] != 1 ){//不是系統管理員就不能刪除

					$_html_msg  = '無權限刪除 ( ' .$row['Tables_Name']. ' )';
					break;
				}else if( !Menu_Use($Menu_Data, 'delete') ){
					
					$_html_msg = '無權限刪除';
					break;
				}else{
									
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_delete($Main_Table);
				}
			}
			
			$db->query_optimize($Main_Table);//最佳化資料表
			
			$Contents 		= new Contents($POST, $GET);
	
			$_html_content	= $Contents->set_Content_List();
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
					
					$row = Get_Tables_data($ID);//取出資料庫資料
					
					if( $Admin_data['Group_ID'] != 1 ){//不是系統管理員就不能編輯

						$_html_msg  = '無權限編輯 ( ' .$row['Tables_Name']. ' )';
						$_html_eval = 'Return_Table()';//重新載入返回
						break;
					}
					
					$_html_[] = $row;
				}
			}
			
			if( empty($_html_msg) ){
				
				ob_start();
					
				include_once(SYS_PATH.$Menu_Data['Menu_Path'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Exec_Name'].".edit.".$Menu_Data['Exec_Sub_Name']);
				
				$_html_content = ob_get_contents();
				ob_end_clean();
			}
		break;
		
		case "Table_Edit_Save"://記錄編輯資料
						
			$ID_Arr = $POST['Tables_ID'];
			
			$Fields = REMOVE_NDATA($POST, array($Main_Key));
			
			foreach( $ID_Arr as $key => $val ){
				
				if( !empty($val) ){
					
					$row = Get_Tables_data($val);//取出資料庫資料
				}
				
				if( $Admin_data['Group_ID'] != 1 ){//不是系統管理員就不能編輯儲存

					$_html_msg  = '無權限編輯儲存 ( ' .$row['Tables_Name']. ' )';
					break;
				}else{
					
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
					}else if( !empty($db_data) ){
						
						if( Menu_Use($Menu_Data, 'add') ){
						
							$db->query_data($Main_Table, $db_data, 'INSERT');
						
							if( !empty($db->Error) ){
							
								$_html_msg = '新增失敗';
								break;
							}else{
															
								$_html_msg = '新增完成';
							}
						}else{
							
							$_html_msg = '無權限新增';
							break;
						}
					}
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