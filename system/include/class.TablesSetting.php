<?php

class TablesSetting{
	
	var $QUERY_STRING = '';
	
	var $TO_InType = 
		array(
			'checkbox' 		=> '勾選格式', 
			'number' 		=> '數字格式',
			'sortasc'		=> '數字格式 ( 升序 )',
			'sortdesc'		=> '數字格式 ( 降序 )',
			'unique' 		=> '唯一格式',
			'password' 		=> '密碼格式',
			'select' 		=> '選擇格式 ( select )',
			'radio' 		=> '選擇格式 ( radio )',
			'aznumber' 		=> '英文數字格式', 
			'datestart'		=> '時間格式 ( 始 )', 
			'dateend'		=> '時間格式 ( 末 )',
			'datecreat'		=> '時間格式 ( 建立 )', 
			'dateedit' 		=> '時間格式 ( 編輯 )',  
			'textarea' 		=> '純文字編輯格式', 
			'textedit' 		=> '文字編輯器格式', 
			'uploadimg' 	=> '上傳圖片格式', 
			'uploadfile' 	=> '上傳檔案格式',
			'link' 			=> '超連結格式',
			'city' 			=> '縣市格式', 
			'county'		=> '區域格式',
			'address'		=> '地址格式',
			'keyword'		=> '關鍵字格式',
			'youtube'		=> 'YouTube'
		);
	
	var $TO_TimeFormat = 
		array(
			'YYYY-MM-DD HH:mm:ss'	=> 'YYYY-MM-DD HH:mm:ss', 
			'YYYY-MM-DD HH:mm'		=> 'YYYY-MM-DD HH:mm',
			'YYYY-MM-DD HH'			=> 'YYYY-MM-DD HH',
			'YYYY-MM-DD' 			=> 'YYYY-MM-DD'
		);
				   
	var $TS_Style = 
		array(
			'style1' 	=> '一般資料表格式',
			//'style2' 	=> '會員資料表格式',
			//'style3' 	=> '購物資料表格式',
			'style4' 	=> '創建資料表選擇',
		);
	
	var $TS_Style1 = 
		array(
			'Title'		=> '標題 ( Title - varchar(60) )',
			'Intro'		=> '簡介 ( Intro - varchar(255) )',
			'Content' 	=> '內容 ( Content - text )',
			'Link' 		=> '連結 ( Link - varchar(100) )',
			'Img' 		=> '圖片 ( Img - varchar(60) )',
			'Mcp' 		=> '封面圖 ( Mcp - varchar(60) )',
			'File'		=> '檔案 ( File - varchar(60) )',
            'YouTube'	=> 'YouTube ( YouTube - varchar(11) )',
			'Keyword'	=> '關鍵字 ( Keyword - varchar(60) )',
            'Sort'		=> '排序 ( Sort - int(5) )',
            'Open'		=> '啟用 ( Open - tinyint(1) )',
            'Sdate'		=> '建立時間 ( Sdate - datetime )',
            'Edate'		=> '編輯時間 ( Edate - datetime )',
            'Open'		=> '啟用 ( Open - tinyint(1) )',
		); 
	
	var $TS_Style2 = 
		array(
			'Name'		=> '名稱 ( Name - varchar(30) )',
			'Email'		=> '信箱 ( Email - varchar(60) )',
			'Sex' 		=> '性別 ( Sex - varchar(2) )',
			'Birthday'	=> '-生日 ( Birthday - varchar(10) )',
			'Tel' 		=> '-電話 ( Tel - varchar(20) )',
			'Fax' 		=> '-傳真 ( Fax - varchar(20) )',
			'Mobile'	=> '-手機 ( Mobile - varchar(20) )',
            'Zipcode'	=> '-區域碼 ( Zipcode - varchar(6) )',
            'City'		=> '-縣市 ( City - varchar(6) )',
            'Area'		=> '-區域 ( Area - varchar(6) )',
            'Address'	=> '-地址 ( Address - varchar(100) )',
            'Company'	=> '-公司名稱 ( Company - varchar(20) )',
            'Uniform'	=> '-公司統編 ( Uniform - varchar(8) )',
            'Acc'		=> '-帳號 ( Acc - varchar(32) )',
            'Pwd'		=> '-密碼 ( Pwd - varchar(32) )',
            'Emailauth'	=> '-信箱認證 ( Emailauth - tinyint(1) )',
		);
		
	var $TS_Style3 = 
		array(
			'Price'		=> '-金額 ( Price - int(10) )',
		);             
			
	var $TS_Style4 = 
		array(
			'newsc'		=> '最新消息創建 ( 含分類 )',
			'news'		=> '最新消息創建 ( 不含分類 )',
			'qandac'	=> '常見問題創建 ( 含分類 )',
			'qanda'		=> '常見問題創建 ( 不含分類 )',
			'productc' 	=> '產品資料創建 ( 含分類 )',
			'product' 	=> '產品資料創建 ( 不含分類 )',
			'albumsc' 	=> '相簿資料創建 ( 含分類 )',
			'albums' 	=> '相簿資料創建 ( 不含分類 )',
			'banner'	=> '廣告輪播創建',
			'member'	=> '會員資料創建',
			'message' 	=> '聯絡我們創建',
			'delivery' 	=> '付款運費創建',
			'class' 	=> '分類資料創建',
		);
	
	var $Options;
				   
