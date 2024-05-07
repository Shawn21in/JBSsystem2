<?php
/*---------------------------------------------------------------------------------//
Mysql - v.16.7
//---------------------------------------------------------------------------------*/

class MySQL{
	
	var $SML_Table_Name = 'sys_mysql_log';
	
	var $Show_Sql   = true;		//TRUE顯示SQL資料和錯誤:FALSE不顯示SQL資料和錯誤
	var $Error_Save	= true;		//TRUE紀錄SQL錯誤:FALSE不紀錄SQL錯誤
	var $Save_Sql   = false;	//TRUE紀錄SQL資料:FALSE不紀錄SQL資料
	var $Create_SML = false;	//紀錄是否已經創建SQL資料表
	var $Where_TF	= true;		//TRUE代表一定要輸入WHERE 字串
	var $Insert_S	= true;		//TRUE紀錄新增SQL資料
	var $Update_S   = true;		//TRUE紀錄更新SQL資料
	var $Delete_S   = true;		//TRUE紀錄刪除SQL資料
	var $Error_Html = true;		//TRUE顯示錯誤頁面
	
	var $Link_DB	= '';		//選擇資料庫
	var $sql		= '';		//執行的SQL
	var $Where		= '';		//WHERE 字串
	var $Order_By	= '';		//ORDER BY 字串 
	var $Group_By	= '';		//GROUP BY 字串 
	var $Search	    = array();	//設定可搜尋欄位
	var $result		= '';		//SQL執行後的資料
	var $Error		= '';		//SQL錯誤紀錄
	var $Error_Old	= '';		//如果有開啟錯誤紀錄，當發生錯誤會記錄到log資料表然後會把將原本錯誤紀錄覆蓋過去，所以再覆蓋前記錄在這
	var $num_row	= '';		//SQL執行後的資料筆數
	var $filed_str	= '';		//串接欄位
	var $value_str	= '';		//串接值
	
