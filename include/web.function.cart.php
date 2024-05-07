<?php

//************************************************************************************************************
//                                                購物車用FUNCTION
//************************************************************************************************************

$Ordersn_db			= "web_ordert_sn";
$member_db			= "web_member";

//--會員資料
function GET_MEM_DATA($_ID)
{

	global $member_db;

	$db = new MySQL();
	$db->Where = "Where Member_ID = '" . $_ID . "'";
	$db->query_sql($member_db, '*');
	if ($row = $db->query_fetch()) {
		$_DATA = $row;
	}

	return $_DATA;
}

//--會員資料
function GET_COM_DATA($_ID)
{

	global $company_db;

	$db = new MySQL();
	$db->Where = "Where Company_ID = '" . $_ID . "'";
	$db->query_sql($company_db, '*');
	if ($row = $db->query_fetch()) {
		$_DATA = $row;
	}
	return $_DATA;
}

//--會員資料
function GET_COMP_DATA()
{

	global $comp_db;

	$db = new MySQL();
	$db->Where = "Where 1 = 1";
	$db->query_sql($comp_db, '*');
	if ($row = $db->query_fetch()) {
		$_DATA = $row;
	}
	return $_DATA;
}

//--取得暫存購物車
function GET_OrderSn($MemberID, $type = '')
{

	global $Ordersn_db;

	$db = new MySQL();
	$db->Where = " WHERE Ord_UID = '" . $MemberID . "'";
	$db->query_sql($Ordersn_db, 'Ord_Sn');
	if ($row = $db->query_fetch()) {

		$rs = $row['Ord_Sn'];
	}

	return $rs;
}
