<?php
class MsSQL{
			
	var $Show_Sql		= true;		//1顯示SQL資料和錯誤:0不顯示SQL資料和錯誤
	var $Error_Save		= true;		//TRUE紀錄SQL錯誤:FALSE不紀錄SQL錯誤
	var $Where_TF		= true;		//true代表一定要輸入WHERE 字串
	var $Insert_S		= true;		//TRUE紀錄新增SQL資料
	var $Update_S   	= true;		//TRUE紀錄更新SQL資料
	var $Delete_S   	= true;		//TRUE紀錄刪除SQL資料


	var $Link_DB		= '';
	var $sql			= '';
	var $Where			= '';		//WHERE 字串
	var $Order_By		= '';		//ORDER BY 字串 
	var $Group_By		= '';		//GROUP BY 字串 
	var $result			= '';
	var $Error			= '';
	var $num_row		= '';
	var $filed_str		= '';
	var $value_str		= '';
	
	var $params  		= array();
	var $options 		= array( 'Scrollable' => 'buffered' );
	
	function MsSQL( $DB = '' ){
		
		if( !isset($_SESSION) ){
			
			session_start();
		}
		
		// if( $DB == 'selldb' ){
			
			include('inc.connect_ms.php');
		// }else{
			
			// include('inc.connect_ms2.php');
		// }
		
		/*if( Link_MDB != 'Link_MDB' && Link_MDB != '' ){
			
			$this->Link_DB = Link_MDB;
		}else if( $this->Host && $this->UserName && $this->PassWord && $this->DataBase ){
			
			$this->Connect_SQL( $this->Host, $this->UserName, $this->PassWord, $this->DataBase );
		}*/
	}
	
	function Connect_SQL( $Host, $UserName, $PassWord, $DataBase ){
		
		$ConnectInfo = array( "UID" => $UserName, "PWD" => $PassWord, "Database" => $DataBase, "CharacterSet" => "UTF-8", "TrustServerCertificate" => 1);
		$this->Link_DB = sqlsrv_connect( $Host, $ConnectInfo );
        return $this->Link_DB;
	}
		
	//執行SQL語法
	function query( $sql, $Method = '' ){
		
		$this->sql = $sql;
		$this->result = sqlsrv_query( $this->Link_DB, $this->sql, $this->params, $this->options );
		
		$this->Error = sqlsrv_errors();
			
		$this->Show_fun();
				
		$admin_name = $_SESSION['system']['admin_name'];
		$admin_id	= $_SESSION['system']['admin_id'];
		if( $this->Error_Save && $this->Error ){
			
			$db = new MySQL();
			
			ob_start();
			
			print_r($this->Error);//因為是陣列所以要印出來儲存
			$Error_Str = ob_get_contents();
			ob_end_clean(); 
			
			$db->SSD( $admin_name, $admin_id, $Error_Str, $sql, 'ERROR', $_SERVER['REQUEST_URI'] );	
		}else if( $this->Insert_S && $Method == 'INSERT' ){
			
			$db = new MySQL();
			
			$db->SSD( $admin_name, $admin_id, '', $sql, 'INSERT', $_SERVER['REQUEST_URI'] );
		}else if( $this->Update_S && $Method == 'UPDATE' ){
			
			$db = new MySQL();
			
			$db->SSD( $admin_name, $admin_id, '', $sql, 'UPDATE', $_SERVER['REQUEST_URI'] );
		}else if( $this->Delete_S && $Method == 'DELETE' ){
			
			$db = new MySQL();
			
			$db->SSD( $admin_name, $admin_id, '', $sql, 'DELETE', $_SERVER['REQUEST_URI'] );
		}
	}
	//搜尋到幾筆資料
	function query_rows(){
		
		$this->num_row = sqlsrv_num_rows( $this->result );
		$this->Error = sqlsrv_errors();
			
		$this->Show_fun();

		return $this->num_row;	
	}
	
	function query_fetch( $Field = '' ){
		 
		$row		 = sqlsrv_fetch_array( $this->result );
		$this->Error = sqlsrv_errors();
		
		if( !empty($Field) ){
			
			$row = $row[$Field];
		}
		
		$this->Show_fun();
		
		return $row;		
	}
	
	function query_insert( $Sheet, $FILED = array(), $VALUE = array(), $Method = "DATA" ){
		
		$this->filed_str = $this->value_str = "";
		
		foreach( $VALUE as $key => $val ){
			
			$val = trim( $val );
			$this->value_str .= preg_match( "/^now()/i", $val) ? $val."," : "N'" .$this->val_check($val). "',";
			
			$fil = trim( $FILED[$key] );
			$this->filed_str .= $fil.",";
		}
		$this->filed_str = substr( $this->filed_str, 0, -1 );
		$this->value_str = substr( $this->value_str, 0, -1 );
		
		$sql = "INSERT INTO " .$Sheet. " (" .$this->filed_str. ") VALUES (" .$this->value_str. ");";
		
		$this->query( $sql, 'INSERT' );
	}
	
