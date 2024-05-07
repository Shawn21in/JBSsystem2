<?php

class Pages{
	
	var $Pages; 		//目前頁碼
	var $Page_Total_Num;//總資料筆數
	var $Page_Size;		//顯示筆數
	var $StartNum;		//撈取起始筆數
	var $Page_Num;		//多少頁碼
	var $Page_Sub = 5;	//顯示幾個頁碼
	
	var $Page_Start;	//開始筆數
	var $Page_End;		//結束筆數
	
	var $Page_Pre;		//上一頁頁碼
	var $Page_Next;		//下一頁頁碼
	
	var $Pstart;		//起始頁碼
	var $Pend;			//結束頁碼
	
	var $Pages_Data = array();		//頁碼資料
	var $Page_TF    = array();		//判斷哪些頁碼能做動作
	
	var $Style;			//頁碼STYLE
	
	var $Pages_Html;	//頁碼輸出頁面

	function __construct( $Pages, $Page_Total_Num, $Page_Size, $Page_Url = '' ){
		
		$Pages								= !empty($Pages) && is_numeric($Pages) ? $Pages : 1;//目前頁碼

		$this->Page_Num 	  				= ceil($Page_Total_Num / $Page_Size);
		$this->Pages_Data['Page_Num']		= $this->Page_Num;//多少頁碼
		
		//if( $Page_Size * ($Pages - 1) >= $Page_Total_Num ){//如果超過總筆數就撈第一頁
		if( $Pages > $this->Page_Num ){//如果超過總筆數就撈最後一頁
			
			$Pages = $this->Page_Num ? $this->Page_Num : 1;	
		}
		
		$this->Pages       					= $Pages;
		$this->Pages_Data['Pages']			= $Pages;//目前頁碼
		
		$this->Page_Total_Num       		= $Page_Total_Num;
		$this->Pages_Data['Page_Total_Num']	= $Page_Total_Num;//總資料筆數
		
		$this->Page_Size      				= $Page_Size;
		$this->Pages_Data['Page_Size'] 		= $Page_Size;//顯示筆數
		
		$this->StartNum       				= $Page_Size * ($Pages - 1);
		$this->Pages_Data['StartNum']		= $this->StartNum;//撈取起始筆數
		
		$this->Pages_Data['Page_Sub']		= $this->Page_Sub;//顯示幾個頁碼
		
		$this->Page_Start 	  		 		= $Page_Total_Num > 0 ? ( $this->StartNum + 1 ) : 0;
		$this->Pages_Data['Page_Start']		= $this->Page_Start;//開始筆數
		
		if( ($this->StartNum + $Page_Size) > $Page_Total_Num ){
			
			$this->Page_End   				= $Page_Total_Num;
		}else{
			
			$this->Page_End   				= $this->StartNum + $Page_Size;
		}
		$this->Pages_Data['Page_End']		= $this->Page_End;//結束筆數
		
		$this->Page_Pre 					= $Pages == 1 ? 1 : ( $Pages - 1 );
		$this->Pages_Data['Page_Pre']		= $this->Page_Pre;//上一頁頁碼
		
		if( $Pages != $this->Page_Num && !empty($this->Page_Num) ){
			
			$this->Page_Next 				= ( $Pages + 1 );
		}else{
			
			$this->Page_Next 				= 1;
		}
		$this->Pages_Data['Page_Next'] 		= $this->Page_Next;//下一頁頁碼
		
		if( $this->Page_Num <= $this->Page_Sub){
			
			$this->Pstart 					= 1;
			$this->Pend   					= $this->Page_Num;  
		}else{    
		
			if( $Pages <= ($this->Page_Sub / 2) ){ 
			
				$this->Pstart 				= 1;
				$this->Pend   				= $this->Page_Sub;   
			}else if( ($Pages + ($this->Page_Sub / 2)) > $this->Page_Num ){
				
				$this->Pstart 				= $this->Page_Num - ($this->Page_Sub - 1);
				$this->Pend   				= $this->Page_Num;
			}else{
				
				$this->Pstart 				= ceil($Pages - ($this->Page_Sub / 2) ); 
				$this->Pend   				= $this->Page_Sub + $this->Pstart - 1; 
			}  
		} 
		$this->Pages_Data['Pstart']			= $this->Pstart;//起始頁碼
		$this->Pages_Data['Pend']			= $this->Pend;//結束頁碼
		
		$this->Pages_Data['Page_Url']		= $Page_Url;
		
		$Pages_Data = $this->Pages_Data;
		
		ob_start();
		
		if( empty($this->Style) ){

			include_once(SYS_PATH.'style'.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.'default.html');
		
			$this->Pages_Html = ob_get_contents();
		}
		
		ob_end_clean(); 
	}
}
?>