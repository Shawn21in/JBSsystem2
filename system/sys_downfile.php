<?php
require_once("include/inc.config.php");
require_once("include/inc.check_login.php");

$msg  = '';
$FILE = $FILEPATH = '';
$code = $_GET['code'];

if( $code != $_SESSION['system']['downloadcode'] || empty($code) || empty($_SESSION['system']['downloadcode']) ){
	
	$msg = '下載編碼錯誤';
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
	
	$db->query_delete(Download_DB);
	
	$db->query_optimize(Download_DB);//最佳化資料表
	
	$db = new MySQL();
	
	$ex 		= explode('-', $_row['DL_DownLoadInfo']);
	$db->Where 	= " WHERE " .$ex[1]. " = '" .$db->val_check($ex[2]). "'";
	$db->query_sql($ex[0], $ex[3]);
	$File_Name 	= $db->query_fetch($ex[3]);
	
	//$FILE 		= ICONV_CODE( 'UTF8_TO_BIG5', $_row['DL_DownLoadUrl'].$File_Name );
	$FILEPATH 	= ICONV_CODE( 'UTF8_TO_BIG5', $_row['DL_DownLoadPath'].$File_Name );
	
	ob_end_clean();
	
	if( !is_file($FILEPATH) ){
		
		$msg = '無此檔案';
	}else{
		
		$len 		= filesize($FILEPATH);
		$ex			= explode('.', $File_Name);
		$extension 	= strtolower(end($ex));
		
		//將檔案格式設定為將要下載的檔案
		switch( $extension ) {
			
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xlsx":
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
			
			ob_end_clean();
			
			@readfile($FILEPATH);
		}
	}
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