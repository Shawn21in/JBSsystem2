<?php
define("ST", "<script>");
define("SD", "</script>");

//JS訊息,導向新網頁
function JSAH($STR, $PATH)
{

	header("Content-Type:text/html; charset=utf-8");
	// echo ST."alert('" .$STR. "');location.href='" .$PATH. "'".SD;
	echo "<script src='js/jquery-1.11.1.min.js'></script>";
	echo '<script src="js/sweetalert.js"></script>';
	echo ST . '$(function() {swal.fire({title: "訊息",text: "' . $STR . '",}).then((result) => {window.location.href = "' . $PATH . '";});})' . SD;
	exit;
}

function get_check($str)
{

	$str = trim($str);
	$str = htmlspecialchars($str, ENT_QUOTES);

	return $str;
}

function clean_spaces($str)
{ //清除所有空白

	$str = trim($str);
	$str = preg_replace('/\s(?=)/', '', $str);

	return $str;
}

function TurnDateFormat($date, $sign = '-')
{ //轉換日期格式

	$year = ((int)substr($date, 0, 4)); //取得年份
	$month = ((int)substr($date, 5, 2)); //取得月份
	$day = ((int)substr($date, 8, 2)); //取得幾號

	$date = $year . $sign . $month . $sign . $day;

	return $date;
}

function TurnDateFormat2($date, $sign = ' ')
{ //轉換日期格式

	$year = ((int)substr($date, 0, 4)); //取得年份
	$month = ((int)substr($date, 5, 2)); //取得月份
	$day = ((int)substr($date, 8, 2)); //取得幾號

	$MonthArray = array(
		'1' => 'JAN',
		'2' => 'FEB',
		'3' => 'MAR',
		'4' => 'APR',
		'5' => 'MAY',
		'6' => 'JUN',
		'7' => 'JUL',
		'8' => 'AUG',
		'9' => 'SEP',
		'10' => 'OCT',
		'11' => 'NOV',
		'12' => 'DEC',

	);

	$month = $MonthArray[$month];

	$date = $day . $sign . $month . $sign . $year;

	return $date;
}
//移除IMG STYLE
function remove_imgstyle($str)
{

	preg_match_all('/<img .* style="(.*?)"/i', $str, $match);

	if (is_array($match[1])) { //拿掉文字編輯圖片的style

		foreach ($match[1] as $val) {

			$str = str_replace($val, 'max-width: 100%;', $str);
		}
	}

	return $str;
}
//移除IMG標籤
function remove_imgTag($str)
{

	$str = preg_replace('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', '', $str);
	return $str;
}
//前端 - 單個檔案下載 ( 路徑 , 資料表前綴 , 資料表, 資料陣列)
function file_download($FilePath, $DataPre, $DataDB, array $DataArray)
{

	if (is_file(ICONV_CODE('UTF8_TO_BIG5', $FilePath . $DataArray[$DataPre . '_File']))) {
		$Code = Turnencode('?t=' . $DataDB . '&k=' . $DataPre . '_ID&i=' . $DataArray[$DataPre . '_ID'] . '&f=' . $DataPre . '_File' . '&p=' . $FilePath, 'downfile');
	}

	return urlencode($Code);
}

//智付通加密
function create_mpg_aes_encrypt($parameter = "", $key = "", $iv = "")
{
	$return_str = '';
	if (!empty($parameter)) {
		//將參數經過 URL ENCODED QUERY STRING
		$return_str = http_build_query($parameter);
	}
	return trim(bin2hex(mcrypt_encrypt(
		MCRYPT_RIJNDAEL_128,
		$key,
		addpadding($return_str),
		MCRYPT_MODE_CBC,
		$iv
	)));
}
function addpadding($string, $blocksize = 32)
{
	$len = strlen($string);
	$pad = $blocksize - ($len % $blocksize);
	$string .= str_repeat(chr($pad), $pad);
	return $string;
}

//智付通解密
function create_aes_decrypt($parameter = "", $key = "", $iv = "")
{
	return strippadding(mcrypt_decrypt(
		MCRYPT_RIJNDAEL_128,
		$key,
		hex2bin($parameter),
		MCRYPT_MODE_CBC,
		$iv
	));
}
function strippadding($string)
{
	$slast = ord(substr($string, -1));
	$slastc = chr($slast);
	$pcheck = substr($string, -$slast);
	if (preg_match("/$slastc{" . $slast . "}/", $string)) {
		$string = substr($string, 0, strlen($string) - $slast);
		return $string;
	} else {
		return false;
	}
}

