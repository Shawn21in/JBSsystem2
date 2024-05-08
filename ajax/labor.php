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
$_api_url = "https://apiservice.mol.gov.tw/OdService/rest/datastore/A17000000J-020014-q8B";

$accept_data = file_get_contents($_api_url);
$accept_array = json_decode($accept_data, true);


$records = array_filter($accept_array['result']['records'], function($example) {return $example['身分別'] == '一般勞工';});
//只提取一般勞工的資料進行處理


$monthly_salaries = array_map(function($example) {return str_replace('元', '', $example['月投保薪資']);}, $records);
// 只提取月投保薪資

//print_r($monthly_salaries);
echo json_encode($monthly_salaries, 256);

?>