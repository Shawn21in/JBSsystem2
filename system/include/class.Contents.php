<?php

class Contents{
	
	var $FUN = FUN;				//FUN參數
	
	var $Lv1_List;				//第一層所有目錄
	var $Now_List;				//第二層目前目錄
	
	var $Title_List = array();	//目前位置陣列
	
	var $html 		= array();
	var $html2 		= array();
		
	var $POST;
	var $GET;
	
	function __construct(  $POST = array(),  $GET = array() ){
		
		$this->POST = $POST;
		$this->GET  = $GET;
	}
	
	function set_MenuList(){//取得目錄列表
		
		global $Admin_data;
		
		$this->html = $this->html2 = array();
		
		$db = new MySQL();
		
		$TablesArr = $db->get_table_status('Name');//撈出資料表資訊
				
		$db->query("SELECT * FROM " .DB_DataBase. "." .Menu_DB. " ORDER BY Menu_Sort DESC, Menu_ID DESC");
		while( $row = $db->query_fetch() ){
			//不是系統管理員不顯示
			if( !Menu_Use($row, 1) ){ continue; }
			//沒權限使用目錄
			if( !Menu_Use($row, 2) ){ continue; }
						
			if( $row['Menu_Lv'] == 1 ){
				
				$this->html[$row['Menu_ID']] = $row;
			}else{
				
				if( !in_array($row['Menu_TableName'], $TablesArr) && !empty($row['Menu_TableName']) && !preg_match('/sys/i', $row['Menu_TableName']) ){ continue; }//判斷資料庫有無此資料表	
				
				$row['Menu_Exec_Name']	= GetUrlVal($row['Menu_Exec'], 'PHP_SELF_NAME');
				$row['Exec_Sub_Name']	= GetUrlVal($row['Menu_Exec'], 'PHP_SELF_SUB_NAME');
				
				/*if( $row['Menu_SysUse'] == 1 ){
					
					$row['Menu_Focus']	= $row['Menu_Exec_Name'];
					$row['Menu_href'] = MAIN_EXEC.'?fun='.$row['Menu_Exec_Name'];
				}else */
				if( $row['Menu_Mode'] == 2 ){
					
					$row['Menu_href']	= $row['Menu_Link'];
				}else if( $row['Menu_SysUse'] == 1 || $row['Menu_Mode'] == 1 || $row['Menu_Mode'] == 3 || $row['Menu_Mode'] == 4 || $row['Menu_Mode'] == 5 ){
					
					if( $row['Menu_Mode'] == 3 || $row['Menu_Mode'] == 5 ){
						
						$row['Menu_Exec'] = $row['Menu_Model'].' 模組';
					}
					
					$row['Menu_Focus']	= $row['Menu_ID'];
					$row['Menu_href']	= MAIN_EXEC.'?fun='.$row['Menu_ID'];
				}else{
					
					$row['Menu_Exec']	= '尚未設定';
					$row['Menu_href'] 	= 'javascript:void(0)';
				}
				
				if( $row['Menu_Focus'] == $this->FUN ){//取得目前位置資料
					
					$this->Now_List 	= $row;
				}
				
				$this->html2[$row['Menu_UpMID']][$row['Menu_ID']] = $row;
			}
		}
		
		$this->Lv1_List = $this->html;
	}
	
	function set_TitleList(){//現在位置
		
		if( empty($this->FUN) ){
			
			$this->Title_List[]['Name'] = "控制台";
		}else{
			
			if( !empty($this->Lv1_List[$this->Now_List['Menu_UpMID']]['Menu_Name']) ){
				
				$this->Title_List[]['Name'] = $this->Lv1_List[$this->Now_List['Menu_UpMID']]['Menu_Name'];
			}
			
			if( !empty($this->Now_List['Menu_Name']) ){
				
				$this->Title_List[]['Name'] = $this->Now_List['Menu_Name'];
			}
		}
		
		return $this->Title_List;
	}
	