	function __construct(){
		
		if( !isset($_SESSION) ){
			
			session_start();
		}
		
		$this->Path = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
		
		$this->Connect_SQL(DB_Host, DB_UserName, DB_PassWord, DB_DataBase);
		
		if( stripos($_SERVER['REQUEST_URI'], "system") ){
			
			$this->Url  = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, stripos($_SERVER['REQUEST_URI'], "system")).'system/';
		}else{
			
			$Request 	= str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['REQUEST_URI']);
			$Request 	= str_replace($_SERVER['QUERY_STRING'], '', $Request);
			$this->Url  = 'http://'.$_SERVER['HTTP_HOST'].$Request.'system/';
		}
	}
	
	function Connect_SQL( $Host, $UserName, $PassWord, $DataBase, $Set = "SET NAMES 'UTF8'" ){
		
		//$this->Link_DB = @mysql_connect( $Host, $UserName, $PassWord ) or die( include($this->Path.'html' .DIRECTORY_SEPARATOR. 'error.html') );
		
		$this->Link_DB = mysqli_connect( $Host, $UserName, $PassWord , $DataBase);
		
		if( !$this->Link_DB && $this->Error_Html ){
			
			$this->Error = mysqli_connect_errno();
			die( include($this->Path.'html' .DIRECTORY_SEPARATOR. 'error.html') );
		}
		
		$this->Select_DB($DataBase, $this->Link_DB, $Set);
	}
	
	function Select_DB( $DataBase, $Link_DB = '', $Set = "SET NAMES 'UTF8'" ){
		
		if( !empty($Link_DB ) ){
			
			if( mysqli_select_db( $Link_DB , $DataBase) ){
				
				mysqli_query( $Link_DB, $Set );
			}else if( $this->Error_Html ){
				
				die( include($this->Path.'html' .DIRECTORY_SEPARATOR. 'error.html') );
			}

		}else{
			
			if( mysqli_select_db( $this->Link_DB , $DataBase ) ){
				
				mysqli_query( $this->Link_DB, $Set );
			}else if( $this->Error_Html ){
				
				die( include($this->Path.'html' .DIRECTORY_SEPARATOR. 'error.html') );
			}
		}
	}
	
	function query( $sql, $Method = '' ){
		
		$this->sql    = $sql;
		$this->result = mysqli_query( $this->Link_DB, $this->sql );
		$this->Error  = mysqli_error( $this->Link_DB );
		
		$this->Show_fun();
		
		if( !empty($_SESSION['system']['admin_name']) ) {
		
			$admin_name = $_SESSION['system']['admin_name'];
		}
		
		if( !empty($_SESSION['system']['admin_id']) ) {
		
			$admin_id	= $_SESSION['system']['admin_id'];
		}
		
		
		if( $Method == 'INSERT' ){
			
			$this->insert_id = '';	
		}
		
		if( $this->Error_Save && $this->Error && $Type != 'SDATA' ){
			
			$this->Error_Old = $this->Error;
			
			$db = new MySQL();
			$db->SSD( $admin_name, $admin_id, $this->Error, $sql, 'ERROR', $_SERVER['REQUEST_URI'] );	
		}else if( $this->Insert_S && $Method == 'INSERT' ){
			
			$this->query_insert_id();
			$this->SSD( $admin_name, $admin_id, '', $sql, 'INSERT', $_SERVER['REQUEST_URI'] );
		}else if( $this->Update_S && $Method == 'UPDATE' ){
			
			$this->SSD( $admin_name, $admin_id, '', $sql, 'UPDATE', $_SERVER['REQUEST_URI'] );
		}else if( $this->Delete_S && $Method == 'DELETE' ){
			
			$this->SSD( $admin_name, $admin_id, '', $sql, 'DELETE', $_SERVER['REQUEST_URI'] );
		}
	}
	
	function query_sql( $Sheet, $Columns = '*', $StartNum = 0, $Num = 0 ){
		
		$LIMIT = '';	
		if( $StartNum >= 0 && $Num > 0 ){
			
			$LIMIT = 'LIMIT ' .$StartNum. ', ' .$Num;
		}
		
		$sql = 'SELECT ' .$Columns. ' FROM ' .$Sheet. ' ' .$this->Where. ' ' .$this->Group_By. ' ' .$this->Order_By. ' ' .$LIMIT. ';'; 
		
		$this->query( $sql );	
	}

	function query_rows(){
		
		$this->num_row = mysqli_num_rows( $this->result );
		$this->Error   = mysqli_error( $this->Link_DB );
		
		$this->Show_fun();
		
		return $this->num_row;	
	}
	
	function query_fetch( $Field = '', $Type = '' ){
		
		if( $Type == 'row' ){
			
			$row		= mysqli_fetch_row( $this->result );
		}else if( $Type == 'assoc' ){
			
			$row		= mysqli_fetch_assoc( $this->result );
		}else{
			
			$row		= mysqli_fetch_array( $this->result );
		}
		
		$this->Error 	= mysqli_error( $this->Link_DB );
		
		if( !empty($Field) ){
			
			$row = $row[$Field];
		}
		
		$this->Show_fun();
		
		return $row;		
	}
	
	function query_insert( $Sheet, $FILED = array(), $VALUE = array(), $Method = 'DATA' ){
		
		$this->filed_str = $this->value_str = '';
		
		foreach( $VALUE as $key => $val ){
			
			$val = trim( $val );
			$this->value_str .= preg_match( "/^now()/i", $val) ? $val."," : "'" .$this->val_check($val). "',";
			$fil = trim( $FILED[$key] );
			$this->filed_str .= "`" .$fil. "`,";
		}
		
		$this->filed_str = substr( $this->filed_str, 0, -1 );
		$this->value_str = substr( $this->value_str, 0, -1 );
		
		$sql = "INSERT INTO " .$Sheet. " (" .$this->filed_str. ") VALUES (" .$this->value_str. ");";
		
		if( $Method == 'SDATA' ){
			
			$this->query( $sql, 'SDATA' );
		}else{
			
			$this->query( $sql, 'INSERT' );
		}		
	}
	
	function query_update( $Sheet, $FILED = array(), $VALUE = array(), $WHERE = '', $Method = 'DATA' ){
		
		$UPDATE = '';
		
		if( empty($WHERE) ){
			
			$WHERE = $this->Where;
		}
		
		if( !$this->Where_TF || !empty($WHERE) ){
			
			foreach( $VALUE as $key => $val ){
			
				$val = trim( $val );
				$val = preg_match( "/^now()/i", $val) ? $val : "'" .$this->val_check($val). "'";
				$fil = trim( $FILED[$key] );
				$UPDATE .= "`" .$fil. "` = " .$val. ",";
			}
			
			$UPDATE = substr($UPDATE, 0, -1);		
			
			$sql = "UPDATE " .$Sheet. " SET ".$UPDATE." " .$WHERE. ";"; 
		}else{
			
			$this->Error = 'Update Not ( WHERE ) String';
			
			$this->Show_fun();
			
			return false;
		}
		
		$this->query( $sql, 'UPDATE' );	
	}
	
	function query_delete( $Sheet ){
		
		if( !$this->Where_TF || !empty($this->Where) ){
			
			$sql = "DELETE FROM " .$Sheet. " " .$this->Where. ";";
		}else{
			
			$this->Error = 'Delete Not ( WHERE ) String';
			
			$this->Show_fun();
			
			return false;
		}
		
		$this->query( $sql, 'DELETE' );	
	}
	
	function query_optimize( $Sheet ){//最佳化資料表
		
		$sql = 'OPTIMIZE TABLE `' .$Sheet. '`';
		
		$this->query( $sql, 'OPTIMIZE' );	
	}
	
	function query_data( $Sheet, $Data, $Type ){
		
		$Type = strtoupper($Type);
		
		$field = $value = array();
		foreach( $Data as $key => $val ){
			
			$field[] = $key;
			$value[] = $val;
		}
		
		if( $Type == 'UPDATE' ){
			
			$this->query_update($Sheet, $field, $value, $this->Where);
		}else if( $Type == 'INSERT' ){
			
			$this->query_insert($Sheet, $field, $value);
		}else{
			
			$this->Error = 'Please select Type ( UPDATE or INSERT )';	
		}
		
		$this->Show_fun();
	}
	
	function query_count( $Sheet, $Columns = '*' ){//計算筆數
			
		$sql = 'SELECT COUNT( ' .$Columns. ' ) as COUNT FROM ' .$Sheet. ' ' .$this->Where. ' ' .$this->Group_By. ';'; 
		
		$this->query( $sql );
		
		$row = $this->query_fetch();
		
		return $row['COUNT'];	
	}
	
	function query_insert_id(){//取出剛新增的ID
		
		if( empty($this->insert_id) ){
			
			$this->insert_id = mysqli_insert_id( $this->Link_DB );
		}
		
		return $this->insert_id;
	}
	
	function query_search( $SearchKey = '' ){//串接搜尋子句
				
		if( !empty($SearchKey) && !empty($this->Search) ){	
			
			foreach( $this->Search as $key => $val ){
				
				if( empty($this->Where) ){
					
					$this->Where  = " WHERE ( " .$val. " LIKE BINARY '%" .$this->val_check($SearchKey). "%'";
				}else{
					
					if( $key == 0 ){
						
						$this->Where .= " AND ( " .$val. " LIKE BINARY '%" .$this->val_check($SearchKey). "%'";
					}else{
						
						$this->Where .= " OR " .$val. " LIKE BINARY '%" .$this->val_check($SearchKey). "%'";
					}
				}
			}
			
			$this->Where .= " )";
		}
	}
	
	function query_concat_search( $SearchKey = array() , $Search_Field = '' ){//串接搜尋子句
				
		if( !empty($SearchKey)  ){	
			
			if( empty($db->Where) ) {
				
				$rs = "WHERE CONCAT(";
			}else{
				$rs = " AND CONCAT(";
			}
			
			$Sn = 0;
			
			foreach( $SearchKey as $fields ){
				if( $Sn == 0 ){
					
					$rs .= "IFNULL(`".$fields."`,'')";
				}else{
					
					$rs .= " ,IFNULL(`".$fields."`,'')";
				}
				$Sn++;
			}
			
			$rs .= " ) LIKE '%".$Search_Field."%'";
			
			
			return $rs;
		}
	}
	
	function get_use_table(){
		
		$sql = 'SELECT DATABASE()'; 
		
		$this->query($sql);
		
		$database = $this->query_fetch('DATABASE()');
		
		return $database;
	}
	
	function get_tables(){
		
		$sql = 'SHOW TABLES'; 
		
		$this->query($sql);
		
		$Arr = array();
		while( $row = $this->query_fetch() ){
				
			$Arr[] = $row[0]; 
		}
		
		return $Arr;
	}
	
	function check_table( $Sheet ){
		
		$this->query("SHOW TABLES LIKE '" .$this->val_check($Sheet). "'");
		
		if( $this->query_rows() == 0 ){
		
			return false;	
		}else{
			
			return true;
		}
	}
	
	function check_database( $DataBase ){
		
		$this->query("SHOW DATABASES LIKE '" .$this->val_check($DataBase). "'");
		
		if( $this->query_rows() == 0 ){
		
			return false;	
		}else{
			
			return true;
		}
	}
	
	function get_table_status( $Field = '' ){
		
		$sql = 'SHOW TABLE STATUS'; 
		
		$this->query($sql);
		
		$Arr = array();
		while( $row = $this->query_fetch() ){
			
			if( empty($Field) ){
					
				$Arr[] = $row; 
			}else{
				
				$Arr[] = $row[$Field]; 
			}
		}
		
		return $Arr;
	}
	
	function get_table_info( $Sheet, $Field = '' ){
		
		$Arr = $Sheet_Arr = array();
		if( !empty($Sheet) ){
			
			if( is_array($Sheet) ){
				
				$Sheet_Arr		= $Sheet;
			}else{
				
				$Sheet_Arr[]	= $Sheet;
			}
			
			foreach( $Sheet_Arr as $val ){
				
				$sql = 'SHOW FULL COLUMNS FROM ' .$val; 
			
				$this->query($sql);
				
				while( $row = $this->query_fetch() ){
					
					if( !empty($Field) ){
						
						$Arr[$row['Field']] = $row[$Field]; 
					}else{
						
						$ext = explode('(', $row['Type']);
						$row['Field_Type'] = $ext[0];
						
						$ext = explode(')', $ext[1]);
						$row['Field_Length'] = $ext[0];
						
						$Arr[$row['Field']] = $row; 
					}
				}
			}
		}
		
		return $Arr;
	}
	
	function val_check( $val ){
		
		$val = mysqli_real_escape_string( $this->Link_DB, $val);
		
		return $val;
	}
	
	function Show_fun(){
		
		global $_setting_;
		
		if( $_setting_['WO_Debug'] == 1 ){//網站Debug開啟
		
			if( $this->Show_Sql && $this->Error ){
				
				if( !empty($this->sql) ){
					
					echo $this->sql."<BR><BR>";
				}
				echo $this->Error;
			}
		}
	}
	
	function CloseTable(){
		
		mysqli_close( $this->Link_DB );
	}
	//SSD = Save_Sql_Data
	function SSD( $user = '', $data_id = '', $comment = '', $sql_con = '', $sql_exec_type = '', $exec_file = '' ){
		
		if( $this->Create_SML == false ){
			
			$this->query("SHOW TABLES LIKE '" .$this->SML_Table_Name. "'");
			
			if( $this->query_rows() == 0 ){
				
				$this->Create_SML();
			}else{
				
				$this->Create_SML = true;	
			}
		}
		
		$user 			= mb_substr( $user, 0, 30, 'UTF-8');
		$data_id 		= mb_substr( $data_id, 0, 30, 'UTF-8');
		$sql_exec_type 	= mb_substr( $sql_exec_type, 0, 10, 'UTF-8');
		$exec_file 		= mb_substr( $exec_file, 0, 255, 'UTF-8');
		
		$filed = array( 'ML_DATE', 'ML_USER', 'ML_DATA_ID', 'ML_COMMENT', 'ML_SQL_CON', 'ML_SQL_EXEC_TYPE', 'ML_EXEC_FILE' );

		$value = array( 'now()', $user, $data_id, $comment, $sql_con, $sql_exec_type, $exec_file );

		$this->query_insert( $this->SML_Table_Name, $filed, $value, 'SDATA' );
	}
	
	//創建sys_mysql_log資料表
	function Create_SML(){
		
		$sql = "
			CREATE TABLE `" .$this->SML_Table_Name. "` (
				`ML_ID` int(10) UNSIGNED NOT NULL,
				`ML_DATE` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '建立時間',
				`ML_USER` varchar(30) default NULL COMMENT '使用者',
				`ML_DATA_ID` varchar(30) default NULL COMMENT '資料ID',
				`ML_COMMENT` text COMMENT '註解',
				`ML_SQL_CON` text COMMENT '執行內容',
				`ML_SQL_EXEC_TYPE` varchar(10) default NULL COMMENT '訊息種類',
				`ML_EXEC_FILE` varchar(255) default NULL COMMENT '執行檔案',
				KEY `ML_DATE` (`ML_DATE`),
				KEY `ML_USER` (`ML_USER`),
				KEY `ML_DATA_ID` (`ML_DATA_ID`),
				KEY `ML_SQL_EXEC_TYPE` (`ML_SQL_EXEC_TYPE`),
				KEY `ML_EXEC_FILE` (`ML_EXEC_FILE`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='執行訊息';";
		
		$this->query( $sql );
		
		$this->query( 'ALTER TABLE `' .$this->SML_Table_Name. '` ADD PRIMARY KEY (`ML_ID`);' );

		$this->query( 'ALTER TABLE `' .$this->SML_Table_Name. '` MODIFY `ML_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
	}
}
?>