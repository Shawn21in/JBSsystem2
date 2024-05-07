<?php
require_once("../../include/inc.config.php");
require_once("../../include/inc.check_login.php");


$resultArray = array();
$columLabel = array();
$columLabel2 = array();
$mergecolumLabel = array();
$getChartValue = array();
$getChartValue2 = array();
$getChartValue3 = array();
$getChartValue4 = array();
$getChartValue5 = array();
$getChartValue6 = array();
$line1_chartlist = array();
$getChartLine2Value = array();
$line2_chartlist = array();
$checkMax = array();
$dtName = 'web_mysql_log';

$chart_type = isset($_POST['type']) ? ($_POST['type']) : '';
$chart_start 	 = isset($_POST['sdate']) ? ($_POST['sdate']) : '';
$chart_end 	 = isset($_POST['edate']) ? ($_POST['edate']) : '';
$chart_comp = isset($_POST['compID']) ? ($_POST['compID']) : '';
$chart_num = isset($_POST['num']) ? ($_POST['num']) : '';
$chart_comp_name = '華越資通企管顧問有限公司';

$trimpara3 = $chart_start.' 00:00:00';
$trimpara4 = $chart_end.' 23:59:59';

function date_range($first, $last)
{
    $period = new DatePeriod(
        new DateTime($first),
        new DateInterval('P1D'),
        new DateTime($last)
    );

    foreach ($period as $date)
        $dates[] = $date->format('Y-m-d');

    return $dates;
}
//列出所有符合日期(且篩選出非六日的日期)
$start_date = new DateTime($chart_start);
$end_date = new DateTime($chart_end);

while ($start_date <= $end_date) {
    $day_of_week = $start_date->format('w'); // 0 (Sunday) to 6 (Saturday)
    
    if ($day_of_week != 0 && $day_of_week != 6) {
        $date_array[] = $start_date->format('Y-m-d'); //符合日期將存至陣列中
    }
    
    $start_date->modify('+1 day');
}
// print_r($date_array);
// test: print_r(date_range($chart_start, $chart_end));

switch ($chart_type) {
    case '1':
        $useexec = 'login';
        $usetype = '登入';
        break;
    case '2':
        $useexec = 'generate';
        $usetype = '生成問券';
        break;
    case '3':
        $useexec = 'send';
        $usetype = '投放問券347張';
        break;
    case '4':
        $useexec = 'coupon';
        $usetype = '折扣券設定';
        break;
    case '5':
        $useexec = 'analyze';
        $usetype = '查看數據分析';
        break;
    case '6':
        $useexec = 'setting';
        $usetype = '會員設定';
        break;
    default:
        break;
}
$date_array_length = count($date_array);
$sep = array();
for ($i=0; $i<$chart_num; $i++) { //總筆數為$chart_num，分配方式由總筆數拆分成1筆1筆，再分發至一個陣列內的任一序號(日期總長度)
    $randnum = rand(0,$date_array_length-1);
    $sep[$randnum] += 1;
}
ksort($sep);
// print_r($sep);
foreach($date_array as $key => $value){
    if($sep[$key]){
        $num=$sep[$key];
    }else{
        continue;
    }
    $_Data = array(
        'ML_DATE'		        => $value.' 12:49:31',
        'ML_USER'	            => $chart_comp,
        'ML_DATA_ID'            => '',
        'ML_COMMENT'	        => $num,
        'ML_SQL_CON'	        => $usetype,
        'ML_SQL_EXEC_TYPE '	    => $useexec,
        'ML_EXEC_FILE'          => ''
    );
    $db->query_data('web_mysql_log', $_Data, 'INSERT');
    if( !empty($db->Error) ){
        echo "生成失敗！";
        exit;
    }
}
echo "生成成功！";



// echo json_encode($resultArray);


?>