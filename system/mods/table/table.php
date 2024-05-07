<?php
if( !function_exists('Chk_Login') ) header('Location: ../../index.php');

$_Search_Option = $Select_Arr = array();

$Table_Field_Arr = $db->get_table_info($Main_Table, 'Comment');

/*if( !empty($Main_Key3) && !empty($Main_Table3) && !empty($Main_TablePre3) ){//有開啟分類資料表

	$Sheet 		= $Main_Table. ' as a LEFT JOIN (' .$Main_Table3. ' as b) ON (a.' .$Main_Key3. ' = b.' .$Main_Key3. ')';
	$Columns 	= 'a.*, b.'.$Main_TablePre3.'_Name';
}else{*/
	
	$Sheet 		= $Main_Table;
	$Columns	= '*';
//}

$Order_ByArr = array();
$Title_Array['Ordersn']	= "序號";
foreach( $table_option_arr as $tofield => $toarr ){
	
	$Comment = $Table_Field_Arr[$tofield];
	if( empty($Comment) ){ continue; }	//無此欄位跳過
	
	$Title_Array[$tofield]	= $Comment;	//欄位註解
	$Order_Array[$tofield]	= '';		//允許排序項目
	
	if( $toarr['TO_InType'] == 'select' ){
		
		if( !empty($Main_Key3) && $tofield == $Main_Key3 ){//有開啟分類資料表
			
			global ${$Main_Key3};
			
			$toarr['TO_SelStates'] = $Main_Key3;
			$$Main_Key3 = Class_Get('TYPE1');//抓取分類列表,進階搜尋使用
			
			if( $toarr['TO_OutEdit'] ){
				
				$Select_Arr[$tofield] = Class_Get('TYPE1', array(''=>'請選擇'));//抓取分類列表
			}else{
				
				$Select_Arr[$tofield] = Class_Get();//抓取分類列表
			}
		}else{
			
			global ${$toarr['TO_SelStates']};
		
			$Select_Arr[$tofield] = ($$toarr)['TO_SelStates'];	
		}
	}
		
	if( $toarr['TO_InType'] == 'uploadimg' || $toarr['TO_InType'] == 'uploadfile' || $toarr['TO_InType'] == 'youtube' /*|| $toarr['TO_InType'] == 'checkbox'*/ ){ 
		
		continue;
	}
	
	if( $toarr['TO_InType'] == 'datecreat' || $toarr['TO_InType'] == 'sortasc' || $toarr['TO_InType'] == 'sortdesc' ){
		
		if( $toarr['TO_InType'] == 'sortasc' ){
			
			$Order_ByArr[0] = $tofield. ' ASC';
		}else if( $toarr['TO_InType'] == 'sortdesc' ){
			
			$Order_ByArr[0] = $tofield. ' DESC';
		}else if( $toarr['TO_InType'] == 'datecreat' ){
			
			$Order_ByArr[1] = $tofield. ' DESC';
		}
	}
	
	//$_Key = $tofield != $Main_Key3 ? $tofield : $Main_TablePre3.'_Name';//判別是否是分類的對應欄位
	$_Key = $tofield;//判別是否是分類的對應欄位
	
	$db->Search[]			= $_Key;//設定可搜尋欄位
	$_Search_Option[$_Key] 	= Search_Option($toarr, $Comment);//設定進階搜尋
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
}else if( !empty($Now_List['Menu_OrderBy']) ){//目錄自定義排序
	
	$db->Order_By = $Now_List['Menu_OrderBy'];
}else{
		
	if( empty($Order_ByArr) || empty($Order_ByArr[1]) ){
		
		$Order_ByArr[] = $Main_Key.' DESC';
	}
	
	$db->Order_By = ' ORDER BY ' .implode(',', $Order_ByArr);
}

$db->query_sql($Sheet, $Columns, $StartNum, $Page_Size);

$sn = ( $StartNum + 1 );
while( $row = $db->query_fetch() ){
		
	$Value_Array['Ordersn'][$sn]		= $sn;
	$Value_Array[$Main_Key][$sn]		= $row[$Main_Key];
	
	foreach( $table_option_arr as $tofield => $toarr ){
		
		//$_Key = $key != $Main_Key3 ? $key : $Main_TablePre3.'_Name';//判別是否是分類的對應欄位

		$Value_Array[$tofield][$sn]	= $row[$tofield];
		
		if( $toarr['TO_InType'] == 'uploadimg' ){
			
			$Value_Array[$tofield.'_bUrl'][$sn] = $Pathweb.$row[$tofield];
			$Value_Array[$tofield.'_sUrl'][$sn] = $Pathweb.'sm_'.$row[$tofield].'?'.time();
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

<!--<div id="table_content_view" class="modal-demo">
    <button type="button" class="close" onclick="Custombox.close()">
        <span>×</span><span class="sr-only">Close</span>
    </button>
    <!--<h4 class="title">Modal title</h4>-->
    <!--<div class="contents"></div>
</div>-->

<div id="table_content_view" class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div class="contents"></div>
</div>
<?php 
}?>