	function __construct( $QUERY_STRING = '' ){
		
		$db = new MySQL();
		
		$this->Options = new Options();//呼叫擴充設定
					
		if( $db->check_table(Tables_Option_DB) == false ){
			
			$this->Options->Create_TableOption();
		}
		
		$this->QUERY_STRING = $QUERY_STRING;
		
		if( $this->QUERY_STRING == 'tablesoption' ){
			
			$this->TablesOption();
		}else if( $this->QUERY_STRING == 'tablescreate' ){
			
			$this->TablesCreate();
		}
	}
	
	function TablesOption(){
		
		global $POST, $Admin_data, $States_Type, $PicSize_Type;
		
		$db = new MySQL();
		$db->Insert_S = false;
		$db->Update_S = false;
		
		$Table_Name 	= $POST['table_name'];//資料表名字
		$Table_Comment 	= $POST['table_comment'];//資料表註解
		
		if( $db->check_table($Table_Name) ){
			
			$table_info = $db->get_table_info($Table_Name);//取出資料表欄位的詳細的訊息
			
			$table_option_info = $db->get_table_info(Tables_Option_DB);//取出資料表設定欄位的詳細的訊息
			unset($table_option_info['TO_Name']);
			unset($table_option_info['TO_Field']);
		
			if( $POST['exec_action'] == 'save' ){
				
				$db->query("ALTER TABLE " .$Table_Name. " COMMENT = '" .$db->val_check($Table_Comment). "'");//更新資料表註解
				
				//-------------------取資料判斷要新增還刪除-------------------//
				$db->Where = " WHERE TO_Name = '" .$db->val_check($Table_Name). "'";
				$db->query_sql(Tables_Option_DB, 'TO_Field');
				
				$table_option_arr = array();
				while( $row = $db->query_fetch() ){//取出資料欄位設定資料
					
					$table_option_arr[$row['TO_Field']] = $row;
				}
				//-------------------取資料判斷要新增還刪除-------------------//
			
				foreach( $table_info as $key => $val ){
					
					if( !empty($POST[$key]) ){//判斷欄位是否還存在
						
						$db_data = array();
						$db_data['TO_Comment1']			= $POST[$key.'_'.'TO_Comment1'];//欄位註解
						$db_data['TO_Comment2']			= $POST[$key.'_'.'TO_Comment2'];//欄位註解1
						$db_data['TO_InShow']			= $POST[$key.'_'.'TO_InShow'] ? 1 : 0;//欄位內顯示
						$db_data['TO_InEdit']			= $POST[$key.'_'.'TO_InEdit'] ? 1 : 0;//欄位內編輯
						$db_data['TO_OutShow']			= $POST[$key.'_'.'TO_OutShow'] ? 1 : 0;//欄位外顯示
						$db_data['TO_OutEdit']			= $POST[$key.'_'.'TO_OutEdit'] ? 1 : 0;//欄位外編輯
						$db_data['TO_Must']				= $POST[$key.'_'.'TO_Must'] ? 1 : 0;//欄位必填
						$db_data['TO_InType']			= $POST[$key.'_'.'TO_InType'];//欄位種類
						//$db_data['TO_ChkType']		= $POST[$key.'_'.'TO_ChkType'];//欄位種類1
						$db_data['TO_NumOpen']			= $POST[$key.'_'.'TO_NumOpen'] ? 1 : 0;//欄位大小啟用
						$db_data['TO_Max']				= $POST[$key.'_'.'TO_Max'] ? $POST[$key.'_'.'TO_Max'] : 0;//欄位最大值
						$db_data['TO_Min']				= $POST[$key.'_'.'TO_Min'] ? $POST[$key.'_'.'TO_Min'] : 0;//欄位最小值
						$db_data['TO_ConnectField']		= $POST[$key.'_'.'TO_ConnectField'];//相互作用欄位
						$db_data['TO_ConnectField1']	= $POST[$key.'_'.'TO_ConnectField1'];//相互作用欄位1
						$db_data['TO_SelPicSize']		= $POST[$key.'_'.'TO_SelPicSize'];//選擇圖片大小
						$db_data['TO_SelStates']		= $POST[$key.'_'.'TO_SelStates'];//欄位狀態選擇
						$db_data['TO_UploadSize']		= $POST[$key.'_'.'TO_UploadSize'] ? $POST[$key.'_'.'TO_UploadSize'] : 0;//欄位上傳大小
						$db_data['TO_TimeFormat']		= $POST[$key.'_'.'TO_TimeFormat'];//時間格式
						$db_data['TO_Sort']				= $POST[$key.'_'.'TO_Sort'] ? $POST[$key.'_'.'TO_Sort'] : 0;//欄位排序
						
						if( empty($table_option_arr[$key]) ){
							
							$db_data['TO_Name']  	= $Table_Name;
							$db_data['TO_Field'] 	= $key;
							$db->query_data(Tables_Option_DB, $db_data, 'INSERT');
						}else{
							
							$db->Where = " WHERE TO_Name = '" .$db->val_check($Table_Name). "' AND TO_Field = '" .$db->val_check($key). "'";
							$db->query_data(Tables_Option_DB, $db_data, 'UPDATE');
						}
						
						$Field_Comment = $POST[$key.'_Comment'];
						
						$Null = '';
						if( $val['Null'] == 'YES' ){
							
							$Null = 'NULL';	
						}else{
							
							$Null = 'NOT NULL';	
						}
						
						$Default = '';
						if( $val['Default'] != '' ){
							
							$Default = 'DEFAULT ' .$val['Default'];
						}
						
						$db->query("ALTER TABLE " .$Table_Name. " CHANGE " .$key. " " .$key. " " .$val['Type']. " " .$Null. " " .$Default. " " .$val['Extra']. " COMMENT '" .$db->val_check($Field_Comment). "'");//更新資料表欄位註解
						
						$table_info[$key]['Comment'] = $Field_Comment;
					}
				}
			}
			
			$table_option_arr = Get_Table_Info(Tables_Option_DB, 'TO_Field', '', " WHERE TO_Name = '" .$db->val_check($Table_Name). "'");//取出資料欄位設定資料
		}
		
		$TablesArr = $db->get_table_status();//撈出資料表資訊
		
		$Comment = '';
		foreach( $TablesArr as $key => $val ){
			
			if( preg_match('/^sys/i', $val['Name']) ){//跳過系統用資料表
				
				unset($TablesArr[$key]);	
			}else if( $val['Name'] == $Table_Name ){
				
				$Comment = $val['Comment'];				
			}
		}		
		
		ob_start();
		include_once(SYS_PATH.'php_sys'.DIRECTORY_SEPARATOR.'tableoption.php');
		$_html = ob_get_contents();
		ob_end_clean(); 
		
		echo $_html;
	}
	
