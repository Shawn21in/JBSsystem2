<?php
if( !function_exists('Chk_Login') ) header('Location: ../../index.php');

global $order_states;

$Table_Field_Arr = $db->get_table_info($Main_Table, 'Comment');

//$Sheet = $Main_Table;
$Sheet = $Main_Table." as a LEFT JOIN web_delivery as b ON a.Orderm_Delivery = b.Delivery_ID";

//查詢欄位名稱設定
$_Search_Option['Orderm_ID']['name']			= $Table_Field_Arr['Orderm_ID'];
$_Search_Option['Member_ID']['name']			= $Table_Field_Arr['Member_ID'];
$_Search_Option['Orderm_RName']['name']			= $Table_Field_Arr['Orderm_RName'];
$_Search_Option['Orderm_RMobile']['name']		= $Table_Field_Arr['Orderm_RMobile'];
$_Search_Option['Orderm_TotalPrice']['name']	= $Table_Field_Arr['Orderm_TotalPrice'];
$_Search_Option['Orderm_States']['name']		= $Table_Field_Arr['Orderm_States'];
$_Search_Option['Orderm_Sdate']['name']			= $Table_Field_Arr['Orderm_Sdate'];
$_Search_Option['Orderm_Outdate']['name']		= $Table_Field_Arr['Orderm_Outdate'];
//查詢欄位設定種類
$_Search_Option['Orderm_ID']['type']			= 'text';
$_Search_Option['Member_ID']['type']			= 'text';
$_Search_Option['Orderm_RName']['type']			= 'text';
$_Search_Option['Orderm_RMobile']['type']		= 'text';
$_Search_Option['Orderm_TotalPrice']['type']	= 'text';
$_Search_Option['Orderm_States']['type']		= 'select';
$_Search_Option['Orderm_Sdate']['type']			= 'datetime';
$_Search_Option['Orderm_Outdate']['type']		= 'datetime';
//查詢欄位設定選擇內容
$_Search_Option['Orderm_States']['select']		= $order_states;

$_Search_Option['Orderm_Sdate']['format']		= 'YYYY-MM-DD';
$_Search_Option['Orderm_Outdate']['format']		= 'YYYY-MM-DD';

if( !empty($POST['search_field']) ){
				
	$db->Where = Search_Fun($db->Where, $POST, $_Search_Option);
}

$_Excel_Option	= false;//進階功能->開啟匯出功能

if( $_Excel_Option && $POST['search_type'] == 'excel' ){//清除資料
	
	echo $db->Where;
	return;
}

$db->Search = array("Orderm_ID", "Member_ID", "Orderm_RName", "Orderm_RMobile", "Orderm_TotalPrice");//設定可搜尋欄位
$db->query_search($SearchKey);//串接搜尋子句

$Page_Total_Num = $db->query_count($Sheet);//總資料筆數

$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
$Pages    = $Page_Calss->Pages;//頁碼
$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈

$Title_Array['Ordersn']				= "序號";
$Title_Array['Orderm_ID']			= $Table_Field_Arr['Orderm_ID'];
$Title_Array['Member_ID']			= $Table_Field_Arr['Member_ID'];
$Title_Array['Orderm_RName']		= $Table_Field_Arr['Orderm_RName'];
$Title_Array['Orderm_RMobile']		= $Table_Field_Arr['Orderm_RMobile'];
$Title_Array['Orderm_TotalPrice']	= $Table_Field_Arr['Orderm_TotalPrice'];
$Title_Array['Orderm_Delivery']		= "付款方式";
$Title_Array['Orderm_card5no']		= "匯款後五碼";
$Title_Array['Orderm_States']		= $Table_Field_Arr['Orderm_States'];
$Title_Array['Orderm_Sdate']		= $Table_Field_Arr['Orderm_Sdate'];
$Title_Array['Orderm_Outdate']		= $Table_Field_Arr['Orderm_Outdate'];

$sn = ( $StartNum + 1 );

