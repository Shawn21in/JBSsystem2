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

$chart_stype = isset($_POST['stype']) ? ($_POST['stype']) : '';
$chart_start 	 = isset($_POST['sdate']) ? ($_POST['sdate']) : '';
$chart_end 	 = isset($_POST['edate']) ? ($_POST['edate']) : '';
$chart_comp = isset($_POST['compID']) ? ($_POST['compID']) : '';
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

// test: print_r(date_range('2014-06-22', '2014-07-02'));

/*領取數值Start*/
$chart_comp = $chart_comp;
//登入 //COUNT(date_format(a.ML_date,'%Y-%m-%d'))
$chart_stype = "login";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM $dtName,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
		AND ML_USER = '$chart_comp'
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
			AND ML_USER = '$chart_comp'
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT SUM(a.ML_COMMENT),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND ML_USER = '$chart_comp'
            GROUP BY xunit
    
    ORDER BY xunit
");

while($rowLine1 = $db->query_fetch()){
    array_push($getChartValue, $rowLine1['countnum']);
	array_push($columLabel, $rowLine1['xunit']."日");
}

//問券生成
$chart_stype = "generate";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM $dtName,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
		AND ML_USER = '$chart_comp'
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
			AND ML_USER = '$chart_comp'
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT SUM(a.ML_COMMENT),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND ML_USER = '$chart_comp'
            GROUP BY xunit
    
    ORDER BY xunit
");

while($rowLine1 = $db->query_fetch()){
    array_push($getChartValue2, $rowLine1['countnum']);
}

//投放問券
$chart_stype = "send";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM $dtName,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
		AND ML_USER = '$chart_comp'
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
			AND ML_USER = '$chart_comp'
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT SUM(a.ML_COMMENT),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND ML_USER = '$chart_comp'
            GROUP BY xunit
    
    ORDER BY xunit
");

while($rowLine1 = $db->query_fetch()){
    array_push($getChartValue3, $rowLine1['countnum']);
}

//折扣券
$chart_stype = "coupon";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM $dtName,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
		AND ML_USER = '$chart_comp'
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
			AND ML_USER = '$chart_comp'
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT SUM(a.ML_COMMENT),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND ML_USER = '$chart_comp'
            GROUP BY xunit
    
    ORDER BY xunit
");

while($rowLine1 = $db->query_fetch()){
    array_push($getChartValue4, $rowLine1['countnum']);
}

//數據
$chart_stype = "analyze";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM $dtName,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
		AND ML_USER = '$chart_comp'
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
			AND ML_USER = '$chart_comp'
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT SUM(a.ML_COMMENT),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND ML_USER = '$chart_comp'
            GROUP BY xunit
    
    ORDER BY xunit
");

while($rowLine1 = $db->query_fetch()){
    array_push($getChartValue5, $rowLine1['countnum']);
}

//設定
$chart_stype = "setting";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM $dtName,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
		AND ML_USER = '$chart_comp'
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
			AND ML_USER = '$chart_comp'
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT SUM(a.ML_COMMENT),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM $dtName a
            WHERE 1=1
            AND a.ML_SQL_EXEC_TYPE = '$chart_stype'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND ML_USER = '$chart_comp'
            GROUP BY xunit
    
    ORDER BY xunit
");

while($rowLine1 = $db->query_fetch()){
    array_push($getChartValue6, $rowLine1['countnum']);
}

/*使用數值End*/



// 取得店家名稱
// $getCompName = $DBConn->pdo_connect()->prepare("
//             SELECT companyid,companyname 
//             FROM web_company 
//             WHERE companyid = '$companyid'
//         ");

// $getCompName->execute();



$db->query("
                SELECT *
                FROM $dtName 
                WHERE 1=1
                AND ML_date BETWEEN '$trimpara3' AND '$trimpara4'
                GROUP BY ML_ID
");

$aData = array();
$tr = '';
while($rowLinedata = $db->query_fetch()){

    $aData[] = $rowLinedata;
    $tr .= '<tr>
                <td>'.$rowLinedata["ML_USER"].'</td>
                <td>'.$rowLinedata["ML_SQL_EXEC_TYPE"].'</td>
                <td>'.$rowLinedata["ML_SQL_CON"].'</td>
                <td>'.$rowLinedata["ML_DATE"].'</td>
            </tr> ';
}



$shtml =    '<table border="1">
        <tr>
            <th>使用者</th>
            <th>訊息種類</th>
            <th>執行內容</th>
            <th>建立時間</th>
        </tr>'.
        $tr.'
    </table>';


$rowCompName = '使用紀錄';

$labelset = [
    'show' => 'true',
    'position' => 'top'
];


$line1_chartlist = [
    'label' => $labelset,
    'name' => '寄送成功',
    'type' => "line",
    'data' => $getChartValue

];

// $line2_chartlist = [
//     'label' => $labelset,
//     'name' => '寄送失敗',
//     'type' => "line",
//     'data' => $getChartLine2Value


// ];


/*將陣列資料組合到總陣列上*/

$resultArray["companyid"] = $chart_comp;
$resultArray["companyname"] = $chart_comp_name.' ';

$resultArray["date1"] = $chart_start.'日';
$resultArray["date2"] = $chart_end.'日';

$resultArray["xunit"] = date_range($chart_start, $chart_end);

$resultArray["line1data"] = $getChartValue;  //領取資料陣列
$resultArray["line2data"] = $getChartValue2;  //領取資料陣列
$resultArray["line3data"] = $getChartValue3;  //領取資料陣列
$resultArray["line4data"] = $getChartValue4;  //領取資料陣列
$resultArray["line5data"] = $getChartValue5;  //領取資料陣列
$resultArray["line6data"] = $getChartValue6;  //領取資料陣列
// $resultArray["line2data"] = $getChartLine2Value;  //使用資料陣列

//找出陣列最大數據
$checkMax[0] = max($getChartValue);
$checkMax[1] = max($getChartValue2);
$checkMax[2] = max($getChartValue3);
$checkMax[3] = max($getChartValue4);
$checkMax[4] = max($getChartValue5);
$checkMax[5] = max($getChartValue6);
$resultArray["maxdata"] = max($checkMax); 

$resultArray["type"]=$chart_stype;
$resultArray["msg"]=$msg;

$resultArray["shtml"]=$shtml;



echo json_encode($resultArray);
// echo json_encode($getChartLine2Value);

?>