	function TablesCreate(){
		
		global $POST, $Admin_data;
		
		$db = new MySQL();
			
		$this->tablename	= $POST['tablename'];	//資料表名稱
		$this->tablepre 	= $POST['tablepre'];	//資料表欄位前輟
		$this->tablesuf 	= $POST['tablesuf'];	//資料表欄位後輟
		$this->tablestyle	= $POST['tablestyle'];	//資料表格式
		$this->tab_chks		= $POST['tab_chks'];	//資料表需要的欄位
		$this->tab_radio	= $POST['tab_radio'];	//創建的資料表種類
		$this->tableclass	= $POST['tableclass'];	//資料表分類層數

		//print_r($POST);
		if( !empty($this->tablename) && !empty($this->tablepre) && !empty($this->TS_Style[$this->tablestyle]) ){
			
			if( $this->tablestyle == 'style1' ){
				
				$this->Style($POST);
			}else if( $this->tablestyle == 'style4' ){
				
				//$this->tablesuf = '';//後輟先清空, 會影響到新增資料表後, Sort和Sdate的寫入不了
				
				if( $db->check_table($this->tablename) ){
					
					$this->_html_msg = '資料表 ( ' .$this->tablename. ' ) 已存在';
				}else{
					
					if( $this->tab_radio == 'banner' ){ 
						
						$this->Options->Create_Banner($this->tablename, $this->tablepre, $this->tablesuf);
						
						$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
						
						$field_arr = $this->Get_Tables_Option($this->tab_radio);//取得表格設定檔資料
						
						$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
						
						$this->Add_Menu_Data($this->tab_radio);//創建目錄
					}else if( $this->tab_radio == 'news' || $this->tab_radio == 'newsc' ){
						
						$tablecname = $this->tab_radio == 'newsc' ? $this->tablename.'class' : '';
						$tablecid 	= $this->tab_radio == 'newsc' ? $this->tablepre.'C_ID' : '';
						$tablecpre 	= $this->tab_radio == 'newsc' ? $this->tablepre.'C' : '';
						
						if( $db->check_table($tablecname) && !empty($tablecname) ){
							
							$this->_html_msg = '分類資料表 ( ' .$tablecname. ' ) 已存在';
						}else{
							
							$this->Options->Create_News($this->tablename, $this->tablepre, $this->tablesuf, $tablecid);
							
							$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
							
							$field_arr = $this->Get_Tables_Option('news', $tablecid);//取得表格設定檔資料
							
							$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
							
							$this->Add_Menu_Data('news', '', $this->tableclass, $tablecid, $tablecname, $tablecpre);//創建目錄
							
							if( $this->tab_radio == 'newsc' ){
								
								$this->tablename	= $tablecname;
								$this->tablepre		= $tablecpre;
						
								$this->Options->Create_Class($this->tablename, $this->tablepre);
							
								$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
								
								$field_arr = $this->Get_Tables_Option('class');//取得表格設定檔資料
								
								$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
								
								$this->Add_Menu_Data('class', '消息分類設定', $this->tableclass);//創建目錄
							}
						}
					}else if( $this->tab_radio == 'qanda' || $this->tab_radio == 'qandac' ){ 
						
						$tablecname = $this->tab_radio == 'qandac' ? $this->tablename.'class' : '';
						$tablecid 	= $this->tab_radio == 'qandac' ? $this->tablepre.'C_ID' : '';
						$tablecpre 	= $this->tab_radio == 'qandac' ? $this->tablepre.'C' : '';
						
						if( $db->check_table($tablecname) && !empty($tablecname) ){
							
							$this->_html_msg = '分類資料表 ( ' .$tablecname. ' ) 已存在';
						}else{
						
							$this->Options->Create_QA($this->tablename, $this->tablepre, $this->tablesuf, $tablecid);
							
							$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
							
							$field_arr = $this->Get_Tables_Option('qanda', $tablecid);//取得表格設定檔資料
							
							$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
							
							$this->Add_Menu_Data('qanda', '', $this->tableclass, $tablecid, $tablecname, $tablecpre);//創建目錄
							
							if( $this->tab_radio == 'qandac' ){
								
								$this->tablename	= $tablecname;
								$this->tablepre		= $tablecpre;
						
								$this->Options->Create_Class($this->tablename, $this->tablepre);
							
								$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
								
								$field_arr = $this->Get_Tables_Option('class');//取得表格設定檔資料
								
								$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
								
								$this->Add_Menu_Data('class', '問題分類設定', $this->tableclass);//創建目錄
							}
						}
					}else if( $this->tab_radio == 'product' || $this->tab_radio == 'productc' ){ 
						
						$tablecname = $this->tab_radio == 'productc' ? $this->tablename.'class' : '';
						$tablecid 	= $this->tab_radio == 'productc' ? $this->tablepre.'C_ID' : '';
						$tablecpre 	= $this->tab_radio == 'productc' ? $this->tablepre.'C' : '';
						
						if( $db->check_table($tablecname) && !empty($tablecname) ){
							
							$this->_html_msg = '分類資料表 ( ' .$tablecname. ' ) 已存在';
						}else{
												
							$this->Options->Create_Product($this->tablename, $this->tablepre, $this->tablesuf, $tablecid);
							
							$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
							
							$field_arr = $this->Get_Tables_Option('product', $tablecid);//取得表格設定檔資料				
							
							$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
							
							$this->Add_Menu_Data('product', '', $this->tableclass, $tablecid, $tablecname, $tablecpre);//創建目錄
							
							if( $this->tab_radio == 'productc' ){
								
								$this->tablename	= $tablecname;
								$this->tablepre		= $tablecpre;
						
								$this->Options->Create_Class($this->tablename, $this->tablepre);
							
								$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
								
								$field_arr = $this->Get_Tables_Option('class');//取得表格設定檔資料
								
								$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
								
								$this->Add_Menu_Data('class', '產品分類設定', $this->tableclass);//創建目錄
							}
						}
					}else if( $this->tab_radio == 'albums' || $this->tab_radio == 'albumsc' ){
						
						$this->tablename1	= $this->tablename.'l';
						$this->tablepre1	= $this->tablepre.'l';
						
						if( $db->check_table($this->tablename1) ){
							
							$this->_html_msg = '擴充資料表 ( ' .$this->tablename1. ' ) 已存在';
						}else{
							
							$tablecname = $this->tab_radio == 'albumsc' ? $this->tablename.'class' : '';
							$tablecid 	= $this->tab_radio == 'albumsc' ? $this->tablepre.'C_ID' : '';
							$tablecpre 	= $this->tab_radio == 'albumsc' ? $this->tablepre.'C' : '';
							
							if( $db->check_table($tablecname) && !empty($tablecname) ){
								
								$this->_html_msg = '分類資料表 ( ' .$tablecname. ' ) 已存在';
							}else{
							
								$this->Options->Create_Albums($this->tablename, $this->tablepre, $this->tablesuf, $this->tablename1, $this->tablepre1, $tablecid);
								
								$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
								
								$field_arr = $this->Get_Tables_Option('albums', $tablecid);//取得表格設定檔資料
															
								$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
								
								$this->Clean_Tables_Option($this->tablename1);//清除表格設定檔資料
								
								$field_arr = $this->Get_Tables_Option('albumsl');//取得表格設定檔資料
								
								$this->Set_Table_Option($this->tablename1, $field_arr);//設定欄位的資料
								
								$this->Add_Menu_Data('albums', '', $this->tableclass, $tablecid, $tablecname, $tablecpre);//創建目錄
								
								if( $this->tab_radio == 'albumsc' ){
									
									$this->tablename	= $tablecname;
									$this->tablepre		= $tablecpre;
							
									$this->Options->Create_Class($this->tablename, $this->tablepre);
								
									$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
									
									$field_arr = $this->Get_Tables_Option('class');//取得表格設定檔資料
									
									$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
									
									$this->Add_Menu_Data('class', '相簿分類設定', $this->tableclass);//創建目錄
								}
							}
						}
					}else if( $this->tab_radio == 'member' ){ 
						
						$this->Options->Create_Member($this->tablename, $this->tablepre, $this->tablesuf);
						
						$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
						
						$field_arr = $this->Get_Tables_Option($this->tab_radio);//取得表格設定檔資料
						
						$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
						
						$this->Add_Menu_Data($this->tab_radio);//創建目錄
					}else if( $this->tab_radio == 'message' ){ 
						
						$this->Options->Create_Message($this->tablename, $this->tablepre, $this->tablesuf);
						
						$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
						
						$field_arr = $this->Get_Tables_Option($this->tab_radio);//取得表格設定檔資料
						
						$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
						
						$this->Add_Menu_Data($this->tab_radio);//創建目錄
					}else if( $this->tab_radio == 'delivery' ){ 
					
						$this->Options->Create_Delivery($this->tablename, $this->tablepre, $this->tablesuf);
						
						$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
						
						$field_arr = $this->Get_Tables_Option($this->tab_radio);//取得表格設定檔資料
						
						$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
						
						$this->Add_Menu_Data($this->tab_radio, '', $this->tableclass);//創建目錄
					}else if( $this->tab_radio == 'class' ){ 
					
						$this->Options->Create_Class($this->tablename, $this->tablepre, $this->tablesuf);
						
						$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
						
						$field_arr = $this->Get_Tables_Option($this->tab_radio);//取得表格設定檔資料
						
						$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
						
						$this->Add_Menu_Data($this->tab_radio, '', $this->tableclass);//創建目錄
					}else{
						
						$this->_html_msg = '無此創建資料';
					}
					
					if( empty($this->_html_msg) ){
						
						$this->_html_msg	= '創建完畢';
						$this->_html_eval 	= 'Reload()';
					}
				}
			}else{
				
				$this->_html_msg = '操作錯誤';
			}
			
			$this->JsonOutput();//輸出json;
		}else if( !empty($this->tablename) ){
						
			if( $db->check_table($this->tablename) ){
				
				$this->_html_content = '資料表新增修改欄位的設定';
			}else{
				
				$this->_html_content = '資料表創建';
			}
			
			$this->JsonOutput();//輸出json;
		}else{
		
			ob_start();
			include_once(SYS_PATH.'php_sys'.DIRECTORY_SEPARATOR.'tablecreate.php');
			$_html = ob_get_contents();
			ob_end_clean(); 
			
			echo $_html;
		}
	}
	
