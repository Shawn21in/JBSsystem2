<?php
if (!function_exists('Chk_Login')) header('Location: ../../index.php');

global $open_states;

$Table_Field_Arr = $db->get_table_info(array($Main_Table, $Main_Table3), 'Comment');

$Sheet = $Main_Table . ' as a LEFT JOIN ' . $Main_Table3 . ' as b ON a.' . $Main_Key3 . ' = b.' . $Main_Key3;

//查詢欄位名稱設定
// $_Search_Option['Product_ID']['name']		= $Table_Field_Arr['Product_ID'];
$_Search_Option['Product_Name']['name']		= $Table_Field_Arr['Product_Name'];
$_Search_Option['ProductC_Name']['name']	= $Table_Field_Arr['ProductC_Name'];
$_Search_Option['Product_Price']['name']	= $Table_Field_Arr['Product_Price'];
$_Search_Option['Product_Price1']['name']	= $Table_Field_Arr['Product_Price1'];
$_Search_Option['Product_Sort']['name']		= $Table_Field_Arr['Product_Sort'];
$_Search_Option['Product_OpenNew']['name']	= $Table_Field_Arr['Product_OpenNew'];
$_Search_Option['Product_OpenHot']['name']	= $Table_Field_Arr['Product_OpenHot'];
$_Search_Option['Product_Open']['name']		= $Table_Field_Arr['Product_Open'];
//查詢欄位設定種類
// $_Search_Option['Product_ID']['type']		= 'text';
$_Search_Option['Product_Name']['type']		= 'text';
$_Search_Option['ProductC_Name']['type']	= 'text';
$_Search_Option['Product_Price']['type']	= 'text';
$_Search_Option['Product_Price1']['type']	= 'text';
$_Search_Option['Product_Sort']['type']		= 'text';
$_Search_Option['Product_OpenNew']['type']	= 'select';
$_Search_Option['Product_OpenHot']['type']	= 'select';
$_Search_Option['Product_Open']['type']		= 'select';
//查詢欄位設定選擇內容
$_Search_Option['Product_OpenNew']['select']	= $open_states;
$_Search_Option['Product_OpenHot']['select']	= $open_states;
$_Search_Option['Product_Open']['select']		= $open_states;

if (!empty($POST['search_field'])) {

	$db->Where = Search_Fun($db->Where, $POST, $_Search_Option);
}

$db->Search = array("Product_ID", "Product_Name", "ProductC_Name", "Product_Price", "Product_Price1", "Product_Sort", "Product_OpenNew", "Product_OpenHot", "Product_Open"); //設定可搜尋欄位
$db->query_search($SearchKey); //串接搜尋子句

$Page_Total_Num = $db->query_count($Sheet); //總資料筆數

$Page_Calss  = new Pages($Pages, $Page_Total_Num, $Page_Size); //頁碼程式
$Pages    = $Page_Calss->Pages; //頁碼
$StartNum = $Page_Calss->StartNum; //從第幾筆開始撈

$Title_Array['Ordersn']			= "序號";
// $Title_Array['Product_ID']		= $Table_Field_Arr['Product_ID'];
$Title_Array['Product_Name']	= $Table_Field_Arr['Product_Name'];
$Title_Array['ProductC_Name']	= $Table_Field_Arr['ProductC_Name'];
$Title_Array['Product_Price']	= $Table_Field_Arr['Product_Price'];
$Title_Array['Product_Price1']	= $Table_Field_Arr['Product_Price1'];
$Title_Array['Product_Mcp']		= $Table_Field_Arr['Product_Mcp'];

$Title_Array['Product_Sort']	= $Table_Field_Arr['Product_Sort'];
$Title_Array['Product_OpenNew']	= $Table_Field_Arr['Product_OpenNew'];
$Title_Array['Product_OpenHot']	= $Table_Field_Arr['Product_OpenHot'];
$Title_Array['Product_Open']	= $Table_Field_Arr['Product_Open'];

$sn = ($StartNum + 1);

if (!empty($Order_By)) {

	$db->Order_By = $Order_By;
} else if (!empty($Now_List['Menu_OrderBy'])) {

	$db->Order_By = $Now_List['Menu_OrderBy'];
} else {

	$db->Order_By = " ORDER BY Product_Sort DESC, Product_ID DESC";
}

