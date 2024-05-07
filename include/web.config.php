<?php
if (!isset($_SESSION)) {
	session_start();
}

//當前是哪種通訊協定
$_HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';


date_default_timezone_set("Asia/Taipei"); //設置時區為台灣台北

define('PHP_SELF', basename($_SERVER['PHP_SELF'])); //目前檔案名稱含副檔名

define('PHP_NAME', basename($_SERVER['PHP_SELF'], '.php')); //目前檔案名稱不含副檔名

define('SYS_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR); //系統路徑

define('WEB_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR); //網頁路徑

define('WEB_URL', $_HTTP_TYPE . dirname($_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']) . '/'); //網頁網址

define('WEB_Mail_URL', $_HTTP_TYPE . dirname($_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']) . '/mailbody/'); //網頁網址
define('WEB_Mail', $_HTTP_TYPE . dirname($_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']) . '/'); //網頁網址

//任何路徑下網頁根目錄
if ($_SERVER['SERVER_NAME'] == 'localhost') {

	$e = explode("/", $_SERVER['SCRIPT_NAME']);

	define('ROOT_URL', $_HTTP_TYPE . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/' . $e[1] . '/'); //網頁網址

} else {

	define('ROOT_URL', $_HTTP_TYPE . $_SERVER['SERVER_NAME'] . '/'); //網頁網址
}



require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.MySQL.php'); //載入MYSQL語法class
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.MsSQL.php'); //載入MSSQL語法class
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.MyCurl.php'); //載入MyCurl語法class
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.Pages.php');
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.Upload.php'); //載入上傳class
require_once(SYS_PATH . 'config' . DIRECTORY_SEPARATOR . 'cfg.picsize.php');
require_once(SYS_PATH . 'config' . DIRECTORY_SEPARATOR . 'cfg.states.php'); //狀態
require_once(SYS_PATH . 'config' . DIRECTORY_SEPARATOR . 'cfg.turncode.php');
require_once(SYS_PATH . 'sys_config.php'); //載入MYSQL語法class
require_once(SYS_PATH . 'plugins' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php'); //寄件class
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'inc.connect.php'); //載入連結MYSQL資訊
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'inc.function.php'); //載入方法
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.Custom.php'); //載入方法
require_once(WEB_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.ShopCart.php'); //載入方法
require_once(WEB_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.SendMail.php'); //載入方法
require_once(SYS_PATH . 'include' . DIRECTORY_SEPARATOR . 'class.ReCaptcha.php'); //載入方法
require_once(WEB_PATH . 'include' . DIRECTORY_SEPARATOR . 'web.function.php'); //載入方法
require_once(WEB_PATH . 'include' . DIRECTORY_SEPARATOR . 'downloadfile.php'); //檔案下載class
require_once(WEB_PATH . 'include' . DIRECTORY_SEPARATOR . 'web.function.extend.php'); //載入額外方法
require_once(WEB_PATH . 'include' . DIRECTORY_SEPARATOR . 'web.function.cart.php'); //載入額外方法


ob_end_clean();

$db = new MySQL();
$db->Where = " WHERE Admin_ID = '2'";
$db->query_sql('sys_web_option', '*');
$_setting_ = $db->query_fetch();

$version = $_setting_['WO_Version'];

$db->Where = " WHERE Admin_ID = '2'";
$db->query_sql('web_setting', '*');
$_web_setting_ = $db->query_fetch();

$alert = $_web_setting_['Setting_Alert'];

if ($_setting_['WO_Debug'] == 1) { //網站Debug開啟

	error_reporting(1);
	ini_set('display_errors', 'On');
} else {

	error_reporting(1);
	ini_set('display_errors', 'Off');
}

$website_open = true;

if ($website_open) {

	$_Website 		= 'jp';

	$Now_Table		= $db->get_use_table();

	$news_db 		= 'news';
	$product_db 	= 'web_product';
	$about_db   	= 'web_about';
	$banner_db 		= 'web_banner';
	$catalog_db 	= 'web_catalog';
	$link_db 		= 'web_link';
	$qa_db 			= 'web_qa';
	$albums_db 		= 'albums';
	$member_db 		= 'web_member';
	$company_db 	= 'web_company';
	$video_db		= "web_video";
	$version_db		= "web_version";
	$delivery_db		= "web_delivery";
	$comp_db 	= 'comp';
	$bank_db 	= 'bank';
	$jobs_db 	= 'jobs';
	$education_db 	= 'education';
	$family_db 	= 'family';
	$part_db 	= 'part';
	$reason_db 	= 'reason';
	$attendance_db 	= 'attendance';
	$deduction_db 	= 'deduction';
	$seclab1_db 	= 'seclab1';
	$purchaser1_db 	= 'purchaser1';
	$holidays_db 	= 'holidays';
	$employee_db = 'employee';
	$ed_db = 'employdeduction';
	$ea_db = 'employeeattend';
	$cardset_db = 'cardset';


	//經銷商代號
	define('Dealer_ID', 'jpapp');

	define('Dealer_Name', '庫點子文創資訊產業有限公司');

	define('BM_ID', 'jpapp');

	define('IMG_PATH', WEB_PATH . 'sys_images' . DIRECTORY_SEPARATOR . $Now_Table . DIRECTORY_SEPARATOR); //圖片路徑

	define('IMG_URL', WEB_URL . 'sys_images/' . $Now_Table . '/'); //圖片網址

	define('FILE_PATH', WEB_PATH . 'sys_files' . DIRECTORY_SEPARATOR . $Now_Table . DIRECTORY_SEPARATOR); //檔案路徑

	define('FILE_URL', WEB_URL . 'sys_files/' . $Now_Table . '/'); //檔案網址

	define('OP_Path', 'sys_images' . DIRECTORY_SEPARATOR . $Now_Table . DIRECTORY_SEPARATOR);


	define('Banner_Url', IMG_URL . 'Banner/');
	define('News_Url', IMG_URL . 'News/');

	define('albums_Path', OP_Path . 'albums' . DIRECTORY_SEPARATOR);

	// $_COOKIE  && SafeFilter($_COOKIE);

	$CM = new Custom();

	$_Aside 		= $CM->GET_NEWS_ASIDE();
	$_ProAside 		= $CM->GET_PRODUCT_ASIDE();
	$_QA_aside 		= $CM->GET_QA_ASIDE();

	//---------------------------------------登入狀態-------------------------------------------------	
	$_state = '';
	if (!empty($_SESSION[$_Website]['website']['member_id'])) {
		$_MemberData = GET_MEM_DATA($_SESSION[$_Website]['website']['member_id']);
		if (!empty($_MemberData)) {
			$_Login = true;
			$_state = 'member';
		} else {
			$_Login = false;
		}
	} elseif (!empty($_SESSION[$_Website]['website']['company_id'])) {
		$_MemberData = GET_COM_DATA($_SESSION[$_Website]['website']['company_id']);
		if (!empty($_MemberData)) {
			$_Login = true;
			$_state = 'company';
			$_MemberFun = $plan_fun[$_MemberData['Company_Plan']];
		} else {
			$_Login = false;
		}
	}


	//---------------------------------------購物車ID-------------------------------------------------

	if (!empty($_MemberData['Member_ID'])) {

		$Order_ID = GET_OrderSn($_MemberData['Member_ID']);
	} else if (!empty($_SESSION[$_Website]['website']['order_Sn'])) {

		$Order_ID = $_SESSION[$_Website]['website']['order_Sn'];
	} else {

		$Order_ID = "";
	}
	//---------------------------------------購物車商品數-------------------------------------------------
	//
	$SC = new ShopCart();

	$order_num = 0;

	if (!empty($Order_ID)) {

		$data = $SC->Get_OrdertList($Order_ID);

		if (!empty($data['Totalnum'])) {

			$order_num = $data['Totalnum'];
		}
	} else {

		$order_num = 0;
	}
	//---------------------------------------版頭功能選單-------------------------------------------------

	// $CM = new Custom();

	$_functiondata 		= $CM->GET_FUNCTION()['Data'];
	//---------------------------------------檔案允許上傳設定-------------------------------------------------
	//答案欄位數量
	$max_col = 5;
	//問卷題目數量
	$max_question = 20;
	//---------------------------------------檔案允許上傳設定-------------------------------------------------
	//格式限制
	$upload_files['images'] = array('jpg', 'jpeg', 'png', 'bmp', 'webp');
	//檔案大小
	$upload_files['max_size'] = 500; //KB計算
	//設置給警告回傳用的，如alery('$upload_files_html')
	$upload_files_html = '格式如下：';
	foreach ($upload_files['images'] as $n => $f) {
		if ($n == 0) {
			$upload_files_html .= $f;
		} else {
			$upload_files_html .= '，' . $f;
		}
	}
	//設置給JS做參數用的，如var img_array = [$upload_files_js];
	$upload_files_js = '';
	foreach ($upload_files['images'] as $n => $f) {
		if ($n == 0) {
			$upload_files_js .= "'" . $f . "'";
		} else {
			$upload_files_js .= ",'" . $f . "'";
		}
	}
}
