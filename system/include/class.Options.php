<?php

class Options{
	
	var $Field_Setting = 
		array( 
			//-------------------------一般-------------------------//
			'ID' 		=> " int(10) UNSIGNED NOT NULL",
			'Title' 	=> " varchar(60) DEFAULT NULL COMMENT '標題'",
			'Intro' 	=> " varchar(255) DEFAULT NULL COMMENT '簡介'",
			'Content'	=> " text COMMENT '內容'",
			'Link' 		=> " varchar(100) DEFAULT NULL COMMENT '連結'",
			'LinkT' 	=> " tinyint(1) UNSIGNED DEFAULT 0 COMMENT '連結跳轉目標'",
			'Img' 		=> " varchar(60) DEFAULT NULL COMMENT '圖片'",
			'File' 		=> " varchar(60) DEFAULT NULL COMMENT '檔案'",
			'YouTube'	=> " varchar(11) DEFAULT NULL COMMENT 'YouTube'",
			'Keyword'	=> " varchar(60) DEFAULT NULL COMMENT '關鍵字'",
			'Sort' 		=> " int(5) UNSIGNED DEFAULT 0 COMMENT '排序'",
			'Open' 		=> " tinyint(1) UNSIGNED DEFAULT 0 COMMENT '啟用'",
			'Edate'		=> " datetime DEFAULT NULL COMMENT '編輯時間'",
			'Sdate'		=> " datetime DEFAULT NULL COMMENT '建立時間'",
			'Mcp' 		=> " varchar(60) DEFAULT NULL COMMENT '封面圖'",
			//-------------------------會員-------------------------//
			'Name' 		=> " varchar(30) DEFAULT NULL COMMENT '名稱'",
			'Email'		=> " varchar(60) DEFAULT NULL COMMENT '信箱'",
			'Sex'		=> " varchar(1) DEFAULT NULL COMMENT '性別'",
			//-------------------------購物-------------------------//
		);
	