	function query_update( $Sheet, $FILED = array(), $VALUE = array(), $WHERE = "", $Method = "DATA" ){
		
		$UPDATE = "";
		
		if( empty($WHERE) ){
			
			$WHERE = $this->Where;
		}
		
		if( !$this->Where_TF || !empty($WHERE) ){
			
			foreach($VALUE as $key => $val){
				
				$val 	 = trim( $val );
				$val 	 = preg_match( "/^now()/i", $val) ? $val : "N'" .$this->val_check($val). "'";
				
				$fil 	 = trim( $FILED[$key] );
				$UPDATE .= $fil. " = " .$val. ",";
			}
			$UPDATE = substr($UPDATE, 0, -1);		
			
			$sql = "UPDATE " .$Sheet. " SET " .$UPDATE. " " .$WHERE. ";"; 
		}else{
			
			$this->Error = "Update Not ( WHERE ) String";
			
			$this->Show_fun();
			
			return false;
		}
				
		$this->query( $sql, 'UPDATE' );	
	}
	
	function query_delete( $Sheet ){
		
		if( !$this->Where_TF || !empty($this->Where) ){
			
			$sql = "DELETE FROM " .$Sheet. " " .$this->Where. ";";
		}else{
			
			$this->Error = "Delete Not ( WHERE ) String";
						
			$this->Show_fun();
			
			return false;
		}
		
		$this->query( $sql, 'DELETE' );	
	}
	
	function query_sql( $Sheet, $Columns = "*", $StartNum = 0, $Num = 0, $Field = "" ){
		
		if( $StartNum >= 0 && $Num > 0 && !empty($Field) ){
		//$Field   設定兩個資料表要對應欄位
			
			if( $Columns != "*" ){
				
				$ex  = explode(",", $Columns);
				foreach( $ex as $key => $val ){
					
					$ex[$key] = "T1." .$val;
				}
				
				$Columns = implode(",", $ex);
			}
			
			$start = $StartNum + 1;
			$end   = $StartNum + $Num;
			
			/*$_Where = '';
			if( !empty($this->Where) ){
				
				$_Where = $this->Where. ' AND ';
			}else{
				
				$_Where = ' WHERE ';
			}*/
			
			$_Oreder = $_Oreder1 = '';
			if( is_array($this->Order_By) ){
				
				$_Oreder  = ' ORDER BY';
				//$_Oreder1 = ' ORDER BY';
				
				foreach( $this->Order_By as $key => $val ){
					
					$_Oreder  .= ' ' .$key. ' ' .$val;
					//$_Oreder1 .= ' T1.' .$key. ' ' .$val;
				}
			}else{
				
				$_Oreder  = $this->Order_By;
			}
				
			//$sql = 'SELECT ' .$Columns. ' FROM ' .$Sheet. ' AS T1, (SELECT ' .$Field. ', ROW_NUMBER() OVER(' .$_Oreder. ') AS sn FROM ' .$Sheet. ' ' .$this->Where. ') AS T2 ' .$_Where. ' T1.' .$Field. ' = T2.' .$Field. ' AND T2.sn BETWEEN ' .$start. ' AND ' .$end. ' ' .$_Oreder1;
			$sql = 'SELECT ' .$Columns. ' FROM (SELECT ' .$Columns. ', ROW_NUMBER() OVER(' .$_Oreder. ') AS sn FROM ' .$Sheet. ' ' .$this->Where. ') AS T1 WHERE T1.sn BETWEEN ' .$start. ' AND ' .$end;
		}else{
		
			$sql = "SELECT " .$Columns. " FROM " .$Sheet. " " .$this->Where. " " .$this->Group_By. " " .$this->Order_By. ";";
		}
		
		$this->query( $sql );	
	}

	function query_sql_membersellList( $Sheet, $Columns = "*", $StartNum = 0, $Num = 0, $Field = "" ){
		
		if( $StartNum >= 0 && $Num > 0 && !empty($Field) ){
		//$Field   設定兩個資料表要對應欄位
			
			if( $Columns != "*" ){	
				$Columns_WithoutT1 = $Columns;
				$ex  = explode(",", $Columns);
				
				foreach( $ex as $key => $val ){	
					$ex[$key] = "T1." .$val;
				}
				
				$Columns = implode(",", $ex);
			}
			
			$start = $StartNum + 1;
			$end   = $StartNum + $Num;

			
			$_Oreder = $_Oreder1 = '';
			if( is_array($this->Order_By) ){
				
				$_Oreder  = ' ORDER BY';
				
				foreach( $this->Order_By as $key => $val ){
				
					$_Oreder  .= ' ' .$key. ' ' .$val;
				}
			}else{
				
				$_Oreder  = $this->Order_By;
			}
				
			//$sql = 'SELECT ' .$Columns. ' FROM ' .$Sheet. ' AS T1, (SELECT ' .$Field. ', ROW_NUMBER() OVER(' .$_Oreder. ') AS sn FROM ' .$Sheet. ' ' .$this->Where. ') AS T2 ' .$_Where. ' T1.' .$Field. ' = T2.' .$Field. ' AND T2.sn BETWEEN ' .$start. ' AND ' .$end. ' ' .$_Oreder1;
			$sql = 'SELECT ' .$Columns. ' FROM (SELECT ' .$Columns_WithoutT1. ', ROW_NUMBER() OVER(' .$_Oreder. ') AS sn FROM ' .$Sheet. ' ' .$this->Where. ') AS T1 WHERE T1.sn BETWEEN ' .$start. ' AND ' .$end;
		}else{
		
			$sql = "SELECT " .$Columns. " FROM " .$Sheet. " " .$this->Where. " " .$this->Group_By. " " .$this->Order_By. ";";
		}
		
		$this->query( $sql );	
	}
	