	function Style( $POST ){//一般格式
		
		$db = new MySQL();
		
		if( empty($this->tablename) ){
			
			$this->_html_msg = '請填寫資料表名稱';
		}else if( empty($this->tablepre) ){
			
			$this->_html_msg = '請填寫資料表前輟';
		}else if( empty($this->tab_chks) ){
			
			$this->_html_msg = '請選擇要( 創建 && 新增 ) 的資料欄位';
		}else{
			
			$field_arr = array();
						
			if( $db->check_table($this->tablename) ){
				
				$table_info = $db->get_table_info($this->tablename, 'Field');//取出資料表欄位的詳細的訊息
				
				$table_option_arr = Get_Table_Info(Tables_Option_DB, 'TO_Field', 'TO_Field', " WHERE TO_Name = '" .$db->val_check($this->tablename). "'");//取出資料欄位設定資料
				
				foreach( $this->tab_chks as $field ){
					
					$key = $this->tablepre.'_'.$field.$this->tablesuf;
					if( !empty($table_option_arr[$key]) ){
						
						$this->Clean_Tables_Option($this->tablename, $key);//清除表格設定檔資料
					}
					
					if(	empty($table_info[$key]) ){//沒有該欄位
						
						//新建可自行調整連結跳轉屬性
						if( $field == 'Link' ) {
						
							$sql = 'ALTER TABLE ' .$this->tablename. ' ADD ' .$this->tablepre.'_'.$field.$this->tablesuf.'T'.$this->Options->Field_Setting['LinkT'];
							$db->query($sql);//創建欄位
						}
						
						$sql = 'ALTER TABLE ' .$this->tablename. ' ADD ' .$key.$this->Options->Field_Setting[$field];
						$db->query($sql);//創建欄位
					}
					
					$field_arr[$field][] = $key;
				}
				
				$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料

				$this->_html_msg = '新增修改資料表欄位的設定完成';
			}else{
				
				$sql_arr = array();
				
				$PRIMARY = $this->tablepre.'_ID'.$this->tablesuf;
				
				$sql  = 'CREATE TABLE ' .$this->tablename. ' ( ';
				
				$field_arr['ID'][] 	= $PRIMARY;
				$sql_arr[] 			= $PRIMARY.$this->Options->Field_Setting['ID'];
				foreach( $this->tab_chks as $field ){
					
					$field_key				= $this->tablepre.'_'.$field.$this->tablesuf;
					$field_arr[$field][]	= $field_key;
					$sql_arr[]				= $field_key.$this->Options->Field_Setting[$field];
				}
				/*$field_arr['Sort'] 	= $tablepre.'_Sort'.$tablesuf;
				$sql_arr[] 			= $tablepre.'_Sort'.$tablesuf.$this->Options->Field_Setting['Sort'];
				$field_arr['Sdate']	= $tablepre.'_Sdate'.$tablesuf;
				$sql_arr[] 			= $tablepre.'_Sdate'.$tablesuf.$this->Options->Field_Setting['Sdate'];*/
				
				$sql .= implode(', ', $sql_arr);

				$sql .= ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
				
				$db->query($sql);//創建資料表
				
				if( empty($db->Error_Old) ){
					
					$sql = 'ALTER TABLE ' .$this->tablename. ' ADD PRIMARY KEY (' .$PRIMARY. ');';
					$db->query($sql);//設定主鍵
					
					$sql = 'ALTER TABLE ' .$this->tablename. ' CHANGE ' .$PRIMARY. ' ' .$PRIMARY. ' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;';
					$db->query($sql);//設定自動排序
					
					$this->Clean_Tables_Option($this->tablename);//清除表格設定檔資料
										
					$this->Set_Table_Option($this->tablename, $field_arr);//設定欄位的資料
					
					$this->_html_msg = '創建資料表完成';
				}
			}
			
			$this->_html_eval = 'Reload()';
		}
	}	
	
