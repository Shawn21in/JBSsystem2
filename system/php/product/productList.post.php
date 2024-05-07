<?php
require_once("../../include/inc.config.php");
require_once("../../include/inc.check_login.php");

$_Type  = $POST['_type']; //主執行case
$_Type1 = $POST['_type1']; //副執行選項

if (!empty($_Type)) {

	$db 			= new MySQL();

	$_TF 			= new TableFun();

	$json_array 	= array();

	$Menu_Data 		= MATCH_MENU_DATA(FUN); //取得目前目錄資料

	$Main_Key  		= $Menu_Data['Menu_TableKey']; //資料表主健

	$Main_Table 	= $Menu_Data['Menu_TableName']; //目錄使用的資料表

	$Main_TablePre  = $Menu_Data['Menu_TablePre']; //資料表前輟

	$Main_Key3		= $Menu_Data['Menu_TableKey2']; //分類資料表主健

	$Main_Table3 	= $Menu_Data['Menu_TableName2']; //分類資料表

	$Main_TablePre3	= $Menu_Data['Menu_TablePre2']; //分類資料表前輟

	$Main_maxLv3	= $Menu_Data['Menu_ClassMax']; //分類最大層級

	$DATE 			= date('Y-m-d H:i:s');

	include('../../include/inc.path.php');

	switch ($_Type) {

		case "Table_Re": //表格刷新

			$Contents 		= new Contents($POST, $GET);

			$_html_content	= $Contents->set_Content_List();
			break;

		case "Table_Del": //表格資料刪除

			$_html_msg  = "";
			$_ID		= $POST['tab_chks'];

			$Del_Arr 	= array();
			if (is_array($_ID)) {

				$Del_Arr   = $_ID;
			} else {

				$Del_Arr[] = $_ID;
			}

			foreach ($Del_Arr as $key => $ID) {

				if (Menu_Use($Menu_Data, 'delete')) {

					$db->Where = " WHERE " . $Main_Key . " = '" . $ID . "'";

					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch();

					if (!empty($row)) {

						$_Path_		= $Path;

						$File_Name 	= $row[$Main_TablePre . '_Img'];

						if (!empty($File_Name)) {

							Delete_File($_Path_, $File_Name);

							$File = ICONV_CODE('UTF8_TO_BIG5', $_Path_ . $File_Name);

							if (is_file($File)) { //刪除原圖

								$_html_msg = '圖片刪除失敗';
								break;
							}
						}

						for ($i = 1; $i <= 3; $i++) {

							$File_Name = $row[$Main_TablePre . '_Img' . $i];

							if (!empty($File_Name)) {

								Delete_File($_Path_, $File_Name);

								$File = ICONV_CODE('UTF8_TO_BIG5', $_Path_ . $File_Name);

								if (is_file($File)) { //刪除原圖

									$_html_msg = '圖片刪除失敗';
									break;
								}
							}
						}

						if (!empty($_html_msg)) {
							break;
						}

						$db->query_delete($Main_Table);
					} else {

						$_html_msg = '無此資料';
						break;
					}
				} else {

					$_html_msg = '無權限刪除';
					break;
				}
			}

			$db->query_optimize($Main_Table); //最佳化資料表

			$Contents 		= new Contents($POST, $GET);

			$_html_content	= $Contents->set_Content_List();
			break;

		case "Table_Edit": //表格編輯畫面

			$_ID = $POST['tab_chks'];

			$Edit_Arr = array();
			if (is_array($_ID)) {

				$Edit_Arr   = $_ID;
			} else {

				$Edit_Arr[] = $_ID;
			}

			$table_info = $db->get_table_info($Main_Table); //取出資料表欄位的詳細的訊息

			$Class_Arr[$Main_Key3] = Class_Get('TYPE1'); //抓取分類列表

			$_html_ = array();
			if (!empty($Edit_Arr)) {

				foreach ($Edit_Arr as $key => $ID) {

					$db->Where = " WHERE " . $Main_Key . " = '" . $ID . "'";
					$db->query_sql($Main_Table, '*');
					$row = $db->query_fetch(); //取出資料

					$row[$Main_TablePre . '_Mcp' . '_sUrl'] = $Pathweb . 'sm_' . $row[$Main_TablePre . '_Mcp'] . '?' . time();
					$row[$Main_TablePre . '_Mcp' . '_bUrl'] = $Pathweb . $row[$Main_TablePre . '_Mcp'];

					for ($i = 1; $i <= 3; $i++) {

						$row[$Main_TablePre . '_Img' . $i . '_sUrl'] = $Pathweb . 'sm_' . $row[$Main_TablePre . '_Img' . $i] . '?' . time();
						$row[$Main_TablePre . '_Img' . $i . '_bUrl'] = $Pathweb . $row[$Main_TablePre . '_Img' . $i];
					}

					$_html_[] = $row;
				}
			}

			if (empty($_html_msg)) {

				ob_start();
				if ($_Type1 == 'View') {

					if (Menu_Use($Menu_Data, 'view')) {

						include_once(SYS_PATH . $Menu_Data['Menu_Path'] . DIRECTORY_SEPARATOR . $Menu_Data['Menu_Exec_Name'] . ".view." . $Menu_Data['Exec_Sub_Name']);

						if ($_Type1 == 'View') {

							$_html_boxtype = 3;
						}
					} else {

						$_html_msg = '無權限檢視';
					}
				} else {

					include_once(SYS_PATH . $Menu_Data['Menu_Path'] . DIRECTORY_SEPARATOR . $Menu_Data['Menu_Exec_Name'] . ".edit." . $Menu_Data['Exec_Sub_Name']);
				}

				$_html_content = ob_get_contents();
				ob_end_clean();
			}
			break;

		case "Table_Edit_Save": //記錄編輯資料

			$ID_Arr = $POST[$Main_Key];

			foreach ($ID_Arr as $key => $val) {

				$Fields = REMOVE_NDATA($POST, array($Main_Key));

				$db_data = array();

		

				foreach ($Fields as $key2 => $val2) {

					$Field = Turndecode($key2);
					
					if (($Field == 'Product_Unit_NO') || ($Field == 'Product_Unit') || ($Field == 'Product_Price') || ($Field == 'Product_Price1')) {

					
						$db_data[$Field] = serialize($val2);
					
						
					} else if($Field == 'Product_Unit_ID'){
						$i=1;
						foreach($val2 as $key3=>$val3){
							if(empty($val3)){
								$val2[$key3]='U'.strtotime($DATE).$i;
								$i++;
							}
						}
						$db_data[$Field] = serialize($val2);
					
					} else {

						$db_data[$Field] = $val2[$key];
					}
					
				}
				// $db_data['Product_Edate'] = $DATE;
			
				if (!empty($val) && !empty($db_data)) {

					if (Menu_Use($Menu_Data, 'edit')) {

						$db->Where = " WHERE " . $Main_Key . " = '" . $val . "'";

						$db->query_data($Main_Table, $db_data, 'UPDATE');

						if (!empty($db->Error)) {

							$_html_msg = '更新失敗';
							break;
						} else {

							$_html_msg = '更新完成';
						}
					} else {

						$_html_msg = '無權限編輯';
						break;
					}
				} else if (!empty($db_data)) {

					if (Menu_Use($Menu_Data, 'add')) {

						$NEW_ID = Get_Sn_Code();

						if (!empty($NEW_ID)) { //有開啟自訂義編號

							$db_data[$Main_Key] = $NEW_ID;
						}

						$db_data[$Main_TablePre . '_Sdate'] = $DATE;

						$db->query_data($Main_Table, $db_data, 'INSERT');

						if (!empty($db->Error)) {

							$_html_msg	= '新增失敗';
							break;
						} else {

							$val 		= !empty($NEW_ID) ? $NEW_ID : $db->query_insert_id();
							$_html_msg 	= '新增完成';
						}
					} else {

						$_html_msg = '無權限新增';
						break;
					}
				}

				if (!empty($_FILES) && !empty($val)) { //判斷有無上傳圖片

					foreach ($_FILES as $Fkey => $Fval) {

						$_Path_		= $Path;

						$Field_Name = $Fkey; //欄位名稱

						$PicSize_Arr = Get_PicSize(1, array());

						$_TF->TableUpload($val, 'uploadimg', $Field_Name, $_Path_, $Field_Name . $val, true, $PicSize_Arr);

						if (!empty($_TF->Msg)) {

							$_html_msg = !empty($_html_msg) ? $_html_msg . ', ' . $_TF->Msg : $_TF->Msg;
							break;
						}
					}

					if (empty($_TF->Msg)) {

						$_html_msg = !empty($_html_msg) ? $_html_msg . ', 上傳完成' : '上傳完成';
					}
				}
			}

			$_html_eval		= 'Return_Table()'; //重新載入返回

			$Contents 		= new Contents($POST, $GET);

			$_html_content	= $Contents->set_Content_List();
			break;

		case "Table_Data_Change": //切換資料

			$_ID 	= $POST['id'];
			$_Field	= Turndecode($POST['field']);
			$_Data	= $POST['data'];

			$_Key	= $Main_Key;
			$_Table	= $Main_Table;

			if ($_Type1 == 'delfile') {

				$_Path_ = $PathFile;
			} else {

				$_Path_	= $Path;
			}

			$Data_Arr  = array(
				'Table' => $_Table,
				'Key' 	=> $_Key,
				'ID' 	=> $_ID,
				'Field' => $_Field,
				'Data' 	=> $_Data
			);

			$_TF->TableChange($_Type1, $Data_Arr, $Path);

			if (!empty($_TF->Msg)) {

				$_html_msg = $_TF->Msg;
			}
			break;

		case "Table_Return": //表格返回

			$json_array = Get_Top_Return($Menu_Data);
			break;
	}

	ob_end_clean();

	$json_array['html_msg']     = $_html_msg ? $_html_msg : ''; //訊息
	$json_array['html_href']    = $_html_href ? $_html_href : ''; //連結
	$json_array['html_eval']    = $_html_eval ? $_html_eval : ''; //確定後要執行的JS
	$json_array['html_content'] = $_html_content ? $_html_content : ''; //輸出內容
	$json_array['html_boxtype'] = $_html_boxtype ? $_html_boxtype : '1'; //彈出視窗格式1=>只有確定,2=>確定與取消
	$json_array['html_clear']   = $_html_clear ? $_html_clear : ''; //清空欄位Array,填欄位ID
	$json_array['html_type'] 	= $_Type ? $_Type : ''; //執行類型
	$json_array['html_type1'] 	= $_Type1 ? $_Type1 : ''; //副執行類型

	echo json_encode($json_array);
}
