<?php

//************************************************************************************************************
//                                                自定義FUNCTION集中區
//************************************************************************************************************
/**
 * 取得隨機數列
 *
 * @param integer   $length 輸入亂數長度
 * @param string 	$type   資料型態(如:純小寫英文、純大寫英文、純數字、全部等)
 * @return string 
 */
function GET_RAND_ID($length, $type = 'all')
{
	$key = '';
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	switch ($type) {
		case 'all':
			for ($i = 0; $i < $length; $i++) {
				$key .= $pattern[mt_rand(0, 61)];
			}
			break;
		case 'num':
			for ($i = 0; $i < $length; $i++) {
				$key .= $pattern[mt_rand(0, 9)];
			}
			break;
		case 'lower':
			for ($i = 0; $i < $length; $i++) {
				$key .= $pattern[mt_rand(10, 35)];
			}
			break;
		case 'upper':
			for ($i = 0; $i < $length; $i++) {
				$key .= $pattern[mt_rand(36, 61)];
			}
			break;
	}

	return $key;
}

/**
 * 判斷值是否為json
 *
 * @param string 	$string   傳遞字串即可
 * @return boolean 
 */
function isJson($string)
{
	json_decode($string);
	return json_last_error() === JSON_ERROR_NONE;
}
/**
 * 過濾json的內容
 *
 * @param string 	$json   傳入的一定要是JSON
 * @return string   回傳json 
 */
function Json_filter($json)
{
	if (!isJson($json)) {
		return $json;
	} else {
		$json_array = json_decode($json, true);
		foreach ($json_array as $key => $value) {
			if (is_array($value)) {
				$json_array[$key] = arr_filter($value);
			} elseif (isJson($value)) {
				$json_array[$key] = Json_filter($value);
			} else {
				$json_array[$key] = htmlspecialchars(addslashes(trim($value)));
			}
		}
		return json_encode($json_array, 256);
	}
}
/**
 * 過濾Array內容
 *
 * @param array 	$array   傳入的一定要是array
 * @return array 
 */
function arr_filter($array)
{
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$array[$key] = arr_filter($value);
			} elseif (isJson($value)) {
				$array[$key] = Json_filter($value);
			} else {
				$array[$key] = htmlspecialchars(addslashes(trim($value)));
			}
		}
	}
	return $array;
}
/**
 * 民國轉換西元
 *
 * @param string 	$tw_date   時間格式以Y-m-d為主
 * @return string   回傳西元日期 
 */
function tw2ad($tw_date = '100-01-01')
{
	// 分割民國年月日
	$parts = explode('-', $tw_date);

	// 將民國年轉換成西元年
	$ad_year = intval($parts[0]) + 1911;

	// 組合西元年和原本的月份日期
	$ad_date = $ad_year . '-' . $parts[1] . '-' . $parts[2];

	return $ad_date;
}