	function Add_Menu_Data( $Type, $Name = '', $ClassMax = 1, $tablecid = '', $tablecname = '', $tablecpre = '' ){
		
		global $Group_MenuUse;
		
		if( !isset($Group_MenuUse) ){//建立後台時宣告群組權限用
			
			$Group_MenuUse = array();
		}
				
		$db = new MySQL();
		
		$Menu_Table = DB_DataBase.'.'.Menu_DB;
		
		$db->Where = " WHERE Menu_Exec LIKE 'web_menu'";
		$db->query_sql($Menu_Table, "Menu_ID");
		$Menu_ID = $db->query_fetch('Menu_ID');
		if( empty($Menu_ID) ){
			
			$SN = date('Ymd');
			$SN = substr($SN, -6);
			$Menu_ID = GET_NEW_ID($Menu_Table, 'Menu_ID', $SN, 3);
			
			$db_data = array('Menu_ID' => $Menu_ID, 'Menu_Name' => '前台管理', 'Menu_Lv' => 1, 'Menu_Exec' => 'web_menu', 'Menu_Smallpic' => 'fa-th-list');
			
			$db->query_data($Menu_Table, $db_data, 'INSERT');
		}
		
		$Group_MenuUse[] = $Menu_ID;
		
		$SN 	= date('Ymd');
		$SN 	= substr($SN, -6);
		$NEW_ID = GET_NEW_ID($Menu_Table, 'Menu_ID', $SN, 3);
		
		$tableid  = $this->tablepre.'_ID'.$this->tablesuf;
			
		$db = new MySQL();
		
		$db_data = array();
		if( strtolower($Type) == 'banner' ){
			
			$Name = $Name ? $Name : '廣告輪播設定';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'table', 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
		}else if( strtolower($Type) == 'news' ){
			
			$Name = $Name ? $Name : '最新消息設定';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'table', 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
		}else if( strtolower($Type) == 'qanda' ){
			
			$Name = $Name ? $Name : '常見問題設定';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'table', 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
		}else if( strtolower($Type) == 'product' ){
			
			$Name = $Name ? $Name : '產品資料設定';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'table', 'Menu_CstSnPre' => 'P', 'Menu_CstSnType' => 'YYMMDD', 'Menu_CstSnNum' => 4, 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
		}else if( strtolower($Type) == 'albums' ){
			
			$Name = $Name ? $Name : '相簿資料設定';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_TableName1' => $this->tablename1, 'Menu_TableKey1' => $this->tablepre1.'_ID'.$this->tablesuf, 'Menu_TablePre1' => $this->tablepre1, 'Menu_Mode' => 3, 'Menu_Model' => 'albums', 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1, 'Menu_Albums_Edt' => 1, 'Menu_Albums_Mpc' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
		}else if( strtolower($Type) == 'member' ){
			
			$Name = $Name ? $Name : '會員資料管理';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'table', 'Menu_CstSnPre' => 'M', 'Menu_CstSnType' => 'YYMMDD', 'Menu_CstSnNum' => 4, 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1, 'Menu_View' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
			$Group_MenuUse[] = $NEW_ID.'_4';
		}else if( strtolower($Type) == 'message' ){
			
			$Name = $Name ? $Name : '聯絡我們';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'message', 'Menu_Del' => 1, 'Menu_View' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_3';
			$Group_MenuUse[] = $NEW_ID.'_4';
		}else if( strtolower($Type) == 'delivery' ){
			
			$Name = $Name ? $Name : '付款與運費設定';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'table', 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
		}else if( strtolower($Type) == 'class' ){
			
			$Name = $Name ? $Name : '分類設定';
			
			$db_data = array('Menu_ID' => $NEW_ID, 'Menu_Name' => $Name, 'Menu_Lv' => 2, 'Menu_UpMID' => $Menu_ID, 'Menu_TableName' => $this->tablename, 'Menu_TableKey' => $tableid, 'Menu_TablePre' => $this->tablepre, 'Menu_Mode' => 3, 'Menu_Model' => 'class', 'Menu_CstSnPre' => substr($this->tablepre, 0, 2), 'Menu_CstSnNum' => 4, 'Menu_Add' => 1, 'Menu_Edt' => 1, 'Menu_Del' => 1);
			
			$Group_MenuUse[] = $NEW_ID;
			$Group_MenuUse[] = $NEW_ID.'_1';
			$Group_MenuUse[] = $NEW_ID.'_2';
			$Group_MenuUse[] = $NEW_ID.'_3';
		}
		
		if( !empty($tablecid) && !empty($tablecname) && !empty($tablecpre) ){
			
			$db_data['Menu_Mode'] 		= 3;
			$db_data['Menu_TableName2'] = $tablecname;
			$db_data['Menu_TableKey2']	= $tablecid;
			$db_data['Menu_TablePre2'] 	= $tablecpre;
		}
			
		$db_data['Menu_ClassMax'] = $ClassMax;
			
		if( !empty($db_data) ){
			
			$db->query_data($Menu_Table, $db_data, 'INSERT');
		}
	}

