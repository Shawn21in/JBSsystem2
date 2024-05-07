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
$_POST = arr_filter($_POST); //簡易輸入過濾

//判斷POST參數是否正確
if (empty($_POST['year'])) {
    $_html_msg = '資料錯誤';
    $_html_status = '1';
}
if (empty($_html_msg)) {
    $_api_url = "https://info.nhi.gov.tw/api/iode0000s01/Dataset?rId=A21030000I-B10006-00T";
    $accept_csv = file_get_contents($_api_url);

    // 使用 str_getcsv() 函數解析 CSV 字符串為二維數組
    $csv_lines = array_map('str_getcsv', explode("\n", $accept_csv));

    // 將數組轉換為 JSON 格式的字符串
    $json_data = json_encode($csv_lines);

    // 寫入 JSON 字符串到文件中
    file_put_contents('data.json', $json_data);

    // 打印 JSON 字符串
    echo $json_data;
    // if (!empty($accept_csv)) {
    //     $accept_array = json_decode($accept_csv, true);
    //     $holiday_array =
    //         array_filter($accept_array, function ($value) {
    //             return $value['description'] != '';
    //         });
    //     foreach ($holiday_array as $h => $ha) {
    //         $holiday_array[$h]['date'] = date('m-d', strtotime($holiday_array[$h]['date']));
    //     }
    //     $_html_content = $holiday_array;
    //     $_html_status = '2';
    //     $_html_msg = '匯入成功';
    // } else {
    //     $_html_msg = '請確認年份是否正確，不可超過當前年份';
    //     $_html_status = '1';
    // }
}

// $json_array['html_msg']     = $_html_msg ? $_html_msg : ''; //訊息
// $json_array['html_href']    = $_html_href ? $_html_href : ''; //連結
// $json_array['html_status']  = $_html_status ? $_html_status : ''; //確定後要執行的JS
// $json_array['html_content'] = $_html_content ? $_html_content : ''; //輸出內容

// echo json_encode($json_array, 256);
