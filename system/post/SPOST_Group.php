<?php
require_once("../include/inc.config.php");
require_once("../include/inc.check_login.php");

$TablesArr = $db->get_table_status('Name');//撈出資料表資訊

require("../include/inc.connect.php");//載入連結MYSQL資訊

$_Type  = $POST['_type'];//主執行case
//$_Type1 = $POST['_type1'];//副執行選項

if( !empty($_Type) ){
	
	$db 			= new MySQL();
	
	$_TF			= new TableFun();
	
	$json_array 	= array();
	
	$Menu_Data 		= MATCH_MENU_DATA(FUN);//取得目前目錄資料
	
	$Main_Key  		= 'Group_ID';//資料表主健
	
	$Main_Table 	= Group_DB;//目錄使用的資料表
	
	$Main_TablePre  = 'Group';//資料表前輟
	
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
				
				$row = Get_Group_data($ID);//取出群組資料
				
				if( $row['Group_ID'] == 1 ){
					
					$_html_msg = '系統管理員無法刪除';
					break;
				}else if( $Admin_data['Group_Lv'] >= $row['Group_Lv'] ){
					
					$_html_msg = '無權限刪除 ( ' .$row['Group_Name']. ' )';
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
					
					if( !empty($ID)){
						
						$row = Get_Group_data($ID);//取出群組資料
						
						if( !empty($row) && $Admin_data['Group_ID'] != $row['Group_ID'] && $Admin_data['Group_Lv'] >= $row['Group_Lv'] ){//不同群組,群組級別大於等於，就不能編輯
	
							$_html_msg  = '無權限編輯 ( ' .$row['Group_Name']. ' )';
							$_html_eval = 'Return_Table()';//重新載入返回
							break;
						}
					}
						
					if( count($Edit_Arr) < 2 ){//編輯筆數只有一筆才抓設定目錄權限列表
						
						$MU_html = $MU_html2 = $MU_In_Arr = array();
						
						if( !empty($row['Group_MenuUse']) ){
							
							$MU_In_Arr = unserialize($row['Group_MenuUse']);
						}
						
						$db->query("SELECT * FROM " .Menu_DB. " ORDER BY Menu_Sort DESC, Menu_ID DESC");
						while( $mrow = $db->query_fetch() ){
							
							if( $mrow['Menu_SysAdminUse'] == '1' ){ continue; }//只能管理者使用不加入設定
							
							if( $mrow['Menu_Lv'] == 2 && !in_array($mrow['Menu_TableName'], $TablesArr) && !empty($mrow['Menu_TableName']) && !preg_match('/sys/i', $mrow['Menu_TableName']) ){ continue; }//判斷資料庫有無此資料表	
								
							if( $mrow['Menu_Lv'] == 1 ){
								
								$MU_html[$mrow['Menu_ID']] = $mrow;
							}else{
								
								if( $mrow['Menu_Add'] ){//判斷能否開新增權限
									
									if( in_array($mrow['Menu_ID'].'_1', $MU_In_Arr) ){
										
										$mrow['chk_Add'] = 1;
									}
								}else{
									
									$mrow['chk_Add'] = 2;
									$mrow['Add_txt'] = '( ' .$mrow['Menu_Name']. ' ) 沒開啟允許新增';
								}
								
								if( $mrow['Menu_Edt'] ){//判斷能否開編輯權限
									
									if( in_array($mrow['Menu_ID'].'_2', $MU_In_Arr) ){
										
										$mrow['chk_Edt'] = 1;
									}
								}else{
									
									$mrow['chk_Edt'] = 2;
									$mrow['Edt_txt'] = '( ' .$mrow['Menu_Name']. ' ) 沒開啟允許編輯';
								}
								
								if( $mrow['Menu_Del'] ){//判斷能否開新增刪除
									
									if( in_array($mrow['Menu_ID'].'_3', $MU_In_Arr) ){
										
										$mrow['chk_Del'] = 1;
									}
								}else{
									
									$mrow['chk_Del'] = 2;
									$mrow['Del_txt'] = '( ' .$mrow['Menu_Name']. ' ) 沒開啟允許刪除';
								}
								
								if( $mrow['Menu_View'] ){//判斷能否開新增檢視
									
									if( in_array($mrow['Menu_ID'].'_4', $MU_In_Arr) ){
										
										$mrow['chk_View'] = 1;
									}
								}else{
									
									$mrow['chk_View'] = 2;
									$mrow['View_txt'] = '( ' .$mrow['Menu_Name']. ' ) 沒開啟允許檢視';
								}
								
								$MU_html2[$mrow['Menu_UpMID']][$mrow['Menu_ID']] = $mrow;
							}
						}
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
			
			$ID_Arr = $POST['Group_ID'];
			
			$Fields = REMOVE_NDATA($POST, array($Main_Key));
			
			foreach( $ID_Arr as $key => $val ){
				
				if( !empty($val) ){
					
					$row = Get_Group_data($val);//取出群組資料
				}
				
				if( !empty($row) && $Admin_data['Group_ID'] != $row['Group_ID'] && $Admin_data['Group_Lv'] >= $row['Group_Lv'] ){
						
					$_html_msg = '無權限編輯 ( ' .$row['Group_Name']. ' )';
					break;
				}else if( !empty($row) && $Admin_data['Group_ID'] == $row['Group_ID'] && $Admin_data['Group_Lv'] != $POST[Turnencode('Group_Lv')][$key] ){
				
					$_html_msg = '自己不能變更群組級別';
					break;
				}else if( $Admin_data['Group_ID'] != $row['Group_ID'] && ( $Admin_data['Group_Lv'] >= $POST[Turnencode('Group_Lv')][$key] || empty($POST[Turnencode('Group_Lv')][$key]) || $POST[Turnencode('Group_Lv')][$key] == 0 ) ){
				
					$_html_msg = '群組級別設定不能小於自己群組級別';	
					break;
				}else{
					
					$db_data = array();
					foreach( $Fields as $key2 => $val2 ){
						
						$Field = Turndecode($key2);
						
						if( $Field == 'Group_MenuUse' ){
						
							$db_data[$Field] = serialize($Fields[$key2]);
						}else{
							
							$db_data[$Field] = $val2[$key];
						}
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
					
					GLOBAL_D();//重新載入全域變數
				}
			}

			$_html_eval		= 'Return_Table()';//重新載入返回
			
			$Contents 		= new Contents($POST, $GET);
	
			$_html_content	= $Contents->set_Content_List();
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