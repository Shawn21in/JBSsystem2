<?php
require_once(dirname(__DIR__) . '/include/web.config.php');
//-----------------------------------------
$_html_href = '';
$_html_msg = '';
$_html_status = '';
$_html_content = '';
//判斷目前是否為登入狀態，且判斷是否有使用該頁面的權限
if (!$_Login) {
    $_html_msg =  "資料錯誤";
    $_html_status = '1';
}
$year = date("Y")-1911;
// A21030000I-B10006-00D 至 A21030000I-B10006-00T
// 民國100~113年 一年可能更新一次到兩次
$URL = array("T","U","V","W","X","Y","Z");
$i = $year-113;
$URLfinal="";
while($i < count($URL)){
    $_api_url = "https://info.nhi.gov.tw/api/iode0000s01/Dataset?rId=A21030000I-B10006-00".$URL[$i];
    
    $accept_csv = file_get_contents($_api_url);

    //echo($_api_url."<br>");
    if(empty($accept_csv)){
        $URLfinal = $URL[$i-1];
        break;
    }
    $i++;
}
    $_api_url = "https://info.nhi.gov.tw/api/iode0000s01/Dataset?rId=A21030000I-B10006-00T".$URLfinal[$i];
    $accept_csv = file_get_contents($_api_url);

    // 使用 str_getcsv() 函數解析 CSV 字符串為二維數組
    $csv_lines = array_map('str_getcsv', explode("\n", $accept_csv));
    $json = array();
    if (!empty($csv_lines)) {
        $i = 1;
        while ($i <= count($csv_lines)) {
            if (!empty($csv_lines[$i][1])) {
                $step = array($csv_lines[$i][1], $csv_lines[$i][2], $csv_lines[$i][6]);
                array_push($json, $step);
            }
            $i++;
        }

        $json_data = json_encode($json);
        file_put_contents('data.json', $json_data);
        echo $json_data;
    }

?>