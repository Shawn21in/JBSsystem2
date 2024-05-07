<?php
if( !function_exists('Chk_Login') ) header('Location: ../../index.php');

if( $Admin_data['Group_ID'] == 1 ){//只有系統管理員能看全部
			
	$Table_Field_Arr = $db->get_table_info($Main_Table, 'Comment');
	
	$Sheet = $Main_Table. ' as a LEFT JOIN ' .DB_DataBase.'.'.Admin_DB. ' as b ON a.Admin_ID = b.Admin_ID';	
	
	$db->Search = array("Admin_Name");//設定可搜尋欄位
	$db->query_search($SearchKey);//串接搜尋子句
	
	$Page_Total_Num = $db->query_count($Sheet);//總資料筆數
	
	$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
	$Pages    = $Page_Calss->Pages;//頁碼
	$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈
				
	$Title_Array['Ordersn']		= "序號";
	$Title_Array['Admin_Name']	= $Table_Field_Arr['Admin_ID'];
	
	$sn = ( $StartNum + 1 );
	
	if( !empty($Order_By) ){
		
		$db->Order_By = $Order_By;
	}else if( !empty($Now_List['Menu_OrderBy']) ){
	
		$db->Order_By = $Now_List['Menu_OrderBy'];
	}else{
		
		$db->Order_By = " ORDER BY a.Admin_ID ASC";
	}
	
	$db->query_sql($Sheet, "*", $StartNum, $Page_Size);
	while( $row = $db->query_fetch() ){
						
		$Value_Array['Ordersn'][$sn]	= $sn;
		$Value_Array[$Main_Key][$sn]	= $row[$Main_Key];
		$Value_Array['Admin_Name'][$sn]	= $row['Admin_Name'];
		
		$sn++;
	}
	
	$Order_Array = array("Admin_Name" => "");//允許排序項目
	
	//第一個值設定對應KEY,第二個值設定對應名稱
	$_FT = new Fixed_Table( $Value_Array[$Main_Key], $Value_Array['Ordersn'] );
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
}else{
			
	$table_info = $db->get_table_info($Now_List['Menu_TableName']);//取出資料表欄位的詳細的訊息	
	
	if( Multi_WebUrl ){
					
		$db->Where = " WHERE Admin_ID = '" .$db->val_check($Admin_data['Admin_ID']). "'";
	}else{
		
		$db->Where = " WHERE Admin_ID = '" .Multi_WebUrl_ID. "'";
	}
		
	$Settype = GetUrlVal($Now_List['Menu_Exec'], 'type');
		
	$db->query_sql($Now_List['Menu_TableName'], "*");
	$_html_[] = $db->query_fetch();
	
			
	ob_start();
	include_once(SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR.$Now_List['Menu_Exec_Name'].".edit.".$Now_List['Exec_Sub_Name']);
	$_html = ob_get_contents();
	ob_end_clean();
}

if( !empty($POST) ){
	
	echo $_html;	
}else{
?>

<script type="text/javascript" src="assets/js/sys-table.js"></script>
<script type="text/javascript">

var Exec_Url = '<?=$Now_List['Menu_Path']?>/<?=$Now_List['Menu_Exec_Name']?>.post.php?fun=' + getUrlVal(location.search, 'fun');

$(document).ready(function(e) {
    
	$('#mainAdd').parent('span').remove();
	$('#mainEdt').parent('span').remove();
	$('#mainDel').parent('span').remove();
});
</script>

<div class="table-header">
	<span><?=$Now_List['Menu_Name']?></span>
    
<?php if( !empty($_Search_Option) ){ ?>
    <span class="extra-fun">
    	<i class="fa fa-tasks"></i>進階功能
    </span>
<?php } ?>
</div>

<?php require_once(SYS_PATH.'php_sys/extra_div.php')?>

<div id="table_content"><?=$_html?></div>

<div id="table_content_edit" class="display_none"></div>
<?php 
}?>