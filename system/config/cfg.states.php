<?php
//資料表設定用
$States_Type = array(
	//key最多30碼
	'open_states' 		=> '啟用/停用狀態 ( 預設 )',
	'sex_states' 		=> '性別狀態 ( 預設 )',
	'gender_states'		=> '性別狀態',
	'contact_states' 	=> '聯絡方式',
	'pay_type'			=> '付款方式',
	'plan'				=> '方案選擇',
	'invoice_type'		=> '發票形式'
);

$open_states 	= array(0 => '停用', 1 => '啟用'); //系統預設

$secure_states 	= array('' => '不加密', 'SSL' => 'SSL', 'TLS' => 'TLS'); //加密方式, 系統預設

$menu_lv_states = array('1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '七'); //系統預設

$sex_states 	= array('1' => '先生', '2' => '小姐'); //系統預設

$gender_states 	= array('男' => '男', '女' => '女');

$equiment_states 	= array('0' => '正常', '1' => '損壞', '2' => '其他'); //系統預設

$order_states	= array(0 => '未受理', 1 => '已受理', 2 => '已出貨', 3 => '已取消'/*, 10=>'已取消'*/); //系統預設

//$order_states	= array(0 => '工單成立', 1=> '已收到刀具', 2=> '已上傳金額', 3=> '磨刀中', 4=> '磨刀完成', 5=> '已出貨', 6=>'已取消', 7=>'已完成');//系統預設

$favor_states	= array(1 => '折', 2 => '元'); //系統預設

$pickup_states	= array(0 => '宅配-不限時段', 1 => '宅配-上午時段', 2 => '宅配-下午時段', 3 => '宅配-晚上時段'); //系統預設

$export_states	= array(1 => '進口', 2 => '出口');

$contact_states	= array(1 => '電話聯絡', 2 => 'Email');

$unit_states	= array(1 => '價值', 2 => '數量');

$html_target	= array('0' => '開新分頁', '1' => '原地跳轉');

$target_states	= array('0' => '_blank', '1' => '_self');

$pay_type 	= array(0 => '臨櫃付款');

$invoice_type 	= array(0 => '二聯式發票', 1 => '三聯式發票');

$answer_type 	= array('radio' => '選擇題（單選）', 'checkbox' => '選取方塊（多選）', 'select' => '下拉式選單');

$plan 	= array(0 => '單次計價方案', 1 => '月租基礎方案', 2 => '月租升級方案');

$week_states = array('1' => '一', '2' => '二', '3' => '三', '4' => '四', '5' => '五', '6' => '六', '7' => '日'); //星期一~日