	function query_sql_dealer( $Sheet, $Columns, $Columns2, $StartNum = 0, $Num = 0, $Field = "" ){
		
		if( $StartNum >= 0 && $Num > 0 && !empty($Field) ){
		//$Field   設定兩個資料表要對應欄位
			
			
			$start = $StartNum + 1;
			$end   = $StartNum + $Num;
			
			/*$_Where = '';
			if( !empty($this->Where) ){
				
				$_Where = $this->Where. ' AND ';
			}else{
				
				$_Where = ' WHERE ';
			}*/
			
			$_Oreder = $_Oreder1 = '';
			if( is_array($this->Order_By) ){
				
				$_Oreder  = ' ORDER BY';
				//$_Oreder1 = ' ORDER BY';
				
				foreach( $this->Order_By as $key => $val ){
					
					$_Oreder  .= ' ' .$key. ' ' .$val;
					//$_Oreder1 .= ' T1.' .$key. ' ' .$val;
				}
			}else{
				
				$_Oreder  = $this->Order_By;
			}
				
			//$sql = 'SELECT ' .$Columns. ' FROM ' .$Sheet. ' AS T1, (SELECT ' .$Field. ', ROW_NUMBER() OVER(' .$_Oreder. ') AS sn FROM ' .$Sheet. ' ' .$this->Where. ') AS T2 ' .$_Where. ' T1.' .$Field. ' = T2.' .$Field. ' AND T2.sn BETWEEN ' .$start. ' AND ' .$end. ' ' .$_Oreder1;
			$sql = 'SELECT ' .$Columns. ' FROM (SELECT ' .$Columns2. ', ROW_NUMBER() OVER(' .$_Oreder. ') AS sn FROM ' .$Sheet. ' ' .$this->Where. ') AS T1 WHERE T1.sn BETWEEN ' .$start. ' AND ' .$end;
		}else{
		
			$sql = "SELECT " .$Columns. " FROM " .$Sheet. " " .$this->Where. " " .$this->Group_By. " " .$this->Order_By. ";";
		}
		
		$this->query( $sql );	
	}
	
	function query_data( $Sheet, $Data, $Type ){
		
		$field = $value = array();
		foreach( $Data as $key => $val ){
			
			$field[] = $key;
			$value[] = $val;
		}
		
		if( $Type == "UPDATE" ){
			
			$this->query_update($Sheet, $field, $value, $this->Where);
		}else if( $Type == "INSERT" ){
			
			$this->query_insert($Sheet, $field, $value);
		}else{
			
			$this->Error = "Please select Type ( UPDATE or INSERT )";	
		}
					
		$this->Show_fun();
	}
	
	function query_count( $Sheet ){//計算筆數
			
		$sql = "SELECT COUNT(*) as COUNT FROM " .$Sheet. " " .$this->Where. " " .$this->Group_By. ";"; 
		
		$this->query( $sql );
		
		$row = $this->query_fetch();
		
		return $row[COUNT];	
	}
	
	function query_search( $SearchKey = "" ){//串接搜尋子句
				
		if( !empty($SearchKey) && !empty($this->Search) ){	
			
			foreach( $this->Search as $key => $val ){
				
				if( empty($this->Where) ){
					
					$this->Where  = " WHERE ( " .$val. " LIKE '%" .$this->val_check($SearchKey). "%'";
				}else{
					
					if( $key == 0 ){
						
						$this->Where .= " AND ( " .$val. " LIKE '%" .$this->val_check($SearchKey). "%'";
					}
						
					$this->Where .= " OR " .$val. " LIKE '%" .$this->val_check($SearchKey). "%'";
				}
			}
			
			$this->Where .= " )";
		}
	}
	
	function val_check( $val ){
		
		$val = str_replace("'", "''", $val);
		
		return $val;
	}
	
	function Show_fun(){
		
		if( $this->Show_Sql && $this->Error ){
			
			if( !empty($this->sql) ){
				
				echo $this->sql."<BR><BR>";
			}
			print_r( $this->Error );
		}
	}
}
?>