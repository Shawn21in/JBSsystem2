<?php require_once('web.config.php')?>
<?php


define("JS_ST","<script>");
define("JS_ED","</script>");
define("CT_UTF8","<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />");

$msg	= '';
$code 	= $_GET['code'];

if( empty($code) ){
	
	$msg = '無檔案';
}else{

	$decode = Turndecode($code, 'downfile');
	
	$t		= GetUrlVal($decode, 't');
	$k		= GetUrlVal($decode, 'k');
	$f		= GetUrlVal($decode, 'f');
	$i		= GetUrlVal($decode, 'i');
	$p		= GetUrlVal($decode, 'p');
	//$a		= GetUrlVal($decode, 'a');	
	
	ob_start();
	
	$db = new MySQL();
	
	$db->Where = " WHERE " .$k. " = '" .$db->val_check($i). "'";
	//$db->Where .= " AND " .$f. " = '" .$db->val_check($a). "'";
	$db->query_sql($t, $f);
	//echo $db->query_sql; exit;
	$_row = $db->query_fetch();

	ob_end_clean();
	
	if( empty($_row) ){
		
		$msg = '編碼錯誤';
	}else{
		
		ob_start();
				
		$File_Name 	= $_row[$f];
		
		$FILE 		= ICONV_CODE( 'UTF8_TO_BIG5', $p.$File_Name );
		
		ob_end_clean();
		
		if( !is_file($FILE) ){
			
			$msg = '無此檔案';
		}else{
			
			$len 		= filesize($FILE);
			$ex			= explode('.', $File_Name);
			$extension 	= strtolower(end($ex));

			//將檔案格式設定為將要下載的檔案
			switch( $extension ) {
				
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; break;
				case "mp3": $ctype="audio/mpeg"; break;
				case "wav": $ctype="audio/x-wav"; break;
				case "mpeg":
				case "mpg":
				case "mpe": $ctype="video/mpeg"; break;
				case "mov": $ctype="video/quicktime"; break;
				case "avi": $ctype="video/x-msvideo"; break;
				//禁止下面幾種類型的檔案被下載
				case "php":
				case "htm":
				case "html":
				case "txt": $msg = '檔案禁止下載'; break;
				
				default: $ctype="application/force-download";
			}
			
			if( empty($msg) ){
				
				//開始編寫header
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				
				//使用利用switch判別的檔案類型
				header("Content-Type: $ctype");
				
				//執行下載動作
				header("Content-Disposition: attachment; filename=".$File_Name);
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".$len);
				@readfile($FILE);
			}
		}
	}
}

if( !empty($msg) ){
	
?>
<!doctype html>
<html>
<head> 
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>下載404 - 錯誤</title>
</head>

<body>
<script type="text/javascript">
	
alert('<?=$msg?>');
window.history.go(-1);
</script>
</body>
</html>
<?php 
} ?>