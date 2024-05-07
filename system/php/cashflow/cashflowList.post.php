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
					
					$row['Setting_Img'.'_sUrl'] = $Pathweb.'sm_'.$row['Setting_Img'].'?'.time();
					$row['Setting_Img'.'_bUrl'] = $Pathweb.$row['Setting_Img'];
					
					for($i=1 ; $i<=10 ;$i++){
						$row['Setting_Img'.$i.'_sUrl'] = $Pathweb.'sm_'.$row['Setting_Img'.$i].'?'.time();
						$row['Setting_Img'.$i.'_bUrl'] = $Pathweb.$row['Setting_Img'.$i];
					}
															
					$_html_[] = $row;
				}
			}
			
			$Settype = GetUrlVal($Menu_Data['Menu_Exec'], 'type');
			
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
								
				if( !empty($_FILES) && !empty($val) ){//判斷有無上傳圖片
						
					//$Path = $PathFile;
										
					foreach( $_FILES as $Fkey => $Fval ){
						
						$Field_Name = $Fkey;//欄位名稱
						
						$PicSize_Arr = Get_PicSize(1, array());
						
						$_TF->TableUpload($val, 'uploadimg', $Field_Name, $Path, $Field_Name.$val, true, $PicSize_Arr);
						
						if( !empty($_TF->Msg) ){
							
							$_html_msg = !empty($_html_msg) ? $_html_msg.', '.$_TF->Msg : $_TF->Msg;
							break;
						}
					}
					
					if( empty($_TF->Msg) ){
						
						$_html_msg = !empty($_html_msg) ? $_html_msg.', 上傳完成' : '上傳完成';
					}
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
			
			$Path = $PathFile;
			
			ob_end_clean();
			
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