	function Get_Tables_Option( $Type, $tablecid = '' ){
		
		$field_arr = array();
		if( strtolower($Type) == 'banner' ){
			
			$field_arr['Title'][]	= $this->tablepre.'_Title'.$this->tablesuf;
			$field_arr['Mcp'][]		= $this->tablepre.'_Mcp'.$this->tablesuf;
			$field_arr['Keyword'][]	= $this->tablepre.'_Keyword'.$this->tablesuf;
			$field_arr['Sort'][]	= $this->tablepre.'_Sort'.$this->tablesuf;
			$field_arr['Open'][]	= $this->tablepre.'_Open'.$this->tablesuf;
			$field_arr['Sdate'][]	= $this->tablepre.'_Sdate'.$this->tablesuf;
		}else if( strtolower($Type) == 'news' ){
			
			$field_arr['Title'][]	= $this->tablepre.'_Title'.$this->tablesuf;
			$field_arr['Content'][]	= $this->tablepre.'_Content'.$this->tablesuf;
			$field_arr['Mcp'][]		= $this->tablepre.'_Mcp'.$this->tablesuf;
			$field_arr['Keyword'][]	= $this->tablepre.'_Keyword'.$this->tablesuf;
			$field_arr['Sort'][]	= $this->tablepre.'_Sort'.$this->tablesuf;
			$field_arr['Open'][]	= $this->tablepre.'_Open'.$this->tablesuf;
			$field_arr['Sdate'][]	= $this->tablepre.'_Sdate'.$this->tablesuf;
			
			if( !empty($tablecid) ){
				
				$field_arr['Class'][] = $tablecid;
			}
		}else if( strtolower($Type) == 'qanda' ){
			
			$field_arr['Title'][]	= $this->tablepre.'_Title'.$this->tablesuf;
			$field_arr['Content'][]	= $this->tablepre.'_Content'.$this->tablesuf;
			$field_arr['Keyword'][]	= $this->tablepre.'_Keyword'.$this->tablesuf;
			$field_arr['Sort'][]	= $this->tablepre.'_Sort'.$this->tablesuf;
			$field_arr['Open'][]	= $this->tablepre.'_Open'.$this->tablesuf;
			$field_arr['Sdate'][]	= $this->tablepre.'_Sdate'.$this->tablesuf;
			
			if( !empty($tablecid) ){
				
				$field_arr['Class'][] = $tablecid;
			}
		}else if( strtolower($Type) == 'product' ){
			
			$field_arr['proID'][] 		= $this->tablepre.'_ID'.$this->tablesuf;
			$field_arr['proName'][]		= $this->tablepre.'_Name'.$this->tablesuf;
			$field_arr['proIntro'][]	= $this->tablepre.'_Intro'.$this->tablesuf;
			$field_arr['proContent'][]	= $this->tablepre.'_Content'.$this->tablesuf;
			$field_arr['proUnit'][]		= $this->tablepre.'_Unit'.$this->tablesuf;
			$field_arr['proPrice'][]	= $this->tablepre.'_Price'.$this->tablesuf;
			$field_arr['proPrice1'][]	= $this->tablepre.'_Price1'.$this->tablesuf;
			$field_arr['proMcp'][]		= $this->tablepre.'_Mcp'.$this->tablesuf;
			$field_arr['proImg'][]		= $this->tablepre.'_Img1'.$this->tablesuf;
			$field_arr['proImg'][]		= $this->tablepre.'_Img2'.$this->tablesuf;
			$field_arr['proImg'][]		= $this->tablepre.'_Img3'.$this->tablesuf;
			$field_arr['proImg'][]		= $this->tablepre.'_Img4'.$this->tablesuf;
			$field_arr['Keyword'][]	= $this->tablepre.'_Keyword'.$this->tablesuf;
			$field_arr['proSort'][]		= $this->tablepre.'_Sort'.$this->tablesuf;
			$field_arr['proOpenNew'][]	= $this->tablepre.'_OpenNew'.$this->tablesuf;
			$field_arr['proOpenHot'][]	= $this->tablepre.'_OpenHot'.$this->tablesuf;
			$field_arr['proOpen'][]		= $this->tablepre.'_Open'.$this->tablesuf;
			$field_arr['Sdate'][] 		= $this->tablepre.'_Sdate'.$this->tablesuf;
			
			if( !empty($tablecid) ){
				
				$field_arr['proClass'][] = $tablecid;
			}
		}else if( strtolower($Type) == 'albums' ){
			
			$field_arr['Title'][]	= $this->tablepre.'_Title'.$this->tablesuf;
			$field_arr['Intro'][]	= $this->tablepre.'_Intro'.$this->tablesuf;
			$field_arr['Sort'][]	= $this->tablepre.'_Sort'.$this->tablesuf;
			$field_arr['Open'][]	= $this->tablepre.'_Open'.$this->tablesuf;
			$field_arr['Sdate'][]	= $this->tablepre.'_Sdate'.$this->tablesuf;
			$field_arr['albMcp'][]	= $this->tablepre.'_Mcp'.$this->tablesuf;
			$field_arr['albQty'][]	= $this->tablepre.'_Qty'.$this->tablesuf;
			
			if( !empty($tablecid) ){
				
				$field_arr['Class'][] = $tablecid;
			}
		}else if( strtolower($Type) == 'albumsl' ){
			
			$field_arr['Title'][]		= $this->tablepre1.'_Title'.$this->tablesuf;
			$field_arr['Intro'][]		= $this->tablepre1.'_Intro'.$this->tablesuf;
			$field_arr['alblSort'][]	= $this->tablepre1.'_Sort'.$this->tablesuf;
			$field_arr['Open'][]		= $this->tablepre1.'_Open'.$this->tablesuf;
			$field_arr['Sdate'][]		= $this->tablepre1.'_Sdate'.$this->tablesuf;
			$field_arr['alblImg'][]		= $this->tablepre1.'_Img'.$this->tablesuf;
		}else if( strtolower($Type) == 'member' ){
			
			$field_arr['memID'][]			= $this->tablepre.'_ID'.$this->tablesuf;
			$field_arr['memAcc'][]			= $this->tablepre.'_Acc'.$this->tablesuf;
			$field_arr['memPwd'][]			= $this->tablepre.'_Pwd'.$this->tablesuf;
			$field_arr['memEmail'][]		= $this->tablepre.'_Email'.$this->tablesuf;
			$field_arr['memName'][]			= $this->tablepre.'_Name'.$this->tablesuf;
			$field_arr['memSex'][]			= $this->tablepre.'_Sex'.$this->tablesuf;
			$field_arr['memBirthday'][]		= $this->tablepre.'_Birthday'.$this->tablesuf;
			$field_arr['memTel'][]			= $this->tablepre.'_Tel'.$this->tablesuf;
			$field_arr['memMobile'][]		= $this->tablepre.'_Mobile'.$this->tablesuf;
			$field_arr['memFax'][] 			= $this->tablepre.'_Fax'.$this->tablesuf;
			$field_arr['memCompany'][]		= $this->tablepre.'_Company'.$this->tablesuf;
			$field_arr['memUniform'][]		= $this->tablepre.'_Uniform'.$this->tablesuf;
			$field_arr['memCity'][]			= $this->tablepre.'_City'.$this->tablesuf;
			$this->Options->Field_Option['memCity']['TO_ConnectField']	= $this->tablepre.'_County'.$this->tablesuf;
			$this->Options->Field_Option['memCity']['TO_ConnectField1'] = $this->tablepre.'_Zipcode'.$this->tablesuf;
			$field_arr['memCounty'][]		= $this->tablepre.'_County'.$this->tablesuf;
			//$field_arr['memZipcode'][]		= $this->tablepre.'_Zipcode'.$this->tablesuf;
			$field_arr['memAddress'][]		= $this->tablepre.'_Address'.$this->tablesuf;
			$this->Options->Field_Option['memAddress']['TO_ConnectField1'] = $this->tablepre.'_Zipcode'.$this->tablesuf;
			$field_arr['memIntro'][]		= $this->tablepre.'_Intro'.$this->tablesuf;
			$field_arr['memSdate'][]		= $this->tablepre.'_Sdate'.$this->tablesuf;
			$field_arr['memEdate'][]		= $this->tablepre.'_Edate'.$this->tablesuf;
			$field_arr['memLastLogin'][]	= $this->tablepre.'_LastLogin'.$this->tablesuf;
			$field_arr['memOpen'][]			= $this->tablepre.'_Open'.$this->tablesuf;
			$field_arr['memEmailauth'][]	= $this->tablepre.'_Emailauth'.$this->tablesuf;	
		}else if( strtolower($Type) == 'message' ){
			
			$field_arr['msgName'][]		= $this->tablepre.'_Name'.$this->tablesuf;
			$field_arr['msgTel'][] 		= $this->tablepre.'_Tel'.$this->tablesuf;
			$field_arr['msgMobile'][]	= $this->tablepre.'_Mobile'.$this->tablesuf;
			$field_arr['msgEmail'][]	= $this->tablepre.'_Email'.$this->tablesuf;
			$field_arr['msgAddress'][]	= $this->tablepre.'_Address'.$this->tablesuf;
			$field_arr['msgTitle'][]	= $this->tablepre.'_Title'.$this->tablesuf;
			$field_arr['msgContent'][] 	= $this->tablepre.'_Content'.$this->tablesuf;
			$field_arr['msgSdate'][]	= $this->tablepre.'_Sdate'.$this->tablesuf;
		}else if( strtolower($Type) == 'delivery' ){
			
			$field_arr['dlvName'][]		= $this->tablepre.'_Name'.$this->tablesuf;
			$field_arr['dlvPrice'][]	= $this->tablepre.'_Price'.$this->tablesuf;
			$field_arr['dlvFree'][]		= $this->tablepre.'_Free'.$this->tablesuf;
			$field_arr['Sort'][]		= $this->tablepre.'_Sort'.$this->tablesuf;
			$field_arr['Open'][]		= $this->tablepre.'_Open'.$this->tablesuf;
			$field_arr['Sdate'][]		= $this->tablepre.'_Sdate'.$this->tablesuf;
		}else if( strtolower($Type) == 'class' ){
			
			$field_arr['cName'][] = $this->tablepre.'_Name'.$this->tablesuf;
			$field_arr['cSort'][] = $this->tablepre.'_Sort'.$this->tablesuf;
			$field_arr['cOpen'][] = $this->tablepre.'_Open'.$this->tablesuf;
		}
		
		return $field_arr;
	}
	
