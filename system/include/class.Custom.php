<?php

class Custom
{



	var $_Data = array();

	function __construct()
	{
	}

	//************************************************************************************************************
	//                                                品牌故事
	//************************************************************************************************************

	//---品牌故事
	function GET_ABOUTUS_ASIDE()
	{

		$Sheet = "web_abouts";

		$db = new MySQL();
		$db->Where 		= "Where Abouts_Open = 1";
		$db->Order_By = ' ORDER BY Abouts_Sort DESC';
		$db->query_sql($Sheet, 'Abouts_ID, Abouts_Name');

		while ($row = $db->query_fetch()) {

			$rs[$row['Abouts_ID']]  = $row['Abouts_Name'];

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Abouts_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Abouts_Name']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}

	//---品牌故事
	function GET_ABOUTUS($Input = '')
	{

		$Sheet = "web_abouts";

		$db = new MySQL();
		$db->Where 		= "Where Abouts_ID = '" . $Input . "'";
		$db->query_sql($Sheet, 'Abouts_Name, Abouts_Content');

		if ($row = $db->query_fetch()) {

			$rs  = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Abouts_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Abouts_Content']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}

	//************************************************************************************************************
	//                                                最新消息
	//************************************************************************************************************

	//---最新消息分類
	function GET_NEWS_ASIDE()
	{

		$Sheet = "web_newsclass";

		$db = new MySQL();
		$db->Where 		= "Where NewsC_Open = 1";
		$db->Order_By = ' ORDER BY NewsC_Sort DESC';
		$db->query_sql($Sheet, '*');

		while ($row = $db->query_fetch()) {

			$rs[$row['NewsC_ID']]  = $row['NewsC_Name'];

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['NewsC_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['NewsC_Name']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}

	//---最新消息
	function GET_NEWS_LIST($Input = '')
	{

		$Sheet = "web_news";

		$db = new MySQL();
		$db->Where 		= "Where News_Open = 1";

		if (!empty($Input)) {

			$db->Where 		.= " AND NewsC_ID = '" . $Input . "'";
		}
		$db->Order_By = ' ORDER BY News_Sort DESC, News_Sdate DESC';
		$db->query_sql($Sheet, 'News_ID,News_Title, News_Mcp, News_Sdate');

		while ($row = $db->query_fetch('', 'assoc')) {

			if (empty($row['News_Mcp'])) {

				$row['News_Mcp'] = 'images/no_photo.jpg';
			} else {

				$row['News_Mcp'] = News_Url . $row['News_Mcp'];
			}

			$rs[$row['News_ID']]  = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['News_Title'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['News_Title']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}

	//---最新消息麵包屑breadcrumb
	function GET_NEWS_BREAD_NAME($Input)
	{

		$Sheet = "web_newsclass";

		$db = new MySQL();
		$db->Where 		= " Where NewsC_ID = '" . $Input . "'";
		$db->query_sql($Sheet, 'NewsC_Name');

		if ($row = $db->query_fetch('', 'assoc')) {

			return $row['NewsC_Name'];
		}
	}

	//---最新消息內頁
	function GET_NEWS_DATA($Input = '')
	{

		$Sheet = "web_news as a LEFT JOIN web_newsclass as b ON a.NewsC_ID=b.NewsC_ID";

		$db = new MySQL();
		$db->Where 		= "Where News_Open = 1";
		$db->Where 		.= " AND News_ID = '" . $Input . "'";
		$db->query_sql($Sheet, 'a.*,b.NewsC_Name,b.NewsC_ID');

		if ($row = $db->query_fetch('', 'assoc')) {

			$rs  = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['News_Title'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['News_Content']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}

	//************************************************************************************************************
	//                                                購物須知
	//************************************************************************************************************

	//---購物須知側邊
	function GET_QA_ASIDE()
	{

		$Sheet = "web_qa";

		$db = new MySQL();
		$db->Where 		= "Where QA_Open = 1";
		$db->Order_By = ' ORDER BY QA_Sort DESC';
		$db->query_sql($Sheet, 'QA_ID, QA_Name');

		while ($row = $db->query_fetch()) {

			$rs[$row['QA_ID']]  = $row['QA_Name'];

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['QA_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['QA_Name']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}

	//---購物須知內容
	function GET_QA_DATA($Input = '')
	{

		$Sheet = "web_qa";

		$db = new MySQL();
		$db->Where 		= "Where QA_ID = '" . $Input . "'";
		$db->query_sql($Sheet, 'QA_Name, QA_Content');

		if ($row = $db->query_fetch()) {

			$rs  = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['QA_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['QA_Content']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}

	//************************************************************************************************************
	//                                                產品列表
	//************************************************************************************************************

	//---產品列表
	function GET_PRODUCT_LIST($Input = array(), $_PageData = array())
	{

		$Sheet = "web_product";

		$_SEO	= array();

		$db = new MySQL();

		$db->Where = " WHERE Product_Open = 1 ";
		$Page_Url_Str = "";

		$_Sort = $Input['sort'];
		$_Mode = $Input['mode'];
		$_Pcid = $Input['pcid'];

		//人氣商品
		if ($_Mode == "hot") {

			$db->Where .= " AND Product_OpenHot = '1'";
			$Page_Url_Str = "c=" . OEncrypt('mode=hot&timestamp=' . time(), 'products');
		} else if (!empty($_Pcid)) {

			$db->Where .= " AND ProductC_ID = '" . $db->val_check($_Pcid) . "'";
			$Page_Url_Str = "c=" . OEncrypt('pcid=' . $_Pcid . '&timestamp=' . time(), 'products');
		}

		//產品排序
		if (!empty($_Sort)) {

			if (!empty($Page_Url_Str)) {

				$Page_Url_Str .= "&";
			}

			if ($_Sort == 'asc') {

				$db->Order_By = " ORDER BY Product_Price1 ASC, Product_Sort DESC, Product_ID DESC ";

				$Page_Url_Str .= "sort=asc";
			} else if ($_Sort == 'desc') {

				$db->Order_By = " ORDER BY Product_Price1 DESC, Product_Sort DESC, Product_ID DESC ";

				$Page_Url_Str .= "sort=desc";
			}
		} else {
			$db->Order_By = " ORDER BY Product_OpenNew DESC, Product_Sort DESC, Product_ID DESC ";
		}


		//-------------------------------頁碼----------------------
		$Page_Total_Num = $db->query_count($Sheet); //總資料筆數

		$Pages		= !empty($_PageData['p']) && is_numeric($_PageData['p']) ? $_PageData['p'] : 1; //目前頁碼
		$Page_Size	= 20; //顯示筆數
		if (empty($Page_Url_Str)) {
			$Page_Url   = PHP_SELF . '?p=';
		} else {
			$Page_Url   = PHP_SELF . '?' . $Page_Url_Str . '&p=';
		}

		$Page_Calss = new Pages($Pages, $Page_Total_Num, $Page_Size, $Page_Url); //頁碼程式
		$Pages    	= $Page_Calss->Pages; //頁碼
		$StartNum 	= $Page_Calss->StartNum; //從第幾筆開始撈
		$Pages_Data = $Page_Calss->Pages_Data;
		//----------------------------------------------------------------

		$_Field = "Product_Mcp, Product_Name, Product_ID, Product_Price, Product_Price1, Product_OpenNew, Product_Intro";

		$db->query_sql($Sheet, $_Field, $StartNum, $Page_Size);
		while ($row = $db->query_fetch('', 'assoc')) {

			if (!empty($row['Product_Mcp'])) {

				$row['Product_Mcp'] = Product_Url . $row['Product_Mcp'];
			} else {

				$row['Product_Mcp'] = "images/no_photo.jpg";
			}

			$_data[$row['Product_ID']] = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Product_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Product_Intro']);
		}

		$_rs['ProductCount'] 	= $Page_Total_Num;
		$_rs['Data'] 			= $_data;
		$_rs['PageData'] 		= $Pages_Data;
		$_rs['SEO'] 			= $_SEO;

		return $_rs;
	}

	//---產品列表
	function GET_PRODUCT_SEARCH($Input = '', $_PageData = array())
	{
		if (!empty($_GET)) {
			$Pages_Data['p'] 	= $_GET['p'];
		}
		$Sheet = "web_product";

		$_SEO	= array();

		$db = new MySQL();

		$db->Where = " WHERE Product_Open = 1 ";

		$db->Order_By = " ORDER BY Product_OpenNew DESC, Product_Sort DESC, Product_ID DESC ";

		$db->Search = array('Product_Name', 'Product_Unit', 'Product_Intro', 'Product_Content');
		$db->query_search($Input);

		//-------------------------------頁碼----------------------
		$Page_Total_Num = $db->query_count($Sheet); //總資料筆數
		$Pages		= !empty($Pages_Data['p']) && is_numeric($Pages_Data['p']) ? $Pages_Data['p'] : 1; //目前頁碼
		$Page_Size	= 20; //顯示筆數

		$Page_Url   = PHP_SELF . '?searchkey=' . $Input . '&p=';
		$Page_Calss = new Pages($Pages, $Page_Total_Num, $Page_Size, $Page_Url); //頁碼程式
		$Pages    	= $Page_Calss->Pages; //頁碼
		$StartNum 	= $Page_Calss->StartNum; //從第幾筆開始撈
		$Pages_Data = $Page_Calss->Pages_Data;


		//----------------------------------------------------------------

		$_Field = "Product_Mcp, Product_Name, Product_ID, Product_Price, Product_Price1, Product_OpenNew, Product_Intro";

		$db->query_sql($Sheet, $_Field, $StartNum, $Page_Size);
		while ($row = $db->query_fetch('', 'assoc')) {

			if (!empty($row['Product_Mcp'])) {

				$row['Product_Mcp'] = Product_Url . $row['Product_Mcp'];
			} else {

				$row['Product_Mcp'] = "images/no_photo.jpg";
			}

			$_data[$row['Product_ID']] = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Product_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Product_Intro']);
		}

		$_rs['ProductCount'] 	= $Page_Total_Num;
		$_rs['Data'] 			= $_data;
		$_rs['PageData'] 		= $Pages_Data;
		$_rs['SEO'] 			= $_SEO;

		return $_rs;
	}

	//---商品列表側邊選單
	function GET_PRODUCT_ASIDE()
	{

		$Sheet 	= "web_productclass";
		$Sheet2 = "web_product";

		$db = new MySQL();
		////************加LV排序 先把第一層撈出來//************
		$db->Where = " Where ProductC_Open = 1 ";
		$db->Order_By = " ORDER BY ProductC_Lv ASC, ProductC_Sort DESC ";
		$db->query_sql($Sheet, '*');

		while ($row = $db->query_fetch()) {

			$mrow 			= array();
			$mrow['id'] 	= $row['ProductC_ID'];
			$mrow['name'] 	= $row['ProductC_Name'];

			//************這邊撈第二層時要判斷第一層是否存在//************
			if (empty($row['ProductC_UpMID'])) {

				$_AsideList[$row['ProductC_ID']] = $mrow;
			} else {

				$_AsideList[$row['ProductC_UpMID']]['NextLv'][$mrow['id']] = $mrow;
			}

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['ProductC_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['ProductC_Name']);
		}

		//去除沒有第二層、分類下無商品之分類
		$db = new MySQL();

		foreach ($_AsideList as $key => $val) {

			//去除無第二層分類
			if (empty($val['NextLv'])) {

				unset($_AsideList[$key]);

				//去除無商品第二層
			} else {

				foreach ($val['NextLv'] as $key2 => $NextLval) {

					$_count = 0;
					$db->Where = " Where Product_Open = 1 ";
					$db->Where .= " AND ProductC_ID = '" . $NextLval['id'] . "'";
					$_count = $db->query_count($Sheet2);

					if ($_count == 0) {

						unset($_AsideList[$key]['NextLv'][$key2]);

						//檢查第二層是否已為空，是則清除第一層
						if (empty($_AsideList[$key]['NextLv'])) {

							unset($_AsideList[$key]);
						}
					}
				}
			}
		}

		$_rs['Data'] 		= $_AsideList;
		$_rs['SEO'] 		= $_SEO;

		return $_rs;
	}

	//---產品資料麵包屑breadcrumb
	function GET_PRODUCT_BREAD_NAME($Input)
	{

		$Sheet = "web_productclass as a LEFT JOIN web_productclass as b ON a.ProductC_UpMID = b.ProductC_ID";

		$db = new MySQL();
		$db->Where 		= " Where a.ProductC_ID = '" . $Input . "'";
		$db->query_sql($Sheet, 'a.ProductC_Name as LV2_Name, b.ProductC_Name as LV1_Name');

		if ($row = $db->query_fetch('', 'assoc')) {

			return $row;
		}
	}

	//---產品內頁資料
	function GET_PRODUCT_DATA($Input = '')
	{

		$Sheet = "web_product as a LEFT JOIN web_productclass as b ON a.ProductC_ID = b.ProductC_ID";

		$db = new MySQL();
		$db->Where 		= "Where Product_ID = '" . $Input . "'";
		$db->query_sql($Sheet, 'a.*,b.ProductC_Name');

		if ($row = $db->query_fetch()) {

			$_ImgList = array(
				'Product_Mcp' 	=> $row['Product_Mcp'],
				'Product_Img1' 	=> $row['Product_Img1'],
				'Product_Img2' 	=> $row['Product_Img2'],
				'Product_Img3' 	=> $row['Product_Img3']
			);

			$_ImgList = array_filter($_ImgList);

			if (empty($_ImgList)) {

				$_ImgList = array(
					'Product_Mcp' 	=> "images/no_photo.jpg",
				);
			} else {

				foreach ($_ImgList as $key => $val) {
					$_ImgList[$key] = Product_Url . $val;
				}
			}

			$row['Img'] = $_ImgList;
			$row['Product_Price'] 	= unserialize($row['Product_Price']);
			$row['Product_Price1'] 	= unserialize($row['Product_Price1']);
			$row['Product_Unit'] 	= unserialize($row['Product_Unit']);
			$rs  = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Product_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Product_Intro']);
		}

		$_Data['Data'] 	= $rs;
		$_Data['SEO'] 	= $_SEO;

		return $_Data;
	}


	//---首頁最新產品列表
	function GET_INDEX_PRODUCT_LIST()
	{

		$Sheet = "web_product";

		$_SEO	= array();

		$db = new MySQL();

		$db->Where = " WHERE Product_Open = 1 ";
		$db->Order_By = " ORDER BY Product_OpenNew DESC, Product_Sdate DESC LIMIT 8";
		$_Field = "Product_Mcp, Product_Name, Product_ID , Product_Price ,Product_Price1";

		$db->query_sql($Sheet, $_Field);
		while ($row = $db->query_fetch('', 'assoc')) {

			if (!empty($row['Product_Mcp'])) {

				$row['Product_Mcp'] = Product_Url . $row['Product_Mcp'];
			} else {

				$row['Product_Mcp'] = "images/no_photo.jpg";
			}

			$_data[$row['Product_ID']] = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Product_Name'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Product_Name']);
		}

		$_rs['Data'] 			= $_data;
		$_rs['SEO'] 			= $_SEO;

		return $_rs;
	}

	//---首頁BANNER
	function GET_BANNER_LIST()
	{

		$Sheet = "web_banner";

		$_SEO	= array();

		$db = new MySQL();

		$db->Where = " WHERE Banner_Open = 1 ";
		$db->Order_By = " ORDER BY Banner_Sort DESC";
		$_Field = "Banner_ID, Banner_Title, Banner_Link, Banner_Mcp";

		$db->query_sql($Sheet, $_Field);
		while ($row = $db->query_fetch('', 'assoc')) {

			if (!empty($row['Banner_Mcp'])) {

				$row['Banner_Mcp'] = Banner_Url . $row['Banner_Mcp'];
			} else {

				$row['Banner_Mcp'] = "images/bg01.png";
			}

			$_data[$row['Banner_ID']] = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Banner_Title'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Banner_Title']);
		}

		$_rs['Data'] 			= $_data;
		$_rs['SEO'] 			= $_SEO;

		return $_rs;
	}


	//---首頁廣告
	function GET_AD_LIST()
	{

		$Sheet = "web_index_adl";

		$_SEO	= array();

		$db = new MySQL();

		$db->Where = " WHERE Adl_Open = 1 ";
		$db->Order_By = " ORDER BY Adl_Sort DESC";
		$_Field = "Adl_ID, Adl_Title, Adl_Link, Adl_Img";

		$db->query_sql($Sheet, $_Field);

		while ($row = $db->query_fetch('', 'assoc')) {

			$row['Adl_Img'] = AD_Url . $row['Adl_Img'];

			$_data[$row['Adl_ID']] = $row;

			$_SEO['WO_Keywords'] 	.= GET_HEAD_KD($row['Adl_Title'], 'K');
			$_SEO['WO_Description'] .= GET_HEAD_KD($row['Adl_Title']);
		}

		$_rs['Data'] 			= $_data;
		$_rs['SEO'] 			= $_SEO;

		return $_rs;
	}

	//---選單
	function GET_FUNCTION()
	{

		//------------------Init------------------
		$db 		= new MySQL();

		$_Sheet 	= "web_function";

		//撈取欄位
		$_Field 	= "Func_ID, Func_Name, Func_Link";

		//------------------SQL_Where------------------
		$db->Where = " WHERE Func_Open = '1'";

		//------------------SQL_ORDER------------------
		$db->Order_By = " ORDER BY Func_Sort DESC ";

		//------------------SQL_INPUT------------------
		$db->query_sql($_Sheet, $_Field);

		while ($row = $db->query_fetch('', 'assoc')) {

			$_Data[$row['Func_ID']] = $row;
		}

		$_rs['Data'] 			= $_Data;

		return $_rs;
	}
	//************************************************************************************************************
	//                                                SETTING
	//************************************************************************************************************

	//---搜取SETTING表
	function GET_WEB_SETTING($Input = '')
	{

		$Sheet = "web_setting";

		if (empty($Input)) {

			$Input = "*";
		}

		$db = new MySQL();
		$db->Where 		= " Where Admin_ID = 2";
		$db->query_sql($Sheet, $Input);

		if ($row = $db->query_fetch('', 'assoc')) {

			return $row;
		}
	}


	function __destruct()
	{


		unset($_Data);
	}
	//************************************************************************************************************
	//                                               資料表集合
	//************************************************************************************************************
	//--銀行編號
	function GET_BANK_DATA($id = '')
	{

		$Sheet = "bank";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where bankno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->Order_By = "Order By bankno asc";
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	//--學歷編號
	function GET_EDUCATION_DATA($id = '')
	{

		$Sheet = "education";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where educationno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->Order_By = "Order By educationno asc";
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	//--職位編號
	function GET_JOBS_DATA($id = '')
	{

		$Sheet = "jobs";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where appno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->Order_By = "Order By appno asc";
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}

	//--部門編號
	function GET_PART_DATA($id = '')
	{

		$Sheet = "part";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where partno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->Order_By = "Order By partno asc";
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	//--眷屬編號
	function GET_FAMILY_DATA($id = '')
	{

		$Sheet = "family";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where relationno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->Order_By = "Order By relationno asc";
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	//--眷屬編號
	function GET_REASON_DATA($id = '')
	{

		$Sheet = "reason";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where reasonno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->Order_By = "Order By reasonno asc";
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	//--眷屬編號
	function GET_DEDUCTION_DATA($id = '')
	{

		$Sheet = "deduction";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where deductionno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->Order_By = "Order By deductionno asc";
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	//--勞保等級
	function GET_SECLAB1_DATA($id = '')
	{

		$Sheet = "seclab1";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where seclab1No = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}

	//--健保等級
	function GET_PURCHASER1_DATA($id = '')
	{

		$Sheet = "purchaser1";

		$db = new MySQL();
		if ($id) {
			$db->Where = "Where purchaserno = '" . $id . "'";
		} else {
			$db->Where = "Where 1 = 1";
		}
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	/**
	 * 班別列表
	 *
	 * @return array 
	 */
	function GET_ATTENDANCE_LIST()
	{

		$Sheet = "attendance";

		$db = new MySQL();
		$db->Where = "Where 1 = 1";
		$db->Order_By = 'Order By attendanceid desc';
		$db->Group_By = 'Group By attendanceno';
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	/**
	 * 班別設定
	 * @param integer   $id 班別編號
	 *
	 * @return array 
	 */
	function GET_ATTENDANCE_DATA($id)
	{

		$Sheet = "attendance";

		$db = new MySQL();
		$db->Where = "Where attendanceno = '" . $id . "'";
		$db->Order_By = 'Order By attendanceid asc';
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	/**
	 * 假別列表
	 *
	 * @return array 
	 */
	function GET_HOLIDAYS_LIST()
	{

		$Sheet = "holidays";

		$db = new MySQL();
		$db->Where = "Where 1 = 1";
		$db->Order_By = 'Order By niandu desc';
		$db->Group_By = 'Group By niandu';
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	/**
	 * 假別設定
	 * 
	 * @param integer   $id 年度
	 * 
	 * @return array 
	 */
	function GET_HOLIDAYS_DATA($id)
	{

		$Sheet = "holidays";

		$db = new MySQL();
		$db->Where = "Where niandu = '" . $id . "'";
		$db->Order_By = 'Order By holiday asc';
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	/**
	 * 員工基本資料
	 * 
	 * @param integer   $id 員工編號或流水號
	 * 
	 * @return array 
	 */
	function get_employee_data($id)
	{

		$Sheet = "employee";

		$db = new MySQL();
		$db->Where = "Where employeid = '" . $id . "' OR eid = '" . $id . "'";
		$db->Order_By = 'Order By eid asc';
		$db->query_sql($Sheet, '*', 0, 1);
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs  = $row;
		}
		return $rs;
	}
	/**
	 * 員工列表
	 * 
	 * @return array 
	 */
	function get_employee_list()
	{

		$Sheet = "employee";

		$db = new MySQL();
		$db->Order_By = 'Order By employeid asc';
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count]  = $row;
			$count++;
		}
		return $rs;
	}
	/**
	 * 員工-固定加扣款資料
	 * 
	 * @param integer   $id 員工編號
	 * 
	 * @return array 
	 */
	function get_employdeduction_list($id)
	{

		$Sheet = "employdeduction";

		$db = new MySQL();
		$db->Where = "Where employeid = '" . $id . "'";
		$db->Order_By = 'Order By edid asc';
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count++]  = $row;
		}
		return $rs;
	}
	/**
	 * 員工-出勤曆列表
	 * 
	 * @param string   $id 員工編號
	 * 
	 * @param string   $year 年度(民國)
	 * 
	 * @param string   $month 月份，若未填則抓整年度，參數可填1 2 3..12。只可填一個數字
	 * 
	 * @return array 
	 */
	function get_employeeattend_list($id, $year, $month = '')
	{

		$Sheet = "employeeattend";

		$db = new MySQL();
		$db->Where = "Where employeid = '" . $id . "' AND ndyear = '" . $year . "'";
		if (!empty($month)) {
			$smonth = $month >= 9 ? $month : '0' . $month;					//該月份初
			$emonth = ($month + 1) >= 9 ? ($month + 1) : '0' . ($month + 1); //下月份初
			$db->Where .= " AND  nddate > " . $year . $smonth . "00 AND nddate < " . $year . $emonth . "00";
		}
		$db->Order_By = 'Order By nddate asc';
		$db->query_sql($Sheet, '*');
		$count = 0;
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs[$count++]  = $row;
		}
		return $rs;
	}

	/**
	 * 員工-出勤曆列表(只取單筆)
	 * 
	 * @param string   $eid 出勤曆編號
	 * 
	 * @return array 
	 */
	function get_employeeattend_data($eid)
	{

		$Sheet = "employeeattend";

		$db = new MySQL();
		$db->Where = "Where eid = '" . $eid . "'";
		$db->query_sql($Sheet, '*');
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs  = $row;
		}
		return $rs;
	}
	/**
	 * 打卡資料設定
	 * 
	 * 
	 * @return array 
	 */
	function get_cardset_data()
	{

		$Sheet = "cardset";

		$db = new MySQL();
		$db->Where = "Where 1=1";
		$db->query_sql($Sheet, '*');
		while ($row = $db->query_fetch('', 'assoc')) {
			$rs  = $row;
		}
		return $rs;
	}
}