if( !empty($Order_By) ){
	
	$db->Order_By = $Order_By;
}else if( !empty($Now_List['Menu_OrderBy']) ){
	
	$db->Order_By = $Now_List['Menu_OrderBy'];
}else{
	
	$db->Order_By = " ORDER BY Orderm_Sdate DESC";
}

$db->query_sql($Sheet, "a.*,b.Delivery_Name", $StartNum, $Page_Size);
while( $row = $db->query_fetch() ){
		
	$Value_Array['Ordersn'][$sn]	 		= $sn;
	$Value_Array[$Main_Key][$sn]	 		= $row[$Main_Key];
	$Value_Array['Orderm_ID'][$sn]			= $row['Orderm_ID'];
	$Value_Array['Member_ID'][$sn]			= $row['Member_ID'];
	$Value_Array['Orderm_RName'][$sn]		= $row['Orderm_RName'];
	
	$Value_Array['Orderm_RMobile'][$sn]		= $row['Orderm_RMobile'];
	$Value_Array['Orderm_Delivery'][$sn]	= $row['Delivery_Name'];
	$Value_Array['Orderm_TotalPrice'][$sn]	= number_format($row['Orderm_TotalPrice']);
	$Value_Array['Orderm_States'][$sn]		= $row['Orderm_States'];
	$Value_Array['Orderm_card5no'][$sn]		= $row['Orderm_card5no'];
	$Value_Array['Orderm_Sdate'][$sn]		= $row['Orderm_Sdate'];
	$Value_Array['Orderm_Outdate'][$sn]		= $row['Orderm_Outdate'];
	
	if( $row['Orderm_Success'] == 0 && $row['Orderm_States'] != 2 && $row['Orderm_States'] != 3 ) {
		
		$Vtype_Array['Orderm_States'][$sn] 		= 'select'; 
	}else{
		if( $row['Orderm_States'] == 2 ){
			$Value_Array['Orderm_States'][$sn]		= "<font color='#0000ff'>".$order_states[ $row['Orderm_States'] ]."</font>";
		}else if( $row['Orderm_States'] == 3 ){
			$Value_Array['Orderm_States'][$sn]		= "<font color='#ff0000'>".$order_states[ $row['Orderm_States'] ]."</font>";
		}
		
	}
	
	
	$sn++;
}

$table_option_arr['Orderm_States']['TO_OutEdit']	= 1;

$Select_Arr['Orderm_States'] = $order_states;

$Order_Array = array("Orderm_ID" => "", "Member_ID" => "", "Orderm_RName" => "", "Orderm_RMobile" => "", "Orderm_TotalPrice" => "", "Orderm_Delivery" => "", "Orderm_States" => "", "Orderm_Sdate" => "", "Orderm_Outdate" => "");//允許排序項目

//第一個值設定對應KEY,第二個值設定對應名稱
$_FT = new Fixed_Table( $Value_Array[$Main_Key], $Value_Array['Ordersn'] );
$_FT->Input_TitleArray( $Title_Array );//設定顯示欄位
$_FT->Input_ValueArray( $Value_Array );//設定顯示欄位裡面的值
$_FT->Input_VtypeArray( $Vtype_Array );//設定顯示欄位呈現方式
$_FT->Input_OrderArray( $Order_Array, $Sort, $Field );//設定可排序的欄位, 升序或降序, 要排序欄位名稱
$_FT->Pages_Data 		= $Page_Calss->Pages_Data;//頁碼資料
$_FT->Pages_Html 		= $Page_Calss->Pages_Html;//頁碼頁面
$_FT->SearchKey  		= $SearchKey;//搜尋字串
$_FT->Operating	 		= array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']);//設定操作類型
$_FT->Path		 		= SYS_PATH.$Now_List['Menu_Path'].DIRECTORY_SEPARATOR;//檔案路徑
$_FT->TableHtml	 		= $Now_List['Menu_Exec_Name'].'.table.'.$Now_List['Exec_Sub_Name'];
$_FT->table_option_arr	= $table_option_arr;//資料表的設定陣列
$_FT->Select_Arr 		= $Select_Arr;

$_html = $_FT->CreatTable();//創建表格
	
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

<div id="table_content_view" class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div  class="contents"></div>
</div>
<?php 
}?>