function get_MailBody($_Type, $data = '')
{

	$url = ROOT_URL . "mailbody/";
	// $url = ROOT_URL."mailbody/";
	// $WO_Url = "http://223.27.48.176/webhost73/xingcloud"; 
	// $url = "http://localhost/webhost73/xingcloud/mailbody/";

	switch ($_Type) {

			//訂單會員
		case "custom_order":
			$url = $url . 'Mail_custom_order.php?c=' . OEncrypt('oid=' . $data, 'custom_order');
			break;

			//訂單經銷
		case "custom_sellorder":
			$url = $url . 'Mail_custom_sellorder.php?c=' . OEncrypt('v=' . $data, 'custom_sellorder');
			break;

			//訂單會員-管理者
		case "admin_order":
			$url = $url . 'Mail_admin_order.php?c=' . OEncrypt('oid=' . $data, 'admin_order');
			break;

			//訂單經銷-管理者
		case "admin_sellorder":
			$url = $url . 'Mail_admin_sellorder.php?c=' . OEncrypt('v=' . $data, 'admin_sellorder');
			break;

			//聯絡我們-會員
		case "custom_contact":
			$url = $url . 'Mail_custom_contact.php?c=' . OEncrypt('v=' . $data, 'custom_contact');
			break;

			//聯絡我們-管理者
		case "admin_contact":
			$url = $url . 'Mail_admin_contact.php?c=' . OEncrypt('v=' . $data, 'admin_contact');
			break;

			//信箱驗證
		case "emailauth":
			$url = $url . 'Mail_custom_emailauth.php?c=' . OEncrypt('v=' . $data, 'emailauth');
			break;

			//第一種方法：會員忘記密碼-重設密碼驗證信
		case "forgetpw_member":
			$url = $url . 'Mail_custom_forgetpw.php?c=' . OEncrypt('v=' . $data . '&time=' . time(), 'forgetpw_member');
			break;

			//第二種方法：會員忘記密碼-直接寄送密碼
		case "forgetpw_member_resend":
			$url = $url . 'Mail_custom_forgetpw_resend.php?c=' . OEncrypt('v=' . $data, 'forgetpw_member_resend');
			break;

			//經銷忘記密碼
		case "forgetpw_sell":
			$url = $url . 'Mail_custom_sellforgetpw.php?c=' . OEncrypt('v=' . $data, 'forgetpw_sell');
			break;

			//經銷申請-管理者
		case "Newsell":
			$url = $url . 'Mail_admin_sellverify.php';
			break;

			//問卷投放-寄信於消費者
		case "survey_launch":
			$url = $url . 'Mail_custom_launch.php?c=' . OEncrypt('v=' . $data, 'survey_launch');
			break;

			//折扣卷發放-寄信於消費者
		case "coupon_send":
			$url = $url . 'Mail_custom_coupon_send.php?c=' . OEncrypt('v=' . $data, 'coupon_send');
			break;
	}

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_NOBODY, false);
	curl_setopt($ch, CURLOPT_URL, $url);


	$result = curl_exec($ch);

	curl_close($ch);

	return $result;
}

function GET_HEAD_KD($Input, $type = '')
{

	global $_setting_;

	if (!empty($Input)) {

		$Input = TurnSymbol($Input);
		$Input = strip_tags($Input);

		if ($type == 'K') {
			$rs = !empty($_setting_['WO_Keywords']) ? ',' . $Input : $Input;
		} else {
			$rs = !empty($_setting_['WO_Description']) ? ' ' . $Input : $Input;
		}
	}

	return $rs;
}

function GET_SETTING($Input = array())
{

	global $setting_db;

	$db = new MySQL();
	$sn = 0;

	if (!empty($Input)) {

		foreach ($Input as $key) {

			if ($sn == 0) {

				$Sel = $key;
			} else {

				$Sel .= ',' . $key;
			}

			$sn++;
		}
	} else {

		$Sel = '*';
	}

	$db->query_sql($setting_db, $Sel);

	if ($row = $db->query_fetch()) {

		$_rs = $row;
	}

	return $_rs;
}


function CHK_INPUT($Input = '')
{

	if (preg_match('/[\Q~!@#$%^&*()+-_=.:?<>\E]/', $Input)) {


		return true;
	} else {

		return false;
	}
}