$db->query_sql($Sheet, "*", $StartNum, $Page_Size);
while ($row = $db->query_fetch()) {
	$price='';
	$price1='';
	$Value_Array['Ordersn'][$sn]	 	= $sn;
	$Value_Array[$Main_Key][$sn]	 	= $row[$Main_Key];
	// $Value_Array['Product_ID'][$sn]		= $row['Product_ID'];
	$Value_Array['Product_Name'][$sn]	= $row['Product_Name'];
	$Value_Array['ProductC_Name'][$sn]	= $row['ProductC_Name'];
	$Value_Array['Product_Name'][$sn]	= $row['Product_Name'];

	$Value_Array['Product_Mcp'][$sn]	= $row['Product_Mcp'];
	if( !empty($row['Product_Mcp']) ){
			
		$Value_Array['Product_Mcp_bUrl'][$sn] = $Pathweb.$row['Product_Mcp'];
		$Value_Array['Product_Mcp_sUrl'][$sn] = $Pathweb.'sm_'.$row['Product_Mcp'].'?'.time();
	}

	$price_arr = unserialize($row['Product_Price']);
	$price1_arr = unserialize($row['Product_Price1']);

	$price_count = count($price_arr);
	$price1_count = count($price1_arr);

	for ($i = 0; $i < $price_count; $i++) {
		
			$price .= $price_arr[$i] . ($i < $price_count-1?'/':'');
		
	}

	for ($i = 0; $i < $price1_count; $i++) {
		
			$price1 .= $price1_arr[$i] . ($i < $price1_count-1?'/':'');
		
	}

	$Value_Array['Product_Price'][$sn]	= $price;
	$Value_Array['Product_Price1'][$sn]	= $price1;


	$Value_Array['Product_Sort'][$sn]		= $row['Product_Sort'];
	$Value_Array['Product_OpenNew'][$sn]	= $row['Product_OpenNew'];
	$Value_Array['Product_OpenHot'][$sn]	= $row['Product_OpenHot'];
	$Value_Array['Product_Open'][$sn]		= $row['Product_Open'];

	//呈現的種類
	$Vtype_Array['Product_Mcp'][$sn]		= 'uploadimg';
	$Vtype_Array['Product_Sort'][$sn]		= 'number';
	$Vtype_Array['Product_OpenNew'][$sn]	= 'checkbox';
	$Vtype_Array['Product_OpenHot'][$sn]	= 'checkbox';
	$Vtype_Array['Product_Open'][$sn]		= 'checkbox';
	$sn++;
}

$table_option_arr['Product_Sort']['TO_OutEdit']		= 1;
$table_option_arr['Product_OpenNew']['TO_OutEdit']	= 1;
$table_option_arr['Product_OpenHot']['TO_OutEdit']	= 1;
$table_option_arr['Product_Open']['TO_OutEdit']		= 1;

$Order_Array = array("Product_ID" => "", "Product_Name" => "", "ProductC_Name" => "", "Product_Price" => "", "Product_Sort" => "", "Product_OpenNew" => "", "Product_OpenHot" => "", "Product_Open" => ""); //允許排序項目	

//第一個值設定對應KEY,第二個值設定對應名稱
$_FT = new Fixed_Table($Value_Array[$Main_Key], $Value_Array['Ordersn']);
$_FT->Input_TitleArray($Title_Array); //設定顯示欄位
$_FT->Input_ValueArray($Value_Array); //設定顯示欄位裡面的值
$_FT->Input_VtypeArray($Vtype_Array); //設定顯示欄位呈現方式
$_FT->Input_OrderArray($Order_Array, $Sort, $Field); //設定可排序的欄位, 升序或降序, 要排序欄位名稱
$_FT->Pages_Data 		= $Page_Calss->Pages_Data; //頁碼資料
$_FT->Pages_Html 		= $Page_Calss->Pages_Html; //頁碼頁面
$_FT->SearchKey  		= $SearchKey; //搜尋字串
$_FT->Operating	 		= array("View" => $Now_List['Menu_View'], "Edit" => $Now_List['Menu_Edt'], "Delete" =>  $Now_List['Menu_Del']); //設定操作類型
$_FT->Path		 		= SYS_PATH . $Now_List['Menu_Path'] . DIRECTORY_SEPARATOR; //檔案路徑
$_FT->TableHtml	 		= $Now_List['Menu_Exec_Name'] . '.table.' . $Now_List['Exec_Sub_Name'];
$_FT->table_option_arr	= $table_option_arr; //資料表的設定陣列

$_html = $_FT->CreatTable(); //創建表格

if (!empty($POST)) {

	echo $_html;
} else {
?>

	<script type="text/javascript" src="assets/js/sys-table.js"></script>
	<script type="text/javascript">
		var Exec_Url = '<?= $Now_List['Menu_Path'] ?>/<?= $Now_List['Menu_Exec_Name'] ?>.post.php?fun=' + getUrlVal(location.search, 'fun');
	</script>

	<div class="table-header">
		<span><?= $Now_List['Menu_Name'] ?></span>

		<?php if (!empty($_Search_Option)) { ?>
			<span class="extra-fun">
				<i class="fa fa-tasks"></i>進階功能
			</span>
		<?php } ?>
	</div>

	<?php require_once(SYS_PATH . 'php_sys/extra_div.php') ?>

	<div id="table_content"><?= $_html ?></div>

	<div id="table_content_edit" class="display_none"></div>

	<div id="table_content_view" class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
		<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
		<div class="contents"></div>
	</div>
<?php
} ?>