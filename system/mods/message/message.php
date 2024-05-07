<?php
if( !function_exists('Chk_Login') ) header('Location: ../../index.php');

$_Search_Option = $Select_Arr = array();

$Table_Field_Arr = $db->get_table_info($Main_Table, 'Comment');

$Sheet = $Main_Table;	

$Order_ByArr = array();
$Title_Array['Ordersn']	= "序號";
foreach( $table_option_arr as $tofield => $toarr ){
	
	$Comment = $Table_Field_Arr[$tofield];
	if( empty($Comment) ){ continue; }	//無此欄位跳過
	
	$Title_Array[$tofield]	= $Comment;	//欄位註解
	$Order_Array[$tofield]	= '';		//允許排序項目
	
	if( $toarr['TO_InType'] == 'select' ){
		
		global ${$toarr['TO_SelStates']};
	
		$Select_Arr[$tofield] = ($$toarr)['TO_SelStates'];
		print_r( $toarr );
	}
	
	if( $toarr['TO_InType'] == 'datecreat' ){
		
		if( $toarr['TO_InType'] == 'datecreat' ){
			
			$Order_ByArr[1] = $tofield. ' DESC';
		}
	}
	
	if( $toarr['TO_InType'] == 'uploadimg' || $toarr['TO_InType'] == 'uploadfile' || $toarr['TO_InType'] == 'youtube' /*|| $toarr['TO_InType'] == 'checkbox'*/ ){ 
		
		continue;
	}
	
	$db->Search[]				= $tofield;//設定可搜尋欄位
	$_Search_Option[$tofield] 	= Search_Option($toarr, $Comment);//設定進階搜尋
}

if( !empty($POST['search_field']) ){
	
	$db->Where = Search_Fun($db->Where, $POST, $_Search_Option);
}

$db->query_search($SearchKey);//串接搜尋子句

$Page_Total_Num = $db->query_count($Sheet);//總資料筆數

$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size);//頁碼程式
$Pages    = $Page_Calss->Pages;//頁碼
$StartNum = $Page_Calss->StartNum;//從第幾筆開始撈

if( !empty($Order_By) ){
	
	$db->Order_By = $Order_By;
}else if( !empty($Now_List['Menu_OrderBy']) ){
	
	$db->Order_By = $Now_List['Menu_OrderBy'];
}else{
	
	if( empty($Order_ByArr) || empty($Order_ByArr[1]) ){
		
		$Order_ByArr[] = $Main_Key.' DESC';
	}
	
	$db->Order_By = ' ORDER BY ' .implode(',', $Order_ByArr);
}

$db->query_sql($Sheet, "*", $StartNum, $Page_Size);

$sn = ( $StartNum + 1 );
while( $row = $db->query_fetch() ){
		
	$Value_Array['Ordersn'][$sn]		= $sn;
	$Value_Array[$Main_Key][$sn]		= $row[$Main_Key];
	
	foreach( $table_option_arr as $tofield => $toarr ){
		
		if( $tofield == 'Contact_conType' ){
			
			global $contact_states;
			
			$Value_Array[$tofield][$sn] = $contact_states[$row[$tofield]];
		}else{
		
			$Value_Array[$tofield][$sn]	= $row[$tofield];
		}
	}
			
	$sn++;
}

//第一個值設定對應KEY,第二個值設定對應名稱
$_FT = new Fixed_Table( $Value_Array[$Main_Key], $Value_Array['Ordersn'] );
$_FT->Input_TitleArray( $Title_Array );//設定顯示欄位
$_FT->Input_ValueArray( $Value_Array );//設定顯示欄位裡面的值
//$_FT->Input_VtypeArray( $Vtype_Array );//設定顯示欄位呈現方式
$_FT->Input_OrderArray( $Order_Array, $Sort, $Field );//設定可排序的欄位, 升序或降序, 要排序欄位名稱
$_FT->Pages_Data 		= $Page_Calss->Pages_Data;//頁碼資料
$_FT->Pages_Html 		= $Page_Calss->Pages_Html;//頁碼頁面
$_FT->SearchKey  		= $SearchKey;//搜尋字串
$_FT->Operating	 		= array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']);//設定操作類型
$_FT->Path		 		= SYS_PATH.'mods'.DIRECTORY_SEPARATOR.$Now_List['Menu_Model'].DIRECTORY_SEPARATOR;//檔案路徑
$_FT->TableHtml	 		= $Now_List['Menu_Model'].'.table.php';
$_FT->table_option_arr	= $table_option_arr;//資料表的設定陣列
$_FT->Select_Arr 		= $Select_Arr;//選擇陣列

$_html = $_FT->CreatTable();//創建表格

if( !empty($POST) ){
	
	echo $_html;	
}else{
?>
<script type="text/javascript" src="assets/js/sys-table.js"></script>
<script type="text/javascript">

var Exec_Url = 'mods/<?=$Now_List['Menu_Model']?>/<?=$Now_List['Menu_Model']?>.post.php?fun=' + getUrlVal(location.search, 'fun');

$(document).ready(function(e) {
    
	$('#mainAdd').parent('span').remove();
	$('#mainEdt').parent('span').remove();
	$('#mainRe').parent('span').remove();
});
</script>

<div class="table-header">
	<?=$Now_List['Menu_Name']?>
    
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
    <div class="contents"></div>
</div>
<?php 
}?>