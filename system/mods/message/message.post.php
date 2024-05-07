<?php
require_once("../../include/inc.config.php");
require_once("../../include/inc.check_login.php");

$_Type  		= $POST['_type'];//主執行case
$_Type1 		= $POST['_type1'];//副執行選項

if( !empty($_Type) ){
	
	$db 			= new MySQL();
	
	$_TF 			= new TableFun();
	
	$json_array 	= array();
	
	$Menu_Data 		= MATCH_MENU_DATA(FUN);//取得目前目錄資料
	
	$Main_Key  		= $Menu_Data['Menu_TableKey'];//資料表主健
	
	$Main_Table 	= $Menu_Data['Menu_TableName'];//目錄使用的資料表
	
	$Main_TablePre  = $Menu_Data['Menu_TablePre'];//資料表前輟
		
	$DATE 			= date('Y-m-d H:i:s');
	
	include('../../include/inc.path.php');
	
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
			
			$table_option_arr = Get_Table_Option($Main_Table, 'ALL');//取出全部的設定
			
			foreach( $Del_Arr as $key => $ID ){
				
				if( Menu_Use($Menu_Data, 'delete') ){
											
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch();
					
					if( !empty($row) ){
						
						$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
						$db->query_delete($Main_Table);
					}else{
						
						$_html_msg = '無此資料';
						break;
					}
				}else{
					
					$_html_msg = '無權限刪除';
					break;
				}											
			}
			
			$db->query_optimize($Main_Table);//最佳化資料表
			
			$Contents 		= new Contents($POST, $GET);
	
			$_html_content	= $Contents->set_Content_List();
		break;
		
		case "Table_Edit"://表格編輯畫面
			
			$_ID = $POST['tab_chks'];
			
			$Edit_Arr = $Vote_Arr = array();
			if( is_array($_ID) ){

				$Edit_Arr   = $_ID;
			}else{
				
				$Edit_Arr[] = $_ID;
			}
			
			$table_info 		= $db->get_table_info($Main_Table);//取出資料表欄位的詳細的訊息
								
			$table_option_arr	= Get_Table_Option($Main_Table, 'IN');//取出編輯頁面的設定
									
			$_html_ = array();
			if( !empty($Edit_Arr) ){
				
				foreach( $Edit_Arr as $key => $ID ){
					
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch();//取出資料
													
					$_html_[] = $row;
				}
			}
			
			if( empty($_html_msg) ){
				
				ob_start();
				if( $_Type1 == 'View' ){
					
					if( Menu_Use($Menu_Data, 'view') ){
					
						include_once(SYS_PATH.'mods'.DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].".view.php");
						
						$_html_boxtype = 3;
					}else{
						
						$_html_msg = '無權限檢視';
					}
				}else{
					
					//include_once(SYS_PATH.'mods'.DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].".edit.php");
				}
				
				$_html_content = ob_get_contents();
				ob_end_clean();
			}			
		break;
		
		case "Table_Data_Change"://切換資料
			
			$_ID 	= $POST['id'];
			$_Field	= Turndecode($POST['field']);
			$_Data	= $POST['data'];
			
			$_Key	= $Main_Key;
			$_Table	= $Main_Table;
					
			if( $_Type1 == 'delfile' ){
						
				$Path = $PathFile;		
			}
					
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
		
		case "Table_Download"://表格檔案下載
			
			$_ID	= $POST['id'];
			$_Field	= Turndecode($POST['field']);
			
			$_Key	= $Main_Key;
			$_Table	= $Main_Table;
						
			$Data_Arr  = array(
				'Table' => $_Table,
				'Key' 	=> $_Key,
				'ID' 	=> $_ID,
				'Field' => $_Field
			);
			
			$_TF->TableDownLoad($Data_Arr);
			
			if( !empty($_TF->Msg) ){
				
				$_html_msg = $_TF->Msg;
			}
			
			if( !empty($_TF->Href) ){
				
				$_html_href = $_TF->Href;
			}
			
			if( !empty($_TF->Eval) ){
				
				$_html_eval = $_TF->Eval;
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