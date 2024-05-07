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
$line1_chartlist = array();
$getChartLine2Value = array();
$line2_chartlist = array();


$chart_stype = isset($_POST['stype']) ? ($_POST['stype']) : '';
$chart_start 	 = isset($_POST['sdate']) ? ($_POST['sdate']) : '';
$chart_end 	 = isset($_POST['edate']) ? ($_POST['edate']) : '';




$trimpara3 = $chart_start.' 00:00:00';
$trimpara4 = $chart_end.' 00:00:00';




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

//新增
$chart_stype = "INSERT";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM sys_mysql_log,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT COUNT(date_format(a.ML_date,'%Y-%m-%d')),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            
            GROUP BY xunit
    
    ORDER BY xunit
");

// $line1QuerySQL = $db->query_fetch();

while($rowLine1 = $db->query_fetch()){

    //echo $rowLine1['SHOP_Name'].$rowLine1['SHOP_ID'].$rowLine1['COUPON_NAME'].$rowLine1['countnum'].$rowLine1['xunit'] ;
    array_push($getChartValue, $rowLine1['countnum']);
    array_push($columLabel, $rowLine1['xunit']."日");

}

//修改
$chart_stype = "UPDATE";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM sys_mysql_log,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT COUNT(date_format(a.ML_date,'%Y-%m-%d')),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            
            GROUP BY xunit
    
    ORDER BY xunit
");

// $line1QuerySQL = $db->query_fetch();

while($rowLine1 = $db->query_fetch()){
    //echo $rowLine1['SHOP_Name'].$rowLine1['SHOP_ID'].$rowLine1['COUPON_NAME'].$rowLine1['countnum'].$rowLine1['xunit'] ;
    array_push($getChartValue2, $rowLine1['countnum']);
    // array_push($columLabel, $rowLine1['xunit']."日");

}

//刪除
$chart_stype = "DELETE";
$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM sys_mysql_log,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT COUNT(date_format(a.ML_date,'%Y-%m-%d')),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            
            GROUP BY xunit
    
    ORDER BY xunit
");

// $line1QuerySQL = $db->query_fetch();

while($rowLine1 = $db->query_fetch()){

    //echo $rowLine1['SHOP_Name'].$rowLine1['SHOP_ID'].$rowLine1['COUPON_NAME'].$rowLine1['countnum'].$rowLine1['xunit'] ;
    array_push($getChartValue3, $rowLine1['countnum']);
    // array_push($columLabel, $rowLine1['xunit']."日");

}

//查看
$chart_stype = "SELECT";

$db->query("
SELECT 0 AS countnum,t.xunit
    FROM (
        SELECT 
        (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
        FROM sys_mysql_log,(select @num:=0) t 
        WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
        ORDER BY xunit
    ) t
    WHERE 1
    AND NOT EXISTS(
        SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
            GROUP BY a.ML_date
    ) 
    
    UNION 
    
    SELECT DISTINCT COUNT(date_format(a.ML_date,'%Y-%m-%d')),date_format(a.ML_date,'%Y-%m-%d') AS xunit
            FROM sys_mysql_log a
            WHERE 1=1
            AND a.ML_SQL_CON LIKE '%$chart_stype%'
            AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            
            GROUP BY xunit
    
    ORDER BY xunit
");

// $line1QuerySQL = $db->query_fetch();

while($rowLine1 = $db->query_fetch()){

    //echo $rowLine1['SHOP_Name'].$rowLine1['SHOP_ID'].$rowLine1['COUPON_NAME'].$rowLine1['countnum'].$rowLine1['xunit'] ;
    array_push($getChartValue4, $rowLine1['countnum']);
    // array_push($columLabel, $rowLine1['xunit']."日");

}




/*領取數值End*/


/*使用數值Start*/

// $db->query("
// SELECT 0 AS countnum,t.xunit
//     FROM (
//         SELECT 
//         (@num:=@num+1) AS sid,date_format(adddate(DATE_SUB('$trimpara3',INTERVAL 1 DAY), INTERVAL @num DAY),'%Y-%m-%d') AS xunit
//         FROM sys_mysql_log,(select @num:=0) t 
//         WHERE adddate('$trimpara3', INTERVAL @num DAY) <= DATE_FORMAT('$trimpara4','%Y-%m-%d')
//         ORDER BY a.ML_date	
//     ) t
//     WHERE 1
//     AND NOT EXISTS(
//         SELECT DISTINCT date_format(a.ML_date,'%Y-%m-%d') AS trimdate
//             FROM sys_mysql_log a
//             WHERE 1=1
//             AND a.ML_SQL_CON LIKE '%$chart_stype%'
//             AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
//             AND date_format(a.ML_date,'%Y-%m-%d') = t.xunit 
//             GROUP BY a.ML_date	
//     ) 
    
//     UNION 
    
//     SELECT DISTINCT COUNT(date_format(a.ML_date,'%Y-%m-%d')),date_format(a.ML_date,'%Y-%m-%d') AS xunit
//             FROM sys_mysql_log a
//             WHERE 1=1
//             AND a.ML_SQL_CON LIKE '%$chart_stype%'
//             AND a.ML_date BETWEEN '$trimpara3' AND '$trimpara4'
            
//             GROUP BY a.ML_date
    
//     ORDER BY a.ML_date	
// ");

// $line2QuerySQL = $db->query_fetch();

// while($rowLine2 = $db->query_fetch()){

//     //echo $rowLine1['SHOP_Name'].$rowLine1['SHOP_ID'].$rowLine1['COUPON_NAME'].$rowLine1['countnum'].$rowLine1['xunit'] ;
//     array_push($getChartLine2Value, $rowLine2['countnum']);
//     array_push($columLabel, $rowLine2['xunit']."日");

// }




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
                FROM sys_mysql_log 
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

$resultArray["companyid"] = 'aaaa';
$resultArray["companyname"] = 'Ai2Bi';

$resultArray["date1"] = $chart_start.'日';
$resultArray["date2"] = $chart_end.'日';

$resultArray["xunit"] = date_range($chart_start, $chart_end);

$resultArray["line1data"] = $getChartValue;  //領取資料陣列
$resultArray["line2data"] = $getChartValue2;  //領取資料陣列
$resultArray["line3data"] = $getChartValue3;  //領取資料陣列
$resultArray["line4data"] = $getChartValue4;  //領取資料陣列
// $resultArray["line2data"] = $getChartLine2Value;  //使用資料陣列

$resultArray["maxdata"] = max($getChartValue); //找出陣列最大數據


$resultArray["type"]=$chart_stype;
$resultArray["msg"]=$msg;

$resultArray["shtml"]=$shtml;



echo json_encode($resultArray);
// echo json_encode($getChartLine2Value);

?>