	function Set_Table_Option( $Sheet, $Filed_Arr ){//設定欄位的資料
		
		$db = new MySQL();
					
		foreach( $Filed_Arr as $fkey => $fdata ){
			
			foreach( $fdata as $key => $field ){
				
				$db_data = array('TO_Name' => $Sheet, 'TO_Field' => $field);
			 
				$Field_Option = $this->Options->Field_Option[$fkey];
				if( !empty($Field_Option) ){
					
					foreach( $Field_Option as $_field => $_val ){
						
						$db_data[$_field] = $_val;
					}
					
					$db->query_data(Tables_Option_DB, $db_data, 'INSERT');
				}
			}
		}
	}
	
	function Clean_Tables_Option( $Sheet, $Field = '' ){
			
		$db = new MySQL();
		
		$db->Where = " WHERE TO_Name = '" .$db->val_check($Sheet). "'";//刪除該資料表設定
		
		if( !empty($Field) ){
			
			$db->Where .= " AND TO_Field = '" .$db->val_check($Field). "'";
		}									
	
		$db->query_delete(Tables_Option_DB);
		
		$db->query_optimize(Tables_Option_DB);//最佳化資料表
	}
			
	function JsonOutput(){
				
		$json_array = array();
		
		$json_array['html_msg']     = $this->_html_msg ? $this->_html_msg : '';
		$json_array['html_href']    = $this->_html_href ? $this->_html_href : '';
		$json_array['html_content'] = $this->_html_content ? $this->_html_content : '';
		$json_array['html_boxtype'] = $this->_html_boxtype ? $this->_html_boxtype : '1';
		$json_array['html_eval']    = $this->_html_eval ? $this->_html_eval : '';
		
		echo json_encode( $json_array );
	}
}
?>