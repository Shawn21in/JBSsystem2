<?php
require_once(__DIR__.'/include/web.config.php');
require 'plugins/phpSpreadsheet/vendor/autoload.php';

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
//設置僅讀取數據
$reader->setReadDataOnly(true);
//設置僅讀取Sheet 1 工作表
// $reader->setLoadSheetsOnly(["Sheet1"]);

//讀取項目根目錄下data文件夾裏的test.xlsx 並返回表格對象
$spreadsheet = $reader->load('客戶清冊.xlsx');
//讀取第一個表
$sheet = $spreadsheet->getSheet(0);

//獲取單元格的集合
$cellCollection = $sheet->getCellCollection();
//獲取具有單元格記錄的工作表的最高列和最高行。
$column = $cellCollection->getHighestRowAndColumn();

//內容爲值存入數組
$data = array();
$col_array = array();
$count = 0;
for($j = 'A'; $j <= $column['column']; $j++){//列
    $keyname = $sheet->getCell($j.'1')->getValue();
    for($i = 2; $i <= $column['row']; $i++){//行
        $key = $j.$i;
        $value = $sheet->getCell($key)->getValue();
        $data[$keyname][$i-2] = $value;
    }
}


$db 	= new MySQL();
	
$Upload = new Upload();

$sdate 	= date('Y-m-d H:i:s');

$count  = count($data['Name']);//資料筆數

for($i=0;$i<$count;$i++){
    $_CandidateLaunchData = array(
        'Candidate_Cuname1'	  => $data['Name'][$i],
        'Candidate_Cuemail'  => $data['Email'][$i],
        'Candidate_Enable'	  => 1,
        'Candidate_Sdate'	  => $sdate,
    );
    $db->query_data($candidate_db, $_CandidateLaunchData, 'INSERT');
}
