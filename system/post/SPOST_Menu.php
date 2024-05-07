<?php
require_once("../include/inc.config.php");
require_once("../include/inc.check_login.php");

$_Type  = $POST['_type'];//主執行case
//$_Type1 = $POST['_type1'];//副執行選項

if( !empty($_Type) ){
	
	$db 			= new MySQL();
	
	$_TF 			= new TableFun();
	
	$json_array 	= array();
	
	$Menu_Data 		= MATCH_MENU_DATA(FUN);//取得目前目錄資料
	
	$Main_Key  		= $Menu_Data['Menu_TableKey'];//資料表主健
	
	$Main_Table 	= DB_DataBase.'.'.Menu_DB;//目錄使用的資料表
	
	$Main_TablePre  = $Menu_Data['Menu_TablePre'];//資料表前輟	
						
	switch( $_Type ){
		
		case "menu_save"://記錄目錄資料
			
			$UpMID_SN 		= 0;
			
			$Menu_ID_Arr   	= $POST['Menu_ID'];
			$Menu_Lv_Arr   	= $POST['Menu_Lv'];
			$Menu_UpMID_Arr = $POST['Menu_UpMID'];
			$Menu_Name_Arr 	= $POST['Menu_Name'];
			$Menu_Sort_Arr 	= $POST['Menu_Sort'];
			
			krsort($Menu_ID_Arr);
			
			$UpMID_SN	= count($Menu_UpMID_Arr) - 1;
			foreach( $Menu_ID_Arr as $key => $val ){
				
				$db_data = array('Menu_Name' => $Menu_Name_Arr[$key], 'Menu_Sort' => $Menu_Sort_Arr[$key]);
				
				if( empty($val) && !empty($Menu_Name_Arr[$key]) ){
					
					if( Menu_Use($Menu_Data, 'add') && Menu_Use($Menu_Data, 'edit') ){
						
						$SN = date('Ymd');
						$SN = substr($SN, -6);
						$NEW_ID = GET_NEW_ID($Main_Table, 'Menu_ID', $SN, 3);
						
						if( $Menu_Lv_Arr[$key] == 2 ){
							
							$db_data['Menu_UpMID'] = $Menu_UpMID_Arr[$UpMID_SN];
							$UpMID_SN--;
						}
						
						$db_data['Menu_Lv'] = $Menu_Lv_Arr[$key];
						$db_data['Menu_ID'] = $NEW_ID;
						
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
						
						$db->Where = " WHERE Menu_ID = '" .$val. "'";
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
			$del1 		= false;
			$del2 		= false;
			$_html_msg 	= '';
			
			if( Menu_Use($Menu_Data, 'delete') ){
				
				$db->Where = " WHERE Menu_ID = '" .$id. "'";
				$db->query_sql($Main_Table, '*');
				$row = $db->query_fetch();
				
				if( $row['Menu_Lv'] == 1 ){
					
					if( $row['Menu_SysUse'] == 1 ){
						
						$_html_msg = "系統檔不能刪除 ( " .$row['Menu_Name']. " )";
					}else if( $Admin_data['Admin_Permissions'] < $row['Menu_Permissions'] ){
					
						$_html_msg = "無權限刪除 ( " .$row['Menu_Name']. " )";
					}else{
						
						$db->Where = " WHERE Menu_UpMID = '" .$id. "'";
						$db->query_sql($Main_Table, '*');
						while( $row2 = $db->query_fetch() ){
							
							if( $row2['Menu_SysUse'] == 1 ){
						
								$_html_msg = "系統檔不能刪除 ( " .$row2['Menu_Name']. " )";
								break;
							}else if( $Admin_data['Admin_Permissions'] < $row2['Menu_Permissions'] ){
					
								$_html_msg = "無權限刪除 ( " .$row2['Menu_Name']. " )";
								break;
							}
						}
						
						if( empty($_html_msg) ){
							
							$del1 = true;
							$del2 = true;
						}
					}
				}else if( $row['Menu_Lv'] == 2 ){
					
					if( $row['Menu_SysUse'] == 1 ){
						
						$_html_msg = "系統檔不能刪除 ( " .$row['Menu_Name']. " )";
					}else if( $Admin_data['Admin_Permissions'] < $row['Menu_Permissions'] ){
					
						$_html_msg = "無權限刪除 ( " .$row['Menu_Name']. " )";
					}
					
					if( empty($_html_msg) ){
	
						$del1 = true;
					}
				}
				
				if( $del1 == true ){
					
					$db->Where = " WHERE Menu_ID = '" .$id. "'";
					$db->query_delete($Main_Table);
				}
				
				if( $del2 == true ){
					
					$db->Where = " WHERE Menu_UpMID = '" .$id. "'";
					$db->query_delete($Main_Table);
				}
				
				require("../include/inc.connect.php");//載入連結MYSQL資訊
				
				$db->query_optimize(Menu_DB);//最佳化資料表
						
				$_html_eval = 'Reload()';
			}else{
				
				$_html_msg = '無權限刪除';
			}
		break;
		
		case "menu_edit"://編輯目錄資料
			
			$id		= $POST['id'];
			
			$DATE	= date('Ymd');
			
			$_html_ = array();
			
			
			
			if( Menu_Use($Menu_Data, 'edit') ){
							
				$db->Where = " WHERE Menu_ID = '" .$id. "'";
				$db->query_sql($Main_Table, '*');
				$_html_ = $db->query_fetch();
				//, 5 => '模組模式(擴充)'
				$Mode_Arr	= array(1 => '自訂模式', 4 => '自訂模式(擴充)', 2 => '連結模式', 3 => '模組模式');
				$CstSn_Arr	= array(
					'YYYYMMDD'	=> '依日期全碼( ' .$DATE. ' )',
					'YYMMDD' 	=> '依日期取後六碼( ' .$DATE. ' => ' .substr($DATE, 2). ' )',
				);
				
				$table_info = $db->get_table_info($Main_Table);//取出資料表欄位的詳細的訊息
				
				if( $_html_['Menu_Lv'] == 1 ){
					
					$SmallArr = Get_Small_Pic();
				}
				
				if( $_html_['Menu_Lv'] == 2 ){
					
					$TablesArr = $db->get_table_status();//撈出資料表資訊
					
					$Menu_Lv1_Arr = array();
					$db->Where = " WHERE Menu_Lv ='1'";
					$db->Order_By = ' ORDER BY Menu_Sort DESC, Menu_ID DESC';
					$db->query_sql($Main_Table, '*');
					while( $row = $db->query_fetch() ){
						
						if( !Menu_Use($row, 2) ){ continue; }//沒權限使用目錄
						
						$Menu_Lv1_Arr[$row['Menu_ID']] = $row['Menu_Name'];
					}
					
					$TablesArr2 = array();//儲存目前資料庫的資料表
					foreach( $TablesArr as $key => $val ){
				
						if( /*$Admin_data['Group_ID'] != 1 &&*/ preg_match('/^sys/i', $val['Name']) ){//如果不是系統管理員以及是系統用資料表
								
							unset($TablesArr[$key]);	
						}else{
							
							$TablesArr[$key]['Title'] = empty($val['Comment']) ? $val['Name'] : $val['Name'].' ( '. $val['Comment']. ' )';
						}
						
						$TablesArr2[] = $val['Name'];
					}
			
					$Table_Field_Arr = $Table_Field_Arr1 = $Table_Field_Arr2 = array();
					
					if( !empty($_html_['Menu_TableName']) && $Admin_data['Group_ID'] == 1 ){//取出資料表資訊
						
						if( in_array( $_html_['Menu_TableName'], $TablesArr2) ){
							
							$Table_Field_Arr  = $db->get_table_info($_html_['Menu_TableName'], 'Field');
						}
					}
					
					if( !empty($_html_['Menu_TableName1']) && $Admin_data['Group_ID'] == 1 ){//取出擴充資料表資訊
						
						if( in_array( $_html_['Menu_TableName1'], $TablesArr2) ){
							
							$Table_Field_Arr1  = $db->get_table_info($_html_['Menu_TableName1'], 'Field');
						}
					}
					
					if( !empty($_html_['Menu_TableName2']) && $Admin_data['Group_ID'] == 1 ){//取出分類資料表資訊
						
						if( in_array( $_html_['Menu_TableName2'], $TablesArr2) ){
							
							$Table_Field_Arr2  = $db->get_table_info($Use_Table.'.'.$_html_['Menu_TableName2'], 'Field');
						}
					}
					
					if( $_html_['Menu_SysUse'] == 1 ){
						
						$ModelArr = Get_Sys_File();
					}else{
						
						$Dir_Path = '../mods';
						$Dir 	  = opendir($Dir_Path);
	
						$ModelArr = $Mode_Sort = $Mode_Temp = array();
						while( $File = readdir($Dir) ){
							
							if( $File[0] == "." ) continue;
							
							//echo $File."<br>";
							$Config_Path = $Dir_Path.DIRECTORY_SEPARATOR.$File.DIRECTORY_SEPARATOR.'config.php';
							if( is_file($Config_Path) ){
								
								include($Config_Path);
								
								if( $ModOpen == 1 ){//啟用
									
									$Mode_Sort[(!empty($Mode_Sort[$ModSort])?($ModSort+1):$ModSort)] = $ModName_en;
									$Mode_Temp[$ModName_en] = $ModName_tw;
								}
							}
						}
						
						ksort($Mode_Sort);
						
						foreach( $Mode_Sort as $mokey => $moval ){
							
							$ModelArr[$moval] = $Mode_Temp[$moval];
						}
					}
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
					
				$db->Where = " WHERE Menu_ID = '" .$id. "'";
				$db->query_sql($Main_Table, '*');
				$row = $db->query_fetch();
				
				if( $row['Menu_Lv'] == 2 ){
					
					$TableTemp 		= $POST['Menu_TableName'];
					$TableKeyTemp 	= $POST['Menu_TableKey'];
						
					$table_info 	= $db->get_table_info($TableTemp);//取出資料表欄位的詳細的訊息
							
					$FieldExtra		= $table_info[$TableKeyTemp]['Extra'];
					$FieldType  	= $table_info[$TableKeyTemp]['Field_Type'];
					$FieldNum		= $table_info[$TableKeyTemp]['Field_Length'];
							
					$CstSnPre		= $POST['Menu_CstSnPre'];
					$CstSnType		= $POST['Menu_CstSnType'];
					$CstSnNum		= $POST['Menu_CstSnNum'];
					if( $CstSnNum ){//自訂編碼判斷是否符合格式
						
						if( empty($TableTemp) ){
							
							$_html_msg = '請選擇資料表名稱';
						}else if( empty($TableKeyTemp) ){
							
							$_html_msg = '請選擇資料表主鍵';
						}else{
							
							$strlen		= strlen($CstSnPre)+strlen($CstSnType)+$CstSnNum;
							if( !empty($CstSnPre) && !is_numeric($CstSnPre) && $FieldType != 'varchar' ){
								
								$_html_msg = '自定編號前輟為 ( varchar ) 格式, 資料表主鍵不適用';
							}else if( strlen($CstSnPre) > 0 && substr($CstSnPre, 0, 1) == 0 && $FieldType == 'int' ){
								
								$_html_msg = '資料表主鍵為 ( int ) 格式, 自定編號前輟第一碼不能為 0';
							}else if( empty($CstSnPre) && empty($CstSnType) && $FieldType == 'int' && $CstSnNum > 1 ){
								
								$_html_msg = '資料表主鍵為 ( int ) 格式, 自訂編號流水碼數不能大於1, 除非填寫自定編號前輟或選擇自訂編號種類';
							}else if( $strlen > $FieldNum ){
								
								$_html_msg = '自定編號最多 ( ' .$FieldNum. '碼 )';
							}
						}
						
						if( !empty($_html_msg) ){ break; }
					}else if( strtolower($FieldExtra) != 'auto_increment' && !empty($TableKeyTemp) && !empty($POST['Menu_Add']) ){
						
						$_html_msg = '已允許新增資料且此資料表主鍵不會自動遞增編號, 請設定自訂編號';
						break;
					}
				}
				
				if( $row['Menu_SysUse'] == 1 && $Admin_data['Group_ID'] != 1 ){
						
					$_html_msg = "系統管理員才能編輯系統檔 ( " .$row['Menu_Name']. " )";
				}else if( $Admin_data['Admin_Permissions'] < $row['Menu_Permissions'] ){
					
					$_html_msg = "無權限編輯 ( " .$row['Menu_Name']. " )";
				}else{
					
					$Fields = REMOVE_NDATA($POST);
					
					$db_data = array();
					foreach( $Fields as $key => $val ){
						
						$db_data[$key] = $val;
					}
					
					$db->Where = " WHERE Menu_ID = '" .$id. "'";
					$db->query_data($Main_Table, $db_data, 'UPDATE');
					
					if( !empty($db->Error) ){
							
						$_html_msg = '更新失敗';
					}else{
						
						$_html_msg = '更新完成';
						//幫主資料表新增 ( 分類資料表 ) 的對應ID
						if( !empty($db_data['Menu_TableName2']) && !empty($db_data['Menu_TableKey2']) ){
							
							$MTable 	= $db_data['Menu_TableName'];
							$MKey 		= $db_data['Menu_TableKey'];
							$MTable2	= $db_data['Menu_TableName2'];
							$MKey2		= $db_data['Menu_TableKey2'];
							
							$table_info = $db->get_table_info($MTable);//取出資料表欄位的詳細的訊息
							
							if( empty($table_info[$MKey2]) ){
								
								$table_info2 = $db->get_table_info($MTable2);//取出資料表欄位的詳細的訊息
								
								$Type		= $table_info2[$db_data['Menu_TableKey2']]['Type'];//欄位型態
								//$Comment	= $table_info2[$db_data['Menu_TableKey2']]['Comment'];//欄位註解
								
								$db->query("ALTER TABLE `" .$MTable. "` ADD `" .$MKey2. "` " .$Type. " NULL DEFAULT NULL COMMENT '分類' AFTER `" .$MKey. "`, ADD INDEX (`" .$MKey2. "`)");//更新資料表欄位註解
								
								if( !empty($db->Error) ){
									
									$_html_msg .= ', 分類ID創建失敗';
								}else{
									
									$db->Where = " WHERE TO_Name = '" .$db->val_check($MTable). "' AND TO_Field = '" .$db->val_check($MKey2). "'";//刪除該資料表欄位設定
									$db->query_delete(Tables_Option_DB);
									
									$db_data = array(
										'TO_Name' 		=> $MTable, 
										'TO_Field' 		=> $MKey2,
										'TO_Sort' 		=> 1,
										'TO_InType' 	=> 'select',
										'TO_InShow' 	=> 1, 
										'TO_InEdit' 	=> 1, 
										'TO_OutShow'	=> 1
									);
																		
									$db->query_data(Tables_Option_DB, $db_data, 'INSERT');
								}
							}
						}
					}
				}				
			}else{
				
				$_html_msg = '無權限編輯';
			}
			
			$_html_eval = 'Reload()';
		break;
		
		case "chg_tablekey"://切換資料表 抓出所有欄位
			
			$table = $POST['table'];
			
			$Table_Field_Arr = array();
			if( !empty($table) ){
				
				$Table_Field_Arr = $db->get_table_info($table, 'Field');
			}
			
			$_html_content = Select_Option($Table_Field_Arr, '');
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
	
	echo json_encode( $json_array );
}
?>