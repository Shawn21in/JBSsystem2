<?php
require_once("../../include/inc.config.php");
require_once("../../include/inc.check_login.php");

$_Type  = $POST['_type'];//主執行case
$_Type1 = $POST['_type1'];//副執行選項

if( !empty($_Type) ){
	
	$db 			= new MySQL();
	
	$_TF			= new TableFun();
	
	$json_array 	= array();
	
	$Menu_Data 		= MATCH_MENU_DATA(FUN);//取得目前目錄資料
	
	$Main_Key  		= $Menu_Data['Menu_TableKey'];//資料表主健
	
	$Main_Table 	= $Menu_Data['Menu_TableName'];//目錄使用的資料表
	
	$Main_TablePre  = $Menu_Data['Menu_TablePre'];//資料表前輟
	
	$DATE 			= date('Y-m-d H:i:s');
	
	switch( $_Type ){
		
		case "menu_save"://記錄目錄資料
			
			$ID_Arr   	= $POST[$Main_Key] ? $POST[$Main_Key] : array();
			
			krsort($ID_Arr);
			
			$Lv_Arr   	= $POST[$Main_TablePre.'_Lv'];
			$UpMID_Arr  = $POST[$Main_TablePre.'_UpMID'];
			$Name_Arr 	= $POST[$Main_TablePre.'_Name'];
			$Sort_Arr 	= $POST[$Main_TablePre.'_Sort'];
			
			$_Data_Arr  = array();
				
			$db->query_sql($Main_Table, '*');
			while( $row = $db->query_fetch() ){
				
				$_Data_Arr[$row[$Main_Key]] = $row;
			}

			$UpMID_SN	= count($UpMID_Arr) - 1;
			foreach( $ID_Arr as $key => $val ){
									
				if( $_Data_Arr[$val][$Main_TablePre.'_Name'] != $Name_Arr[$key] || $_Data_Arr[$val][$Main_TablePre.'_Sort'] != $Sort_Arr[$key] ){
					
					$db_data = array($Main_TablePre.'_Name' => $Name_Arr[$key], $Main_TablePre.'_Sort' => $Sort_Arr[$key]);
				}else{
					
					continue;
				}
				
				if( empty($val) && !empty($Name_Arr[$key]) ){
					
					if( Menu_Use($Menu_Data, 'add') && Menu_Use($Menu_Data, 'edit') ){
						
						$NEW_ID = Get_Sn_Code();
						
						if( empty($NEW_ID) ){
							
							$_html_msg = '請至目錄下設定自訂編號編碼格式';
							break;
						}
						
						if( $Lv_Arr[$key] > 1 ){
						
							$db_data[$Main_TablePre.'_UpMID'] = $UpMID_Arr[$UpMID_SN];
							$UpMID_SN--;
						}
						
						$db_data[$Main_TablePre.'_Lv'] = $Lv_Arr[$key];
						$db_data[$Main_Key] = $NEW_ID;
						
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
				}else if( !empty($val) ){
					
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
				}
			}
			
			$_html_eval = 'Reload()';
		break;
		
		case "menu_del"://刪除目錄資料
			
			$id  		= $POST['id'];
			$_html_msg 	= '';
			
			if( Menu_Use($Menu_Data, 'delete') ){
				
				MENU_UpMID_DEL( $id );//刪除目錄
				
				$db->query_optimize($Main_Table);//最佳化資料表
				
				$_html_eval = 'Reload()';
			}else{
				
				$_html_msg = '無權限刪除';
			}
		break;
		
		case "menu_edit"://編輯目錄資料
			
			$id = $POST['id'];
			
			$_html_ = array();
			if( Menu_Use($Menu_Data, 'edit') ){
				
				$table_info = $db->get_table_info($Main_Table);//取出資料表欄位的詳細的訊息
		
				$db->Where = " WHERE " .$Main_Key. " = '" .$id. "'";
				$db->query_sql($Main_Table, '*');
				$_html_[] = $db->query_fetch();
				
				$Menu_Lv_Arr = array();
				if( $_html_[0][$Main_TablePre.'_Lv'] > 1 ){
					
					$Menu_Lv_Arr = Get_Table_Info($Main_Table, $Main_Key, $Main_TablePre.'_Name', " WHERE " .$Main_TablePre. "_Lv = '" .($_html_[0][$Main_TablePre.'_Lv'] - 1). "'");
				}
												
				ob_start();
				include_once(SYS_PATH.$Menu_Data['Menu_Path'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Exec_Name'].".edit.".$Menu_Data['Exec_Sub_Name']);
				$_html_content = ob_get_contents();
				ob_end_clean(); 
			}else{
				
				$_html_msg  = '無權限編輯';
				$_html_eval = 'Reload()';
			}
		break;
		
		case "menu_edit_save"://記錄編輯資料
			
			$id = $POST['id'];
			
			if( Menu_Use($Menu_Data, 'edit') ){	
						
				$Fields = REMOVE_NDATA($POST);
				
				$db_data = array();
				foreach( $Fields as $key => $val ){
					
					$Field = Turndecode($key);
										
					$db_data[$Field] = $val[0];
				}
				
				$db->Where = " WHERE " .$Main_Key. " = '" .$id. "'";
								
				$db->query_data($Main_Table, $db_data, 'UPDATE');
				
				if( $db->Error ){
					
					$_html_msg = "更新失敗";
				}else{
					
					$_html_msg = "更新完成";
				}
			}else{
				
				$_html_msg = '無權限編輯';
			}
			
			$_html_eval = 'Reload()';
		break;
		
		case "menu_display"://切換顯示/隱藏
			
			$id 	= $POST['id'];
			$show 	= $POST['show'] ? 1 : 0;
			
			if( Menu_Use($Menu_Data, 'edit') ){	
										
				$db_data[$Main_TablePre.'_Open'] = $show;
				
				$db->Where = " WHERE " .$Main_Key. " = '" .$id. "'";
								
				$db->query_data($Main_Table, $db_data, 'UPDATE');
				
				if( $db->Error ){
					
					$_html_msg = "更新失敗";
				}
			}else{
				
				$_html_msg = '無權限編輯';
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