	function set_Content_List(){//顯示內容
	
		global $Admin_data, $Now_List, $Main_Key, $Main_Table, $Main_TablePre, $Main_Key2, $Main_Table2, $Main_TablePre2, $Main_Key3, $Main_Table3, $Main_TablePre3, $Main_maxLv3, $table_info, $_html_;

		$db   				= new MySQL();
		
		$_TF				= new TableFun();
		
		$POST 				= $this->POST;
		$GET  				= $this->GET;
		
		$Title_Array   		= array();//欄位標頭
		$Value_Array   		= array();//欄位值
		$Vtype_Array		= array();//欄位呈現方式
		$Order_Array   		= array();//可排序欄位
		$Order_By	   		= "";//排序
		
		$table_option_arr	= array();//資料表的設定陣列
		
		$Pages     			= !empty($POST['Pages']) ? $POST['Pages'] : 1;//目前頁碼
		$Page_Size 			= !empty($POST['Page_Size']) ? $POST['Page_Size'] : 10;//顯示筆數
		$SearchKey 			= $POST['SearchKey'];//搜尋字串
		
		$Sort 	   			= !empty($POST['Sort']) ? $POST['Sort'] : "";//升序降序
		$Field 	   			= !empty($POST['Field']) ? $POST['Field'] : "";//排序欄位
			
		if( empty($this->Now_List) ){//目前目錄資料
			
			$Now_List = MATCH_MENU_DATA($this->FUN);
		}else{
			
			$Now_List = $this->Now_List;
		}
		
		if( !empty($Sort) && !empty($Field) ){
				
			$Order_By = " ORDER BY " .$Field. " " .$Sort;
		}
	
		$Main_Table		= $Now_List['Menu_TableName'];
		$Main_Key  		= $Now_List['Menu_TableKey'];
		$Main_TablePre	= $Now_List['Menu_TablePre'];
		$Main_Key2		= $Now_List['Menu_TableKey1'];//擴充資料表主健
		$Main_Table2 	= $Now_List['Menu_TableName1'];//擴充資料表
		$Main_TablePre2	= $Now_List['Menu_TablePre1'];//擴充資料表前輟
		$Main_Key3		= $Now_List['Menu_TableKey2'];//分類資料表主健
		$Main_Table3 	= $Now_List['Menu_TableName2'];//分類資料表
		$Main_TablePre3	= $Now_List['Menu_TablePre2'];//分類資料表前輟
		$Main_maxLv3	= $Now_List['Menu_ClassMax'];//分類最大層級
		
		include(SYS_PATH.'include/inc.path.php');//取得檔案位置
		
		//---------------------------------------------------------------------//
		if( empty($this->FUN) || empty($Now_List) || !Menu_Use($Now_List, 1) || !Menu_Use($Now_List, 2) ){
				
			include_once("php_sys/control.php");
		//---------------------------------------------------------------------//
		}else if( $Now_List['Menu_Model'] == 'SYS_LOGS' ){//系統log表
						
			if( $db->check_table(Log_DB) == false ){
				
				$db->Create_SML();
			}
			
			$Table_Field_Arr = $db->get_table_info(Log_DB, 'Comment');
			
			//查詢欄位名稱設定
			$_Search_Option['ML_DATE']['name'] 			= $Table_Field_Arr['ML_DATE'];
			$_Search_Option['ML_USER']['name'] 			= $Table_Field_Arr['ML_USER'];
			$_Search_Option['ML_DATA_ID']['name'] 		= $Table_Field_Arr['ML_DATA_ID'];
			$_Search_Option['ML_COMMENT']['name'] 		= $Table_Field_Arr['ML_COMMENT'];
			$_Search_Option['ML_SQL_CON']['name'] 		= $Table_Field_Arr['ML_SQL_CON'];
			$_Search_Option['ML_SQL_EXEC_TYPE']['name'] = $Table_Field_Arr['ML_SQL_EXEC_TYPE'];
			$_Search_Option['ML_EXEC_FILE']['name'] 	= $Table_Field_Arr['ML_EXEC_FILE'];
			//查詢欄位設定種類
			$_Search_Option['ML_DATE']['type'] 			= 'datetime';
			$_Search_Option['ML_USER']['type'] 			= 'text';
			$_Search_Option['ML_DATA_ID']['type'] 		= 'text';
			$_Search_Option['ML_COMMENT']['type'] 		= 'text';
			$_Search_Option['ML_SQL_CON']['type'] 		= 'text';
			$_Search_Option['ML_SQL_EXEC_TYPE']['type'] = 'text';
			$_Search_Option['ML_EXEC_FILE']['type'] 	= 'text';
			
			if( !empty($POST['search_field']) ){
				
				$db->Where = Search_Fun($db->Where, $POST, $_Search_Option);
			}
			
			$_Clean_Option = true;//進階功能->開啟清除功能
			
			if( $_Clean_Option && !empty($db->Where) && $POST['search_type'] == 'clean' ){//清除資料
				
				$db->query_delete(Log_DB);
				$db->query_optimize(Log_DB);//最佳化資料表
			}
			
			$db->Search = array('ML_DATE', 'ML_USER', 'ML_DATA_ID', 'ML_COMMENT', 'ML_SQL_CON', 'ML_SQL_EXEC_TYPE', 'ML_EXEC_FILE');//設定可搜尋欄位
			$db->query_search($SearchKey);//串接搜尋子句

			$Page_Total_Num = $db->query_count(Log_DB);//總資料筆數
			
			$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
			$Pages    = $Page_Calss->Pages;//頁碼
			$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈
			//print_r($_Page_c->Pages_Data);			
			
			$Title_Array['Ordersn']				= "序號";
			$Title_Array['ML_DATE']				= $Table_Field_Arr['ML_DATE'];
			$Title_Array['ML_USER']				= $Table_Field_Arr['ML_USER'];
			$Title_Array['ML_DATA_ID']			= $Table_Field_Arr['ML_DATA_ID'];
			$Title_Array['ML_COMMENT']			= $Table_Field_Arr['ML_COMMENT'];
			$Title_Array['ML_SQL_CON']			= $Table_Field_Arr['ML_SQL_CON'];
			$Title_Array['ML_SQL_EXEC_TYPE']	= $Table_Field_Arr['ML_SQL_EXEC_TYPE'];
			$Title_Array['ML_EXEC_FILE']		= $Table_Field_Arr['ML_EXEC_FILE'];
			
			$sn = ( $StartNum + 1 );
			
			if( !empty($Order_By) ){
				
				$db->Order_By = $Order_By;
			}else{
				
				$db->Order_By = " ORDER BY ML_ID DESC, ML_DATE DESC";
			}
						
			$db->query_sql(Log_DB, "*", $StartNum, $Page_Size);
			while( $row = $db->query_fetch() ){
								
				$Value_Array['Ordersn'][$sn]			= $sn;
				$Value_Array['ML_DATE'][$sn]			= $row['ML_DATE'];
				$Value_Array['ML_USER'][$sn]			= $row['ML_USER'];
				$Value_Array['ML_DATA_ID'][$sn]			= $row['ML_DATA_ID'];
				$Value_Array['ML_COMMENT'][$sn]			= $row['ML_COMMENT'];
				$Value_Array['ML_SQL_CON'][$sn]			= $row['ML_SQL_CON'];
				$Value_Array['ML_SQL_EXEC_TYPE'][$sn]	= $row['ML_SQL_EXEC_TYPE'];
				$Value_Array['ML_EXEC_FILE'][$sn]		= $row['ML_EXEC_FILE'];

				$sn++;
			}

			$Order_Array = array("ML_DATE" => "", "ML_USER" => "", "ML_DATA_ID" => "", "ML_COMMENT" => "", "ML_SQL_CON" => "", "ML_SQL_EXEC_TYPE" => "", "ML_EXEC_FILE" => "");//允許排序項目
			
			//第一個值設定對應KEY,第二個值設定對應名稱
			$_FT = new Fixed_Table( $Value_Array['ML_DATE'], array() );
			$_FT->Input_TitleArray( $Title_Array );//設定顯示欄位
			$_FT->Input_ValueArray( $Value_Array );//設定顯示欄位裡面的值
			$_FT->Input_VtypeArray( $Vtype_Array );//設定顯示欄位呈現方式
			$_FT->Input_OrderArray( $Order_Array, $Sort, $Field );//設定可排序的欄位, 升序或降序, 要排序欄位名稱
			$_FT->Pages_Data = $Page_Calss->Pages_Data;//頁碼資料
			$_FT->Pages_Html = $Page_Calss->Pages_Html;//頁碼頁面
			$_FT->SearchKey  = $SearchKey;//搜尋字串
			$_FT->Operating	 = array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']);//設定操作類型
			$_FT->Path		 = SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR;//檔案路徑
			$_FT->TableHtml	 = $Now_List['Menu_Exec_Name'].'.table.'.$Now_List['Exec_Sub_Name'];//設定使用的Html
			
			$_html = $_FT->CreatTable();//創建表格
			
			if( empty($POST) ){
				
				ob_start();
				include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name']);
				$_html = ob_get_contents();
				ob_end_clean(); 
			}
			
			return $_html;
		//---------------------------------------------------------------------//	
		}else if( $Now_List['Menu_Model'] == 'SYS_TABLES' ){//系統資料庫
			
			require(SYS_PATH."include/inc.connect.php");//載入連結MYSQL資訊

			$Table_Field_Arr = $db->get_table_info(Tables_DB, 'Comment');
			
			$Sheet = Tables_DB;
			
			$db->Search = array('Tables_Name', 'Tables_Name1', 'Tables_Open');//設定可搜尋欄位
			$db->query_search($SearchKey);//串接搜尋子句

			$Page_Total_Num = $db->query_count($Sheet);//總資料筆數
			
			$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
			$Pages    = $Page_Calss->Pages;//頁碼
			$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈
			//print_r($_Page_c->Pages_Data);
			
			$Title_Array['Ordersn']			= "序號";
			$Title_Array['Tables_Name']		= $Table_Field_Arr['Tables_Name'];
			$Title_Array['Tables_Name1']	= $Table_Field_Arr['Tables_Name1'];
			$Title_Array['Tables_Open']		= $Table_Field_Arr['Tables_Open'];
			
			$sn = ( $StartNum + 1 );
			
			if( !empty($Order_By) ){
				
				$db->Order_By = $Order_By;
			}else{
				
				$db->Order_By = " ORDER BY Tables_ID ASC";
			}
						
			$db->query_sql($Sheet, "*", $StartNum, $Page_Size);
			while( $row = $db->query_fetch() ){
								
				$Value_Array['Ordersn'][$sn]		= $sn;
				$Value_Array['Tables_ID'][$sn]		= $row['Tables_ID'];
				$Value_Array['Tables_Name'][$sn]	= $row['Tables_Name'];
				$Value_Array['Tables_Name1'][$sn]	= $row['Tables_Name1'];
				$Value_Array['Tables_Open'][$sn]	= $row['Tables_Open'];
				
				//呈現的種類
				$Vtype_Array['Tables_Open'][$sn]			= 'checkbox';
				
				$sn++;
			}

			$Order_Array = array("Tables_Name" => "", "Tables_Name1" => "", "Tables_Open" => "");//允許排序項目
			
			//第一個值設定對應KEY,第二個值設定對應名稱
			$_FT = new Fixed_Table( $Value_Array['Tables_ID'], $Value_Array['Ordersn'] );
			$_FT->Input_TitleArray( $Title_Array );//設定顯示欄位
			$_FT->Input_ValueArray( $Value_Array );//設定顯示欄位裡面的值
			$_FT->Input_VtypeArray( $Vtype_Array );//設定顯示欄位呈現方式
			$_FT->Input_OrderArray( $Order_Array, $Sort, $Field );//設定可排序的欄位, 升序或降序, 要排序欄位名稱
			$_FT->Pages_Data = $Page_Calss->Pages_Data;//頁碼資料
			$_FT->Pages_Html = $Page_Calss->Pages_Html;//頁碼頁面
			$_FT->SearchKey  = $SearchKey;//搜尋字串
			$_FT->Operating	 = array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']);//設定操作類型
			$_FT->Path		 = SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR;//檔案路徑
			$_FT->TableHtml	 = $Now_List['Menu_Exec_Name'].'.table.'.$Now_List['Exec_Sub_Name'];//設定使用的Html
			
			$_html = $_FT->CreatTable();//創建表格
			
			if( empty($POST) ){
				
				ob_start();
				include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name']);
				$_html = ob_get_contents();
				ob_end_clean(); 
			}
			
			return $_html;
		//---------------------------------------------------------------------//	
		}else if( $Now_List['Menu_Model'] == 'SYS_ADMIN' ){//系統使用者
			
			require(SYS_PATH."include/inc.connect.php");//載入連結MYSQL資訊
						
			$Table_Field_Arr = $db->get_table_info(Admin_DB, 'Comment');
						
			$Sheet = Admin_DB. ' as a LEFT JOIN ' .Group_DB. ' as b ON a.Group_ID = b.Group_ID';
			
			//查詢欄位名稱設定
			$_Search_Option['Admin_Name']['name'] 		= $Table_Field_Arr['Admin_Name'];
			$_Search_Option['Admin_Acc']['name'] 		= $Table_Field_Arr['Admin_Acc'];
			$_Search_Option['Group_Name']['name'] 		= $Table_Field_Arr['Group_ID'];
			$_Search_Option['Admin_LastLogin']['name'] 	= $Table_Field_Arr['Admin_LastLogin'];
			$_Search_Option['Admin_Open']['name'] 		= $Table_Field_Arr['Admin_Open'];
			//查詢欄位設定種類
			$_Search_Option['Admin_Name']['type'] 		= 'text';
			$_Search_Option['Admin_Acc']['type'] 		= 'text';
			$_Search_Option['Group_Name']['type'] 		= 'text';
			$_Search_Option['Admin_LastLogin']['type'] 	= 'datetime';
			$_Search_Option['Admin_Open']['type'] 		= 'select';
			//查詢欄位設定選擇內容
			$_Search_Option['Admin_Open']['select'] 	= array('0' => '停用', '1' => '啟用');
			
			if( !empty($POST['search_field']) ){
				
				$db->Where = Search_Fun($db->Where, $POST, $_Search_Option);
			}
						
			$db->Search = array("Admin_Name", "Admin_Acc", "Group_Name");//設定可搜尋欄位
			$db->query_search($SearchKey);//串接搜尋子句

			$Page_Total_Num = $db->query_count($Sheet);//總資料筆數
			
			$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
			$Pages    = $Page_Calss->Pages;//頁碼
			$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈
			//print_r($_Page_c->Pages_Data);
						
			$Title_Array['Ordersn']    		= "序號";
			$Title_Array['Admin_Name']		= $Table_Field_Arr['Admin_Name'];
			$Title_Array['Admin_Acc']		= $Table_Field_Arr['Admin_Acc'];
			$Title_Array['Group_Name']		= $Table_Field_Arr['Group_ID'];
			$Title_Array['Admin_LastLogin']	= $Table_Field_Arr['Admin_LastLogin'];
			$Title_Array['Admin_IP']  		= $Table_Field_Arr['Admin_IP'];
			$Title_Array['Admin_Open']		= $Table_Field_Arr['Admin_Open'];
			
			$sn = ( $StartNum + 1 );
			
			if( !empty($Order_By) ){
				
				$db->Order_By = $Order_By;
			}else{
				
				$db->Order_By = " ORDER BY a.Group_ID ASC, a.Admin_ID ASC";
			}
						
			$db->query_sql($Sheet, "*", $StartNum, $Page_Size);
			while( $row = $db->query_fetch() ){
				
				if( $Admin_data['Group_ID'] != 1 && $row['Group_ID'] == 1 ){ continue; }//系統管理員只有自己才看得到
				
				$Value_Array['Ordersn'][$sn]			= $sn;
				$Value_Array['Admin_ID'][$sn]			= $row['Admin_ID'];
				$Value_Array['Admin_Name'][$sn]			= $row['Admin_Name'];
				$Value_Array['Admin_Acc'][$sn]			= $row['Admin_Acc'];
				$Value_Array['Group_Name'][$sn]			= $row['Group_Name'];
				$Value_Array['Admin_LastLogin'][$sn]	= $row['Admin_LastLogin'];
				$Value_Array['Admin_IP'][$sn]			= $row['Admin_IP'];
				$Value_Array['Admin_Open'][$sn]			= $row['Admin_Open'];
				
				$Value_Array['Group_Lv'][$sn]			= $row['Group_Lv'];
				
				//呈現的種類
				$Vtype_Array['Admin_Open'][$sn]			= 'checkbox';
				
				$sn++;
			}

			$Order_Array = array("Admin_Name" => "", "Admin_Acc" => "", "Group_Name" => "", "Admin_LastLogin" => "", "Admin_IP" => "", "Admin_Open" => "");//允許排序項目
			
			//第一個值設定對應KEY,第二個值設定對應名稱
			$_FT = new Fixed_Table( $Value_Array['Admin_ID'], $Value_Array['Ordersn'] );
			$_FT->Input_TitleArray( $Title_Array );//設定顯示欄位
			$_FT->Input_ValueArray( $Value_Array );//設定顯示欄位裡面的值
			$_FT->Input_VtypeArray( $Vtype_Array );//設定顯示欄位呈現方式
			$_FT->Input_OrderArray( $Order_Array, $Sort, $Field );//設定可排序的欄位, 升序或降序, 要排序欄位名稱
			$_FT->Pages_Data = $Page_Calss->Pages_Data;//頁碼資料
			$_FT->Pages_Html = $Page_Calss->Pages_Html;//頁碼頁面
			$_FT->SearchKey  = $SearchKey;//搜尋字串
			$_FT->Operating	 = array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']);//設定操作類型
			$_FT->Path		 = SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR;//檔案路徑
			$_FT->TableHtml	 = $Now_List['Menu_Exec_Name'].'.table.'.$Now_List['Exec_Sub_Name'];//設定使用的Html
			
			$_html = $_FT->CreatTable();//創建表格
			
			if( empty($POST) ){
				
				ob_start();
				include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name']);
				$_html = ob_get_contents();
				ob_end_clean(); 
			}
			
			return $_html;
		//---------------------------------------------------------------------//
		
		}else if( $Now_List['Menu_Model'] == 'SYS_RECAP' ){//系統使用者
			
			require(SYS_PATH."include/inc.connect.php");//載入連結MYSQL資訊
			
			$table_info = $db->get_table_info($Now_List['Menu_TableName']);//取出資料表欄位的詳細的訊息	
	
			if( Multi_WebUrl ){
							
				$db->Where = " WHERE Admin_ID = '" .$db->val_check($Admin_data['Admin_ID']). "'";
			}else{
				
				$db->Where = " WHERE Admin_ID = '" .Multi_WebUrl_ID. "'";
			}
				
			$db->query_sql( Recaptcha_DB , "*");
			$_html_[] = $db->query_fetch();
			
			//return $_html_;
			ob_start();
			include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".edit.".$Now_List['Exec_Sub_Name']);
			$_html = ob_get_contents();
			ob_end_clean();
			
			return $_html;
		//---------------------------------------------------------------------//
		}else if( $Now_List['Menu_Model'] == 'SYS_GROUP' ){//系統群組
			
			require(SYS_PATH."include/inc.connect.php");//載入連結MYSQL資訊
			
			$Table_Field_Arr = $db->get_table_info(Group_DB, 'Comment');
			
			$Sheet = Group_DB;
			
			$db->Search = array("Group_Name");//設定可搜尋欄位
			$db->query_search($SearchKey);//串接搜尋子句

			$Page_Total_Num = $db->query_count($Sheet);//總資料筆數
			
			$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
			$Pages    = $Page_Calss->Pages;//頁碼
			$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈
			//print_r($_Page_c->Pages_Data);
						
			$Title_Array['Ordersn']    		= "序號";
			$Title_Array['Group_Name']		= $Table_Field_Arr['Group_Name'];
			$Title_Array['Group_Lv']		= $Table_Field_Arr['Group_Lv'];
			
			$sn = ( $StartNum + 1 );
			
			if( !empty($Order_By) ){
				
				$db->Order_By = $Order_By;
			}else{
				
				$db->Order_By = " ORDER BY Group_Lv ASC";
			}
						
			$db->query_sql($Sheet, "*", $StartNum, $Page_Size);
			while( $row = $db->query_fetch() ){
				
				if( $Admin_data['Group_ID'] != 1 && $row['Group_ID'] == 1 ){ continue; }//系統管理員只有自己才看得到
				
				$Value_Array['Ordersn'][$sn]			= $sn;
				$Value_Array['Group_ID'][$sn]			= $row['Group_ID'];
				$Value_Array['Group_Name'][$sn]			= $row['Group_Name'];
				$Value_Array['Group_Lv'][$sn]			= $row['Group_Lv'];

				$sn++;
			}

			$Order_Array = array("Group_Name" => "", "Group_Lv" => "");//允許排序項目
			
			//第一個值設定對應KEY,第二個值設定對應名稱
			$_FT = new Fixed_Table( $Value_Array['Group_ID'], $Value_Array['Ordersn'] );
			$_FT->Input_TitleArray( $Title_Array );//設定顯示欄位
			$_FT->Input_ValueArray( $Value_Array );//設定顯示欄位裡面的值
			$_FT->Input_VtypeArray( $Vtype_Array );//設定顯示欄位呈現方式
			$_FT->Input_OrderArray( $Order_Array, $Sort, $Field );//設定可排序的欄位, 升序或降序, 要排序欄位名稱
			$_FT->Pages_Data = $Page_Calss->Pages_Data;//頁碼資料
			$_FT->Pages_Html = $Page_Calss->Pages_Html;//頁碼頁面
			$_FT->SearchKey  = $SearchKey;//搜尋字串
			$_FT->Operating	 = array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']);//設定操作類型
			$_FT->Path		 = SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR;//檔案路徑
			$_FT->TableHtml	 = $Now_List['Menu_Exec_Name'].'.table.'.$Now_List['Exec_Sub_Name'];//設定使用的Html
			
			$_html = $_FT->CreatTable();//創建表格
			
			if( empty($POST) ){
				
				ob_start();
				include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name']);
				$_html = ob_get_contents();
				ob_end_clean(); 
			}
			
			return $_html;
		//---------------------------------------------------------------------//
		}else if( $Now_List['Menu_Model'] == 'SYS_MENU' ){//系統目錄
			
			$TablesArr = $db->get_table_status('Name');//撈出資料表資訊
			
			require(SYS_PATH."include/inc.connect.php");//載入連結MYSQL資訊
						
			$table_info = $db->get_table_info(Menu_DB);//取出資料表欄位的詳細的訊息			
			
			$Sheet = Menu_DB;
			
			$_html = $_html2 = array();
			//$db->Where    = " WHERE Menu_Lv >= '1'";
			$db->Order_By = " ORDER BY Menu_Sort DESC, Menu_ID DESC";
			
			$db->query_sql($Sheet, "*");
			while( $row = $db->query_fetch() ){
				
				if( $Admin_data['Group_ID'] != 1 && $row['Menu_SysUse'] == 1 ){ continue; }//系統檔只有系統管理員能看
								
				if( $Admin_data['Group_ID'] != 1 && $Admin_data['Admin_Permissions'] < $row['Menu_Permissions'] ){ continue; }//沒權限跳過
				if( !Menu_Use($row, 2) ){ continue; }//沒權限使用目錄
				
				//if( $row['Menu_Lv'] == 2 && !in_array($row['Menu_TableName'], $TablesArr) && !empty($row['Menu_TableName']) && !preg_match('/sys/i', $row['Menu_TableName']) ){ continue; }//判斷資料庫有無此資料表	
				
				//$row['Menu_disabled'] = $disabled;
				
				if( $row['Menu_Lv'] == 1 ){
					
					$_html[$row['Menu_ID']] = $row;
				}else if( $row['Menu_Lv'] == 2 ){
					
					$_html2[$row['Menu_UpMID']][$row['Menu_ID']] = $row;
				}
			}
			
			$Main_TablePre = 'Menu';
						
			ob_start();
			include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name']);
			$_html = ob_get_contents();
			ob_end_clean(); 
			
			return $_html;
		//---------------------------------------------------------------------//
		}else if( $Now_List['Menu_Model'] == 'SYS_WEBOP' ){//系統公司資料
			
			global $secure_states;
			
			require(SYS_PATH."include/inc.connect.php");//載入連結MYSQL資訊
			
			if( $Admin_data['Group_ID'] == 1 ){//只有系統管理員能看全部
			
				$Table_Field_Arr = $db->get_table_info(Web_Option_DB, 'Comment');
					
				$Sheet = Web_Option_DB. ' as a LEFT JOIN ' .Admin_DB. ' as b ON a.Admin_ID = b.Admin_ID';	
				
				$db->Search = array("Admin_Name", "WO_Name", "WO_Tel", "WO_Title");//設定可搜尋欄位
				$db->query_search($SearchKey);//串接搜尋子句
	
				$Page_Total_Num = $db->query_count($Sheet);//總資料筆數
				
				$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
				$Pages    = $Page_Calss->Pages;//頁碼
				$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈
							
				$Title_Array['Ordersn']		= "序號";
				$Title_Array['Admin_Name']	= $Table_Field_Arr['Admin_ID'];
				$Title_Array['WO_Name']		= $Table_Field_Arr['WO_Name'];
				$Title_Array['WO_Tel']		= $Table_Field_Arr['WO_Tel'];
				$Title_Array['WO_Title']	= $Table_Field_Arr['WO_Title'];
				$Title_Array['WO_Open']		= $Table_Field_Arr['WO_Open'];
				
				$sn = ( $StartNum + 1 );
				
				if( !empty($Order_By) ){
					
					$db->Order_By = $Order_By;
				}else{
					
					$db->Order_By = " ORDER BY a.Admin_ID ASC";
				}
							
				$db->query_sql($Sheet, "*", $StartNum, $Page_Size);
				while( $row = $db->query_fetch() ){
									
					$Value_Array['Ordersn'][$sn]	= $sn;
					$Value_Array['Admin_ID'][$sn]	= $row['Admin_ID'];
					$Value_Array['Admin_Name'][$sn]	= $row['Admin_Name'];
					$Value_Array['WO_Name'][$sn]	= $row['WO_Name'];
					$Value_Array['WO_Tel'][$sn]		= $row['WO_Tel'];
					$Value_Array['WO_Title'][$sn]	= $row['WO_Title'];
					$Value_Array['WO_Open'][$sn]	= $row['WO_Open'];
					
					//呈現的種類
					$Vtype_Array['WO_Open'][$sn]	= 'checkbox';
					
					$sn++;
				}
	
				$Order_Array = array("Admin_Name" => "", "WO_Name" => "", "WO_Tel" => "", "WO_Title" => "", "WO_Open" => "");//允許排序項目
				
				//第一個值設定對應KEY,第二個值設定對應名稱
				$_FT = new Fixed_Table( $Value_Array['Admin_ID'], $Value_Array['Ordersn'] );
				$_FT->Input_TitleArray( $Title_Array );//設定顯示欄位
				$_FT->Input_ValueArray( $Value_Array );//設定顯示欄位裡面的值
				$_FT->Input_VtypeArray( $Vtype_Array );//設定顯示欄位呈現方式
				$_FT->Input_OrderArray( $Order_Array, $Sort, $Field );//設定可排序的欄位, 升序或降序, 要排序欄位名稱
				$_FT->Pages_Data = $Page_Calss->Pages_Data;//頁碼資料
				$_FT->Pages_Html = $Page_Calss->Pages_Html;//頁碼頁面
				$_FT->SearchKey  = $SearchKey;//搜尋字串
				$_FT->Operating	 = array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']);//設定操作類型
				$_FT->Path		 = SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR;//檔案路徑
				$_FT->TableHtml	 = $Now_List['Menu_Exec_Name'].'.table.'.$Now_List['Exec_Sub_Name'];//設定使用的Html
				
				$_html = $_FT->CreatTable();//創建表格
				
				if( empty($POST) ){
					
					ob_start();
					include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name']);
					$_html = ob_get_contents();
					ob_end_clean(); 
				}
			}else{
				
				$table_info = $db->get_table_info(Web_Option_DB);//取出資料表欄位的詳細的訊息	
				
				$db->Where = " WHERE Admin_ID = '" .$db->val_check($Admin_data['Admin_ID']). "'";
				
				if( Multi_WebUrl ){
					
					$db->Where = " WHERE Admin_ID = '" .$db->val_check($Admin_data['Admin_ID']). "'";
				}else{
					
					$db->Where = " WHERE Admin_ID = '" .Multi_WebUrl_ID. "'";
				}	 
				/*
				$_html_[] = $db->query_fetch();
				*/
				
				$db->query_sql(Web_Option_DB, '*');
				$row = $db->query_fetch();//取出資料
				
				$URLweb 	= SYS_URL."assets/css/images/";
				
				// $row['WO_Banner'.'_bUrl'] 		= $URLweb.$row['WO_Banner'];
				// $row['WO_Banner'.'_sUrl'] 		= $URLweb.'sm_'.$row['WO_Banner'];
				
				$row['WO_LOGO'.'_bUrl'] 		= $URLweb.$row['WO_LOGO'];
				$row['WO_LOGO'.'_sUrl'] 		= $URLweb.'sm_'.$row['WO_LOGO'];
				
				$row['WO_LOGO2'.'_bUrl'] 		= $URLweb.$row['WO_LOGO2'];
				$row['WO_LOGO2'.'_sUrl'] 		= $URLweb.'sm_'.$row['WO_LOGO2'];
				
				$row['WO_favicon'.'_bUrl'] 		= $URLweb.$row['WO_favicon'];
				$row['WO_favicon'.'_sUrl'] 		= $URLweb.'sm_'.$row['WO_favicon'];
				
				$row['WO_ShareIcon'.'_bUrl'] 	= $URLweb.$row['WO_ShareIcon'];
				$row['WO_ShareIcon'.'_sUrl'] 	= $URLweb.'sm_'.$row['WO_ShareIcon'];
				
				$row['WO_FooterLOGO'.'_bUrl'] 	= $URLweb.$row['WO_FooterLOGO'];
				$row['WO_FooterLOGO'.'_sUrl'] 	= $URLweb.'sm_'.$row['WO_FooterLOGO'];
				
				$row['WO_FooterImg'.'_bUrl'] 	= $URLweb.$row['WO_FooterImg'];
				$row['WO_FooterImg'.'_sUrl'] 	= $URLweb.'sm_'.$row['WO_FooterImg'];
				
				$_html_[] = $row;
				
				$Main_Key  		= 'Admin_ID';//資料表主健
				
				$Main_Table 	= Web_Option_DB;//目錄使用的資料表
	
				$WOtype			= GetUrlVal($Now_List['Menu_Exec'], 'type');
				
				include_once(SYS_PATH.'config'.DIRECTORY_SEPARATOR.'cfg.woption.php');//載入設定
				
				ob_start();
				include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".edit.".$Now_List['Exec_Sub_Name']);
				$_html = ob_get_contents();
				ob_end_clean();
				
				ob_start();
				include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name']);
				$_html = ob_get_contents();
				ob_end_clean(); 
			}
		
			return $_html;
		//---------------------------------------------------------------------//
		}else if( !empty($Now_List['Menu_Model']) && ( $Now_List['Menu_Mode'] == 3 || $Now_List['Menu_Mode'] == 5 ) ){//模組用
			
			Check_Table($Main_Table);
						
			$table_option_arr = Get_Table_Option($Main_Table, 'OUT');//取出列表頁面的設定
			
			ob_start();
			include_once(SYS_PATH.'mods'.DIRECTORY_SEPARATOR.$Now_List['Menu_Model'].DIRECTORY_SEPARATOR.$Now_List['Menu_Model'].'.php');
			$_html = ob_get_contents();
			ob_end_clean(); 
			
			return $_html;
		//---------------------------------------------------------------------//
		}else{
			
			$Exec_File = SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".".$Now_List['Exec_Sub_Name'];
			
			Check_Table($Main_Table);
			
			Check_File($Exec_File);
			
			ob_start();
			include_once($Exec_File);
			$_html = ob_get_contents();
			ob_end_clean(); 
			
			return $_html;
		}
	}
}
?>