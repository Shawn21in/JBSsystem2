<?php
if( !isset($_SESSION) ){
	
	session_start();
}

if( empty($_SESSION['system']['language']) || !isset($_SESSION['system']['language']) ) {

	$_SESSION['system']['language'] = 'zh_TW';
}
//當前是哪種通訊協定
$_HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

error_reporting(1);
//error_reporting(E_ALL);

//ini_set('display_errors','Off');

date_default_timezone_set('Asia/Taipei');//設置時區為台灣台北

define( 'CONFIG_EXEC', 'sys_config.php' );//後台主設定檔

define( 'MAIN_EXEC', 'sys_main.php' );//後台主執行檔

define( 'DOWN_EXEC', 'sys_downfile.php' );//後台下載主檔

define( 'PHP_SELF', basename($_SERVER['PHP_SELF']) );//目前檔案名稱含副檔名

define( 'PHP_NAME', basename($_SERVER['PHP_SELF'], '.php') );//目前檔案名稱不含副檔名

define( 'FUN', $_GET['fun'] );//取得fun的內容

define( 'Admin_DB', 'sys_admin' );//管理者的資料表

define( 'Group_DB', 'sys_group' );//群組的資料表


if( $_SESSION['system']['language'] == 'en' ) {
	define( 'Menu_DB', 'en_sys_menu' );//目錄的資料表
}else{
	define( 'Menu_DB', 'sys_menu' );//目錄的資料表
}

define( 'Tables_DB', 'sys_tables' );//資料庫的資料表

define( 'Web_Option_DB', 'sys_web_option' );//網站設定的資料表

define( 'Tables_Option_DB', 'sys_tables_option' );//資料表設定的資料表

define( 'Log_DB', 'sys_mysql_log' );//紀錄訊息的資料表

define( 'Download_DB', 'sys_download' );//下載的資料表

define( 'Recaptcha_DB', 'mod_recaptcha' );//驗證碼資料表

define( 'SYS_PATH', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR );//系統路徑

define( 'SYS_URL', $_HTTP_TYPE.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, stripos($_SERVER['REQUEST_URI'], 'system')).'system/' );//系統網址

define( 'WEB_PATH', dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR );//網站路徑

define( 'WEB_URL', $_HTTP_TYPE.$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'], 0, stripos($_SERVER['REQUEST_URI'], 'system')) );//網站網址

define( 'IMG_PATH', WEB_PATH.'upload'.DIRECTORY_SEPARATOR.'sys_images'.DIRECTORY_SEPARATOR );//圖片上傳路徑

//任何路徑下網頁根目錄
if( $_SERVER['SERVER_NAME'] == 'localhost' ) {

	$e = explode( "/" , $_SERVER['SCRIPT_NAME'] );
	
	define( 'ROOT_URL', $_HTTP_TYPE.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.$e[1].'/' );//網頁網址
	
}else{

	define( 'ROOT_URL', $_HTTP_TYPE.$_SERVER['SERVER_NAME'].'/' );//網頁網址
}

require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.MySQL.php');//載入MYSQL語法class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.Custom.php');//載入MsSQL語法class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.Main.php');//載入主要框架class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.Contents.php');//載入內容class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.Fixed_Table.php');//載入表格class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.TableFun.php');//載入表格語法class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.TablesSetting.php');//載入資料表設定class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.Options.php');//載入資料表創建class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.Pages.php');//載入頁碼class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.MyCurl.php');//載入curl虛擬class
require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'class.Upload.php');//載入上傳class

require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'inc.function.php');//載入方法

if( is_file(SYS_PATH.CONFIG_EXEC) ){
	
	/*if( !is_dir(IMG_PATH) ){
				
		mkdir(IMG_PATH, 0775);
	}*/
	
	define( 'IMG_URL', WEB_URL.'upload/sys_images/' );//圖片顯示網址
	
	define( 'FILE_PATH', WEB_PATH.'upload'.DIRECTORY_SEPARATOR.'sys_files'.DIRECTORY_SEPARATOR );//檔案上傳路徑
	
	/*if( !is_dir(FILE_PATH) ){
				
		mkdir(FILE_PATH, 0775);
	}*/
	
	define( 'FILE_URL', WEB_URL.'upload/sys_files/' );//檔案網址
	
	define( 'Date_YmdHis', '1911-00-00 00:00:00' );//預設時間
	
	define( 'NDT', date('Y-m-d H:i:s') );//預設時間
	
	require_once(SYS_PATH.'plugins'.DIRECTORY_SEPARATOR.'PHPMailer' .DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');//寄件class
	
	require_once(SYS_PATH.'config'.DIRECTORY_SEPARATOR.'cfg.states.php');//載入狀態
	require_once(SYS_PATH.'config'.DIRECTORY_SEPARATOR.'cfg.picsize.php');//載入圖片大小

	require_once(SYS_PATH.CONFIG_EXEC);//載入設定資訊
	require_once(SYS_PATH.'include'.DIRECTORY_SEPARATOR.'inc.connect.php');//載入連結MYSQL資訊
	
	GLOBAL_D();//宣告全域變數
	
	$Login_TF = false;
	if( !empty($Admin_data) ){
		
		$db = new MySQL();

		$db->Where = " WHERE Admin_ID = '" .Multi_WebUrl_ID. "'";
		$db->query_sql(Web_Option_DB, '*');
		
		$_setting_ = $db->query_fetch();
		
		$Use_Table = '';
		if( ( $Admin_data['Group_ID'] == 1 || $Admin_data['Admin_Checkbox'] == 1 ) && !empty($Sys_Tables_data[$_SESSION['system']['tables_chg']]) ){//有權限管理者才能做資料庫切換
		
			$Use_Table = $Sys_Tables_data[$_SESSION['system']['tables_chg']]['Tables_Name'];
		}else if( !empty($Admin_data['Tables_Name']) ){//如果使用者有選擇資料庫
			
			$Use_Table = $Admin_data['Tables_Name'];
			$_SESSION['system']['tables_chg'] = $Admin_data['Tables_ID'];
		}else{//如果都沒有選擇資料庫
			
			
			$_SESSION['system']['tables_chg'] = '';
		}
		
		if( !empty($Use_Table) ){
			
			$db->Select_DB($Use_Table, $db->Link_DB);//選擇要用資料表
		}	
		
		$Login_TF = true;
		
		if( $POST['_type'] == 'Table_Download' ){//判斷如果按下下載時, 有無創建該資料表
			
			$db = new MySQL();
						
			if( $db->check_table(Download_DB) == false ){
				
				$Options = new Options();
				$Options->Create_DownLoad();
			}
		}
	}
}
?>