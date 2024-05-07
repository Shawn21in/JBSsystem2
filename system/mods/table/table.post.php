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
	
	$Main_Key3		= $Menu_Data['Menu_TableKey2'];//分類資料表主健
	
	$Main_Table3 	= $Menu_Data['Menu_TableName2'];//分類資料表
	
	$Main_TablePre3	= $Menu_Data['Menu_TablePre2'];//分類資料表前輟

	$Main_maxLv3	= $Menu_Data['Menu_ClassMax'];//分類最大層級
	
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
			
			$table_option_arr = Get_Table_Option($Main_Table, 'ALL', array('uploadimg', 'uploadfile'));//取出全部的設定裡面的uploadimg和uploadfile
			
			foreach( $Del_Arr as $key => $ID ){
				
				if( Menu_Use($Menu_Data, 'delete') ){
											
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch();
					
					if( !empty($row) ){
						
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
									
										$_html_msg = $toarr['TO_InType'] == 'uploadimg' ? '圖片刪除失敗' : '檔案刪除失敗';
										break;
									}
								}/*else{
									
									$_html_msg = '無圖片資料, 請檢查';
									break;
								}*/
							}
						}
						
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
							
			$table_option_arr 	= Get_Table_Option($Main_Table, 'IN');//取出編輯頁面的設定
			
			if( !empty($Main_Key3) && !empty($Main_Table3) && !empty($Main_TablePre3) ){//有開啟分類資料表
				
				if( $_Type1 == 'View' ){
					
					$Class_Arr[$Main_Key3] = Class_Get();//抓取分類列表
				}else{
					
					$Class_Arr[$Main_Key3] = Class_Get('TYPE1', array(''=>'請選擇'));//抓取分類列表
				}
			}
						
			$_html_ = array();
			if( !empty($Edit_Arr) ){
				
				foreach( $Edit_Arr as $key => $ID ){
					
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch();//取出資料
					
					foreach( $table_option_arr as $tofield => $toarr ){
						
						if( $toarr['TO_InType'] == 'uploadimg' ){
							
							$row[$tofield.'_bUrl'] = $Pathweb.$row[$tofield];
							$row[$tofield.'_sUrl'] = $Pathweb.'sm_'.$row[$tofield].'?'.time();
						}
					}
								
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
					
					include_once(SYS_PATH.'mods'.DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].".edit.php");
				}
				
				$_html_content = ob_get_contents();
				ob_end_clean();
			}			
		break;
		
		case "Table_Edit_Save"://記錄編輯資料
						
			$ID_Arr = $POST[$Main_Key];
			
			$Fields = REMOVE_NDATA($POST, array($Main_Key));
			
			$table_option_arr 	= Get_Table_Option($Main_Table, 'ALL');//取出全部的設定

			$Table_Field_Arr 	= $db->get_table_info($Main_Table, 'Comment');

			foreach( $ID_Arr as $key => $val ){
			
				$db_data = array();
				foreach( $Fields as $key2 => $val2 ){
					
					$Field = Turndecode($key2);
					
					if( $table_option_arr[$Field]['TO_InType'] == 'password' ){
						
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
						
						foreach( $table_option_arr as $tofield => $toarr ){//判斷有無編輯時間
							
							if( $toarr['TO_InType'] == 'dateedit' ){
						
								$db_data[$tofield] = $DATE;
							}
						}
												
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
						
						$NEW_ID = Get_Sn_Code();
						
						if( !empty($NEW_ID) ){//有開啟自訂義編號
							
							$db_data[$Main_Key] = $NEW_ID;
						}
						
						foreach( $table_option_arr as $tofield => $toarr ){//判斷有無創建時間或編輯時間
							
							if( $toarr['TO_InType'] == 'datecreat' || $toarr['TO_InType'] == 'dateedit' ){
						
								$db_data[$tofield] = $DATE;
							}
							if( $toarr['TO_InType'] == 'checkbox' && $toarr['TO_Sort'] == '97' ){
						
								//$db_data[$tofield] = 1;
							}
						}
						
						$db->query_data($Main_Table, $db_data, 'INSERT');
						
						if( !empty($db->Error) ){
							
							$_html_msg	= '新增失敗';
							break;
						}else{
							
							$val 		= !empty($NEW_ID) ? $NEW_ID : $db->query_insert_id();
							$_html_msg 	= '新增完成';
						}
					}else{
						
						$_html_msg = '無權限新增';
						break;
					}
				}
				
				if( !empty($_FILES) && empty($db->Error) && !empty($val) ){//判斷有無上傳檔案
									
					foreach( $_FILES as $Fkey => $Fval ){
						
						$Field_Name		= $Fkey;//欄位名稱
						
						$Upload_Type 	= $table_option_arr[$Field_Name]['TO_InType'];
						if( $Upload_Type == 'uploadimg' ){
							
							$Path = $Path;
							
							$PicSize_Arr = Get_PicSize(1, ($$table_option_arr)[$Field_Name]['TO_SelPicSize']);
							
							$_TF->TableUpload($val, $Upload_Type, $Field_Name, $Path, $Field_Name.$val.time(), true, $PicSize_Arr);
						}else if( $Upload_Type == 'uploadfile' ){
							
							$Path = $PathFile;
							
							$_TF->TableUpload($val, $Upload_Type, $Field_Name, $Path, '', false);
						}else{
							
							$_html_msg .= ', ' .$Field_Name. '欄位未設定上傳格式';
							break;
						}
						
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
	
	//ob_end_clean();
	
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