<?php 
if( !function_exists('Chk_Login') ) header('Location: ../index.php'); 

$table_info = $db->get_table_info($Main_Table);//取出資料表欄位的詳細的訊息		


$_html = $_html2 = array();
		
$db->Where = " WHERE " .$Main_TablePre. "_Lv <= '" .$Main_maxLv3. "'";

$db->Order_By = " ORDER BY " .$Main_TablePre. "_Lv ASC, " .$Main_TablePre. "_Sort ASC, " .$Main_TablePre. "_ID ASC";

$db->query_sql($Main_Table, "*");
while( $row = $db->query_fetch() ){
	
	if( $row[$Main_TablePre.'_Lv'] == 1 ){
		
		$_html[$row[$Main_TablePre.'_ID']] = $row;
	}else{
		
		$_html2[$row[$Main_TablePre.'_UpMID']][] = $row;
	}
}
/*ID陣列*/
$_test1 = $_test2 = array();
$db2 = new MySQL();
$db2->query_sql($Main_Table, $Main_TablePre.'_ID');
while( $row = $db2->query_fetch() ){
	
	$_test1[$row[$Main_TablePre.'_ID']] = $row[$Main_TablePre.'_ID'];
}
//print_r($_test1);
/*被比對陣列*/
$db3 = new MySQL();
$db3->query_sql($Main_Table, $Main_TablePre.'_ID'.",".$Main_TablePre.'_UpMID'.",".$Main_TablePre.'_Lv');
while( $row = $db3->query_fetch() ){
	
	$_test2[$row[$Main_TablePre.'_ID']] = $row;
}
//print_r($_test2);
/*被比對ID不在ID陣列中，並且lv!=1，進行刪除*/
$db4 = new MySQL();

foreach( $_test2 as $key => $val ){
	
	if( !in_array($val[$Main_TablePre.'_UpMID'], $_test1) && ($val[$Main_TablePre.'_Lv'] != 1) ){
		
		$db4->Where = " WHERE " .$Main_TablePre. "_ID ='".$key."'";
		$db4->query_delete($Main_Table);
		//echo $db4->sql;
	}
}
//取公司列表
$db5 = new MySQL();
$db->Where = " WHERE 1=1";
$db5->query_sql('web_company', '*');
while( $row = $db5->query_fetch() ){
	$_comlist[$row['Company_bid']] = $row;
}
?>


<!-- echart -->
<script src="assets/js/chart_record.js"></script>
<script src="plugins/chart/plugin/lib/simpleRequire.js"></script>
<script src="plugins/chart/plugin/lib/config.js"></script>
<!-- <script src="plugins/chart/plugin/lib/jquery.min.js"></script> -->
<script src="plugins/chart/plugin/lib/facePrint.js"></script>
<script src="plugins/chart/plugin/lib/testHelper.js"></script>
<link rel="stylesheet" href="plugins/chart/plugin/lib/reset.css" />
<!-- echart -->

<script type="text/javascript">



var Exec_Url = '<?=$Now_List['Menu_Path']?>/<?=$Now_List['Menu_Exec_Name']?>.post.php?fun=' + getUrlVal(location.search, 'fun');
</script>
<div class="table-header"><?=$Now_List['Menu_Name']?></div>

<div id="menu_content">
    <form id="form_all" method="post" onSubmit="return false;">
	    <div class="content_top_option" style="padding-bottom: 10px;">
            <!-- <a>選擇類型:</a>
                <select name="stype" id="stype">
                    <option value="login">登入</option>
                    <option value="select">查詢</option>
                    <option value="update">修改</option>
                </select> -->
            <a>選擇公司:</a>
            <select name="compID" id="compID">
                <!-- <option value="">全部</option> -->
                <?php foreach($_comlist as $key => $value){ ?>
                    <option value="<?= $key ?>"><?= $value['Company_NAME'] ?></option>
                <?php } ?>
			</select>
            <a>開始時間:</a>
            <input name="sdate" type="date" value="<?php echo date("Y/m/d")?>" >
	    	<a>結束時間:</a>
            <input name="edate" type="date" value="<?php echo date("Y/m/d")?>" >

	    	<button type="button" class="btn btn-white btn-purple btn-sm " onclick="lineChartResult($('#form_all').serializeArray(),'chartlocation','')">
                <span>送出</span>
            </button>
        </div>
    </form>
    <!-- <div class="datalist" style="height: 300px; overflow-y: scroll;">
    
    </div> -->

    <!-- 圖表位置START -->
    <div class="employee">
        <div id="chartlocation" style="width:100%;height: 90%;"></div>
        <div class="" style="min-height: 60vh;width:100%; "></div>
    </div>
    <!-- 圖表位置END -->
</div>

<div id="menu_content_edit" class="display_none">
</div>