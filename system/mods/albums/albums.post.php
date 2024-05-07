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
	
	$Main_Key2		= $Menu_Data['Menu_TableKey1'];//分類資料表主健
	
	$Main_Table2 	= $Menu_Data['Menu_TableName1'];//分類資料表
	
	$Main_TablePre2	= $Menu_Data['Menu_TablePre1'];//分類資料表前輟
	
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
			
			$table_option_arr	= Get_Table_Option($Main_Table, 'ALL', array('uploadimg', 'uploadfile'));//取出全部的設定
			
			$table_option_arr2	= Get_Table_Option($Main_Table2, 'ALL', array('uploadimg', 'uploadfile'));//取出全部的設定
			
			$PathTemp = $Path;
			foreach( $Del_Arr as $key => $ID ){
				
				$Path = $PathTemp.$ID.DIRECTORY_SEPARATOR;
				
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

								$_html_msg='console.log("1111111")';
								break;
								
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
												
						$db->query_sql($Main_Table2, '*');
						while( $prow = $db->query_fetch() ){
							
							foreach( $table_option_arr2 as $tofield2 => $toarr2 ){
								
								$File_Name = $prow[$tofield2];
								
								$File = ICONV_CODE( 'UTF8_TO_BIG5', $Path.$File_Name );
								
								if( is_file($File) ){//圖片存在
								
									Delete_File($Path, $File_Name);
									
									if( is_file($File) ){//刪除原圖
									
										$_html_msg = '檔案刪除失敗';
										break;
									}
								}
							}
						}
												
						if( is_dir($Path) ){
							
							if( @!rmdir($Path) ){
								
								$_html_msg = '相簿資料刪除失敗, 可能資料夾權限不足';
								break;
							}
						}
						
						$db->query_delete($Main_Table);
						$db->query_delete($Main_Table2);
						
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
			
			$table_info2 		= $db->get_table_info($Main_Table2);//取出資料表欄位的詳細的訊息
								
			$table_option_arr	= Get_Table_Option($Main_Table, 'IN');//取出編輯頁面的設定
			
			$table_option_arr2	= Get_Table_Option($Main_Table2, 'OUT', array('uploadimg', 'sortasc', 'sortdesc'));//取出列表頁面的設定

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
					
					$PathUrl = $Pathweb.$ID.'/';
					
					$db->Where = " WHERE " .$Main_Key. " = '" .$ID. "'";
					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch();//取出資料
					
					foreach( $table_option_arr as $tofield => $toarr ){
						
						if( $toarr['TO_InType'] == 'uploadimg' ){
							
							$row[$tofield.'_bUrl'] = $PathUrl.$row[$tofield];
							$row[$tofield.'_sUrl'] = $PathUrl.'sm_'.$row[$tofield].'?'.time();
						}
					}
					
					//撈取相簿
					$Order_ByArr = array();
					foreach( $table_option_arr2 as $tofield2 => $toarr2 ){
					
						if( $toarr2['TO_InType'] == 'sortasc' ){
							
							$Order_ByArr[0] = $tofield2. ' ASC';
							unset($table_option_arr2[$tofield2]);
						}else if( $toarr2['TO_InType'] == 'sortdesc' ){
							
							$Order_ByArr[0] = $tofield2. ' DESC';
							unset($table_option_arr2[$tofield2]);
						}
					}
					
					$Order_ByArr[] = $Main_Key2.' DESC';
					
					$db->Order_By = ' ORDER BY ' .implode(',', $Order_ByArr);					
					$db->query_sql($Main_Table2, '*');
					while( $prow = $db->query_fetch() ){
						
						foreach( $table_option_arr2 as $tofield2 => $toarr2 ){
						
							if( $toarr2['TO_InType'] == 'uploadimg' ){
								
								$prow[$tofield2.'_bUrl'] = $PathUrl.$prow[$tofield2];
								$prow[$tofield2.'_sUrl'] = $PathUrl.'sm_'.$prow[$tofield2];
								
								$row[$tofield2.'_Arr'][] = $prow;
							}
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
			
			$table_option_arr	= Get_Table_Option($Main_Table, 'IN');//取出編輯頁面的設定
			
			$Table_Field_Arr 	= $db->get_table_info($Main_Table, 'Comment');
			
			$PathTemp = $Path;
			foreach( $ID_Arr as $key => $val ){

				$db_data = array();
				foreach( $Fields as $key2 => $val2 ){
					
					$Field = Turndecode($key2);
					
					if( $table_option_arr[$Field]['TO_InType'] == 'password' ){
						
						if( !empty($val2[$key]) ){
							
							$val2[$key] = md5('sign' .trim($val2[$key]) . 'sign');
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
						
						if( !empty($NEW_ID) ){//有開啟自訂義編號
							
							$db_data[$Main_Key] = $NEW_ID;
						}
						
						foreach( $table_option_arr as $tofield => $toarr ){//判斷有無創建時間或編輯時間
							
							if( $toarr['TO_InType'] == 'datecreat' || $toarr['TO_InType'] == 'dateedit' ){
						
								$db_data[$tofield] = $DATE;
							}
							if( $toarr['TO_InType'] == 'checkbox' && $toarr['TO_Sort'] == '97' ){
						
								$db_data[$tofield] = 1;
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
					
					$Path 				= $PathTemp.$val.DIRECTORY_SEPARATOR;
					
					$table_option_arr2	= Get_Table_Option($Main_Table2, 'OUT');//取出列表頁面的設定
					
					$Re_Count			= false;//相簿數量是否更新
					foreach( $_FILES as $Fkey => $Fval ){
						
						$Field_Name		= $Fkey;//欄位名稱
						
						$Upload_Type 	= $table_option_arr[$Field_Name]['TO_InType'];
						if( $table_option_arr2[$Field_Name]['TO_InType'] == 'uploadimg' ){
														
							$PicSize_Arr = Get_PicSize(2, array());
							
							$_TF->TableUpload($val, 'uploadmultiple', $Field_Name, $Path, '', true, $PicSize_Arr);
						}else if( $Upload_Type == 'uploadimg' ){
														
							$PicSize_Arr = Get_PicSize(1, ($$table_option_arr)[$Field_Name]['TO_SelPicSize']);
							
							$_TF->TableUpload($val, $Upload_Type, $Field_Name, $Path, $Field_Name.$val, true, $PicSize_Arr);
						}else if( $Upload_Type == 'uploadfile' ){
														
							$_TF->TableUpload($val, $Upload_Type, $Field_Name, $Path, '', false);
						}else{
							
							$_html_msg .= ', ' .$Field_Name. '欄位未設定上傳格式';
							break;
						}
						
						if( !empty($_TF->Msg) ){
							
							$_html_msg = !empty($_html_msg) ? $_html_msg.', '.$_TF->Msg : $_TF->Msg;
							break;
						}else{
																					
							if( $table_option_arr2[$Field_Name]['TO_InType'] == 'uploadimg' ){ $Re_Count = true; }
						}
					}
					
					if( empty($_TF->Msg) ){
						
						$_html_msg = !empty($_html_msg) ? $_html_msg.', 上傳完成' : '上傳完成';
					}
					
					if( $Re_Count ){
						
						$db = new MySQL();
								
						$db->Where = " WHERE " .$Main_Key. " = '" .$val. "'";
						$Qty = $db->query_count($Main_Table2);
						
						$db->query_data($Main_Table, array($Main_TablePre.'_Qty' => $Qty), 'UPDATE');
						if( !empty($db->Error) ){
							
							$_html_msg .= ', 相簿張數更新失敗';
						}
					}
				}
			}
			
			$_html_eval		= 'Return_Table()';//重新載入返回
			
			$Contents 		= new Contents($POST, $GET);
	
			$_html_content	= $Contents->set_Content_List();
		break;
		
		case "Table_PEdit"://多圖片編輯
					
			$_ID = $POST['tab_chks'];
			
			$table_info2 		= $db->get_table_info($Main_Table2);//取出資料表欄位的詳細的訊息
						
			$table_option_arr2	= Get_Table_Option($Main_Table2, 'IN');//取出編輯頁面的設定
								
			$db->Where = " WHERE " .$Main_Key2. " = '" .$db->val_check($_ID). "'";
			$db->query_sql($Main_Table2, '*');
			
			if( $row = $db->query_fetch() ){//取出資料
				
				$PathUrl = $Pathweb.$row[$Main_Key].'/';
				
				$row['Img_sUrl'] = $PathUrl.'sm_'.$row[$Main_TablePre2.'_Img'];
				
				$_html_[] = $row;
				
				ob_start();
				
				include_once(SYS_PATH.'mods'.DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].DIRECTORY_SEPARATOR.$Menu_Data['Menu_Model'].".pedit.php");
				
				$_html_content = ob_get_contents();
				ob_end_clean();
				
				$_html_boxtype = 3;
			}else{
				
				$_html_msg = '無此資料';
			}
		break;
		
		case "Table_Edit_PSave"://記錄編輯資料
								
			$_ID = $POST[$Main_Key2];
			
			$table_option_arr2	= Get_Table_Option($Main_Table2, 'ALL');//取出全部的設定
			
			$_html_href = '#';
			if( empty($_ID) ){
				
				$_html_msg = '無主鍵值';
			}else{
				
				$Fields = REMOVE_NDATA($POST, array($Main_Key2));
				
				$db_data = array();
				foreach( $Fields as $key => $val ){
					
					$Field = Turndecode($key);
					
					$db_data[$Field] = $val[0];
				}
				
				foreach( $table_option_arr2 as $tofield => $toarr ){//判斷有無編輯時間
					
					if( $toarr['TO_InType'] == 'dateedit' ){
				
						$db_data[$tofield] = $DATE;
					}
				}
					
				if( !empty($db_data) ){
					
					$db->Where = " WHERE " .$Main_Key2. " = '" .$_ID. "'";
					$db->query_data($Main_Table2, $db_data, 'UPDATE');
					
					if( empty($db->Error) ){
						
						$_html_msg = '更新完成';
					}else{
						
						$_html_msg = '更新失敗';	
					}	
				}
			}
		break;
		
		case "Table_Data_Change"://切換資料
			
			$_ID 	= $POST['id'];
			$_Field	= Turndecode($POST['field']);
			$_Data	= $POST['data'];
			
			$_Key	= $Main_Key;
			$_Table	= $Main_Table;
								
			if( $_Type1 == 'delimgs' || $_Type1 == 'numbers' || $_Type1 == 'checkboxs' ){
				
				$_Key	= $Main_Key2;
				$_Table	= $Main_Table2;
			}

			if( $_Type1 == 'delimg' || $_Type1 == 'delimgs' || $_Type1 == 'delfile' ){
						
				if( $_Type1 == 'delfile' ){
					
					$Path = $PathFile;
				}else if( $_Type1 == 'delimg' || $_Type1 == 'delimgs' ){
					
					if( $_Type1 == 'delimgs' ){
						
						$db->Where = " WHERE " .$Main_Key2. " = '" .$db->val_check($_ID). "'";
						$db->query_sql($Main_Table2, $Main_Key.', '.$_Field);
					}else{
						
						$db->Where = " WHERE " .$Main_Key. " = '" .$db->val_check($_ID). "'";
						$db->query_sql($Main_Table, $Main_Key.', '.$_Field);
					}
					
					$row = $db->query_fetch();
					
					$Path = $Path.$row[$Main_Key].DIRECTORY_SEPARATOR;
				}	
				
				if( empty($row) ){
				
					$_html_msg = '資料錯誤請檢查';
					break;
				}				
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
			}else if( $_Type1 == 'delimgs' ){
				
				$db = new MySQL();
								
				$db->Where = " WHERE " .$Main_Key. " = '" .$row[$Main_Key]. "'";
				$Qty = $db->query_count($Main_Table2);
				
				$db->query_data($Main_Table, array($Main_TablePre.'_Qty' => $Qty), 'UPDATE');
				if( !empty($db->Error) ){
					
					$_html_msg = '相簿張數更新失敗';
				}
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