	//欄位註解 => TO_Comment1, 欄位註解1 => TO_Comment2, 欄位內顯示 => TO_InShow, 欄位內編輯 => TO_InEdit, 欄位外顯示 => TO_OutShow, 欄位外顯示 => TO_OutEdit, 欄位必填 => TO_Must, 欄位種類 => TO_InType, 欄位種類1 => TO_ChkType, 欄位大小啟用 => TO_NumOpen, 欄位最大值 => TO_Max, 欄位最小值 => TO_Min, 欄位排序 => TO_Sort
	var $Field_Option =
		array(
			//-------------------------一般-------------------------//
			'Title' 	=> array('TO_Sort' => 0, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1),
			'Class' 	=> array('TO_Sort' => 1, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'select'),
			'Intro' 	=> array('TO_Sort' => 2, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'textarea'),
			'Content' 	=> array('TO_Sort' => 3, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'textedit'),
			'Link' 		=> array('TO_Sort' => 4, 'TO_InShow' => 1, 'TO_InEdit' => 1),
			'Mcp' 		=> array('TO_Sort' => 5, 'TO_Comment2' => '( 建議尺寸 XXX * XXX )', 'TO_InShow' => 1, ' TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'uploadimg'),
			'Img' 		=> array('TO_Sort' => 6, 'TO_Comment2' => '( 建議尺寸 XXX * XXX )', 'TO_InShow' => 1, ' TO_InEdit' => 1, 'TO_InType' => 'uploadimg'),
			'File' 		=> array('TO_Sort' => 7, 'TO_Comment2' => '', 'TO_InShow' => 1, ' TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'uploadfile'),
			'YouTube'	=> array('TO_Sort' => 8, 'TO_Comment2' => '( 填寫Youtube影片碼 )', 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'youtube'),
			'Sort' 		=> array('TO_Sort' => 96, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'sortdesc', 'TO_NumOpen' => 1, 'TO_Max' => 99999, 'TO_Min' => 0),
			'Open' 		=> array('TO_Sort' => 97, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'checkbox', 'TO_SelStates' => 'open_states'),
			'Edate' 	=> array('TO_Sort' => 98, 'TO_InShow' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'dateedit'),
			'Sdate' 	=> array('TO_Sort' => 99, 'TO_InShow' => 1, 'TO_InType' => 'datecreat'),
			//-------------------------2020新增-------------------------//
			'Keyword'	=> array('TO_Sort' => 9, 'TO_Comment2' => '( 輸入關鍵字後按下ENTER完成 )', 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'keyword'),
			//-------------------------相簿創建-------------------------//
			'albMcp'		=> array('TO_Sort' => 5, 'TO_OutShow' => 1, 'TO_InType' => 'uploadimg'),
			'albQty'		=> array('TO_Sort' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'number'),
			'alblSort'		=> array('TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'sortdesc', 'TO_NumOpen' => 1, 'TO_Max' => 99999, 'TO_Min' => 0),
			'alblImg'		=> array('TO_InShow' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'uploadimg'),
			//-------------------------產品創建-------------------------//
			'proID'			=> array('TO_Sort' => 0, 'TO_InShow' => 1, 'TO_OutShow' => 1),
			'proName'		=> array('TO_Sort' => 1, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1),
			'proClass' 		=> array('TO_Sort' => 2, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'select'),
			'proIntro' 		=> array('TO_Sort' => 3, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'textarea'),
			'proContent' 	=> array('TO_Sort' => 4, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'textedit'),
			'proUnit' 		=> array('TO_Sort' => 5, 'TO_InShow' => 1, 'TO_InEdit' => 1),
			'proPrice' 		=> array('TO_Sort' => 6, 'TO_InShow' => 1, 'TO_OutShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'number'),
			'proPrice1' 	=> array('TO_Sort' => 7, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'number'),
			'proMcp'		=> array('TO_Sort' => 8, 'TO_Comment2' => '( 建議尺寸 XXX * XXX )', 'TO_InShow' => 1, ' TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'uploadimg'),
			'proImg'		=> array('TO_Sort' => 9, 'TO_Comment2' => '( 建議尺寸 XXX * XXX )', 'TO_InShow' => 1, ' TO_InEdit' => 1, 'TO_InType' => 'uploadimg'),
			'proSort' 		=> array('TO_Sort' => 95, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'sortdesc', 'TO_NumOpen' => 1, 'TO_Max' => 99999, 'TO_Min' => 0),
			'proOpenNew'	=> array('TO_Sort' => 96, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'checkbox', 'TO_SelStates' => 'open_states'),
			'proOpenHot'	=> array('TO_Sort' => 97, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'checkbox', 'TO_SelStates' => 'open_states'),
			'proOpen'		=> array('TO_Sort' => 98, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'checkbox', 'TO_SelStates' => 'open_states'),
			//-------------------------會員創建-------------------------//
			'memID'			=> array('TO_Sort' => 0, 'TO_InShow' => 1, 'TO_OutShow' => 1),	
			'memAcc'		=> array('TO_Sort' => 1, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'unique'),	
			'memPwd'		=> array('TO_Sort' => 2, 'TO_Comment2' => '( 輸入新密碼, 儲存後直接更新密碼 )', 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'password'),
			'memName'		=> array('TO_Sort' => 3, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1),
			'memSex'		=> array('TO_Sort' => 4, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'radio', 'TO_SelStates' => 'sex_states'),
			'memEmail'		=> array('TO_Sort' => 5, 'TO_InShow' => 1, 'TO_InEdit' => 1),
			'memBirthday'	=> array('TO_Sort' => 6, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'datestart', 'TO_TimeFormat' => 'YYYY-MM-DD'),
			'memTel'		=> array('TO_Sort' => 7, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'number'),
			'memMobile'		=> array('TO_Sort' => 8, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'number'),
			'memFax'		=> array('TO_Sort' => 9, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'number'),
			'memCompany'	=> array('TO_Sort' => 10, 'TO_InShow' => 1, 'TO_InEdit' => 1),
			'memUniform'	=> array('TO_Sort' => 11, 'TO_InShow' => 1, 'TO_InEdit' => 1),
			'memCity'		=> array('TO_Sort' => 12, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'city', 'TO_ConnectField' => '', 'TO_ConnectField1' => ''),
			'memCounty'		=> array('TO_Sort' => 13, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'county'),
			//'memZipcode'	=> array('TO_Sort' => 14, 'TO_InShow' => 1, 'TO_InEdit' => 1),
			'memAddress'	=> array('TO_Sort' => 15, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'address', 'TO_ConnectField1' => ''),
			'memIntro'		=> array('TO_Sort' => 16, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_InType' => 'textarea'),
			'memEdate'		=> array('TO_Sort' => 17, 'TO_InShow' => 1, 'TO_InType' => 'dateedit'),
			'memLastLogin'	=> array('TO_Sort' => 18, 'TO_InShow' => 1, 'TO_InType' => 'datestart'),
			'memSdate'		=> array('TO_Sort' => 19, 'TO_InShow' => 1, 'TO_InType' => 'datecreat'),
			'memOpen'		=> array('TO_Sort' => 20, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'checkbox'),
			'memEmailauth'	=> array('TO_Sort' => 21, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_OutEdit' => 1, 'TO_InType' => 'checkbox'),
			//-------------------------訊息創建-------------------------//
			'msgName'		=> array('TO_Sort' => 0, 'TO_InShow' => 1, 'TO_OutShow' => 1),
			'msgTel'		=> array('TO_Sort' => 1, 'TO_InShow' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'number'),
			'msgMobile'		=> array('TO_Sort' => 2, 'TO_InShow' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'number'),
			'msgEmail'		=> array('TO_Sort' => 3, 'TO_InShow' => 1, 'TO_OutShow' => 1),
			'msgAddress'	=> array('TO_Sort' => 4, 'TO_InShow' => 1),
			'msgTitle'		=> array('TO_Sort' => 5, 'TO_InShow' => 1, 'TO_OutShow' => 1),
			'msgContent'	=> array('TO_Sort' => 6, 'TO_InShow' => 1, 'TO_InType' => 'textarea'),
			'msgSdate'		=> array('TO_Sort' => 99, 'TO_InShow' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'datecreat'),
			//-------------------------付款運費-------------------------//
			'dlvName'		=> array('TO_Sort' => 0, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_Must' => 1),
			'dlvPrice'		=> array('TO_Sort' => 1, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'number'),
			'dlvFree'		=> array('TO_Sort' => 2, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'number'),
			//-------------------------分類創建-------------------------//
			'cName'		=> array('TO_Sort' => 1, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_Must' => 1),
			'cSort'		=> array('TO_Sort' => 96, 'TO_InShow' => 1, 'TO_InEdit' => 1, 'TO_OutShow' => 1, 'TO_InType' => 'sortdesc', 'TO_NumOpen' => 1, 'TO_Max' => 99999, 'TO_Min' => 0),
			'cOpen'		=> array('TO_Sort' => 97, 'TO_InShow' => 1, ' TO_InEdit' => 1, 'TO_InType' => 'checkbox'),
		);
		
	//創建sys_tables_option資料表
	function Create_TableOption(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Tables_Option_DB. "` (
				`TO_Name` varchar(30) DEFAULT NULL COMMENT '資料表名稱',
				`TO_Field` varchar(20) DEFAULT NULL COMMENT '資料表欄位',
				`TO_Comment1` varchar(30) DEFAULT NULL COMMENT '欄位註解1',
				`TO_Comment2` varchar(30) DEFAULT NULL COMMENT '欄位註解2',
				`TO_InShow` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '欄位內顯示',
				`TO_InEdit` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '欄位內編輯',
				`TO_OutShow` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '欄位外顯示',
				`TO_OutEdit` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '欄位外編輯',
				`TO_Must` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '欄位必填',
				`TO_InType` varchar(15) DEFAULT NULL COMMENT '欄位種類',
				`TO_ChkType` varchar(15) DEFAULT NULL COMMENT '欄位種類1',
				`TO_NumOpen` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '數字大小啟用',
				`TO_Max` int(9) DEFAULT '0' COMMENT '數字最大值',
				`TO_Min` int(9) DEFAULT '0' COMMENT '數字最小值',
				`TO_ConnectField` varchar(20) DEFAULT NULL COMMENT '相互作用欄位',
				`TO_ConnectField1` varchar(20) DEFAULT NULL COMMENT '互相作用欄位1',
				`TO_SelPicSize` varchar(30) DEFAULT NULL COMMENT '選擇圖片大小',
				`TO_SelStates` varchar(30) DEFAULT NULL COMMENT '欄位狀態選擇',
				`TO_UploadSize` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '欄位上傳大小',
				`TO_TimeFormat` varchar(20) DEFAULT NULL COMMENT '時間格式',
				`TO_Sort` int(5) UNSIGNED DEFAULT '0' COMMENT '欄位排序'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系統資料表設定';";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .Tables_Option_DB. '` ADD UNIQUE KEY `Tableso_Name` (`TO_Name`,`TO_Field`) USING BTREE;' );
	}
	
	//創建sys_download資料表
	function Create_DownLoad(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Download_DB. "` (
				`DL_Session` varchar(32) NOT NULL COMMENT '下載碼',
				`DL_DownLoadInfo` varchar(100) DEFAULT NULL COMMENT '下載資訊',
				`DL_DownLoadPath` varchar(100) DEFAULT NULL COMMENT '檔案路徑',
				`DL_DownLoadUrl` varchar(100) DEFAULT NULL COMMENT '下載位址'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='下載資料表';";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .Download_DB. '` ADD PRIMARY KEY (`DL_Session`);' );
	}
	
	//創建sys_download資料表
	function Create_Recaptcha(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Recaptcha_DB. "` (
				`Admin_ID` int(10) NOT NULL COMMENT '管理者',
				`Recaptcha_JS_url` varchar(80) DEFAULT NULL COMMENT 'JS引用路徑',
				`Recaptcha_API_url` varchar(80) DEFAULT NULL COMMENT 'API路徑',
				`Recaptcha_SiteKey` varchar(40) DEFAULT NULL COMMENT '公鑰',
				`Recaptcha_SecretKey` varchar(40) DEFAULT NULL COMMENT '私鑰'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='驗證碼資料表';";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .Recaptcha_DB. '` ADD PRIMARY KEY (`Admin_ID`);' );
	}
	
	//創建sys_admin資料表
	function Create_Admin(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Admin_DB. "` (
				`Admin_ID` int(10) UNSIGNED NOT NULL,
				`Admin_Acc` varchar(20) NOT NULL COMMENT '管理者帳號',
				`Admin_Pwd` varchar(32) NOT NULL COMMENT '管理者密碼',
				`Admin_Name` varchar(20) DEFAULT NULL COMMENT '管理者名稱',
				`Admin_Permissions` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '管理者權限',
				`Group_ID` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '管理者群組',
				`Tables_ID` int(10) UNSIGNED DEFAULT NULL COMMENT '管理者資料庫',
				`Admin_Code` varchar(32) DEFAULT NULL COMMENT '登入碼',
				`Admin_Sdate` datetime DEFAULT '1911-00-00 00:00:00' COMMENT '建立時間',
				`Admin_LastLogin` datetime DEFAULT NULL COMMENT '最後登入日期',
				`Admin_IP` varchar(20) DEFAULT NULL COMMENT '登入IP',
				`Admin_Open` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '啟用',
				`Admin_Checkbox` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '切換資料庫啟用'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系統管理員表';";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .Admin_DB. '` ADD PRIMARY KEY (`Admin_ID`), ADD UNIQUE KEY `Admin_Acc` (`Admin_Acc`), ADD KEY `Admin_Code` (`Admin_Code`);' );

		$db->query( 'ALTER TABLE `' .Admin_DB. '` MODIFY `Admin_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
	}
	
	//創建sys_group資料表
	function Create_Group(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Group_DB. "` (
				`Group_ID` tinyint(3) UNSIGNED NOT NULL COMMENT '群組ID',
				`Group_Name` varchar(30) DEFAULT NULL COMMENT '群組名稱',
				`Group_Lv` tinyint(3) UNSIGNED DEFAULT '1' COMMENT '群組級別',
				`Group_MenuUse` text COMMENT '群組目錄權限'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系統群組表';";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .Group_DB. '` ADD PRIMARY KEY (`Group_ID`);' );

		$db->query( 'ALTER TABLE `' .Group_DB. '` MODIFY `Group_ID` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "群組ID";' );
	}
	
	//創建sys_menu資料表
	function Create_Menu(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Menu_DB. "` (
				`Menu_ID` varchar(11) NOT NULL COMMENT '目錄編號',
				`Menu_Name` varchar(20) DEFAULT NULL COMMENT '目錄名稱',
				`Menu_Lv` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '目錄層級',
				`Menu_Exec` varchar(60) DEFAULT NULL COMMENT '執行檔',
				`Menu_Path` varchar(20) DEFAULT NULL COMMENT '執行檔位置',
				`Menu_Sort` int(5) DEFAULT '0' COMMENT '目錄排序',
				`Menu_UpMID` varchar(11) DEFAULT NULL COMMENT '上層類別',
				`Menu_Permissions` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '目錄權限',
				`Menu_Smallpic` varchar(20) DEFAULT NULL COMMENT '目錄小圖示',
				`Menu_TableName` varchar(30) DEFAULT NULL COMMENT '資料表名稱',
				`Menu_TableName1` varchar(30) DEFAULT NULL COMMENT '擴充資料表名稱',
				`Menu_TableName2` varchar(30) DEFAULT NULL COMMENT '分類資料表名稱',
				`Menu_TableKey` varchar(20) DEFAULT NULL COMMENT '資料表主鍵',
				`Menu_TableKey1` varchar(20) DEFAULT NULL COMMENT '擴充資料表主鍵',
				`Menu_TableKey2` varchar(20) DEFAULT NULL COMMENT '分類資料表主鍵',
				`Menu_TablePre` varchar(10) DEFAULT NULL COMMENT '資料表前輟',
				`Menu_TablePre1` varchar(10) DEFAULT NULL COMMENT '擴充資料表前輟',
				`Menu_TablePre2` varchar(10) DEFAULT NULL COMMENT '分類資料表前輟',
				`Menu_OrderBy` varchar(60) DEFAULT NULL COMMENT '自定義排序',
				`Menu_ClassMax` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '分類最大層數',
				`Menu_Mode` tinyint(1) DEFAULT '0' COMMENT '目錄模式',
				`Menu_Model` varchar(10) DEFAULT NULL COMMENT '目錄模組',
				`Menu_Link` varchar(100) DEFAULT NULL COMMENT '目錄連結',
				`Menu_CstSnPre` varchar(5) DEFAULT NULL COMMENT '自定編號前輟',
				`Menu_CstSnType` varchar(6) DEFAULT NULL COMMENT '自訂編號種類',
				`Menu_CstSnNum` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '自訂編號流水碼數',
				`Menu_Add` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '允許新增資料',
  				`Menu_Edt` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '允許編輯資料',
  				`Menu_Del` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '允許刪除資料',
  				`Menu_View` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '允許檢視資料',
  				`Menu_Albums_Edt` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '允許編輯相片',
  				`Menu_Albums_Mpc` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '允許設封面圖',
				`Menu_SysUse` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '系統使用',
				`Menu_SysAdminUse` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '系統管理員使用'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系統目錄表';";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .Menu_DB. '` ADD PRIMARY KEY (`Menu_ID`), ADD KEY `Menu_Exec` (`Menu_Exec`);' );
	}
	
	//創建sys_tables資料表
	function Create_Tables(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Tables_DB. "` (
				`Tables_ID` int(10) UNSIGNED NOT NULL COMMENT '資料庫ID',
				`Tables_Name` varchar(30) DEFAULT NULL COMMENT '資料庫名稱',
				`Tables_Name1` varchar(30) DEFAULT NULL COMMENT '資料庫中文名稱',
				`Tables_Open` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '資料庫啟用'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系統資料庫表';";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .Tables_DB. '` ADD PRIMARY KEY (`Tables_ID`);' );

		$db->query( 'ALTER TABLE `' .Tables_DB. '` MODIFY `Tables_ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "資料庫ID";' );
	}
	
	//創建sys_web_option資料表
	function Create_WOption(){
		
		$db = new MySQL();
		
		$sql = "
			CREATE TABLE `" .Web_Option_DB. "` (
				`Admin_ID` int(10) UNSIGNED DEFAULT NULL COMMENT '管理者',
				`WO_Name` varchar(20) DEFAULT NULL COMMENT '公司名稱',
				`WO_Addr` varchar(10) DEFAULT NULL COMMENT '縣市',
				`WO_Addr1` varchar(10) DEFAULT NULL COMMENT '區域',
				`WO_Addr2` varchar(100) DEFAULT NULL COMMENT '地址',
				`WO_Tel` varchar(20) DEFAULT NULL COMMENT '公司電話',
				`WO_Tel1` varchar(20) DEFAULT NULL COMMENT '公司手機',
				`WO_Fax` varchar(12) DEFAULT NULL COMMENT '公司傳真',
				`WO_Email` varchar(60) DEFAULT NULL COMMENT '公司信箱',
				`WO_Idn` varchar(8) DEFAULT NULL COMMENT '公司統編',
				`WO_About` text COMMENT '關於我們',
				`WO_Privacy` text COMMENT '隱私權保護及資訊安全宣告',
				`WO_Title` varchar(20) DEFAULT NULL COMMENT '網站標題',
				`WO_Url` varchar(100) DEFAULT NULL COMMENT '網站網址',
				`WO_FBLink` varchar(100) DEFAULT NULL COMMENT 'Facebook',
				`WO_LineLink` varchar(100) DEFAULT NULL COMMENT 'LineID',
				`WO_Youtube` varchar(12) DEFAULT NULL COMMENT 'Youtube影片碼',
				`WO_YoutubeLink` varchar(100) DEFAULT NULL COMMENT 'Youtube影片連結',
				`WO_Description` text COMMENT '網站介紹',
				`WO_Keywords` tinytext COMMENT '網站關鍵字',
				`WO_GMAP` text COMMENT 'Google MAP',
  				`WO_GAnalytics` text COMMENT 'Google Analytics',
				`WO_MapLat` varchar(20) DEFAULT NULL COMMENT '地圖北緯',
				`WO_MapLng` varchar(20) DEFAULT NULL COMMENT '地圖東經',
				`WO_Open` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '網站開啟',
				`WO_Debug` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '網站Debug',
				`WO_StmpHost` varchar(100) DEFAULT NULL COMMENT '郵件伺服器(SMTP)',
				`WO_StmpPort` varchar(6) DEFAULT NULL COMMENT '郵件伺服器(PORT)',
				`WO_SendName` varchar(20) DEFAULT NULL COMMENT '寄件者名稱',
				`WO_SendEmail` varchar(60) DEFAULT NULL COMMENT '寄件者Email',
				`WO_StmpAuth` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '啟用驗證',
				`WO_StmpAcc` varchar(30) DEFAULT NULL COMMENT '驗證帳號',
				`WO_StmpPass` varchar(30) DEFAULT NULL COMMENT '驗證密碼',
				`WO_StmpSecure` varchar(3) DEFAULT NULL COMMENT '加密方式',
				`WO_AddrName` varchar(20) DEFAULT NULL COMMENT '測試收件者名稱',
				`WO_AddrEmail` varchar(60) DEFAULT NULL COMMENT '測試收件者信箱',
				`WO_MailSubject` varchar(100) DEFAULT NULL COMMENT '測試信件主題',
				`WO_LOGO` varchar(60) DEFAULT NULL COMMENT '網站LOGO',
				`WO_LOGO2` varchar(60) DEFAULT NULL COMMENT '後台登入頁LOGO',
				`WO_favicon` varchar(60) DEFAULT NULL COMMENT '網址小圖標',
				`WO_ShareIcon` varchar(60) DEFAULT NULL COMMENT '社群分享預覽圖',
				`WO_MailBody` text COMMENT '測試信件內容'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='網站資料設定';";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .Web_Option_DB. '` ADD UNIQUE KEY `Admin_ID` (`Admin_ID`) USING BTREE;' );
	}	
	
	function Create_Banner( $tablename, $tablepre, $tablesuf = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
		
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` int(10) UNSIGNED NOT NULL,
				`" .$tablepre. "_Title"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '輪播標題',
				`" .$tablepre. "_Mcp"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '輪播圖',
				`" .$tablepre. "_Keyword"	.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '關鍵字',
				`" .$tablepre. "_Sort"		.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre. "_Open"		.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '啟用',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`);' );
		
		$db->query( 'ALTER TABLE `' .$tablename. '` MODIFY `' .$tableid. '` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
	}
	
	function Create_News( $tablename, $tablepre, $tablesuf = '', $tablecid = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
		
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` int(10) UNSIGNED NOT NULL,
				" .(!empty($tablecid) ? "`" .$tablecid. "` varchar(11) DEFAULT NULL COMMENT '消息分類'," : ""). "
				`" .$tablepre. "_Title"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '消息標題',
				`" .$tablepre. "_Content"	.$tablesuf. "` text COMMENT '消息內容',
				`" .$tablepre. "_Mcp"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '封面圖',
				`" .$tablepre. "_Keyword"	.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '關鍵字',
				`" .$tablepre. "_Sort"		.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre. "_Open"		.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '啟用',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`);' );
		
		$db->query( 'ALTER TABLE `' .$tablename. '` MODIFY `' .$tableid. '` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
		
		if(	!empty($tablecid) ){
	
			$db->query( 'ALTER TABLE `' .$tablename. '` ADD INDEX (`' .$tablecid. '`)' );
		}
	}
	
	function Create_QA( $tablename, $tablepre, $tablesuf = '', $tablecid = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
				
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` int(10) UNSIGNED NOT NULL,
				" .(!empty($tablecid) ? "`" .$tablecid. "` varchar(11) DEFAULT NULL COMMENT 'QA分類'," : ""). "
				`" .$tablepre. "_Title"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT 'QA標題',
				`" .$tablepre. "_Content"	.$tablesuf. "` text COMMENT 'QA內容',
				`" .$tablepre. "_Keyword"	.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '關鍵字',
				`" .$tablepre. "_Sort"		.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre. "_Open"		.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '啟用',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`);' );
		
		$db->query( 'ALTER TABLE `' .$tablename. '` MODIFY `' .$tableid. '` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
		
		if(	!empty($tablecid) ){
	
			$db->query( 'ALTER TABLE `' .$tablename. '` ADD INDEX (`' .$tablecid. '`)' );
		}
	}
	
	function Create_Product( $tablename, $tablepre, $tablesuf = '', $tablecid = '' ){
		
		$db = new MySQL();
		
		$tableid 	= $tablepre. '_ID' .$tablesuf;
					
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` varchar(11) NOT NULL COMMENT '產品編號',
				" .(!empty($tablecid) ? "`" .$tablecid. "` varchar(11) DEFAULT NULL COMMENT '產品分類'," : ""). "
				`" .$tablepre. "_Name"		.$tablesuf. "` varchar(30) DEFAULT NULL COMMENT '產品名稱',
				`" .$tablepre. "_Intro"		.$tablesuf. "` varchar(255) DEFAULT NULL COMMENT '產品簡介',
				`" .$tablepre. "_Content"	.$tablesuf. "` text COMMENT '詳細內容',
				`" .$tablepre. "_Unit"		.$tablesuf. "` varchar(3) DEFAULT NULL COMMENT '單位',
				`" .$tablepre. "_Price"		.$tablesuf. "` int(10) DEFAULT '0' COMMENT '售價',
				`" .$tablepre. "_Price1"	.$tablesuf. "` int(10) DEFAULT '0' COMMENT '會員價',
				`" .$tablepre. "_Keyword"	.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '關鍵字',
				`" .$tablepre. "_Mcp"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '封面圖',
				`" .$tablepre. "_Img1"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '產品圖一',
				`" .$tablepre. "_Img2"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '產品圖二',
				`" .$tablepre. "_Img3"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '產品圖三',
				`" .$tablepre. "_Img4"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '產品圖四',
				`" .$tablepre. "_Sort"		.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre. "_OpenNew"	.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '新品',
				`" .$tablepre. "_OpenHot"	.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '熱門',
				`" .$tablepre. "_Open"		.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '啟用',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`), ADD INDEX (`' .$tablepre. "_Name" .$tablesuf. '`), ADD INDEX (`' .$tablepre. "_Price" .$tablesuf. '`);' );
		
		if(	!empty($tablecid) ){
	
			$db->query( 'ALTER TABLE `' .$tablename. '` ADD INDEX (`' .$tablecid. '`)' );
		}
	}
	
	function Create_Albums( $tablename, $tablepre, $tablesuf = '', $tablename1, $tablepre1, $tablecid = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
				
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` int(10) UNSIGNED NOT NULL COMMENT '相簿主編號',
				" .(!empty($tablecid) ? "`" .$tablecid. "` varchar(11) DEFAULT NULL COMMENT '相簿分類'," : ""). "
				`" .$tablepre. "_Title"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '相簿標題',
				`" .$tablepre. "_Intro"		.$tablesuf. "` varchar(255) DEFAULT NULL COMMENT '相簿簡介',
				`" .$tablepre. "_Mcp"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '封面圖',
				`" .$tablepre. "_Img"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '圖片',
				`" .$tablepre. "_Qty"		.$tablesuf. "` int(5) DEFAULT '0' COMMENT '相簿張數',
				`" .$tablepre. "_Sort"		.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre. "_Open"		.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '啟用',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`);' );

		$db->query( 'ALTER TABLE `' .$tablename. '` MODIFY `' .$tableid. '` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
		
		if(	!empty($tablecid) ){
	
			$db->query( 'ALTER TABLE `' .$tablename. '` ADD INDEX (`' .$tablecid. '`)' );
		}
		//----------------------------------------------------//
		
		$tableid1 = $tablepre1. "_ID" .$tablesuf;
			
		$sql = "
			CREATE TABLE `" .$tablename1. "` (
				`" .$tableid1. "` int(10) UNSIGNED NOT NULL COMMENT '編號',
				`" .$tablepre. "_ID"		.$tablesuf. "` int(10) UNSIGNED DEFAULT NULL COMMENT '相簿主編號',
				`" .$tablepre1. "_Title"	.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '相片標題',
				`" .$tablepre1. "_Intro"	.$tablesuf. "` varchar(255) DEFAULT NULL COMMENT '相片簡介',
				`" .$tablepre1. "_Img"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '批次圖片',
				`" .$tablepre1. "_Sort"		.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre1. "_Open"		.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '啟用',
				`" .$tablepre1. "_Sdate"	.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );
		
		$db->query( 'ALTER TABLE `' .$tablename1. '` ADD PRIMARY KEY (`' .$tableid1. '`), ADD KEY `' .$tableid. '` (`' .$tableid. '`);' );

		$db->query( 'ALTER TABLE `' .$tablename1. '` MODIFY `' .$tableid1. '` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
	}	
	
	function Create_Member( $tablename, $tablepre, $tablesuf = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
		
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` varchar(11) NOT NULL COMMENT '會員編號',
				`" .$tablepre. "_Acc"		.$tablesuf. "` varchar(50) DEFAULT NULL COMMENT '會員帳號',
				`" .$tablepre. "_Pwd"		.$tablesuf. "` varchar(32) DEFAULT NULL COMMENT '會員密碼',
				`" .$tablepre. "_Email"		.$tablesuf. "` varchar(50) DEFAULT NULL COMMENT '電子信箱',
				`" .$tablepre. "_Company"	.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '公司名稱',
				`" .$tablepre. "_Uniform"	.$tablesuf. "` varchar(8) DEFAULT NULL COMMENT '公司統編',
				`" .$tablepre. "_Name"		.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '會員名稱',
				`" .$tablepre. "_Gender"	.$tablesuf. "` varchar(2) DEFAULT NULL COMMENT '會員性別',
				`" .$tablepre. "_Birthday"	.$tablesuf. "` varchar(10) DEFAULT NULL COMMENT '生日日期',
				`" .$tablepre. "_Tel"		.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '聯絡電話',
				`" .$tablepre. "_Mobile"	.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '聯絡手機',
				`" .$tablepre. "_Fax"		.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '傳真號碼',
				`" .$tablepre. "_Zipcode"	.$tablesuf. "` varchar(6) DEFAULT NULL COMMENT '區域碼',
				`" .$tablepre. "_City"		.$tablesuf. "` varchar(6) DEFAULT NULL COMMENT '區住縣市',
				`" .$tablepre. "_County"	.$tablesuf. "` varchar(6) DEFAULT NULL COMMENT '區住地區',
				`" .$tablepre. "_Address"	.$tablesuf. "` varchar(100) DEFAULT NULL COMMENT '區住地址',
				`" .$tablepre. "_Photo"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '會員照片',
				`" .$tablepre. "_Intro"		.$tablesuf. "` varchar(300) DEFAULT NULL COMMENT '自我簡介',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '加入時間',
				`" .$tablepre. "_Edate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '編輯時間',
				`" .$tablepre. "_LastLogin"	.$tablesuf. "` datetime DEFAULT NULL COMMENT '最後登入時間',
				`" .$tablepre. "_Open"		.$tablesuf. "` tinyint(1) DEFAULT NULL COMMENT '帳號啟用',
				`" .$tablepre. "_Emailauth"	.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '信箱認證',
				`" .$tablepre. "_FBID"		.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT 'FacebookID',
				`" .$tablepre. "_GoogleID"	.$tablesuf. "` varchar(25) DEFAULT NULL COMMENT 'GoogleID'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`), ADD UNIQUE KEY `' .$tablepre. '_Acc' .$tablesuf. '` (`' .$tablepre. '_Acc' .$tablesuf. '`);' );
	}
	
	function Create_Message( $tablename, $tablepre, $tablesuf = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
				
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` int(10) UNSIGNED NOT NULL COMMENT '編號',
				`" .$tablepre. "_Name"		.$tablesuf. "` varchar(30) DEFAULT NULL COMMENT '聯絡人',
				`" .$tablepre. "_Tel"		.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '聯絡電話',
				`" .$tablepre. "_Mobile"	.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '聯絡手機',
				`" .$tablepre. "_Email"		.$tablesuf. "` varchar(100) DEFAULT NULL COMMENT '電子信箱',
				`" .$tablepre. "_Address"	.$tablesuf. "` varchar(100) DEFAULT NULL COMMENT '聯絡地址',
				`" .$tablepre. "_Title"		.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '標題',
				`" .$tablepre. "_Verify"	.$tablesuf. "` varchar(60) DEFAULT NULL COMMENT '辨別碼',
				`" .$tablepre. "_Content"	.$tablesuf. "` text COMMENT '詳細內容',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`);' );

		$db->query( 'ALTER TABLE `' .$tablename. '` MODIFY `' .$tableid. '` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;' );
	}
	
	function Create_Delivery( $tablename, $tablepre, $tablesuf = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
		
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` int(3) UNSIGNED NOT NULL,
				`" .$tablepre. "_Name"		.$tablesuf. "` varchar(30) DEFAULT NULL COMMENT '付款方式',
				`" .$tablepre. "_Price"		.$tablesuf. "` int(10) DEFAULT '0' COMMENT '運費金額',
				`" .$tablepre. "_Free"		.$tablesuf. "` int(10) DEFAULT '0' COMMENT '免運金額',
				`" .$tablepre. "_Sort"		.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre. "_Open"		.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '啟用',
				`" .$tablepre. "_Sdate"		.$tablesuf. "` datetime DEFAULT NULL COMMENT '建立時間'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`);' );
		
		$db->query( 'ALTER TABLE `' .$tablename. '` MODIFY `' .$tableid. '` int(3) UNSIGNED NOT NULL AUTO_INCREMENT;' );
	}
	
	function Create_Class( $tablename, $tablepre, $tablesuf = '' ){
		
		$db = new MySQL();
		
		$tableid = $tablepre. '_ID' .$tablesuf;
				
		$sql = "
			CREATE TABLE `" .$tablename. "` (
				`" .$tableid. "` varchar(11) NOT NULL COMMENT '分類編號',
				`" .$tablepre. "_Name"	.$tablesuf. "` varchar(20) DEFAULT NULL COMMENT '分類名稱',
				`" .$tablepre. "_Lv"	.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '目錄層級',
				`" .$tablepre. "_Sort"	.$tablesuf. "` int(5) UNSIGNED DEFAULT '0' COMMENT '排序',
				`" .$tablepre. "_Open"	.$tablesuf. "` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '顯示',
				`" .$tablepre. "_UpMID" .$tablesuf. "` varchar(11) DEFAULT NULL COMMENT '上層目錄ID'
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
		
		$db->query( $sql );

		$db->query( 'ALTER TABLE `' .$tablename. '` ADD PRIMARY KEY (`' .$tableid. '`);' );
	}
}
?>