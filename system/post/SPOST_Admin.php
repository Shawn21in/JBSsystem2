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
	
	$Main_Table 	= Admin_DB;//目錄使用的資料表
	
	$Main_TablePre  = 'Admin';//資料表前輟
	 	
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
				
				$Admin_data2 = Get_Admin_data($ID);//取得使用者資料
				//print_r($Admin_data);
				
				if( $Admin_data2['Group_ID'] == 1 ){
					
					$_html_msg = '系統管理員無法刪除';
					break;
				}else if( $Admin_data['Group_Lv'] >= $Admin_data2['Group_Lv'] ){
					
					$_html_msg = '無權限刪除 ( ' .$Admin_data2['Admin_Name']. ' ) 使用者';
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
			
			$Group_Arr  = Get_Group_data('', 'Group_Name', 'LV');//取出群組資料
						
			$Tables_Arr = Get_Tables_data('', '', 'NAMES');//取出資料庫中英文名稱資料
			
			$_html_ = array();
			if( !empty($Edit_Arr) ){
				
				foreach( $Edit_Arr as $key => $ID ){
				
					if( !empty($ID)){ 
					
						$row = Get_Admin_data($ID);//取得使用者資料
						
						if( !empty($row) && $row['Admin_ID'] != $Admin_data['Admin_ID'] && $Admin_data['Group_Lv'] >= $row['Group_Lv'] ){
					
							$_html_msg  = '無權限編輯 ( ' .$row['Admin_Name']. ' )';
							$_html_eval = 'Return_Table()';//重新載入返回
							break;
						}
					}
					
					$_html_[] = $row;
				}
			}
			
			if( empty($_html_msg) ){
				
				ob_start();
				if( $_Type1 == 'View' ){
					
					if( Menu_Use($Menu_Data, 'view') ){
						
						
						
						include_once(SYS_PATH.$Menu_Data['Menu_Path'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Exec_Name'].".view.".$Menu_Data['Exec_Sub_Name']);
					
					$_html_boxtype = 3;
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
					
			$ID_Arr = $POST['Admin_ID'];
						
			$Group_Arr = Get_Group_data('', 'Group_Lv');//取出群組資料
			
			$Fields = REMOVE_NDATA($POST, array($Main_Key));
			
			foreach( $ID_Arr as $key => $val ){
				
				if( !empty($val) ){
				
					$row = Get_Admin_data($val);//取得使用者資料
				}
				
				if( !empty($row) && $row['Admin_ID'] != $Admin_data['Admin_ID'] && $row['Group_ID'] == 1 ){
						
					$_html_msg = '系統管理員才能編輯';
					break;
				}else if( !empty($row) && $row['Admin_ID'] != $Admin_data['Admin_ID'] && $Admin_data['Group_Lv'] >= $row['Group_Lv'] ){
					
					$_html_msg = '無權限編輯 ( ' .$row['Admin_Name']. ' )';
					break;
				}else if( !empty($row) && $row['Admin_ID'] == $Admin_data['Admin_ID'] && $Admin_data['Group_ID'] != $POST[Turnencode('Group_ID')][$key] && !empty($POST[Turnencode('Group_ID')]) ){
					
					$_html_msg = '管理員群組不能自己變更';
					break;
				}else if( !empty($row) && $row['Admin_ID'] == $Admin_data['Admin_ID'] && $Admin_data['Admin_Open'] != $POST[Turnencode('Admin_Open')][$key] && !empty($POST[Turnencode('Admin_Open')]) ){
					
					$_html_msg = '帳號啟用不能自己變更';
					break;
				}else if( $row['Group_ID'] != $Admin_data['Group_ID'] && $Admin_data['Group_Lv'] >= $Group_Arr[$POST[Turnencode('Group_ID')][$key]] ){
					
					$_html_msg = '管理員群組不能設定大於等於自己同級別';
					break;
				}else if( $row['Group_ID'] != 1 && $POST[Turnencode('Group_ID')][$key] == 1 ){
					
					$_html_msg = '不能設定系統管理員';
					break;
				}else{
					
					$db_data = array();
					foreach( $Fields as $key2 => $val2 ){
						
						$Field = Turndecode($key2);
						
						if( preg_match('/Admin_Pwd/i', $Field) ){//有值再更改密碼
							
							if( !empty($val2[$key]) ){
								
								$val2[$key] = md5(Turnencode(trim($val2[$key]), 'password'));
							}else{
								
								continue;	
							}
						}
						
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
						
							$DATE = date('Y-m-d H:i:s');
							$db_data['Admin_Sdate'] = $DATE;
							
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
		
			$json_array = Get_Top_Return($Menu_Data);//取得TOP按鈕是否使用
		break;
	}
	
	ob_end_clean();
	
	$json_array['html_msg']     = $_html_msg ? $_html_msg : '';//訊息
	$json_array['html_href']    = $_html_href ? $_html_href : '';//連結
	$json_array['html_eval']    = $_html_eval ? $_html_eval : '';//確定後要執行的JS
	$json_array['html_content'] = $_html_content ? $_html_content : '';//輸出內容
	$json_array['html_boxtype'] = $_html_boxtype ? $_html_boxtype : '1';//彈出視窗格式1=>只有確定,2=>確定與取消,3=>檢視畫面
	$json_array['html_clear']   = $_html_clear ? $_html_clear : '';//清空欄位Array,填欄位ID
	$json_array['html_type'] 	= $_Type ? $_Type : '';//執行類型
		
	echo json_encode( $json_array );
}
?>