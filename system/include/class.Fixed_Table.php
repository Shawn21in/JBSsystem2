<?php

class Fixed_Table{

	var $Msg			= '';									//訊息
	var $Operating 		= array();								//設定操作類型
	var $TableCssName 	= 'mainTable'; 							//設定TABLE一個ID名稱
	var $TableHtml		= 'Fixed_Table.html';					//設定使用的Html
	
	var $Key_Arr		= array();								//資料ID
	var $Name_Arr		= array();								//資料名稱
	var $Title_Array;   										//欄位標題陣列
	var $Value_Array;											//欄位值陣列
	var $Vtype_Array;											//欄位種類陣列
	
	var $Order_Array;											//允許排序陣列

	var $Table_Html;											//儲存TABLE輸出資料
	
	var $SearchKey;												//搜尋字串
	
	var $Pages_Data;											//頁碼資料
	
	var $Pages_Html;											//頁碼頁面資料
	
	var $Path;													//路徑
	
	var $table_option_arr;										//資料表的設定陣列
	
	var $Show_SizeArr = array('5', '10', '25', '50', '100');	//顯示筆數的陣列
	
	var $Albums_TF = false;										//是否為相簿列表
	
	function __construct( $Key_Arr = array(), $Name_Arr = array(), $TableID = '' ) {
	
		if( !empty($TableID) ){
			
			$this->TableCssName  = $TableID;
		}
	
		if( !empty($Key_Arr) ){
			
			$this->Key_Arr = $Key_Arr;	
		}
		
		if( !empty($Name_Arr) ){
			
			$this->Name_Arr = $Name_Arr;	
		}
		
		//$this->Path = SYS_PATH.'html_sys'.DIRECTORY_SEPARATOR;//上層路徑
	}
	
	function Input_TitleArray( $Title_Array = array() ){
	
		if( !empty($Title_Array) ){
			
			$this->Title_Array = $Title_Array;
		}else{
			
			$this->Title_Array = array();
		}
	}
	
	function Input_ValueArray( $Value_Array = array() ){
		
		if( !empty($Value_Array) ){
			
			$this->Value_Array = $Value_Array;	
		}else{
			
			$this->Value_Array = array();
		}
	}
	
	function Input_VtypeArray( $Vtype_Array = array() ){
		
		if( !empty($Vtype_Array) ){
			
			$this->Vtype_Array = $Vtype_Array;	
		}else{
			
			$this->Vtype_Array = array();
		}
	}
	
	function Input_OrderArray( $Order_Array = array(), $Sort, $Field ){
		
		if( !empty($Order_Array) ){
			
			if( $Sort == 'DESC' ){
				
				$Order_Array[$Field] = ' sorting_desc';
			}else if( $Sort == 'ASC' ){
				
				$Order_Array[$Field] = ' sorting_asc';
			}
			
			$this->Order_Array = $Order_Array;	
		}else{
			
			$this->Order_Array = array();
		}
	}
	
	function CreatTable(){//建立報表資料
		
		global $Admin_data, $Now_List;

		$Key_Arr     		= $this->Key_Arr;
		$Name_Arr     		= $this->Name_Arr;
		$Show_SizeArr   	= $this->Show_SizeArr;
		$Title_Array 		= $this->Title_Array;
		$Value_Array		= $this->Value_Array;
		$Vtype_Array		= $this->Vtype_Array;
		$Order_Array		= $this->Order_Array;
		
		$Pages_Data     	= $this->Pages_Data;
		$Pages_Html			= $this->Pages_Html;
		
		$SearchKey      	= $this->SearchKey;
		
		$Operating			= $this->Operating;
		
		$table_option_arr 	= $this->table_option_arr;
		
		$this->Table_Html 	= '';

		ob_start();
		include_once($this->Path.$this->TableHtml);
		
		/*if( empty($Title_Array) ){
			echo "沒有標題<br>";
		}
		
		if( empty($Value_Array) ){
			echo "沒有資料<br>";
		}
		
		if( empty($Key_Arr) ){
			echo "沒有Key值<br>";
		}*/
		
		$this->Table_Html = ob_get_contents();
		ob_end_clean(); 
		
		return $this->Table_Html;
	}
}
?>