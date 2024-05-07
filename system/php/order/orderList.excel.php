<?php
require_once("../../include/inc.config.php");
require_once("../../include/inc.check_login.php");

$msg  = '';
$code = $_GET['code'];

if( $code != $_SESSION['system']['downloadcode'] || empty($code) || empty($_SESSION['system']['downloadcode']) ){
	
	$msg = '匯出編碼錯誤';
}

ob_start();

$db = new MySQL();

$db->Where = " WHERE DL_Session = '" .$db->val_check($code). "'";
$db->query_sql(Download_DB, '*');

$_row = $db->query_fetch();

ob_end_clean();

if( empty($_row) ){
	
	$msg = '編碼錯誤';
}else{
	
	ob_start();
	
	//$db->query_delete(Download_DB);
	
	//$db->query_optimize(Download_DB);//最佳化資料表
	
	$db = new MySQL();
	
	require_once(SYS_PATH.'plugins'.DIRECTORY_SEPARATOR.'PHPExcels'.DIRECTORY_SEPARATOR.'PHPExcel.php');

	$Menu_Data 		= MATCH_MENU_DATA(FUN);//取得目前目錄資料
	
	$Main_Key  		= $Menu_Data['Menu_TableKey'];//資料表主鍵
	
	$Main_Table 	= $Menu_Data['Menu_TableName'];//目錄使用的資料表
		
	$Main_Key2		= $Menu_Data['Menu_TableKey1'];//擴充資料表主鍵
	
	$Main_Table2 	= $Menu_Data['Menu_TableName1'];//擴充資料表
	
	
	$Sheet = $Main_Table. ' as a LEFT JOIN ' .$Main_Table2. ' as b ON a.' .$Main_Key. ' = b.' .$Main_Key;
	
	$db->Where 		= str_replace('Member_ID', 'a.Member_ID', $_row['DL_DownLoadInfo']);
	$db->Order_By 	= ' ORDER BY Orderd_ID ASC';
	
	$db->query_sql($Sheet, '*');
	$Order_Arr = array();
	while( $row = $db->query_fetch() ){
	
		$Order_Arr[$row[$Main_Key]][] = $row;
	}
	//print_r($Order_Arr);
	//exit;
	
	$objExcel  = new PHPExcel();
	$objWriter = new PHPExcel_Writer_Excel5($objExcel);
				
	$objExcel->setActiveSheetIndex(0);
	
	$objActSheet = $objExcel->getActiveSheet();
	
	$objActSheet->getDefaultRowDimension()->setRowHeight(20);//預設欄高
	$objActSheet->setTitle('訂單匯出');
	
	$Title_Arr	= array('訂單編號' => 'A', '產品編號' => 'B', '產品名稱' => 'C', '數量' => 'D', '價錢' => 'E', '小計' => 'F', '總金額' => 'G', '運費' => 'H', '收件人姓名' => 'I', '收件人電話' => 'J', '收件人地址' => 'K', '付款方式' => 'L', '配送時間' => 'M', '備註' => 'N');
	
	$Sn = 1;
	
	foreach( $Title_Arr as $Name => $Number ){
		
		$objActSheet->setCellValue($Number.$Sn, $Name);// 字符串內容
		$objActSheet->getColumnDimension($Number)->setAutoSize(true);//這個比較有用，能自適應列寬
	}
	
	$Sn++;
	
	foreach( $Order_Arr as $Oid => $Arr ){
		
		foreach( $Arr as $key => $val ){
			
			foreach( $Title_Arr as $Name => $Number ){
				
				if( $key == 0 ){
				
					if( $Number == 'A' ){
						
						$objActSheet->setCellValue($Number.$Sn, $Oid);
					}else if( $Number == 'B' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Product_ID']);
					}else if( $Number == 'C' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderd_Name']);
					}else if( $Number == 'D' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderd_Count']);
					}else if( $Number == 'E' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderd_Price']);
					}else if( $Number == 'F' ){
						
						$objActSheet->setCellValue($Number.$Sn, ( $val['Orderd_Count'] * $val['Orderd_Price'] ));
					}else if( $Number == 'G' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderm_TotalPrice']);
					}else if( $Number == 'H' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderm_Freight']);
					}else if( $Number == 'I' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderm_RName']);
					}else if( $Number == 'J' ){
						//轉成字串
						$objActSheet->setCellValueExplicit($Number.$Sn, $val['Orderm_RMobile']);
					}else if( $Number == 'K' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderm_RCity'].$val['Orderm_RCounty'].$val['Orderm_RAddr']);
					}else if( $Number == 'L' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderm_Delivery']);
					}else if( $Number == 'M' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderm_PickupTime']);
					}else if( $Number == 'N' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderm_Note']);
					}
				}else{
					
					if( $Number == 'B' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Product_ID']);
					}else if( $Number == 'C' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderd_Name']);
					}else if( $Number == 'D' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderd_Count']);
					}else if( $Number == 'E' ){
						
						$objActSheet->setCellValue($Number.$Sn, $val['Orderd_Price']);
					}else if( $Number == 'F' ){
						
						$objActSheet->setCellValue($Number.$Sn, ( $val['Orderd_Count'] * $val['Orderd_Price'] ));
					}
				}
				
				$objActSheet->getStyle($Number.$Sn)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直置中	
			}
			
			$Sn++;
		}
	}
	
	//$objActSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//只設定單一欄位
	//$objActSheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//同時設定多個欄位 水平置中
	//HORIZONTAL_CENTER 水平置中
	//HORIZONTAL_RIGHT 水平靠右
	//HORIZONTAL_LEFT 水平靠左
	//$objActSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);//垂直置頂
	//VERTICAL_TOP 垂直置頂	
	//$objActSheet->getColumnDimension('A')->setAutoSize(true);//這個比較有用，能自適應列寬
	//$objActSheet->getColumnDimension('A')->setWidth(50);//設定欄寬
	
	//$objWriter->save('output.xls');//儲存
	
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	header("Content-Type:application/force-download");
	header("Content-Type:application/vnd.ms-execl");
	header("Content-Type:application/octet-stream");
	header("Content-Type:application/download");;
	header('Content-Disposition:attachment;filename="output.xls"');
	header("Content-Transfer-Encoding:binary");
	
	//$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');//2003格式 
	$objWriter->save('php://output');//下載
	}

if( !empty($msg) ){
	
	$Main = new Main();
	$Main->set_head();
?> 

	<script type="text/javascript">
    
    $(document).ready(function(e) {
        
    <?php if( !empty($msg) ){ ?>
        $(".tc_box").BoxWindow({
            _msg: '<?=$msg?>',//訊息
            _eval: 'Back()'
        });
    <?php } ?>
    });
    </script>
    
    <body>
    <?=$Main->set_box()?>
    </body>
    </html>
<?php 
} ?>