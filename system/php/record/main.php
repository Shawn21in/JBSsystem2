<?php

date_default_timezone_set('Asia/Taipei');

class dbCommand
{	
	public $host = 'xpdb01.hihosting.hinet.net:3306';
	// public $host = '127.0.0.1';

	public $username = '89880827_kmsystem_smartorder';
	public $password = 'Bm_23586802';
	public $database = '89880827_kmsystem_smartorder';

	// public $username = 'root';
	// public $password = 'admin';
	// public $database = '89880827_bmworkflow';


	public $severip = '192.168.1.102';
	public $result;
	static public $admemail = '';
	static public $websiteName = 'erp';
	static public $mainMenuRoot = 'tree2_getdata';
	protected $company = 'erp';
	static public $defaultPWD = 'eip1234';
	static public $viewfloder = 'view';
	static public $query = 'query';
	static public $control = 'control';
	static public $insert = 'insert';
	static public $delete = 'delete';
	static public $update = 'update';
	static public $search = 'search';
	static public $report = 'report';
	static public $folderNameCms = 'cms';
	static public $folderNameSys = 'sys';
	static public $php = '.php';
	static public $systemname = 'erp';
	/*
	 * 上SSL憑證 static public $httpstypes = 'https';
	 * 測試機先改為 static public $httpstypes = 'http';
	 *
	 * */
	static public $httpstypes = 'https';
	static public $httptype = 'http';
	static public $folderStr = '/';
	static public $urlRoot = '://';
	static public $websiteTWname = '華越WEB ERP系統';

	//攻頂變數
	public $testUserListArray = array(
		"H55", "AA"
	);

	function __construct()
	{
		$this->sql_connect();
		$this->sql_database();
		$this->set_db_encode($this->sql_connect());
	}

	public function sql_connect()
	{
		try {
			$conn = mysqli_connect($this->host, $this->username, $this->password);
			mysqli_query($conn, "SET NAMES 'utf8'");
			return $conn;
		} catch (Exception $e) {
			die("Connect Error Infomation:" . $e->getMessage());
		}
	}

	public function pdo_connect()
	{
		try {
			// //專用變數
			// if (in_array($_SESSION['account'], $this->testUserListArray))
			// {
			// 	$this->database = "89880827_bmworkflow_test";
			// 	$this->username = "89880827_bmworkflow_test";
			// }

			$connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database . ';charset=utf8', $this->username, $this->password);
			//$pdo = new PDO($connection,$this->username,$this->password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $connection;
		} catch (PDOException $e) {
			echo "Connect Error Infomation:" . $e->getMessage();
		}
	}

	function set_db_encode($conn)
	{
		return mysqli_query($conn, "SET NAMES 'utf8'");
	}

	function sql_database()
	{
		return @mysqli_select_db($this->database);
	}

	function query($sql_string)
	{
		$result = mysqli_query($sql_string);
		$query = new db_query($result);
		$result = $query->result();
		return $result;
	}

	function mysqlQuery($sql_string)
	{
		$result = mysqli_query($sql_string);
		return $result;
	}

	public function nowYear()
	{
		return date("Y");
	}

	public function footerBar()
	{

?>

		<div data-options="region:'south',border:false" style="height:40px;background:#DBDCE2;padding:5px;" align="center">


			<div class="easyui-panel" style="padding:0px;background:#DBDCE2;" align="center">
				<a href="#" class="easyui-linkbutton" data-options="plain:true">
					<font style="font-family:微軟正黑體;">©<?php echo $this->nowYear() . '&nbsp;' . $this->company; ?> . All rights reserved.</font>
				</a>

				<a href="#" class="easyui-menubutton" data-options="menu:'#mm2',iconCls:'icon-help'">Help</a>
				<a href="#" class="easyui-menubutton" data-options="menu:'#mm3'">Version</a>
			</div>

			<div id="mm2" style="width:100px;">
				<div><a href="javascript:void(0)" class="easyui-menubutton" onclick="$('#w').window('open')">About</a></div>
				<div>Update</div>

			</div>
			<div id="mm3" class="menu-content" style="background:#f0f0f0;padding:10px;text-align:left">
				<img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/systemlogo.png'; ?>">
				<p style="font-size:14px;color:#444;">Bm Web System V1.0.0</p>
			</div>
			<div id="w" class="easyui-window" title="Help Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:200px;padding:10px;">
				System Contact Email : bm888@ms39.hinet.net
			</div>


		</div>


	<?php
	}


	//	public function checkUser($userAccount){
	//		$result = $this->mysqlQuery("SELECT username FROM `javyw_users` WHERE username='$userAccount' ");
	//		$table = mysql_fetch_assoc($result);
	//		return $table['username'];
	//	}

	public function checkUserLV($userid)
	{
		$result = $this->pdo_connect()->prepare("SELECT level FROM eip_hrlevel WHERE USERID = '$userid' ");

		$result->execute();


		if ($result->rowCount()) {
			$row = $result->fetch();
			return $row['level'];

			mysqli_free_result($stmt);
			mysqli_close($this->pdo_connect());
		}
	}

	public function checkUserAuth($userid, $basename)
	{
		$result = $this->pdo_connect()->prepare("
			
			SELECT a.groupid,b.name,b.url
			FROM eip_hrlevel a
			LEFT JOIN eip_flowmenu b ON a.groupid = b.groupid
			WHERE a.USERID = '$userid'
			AND b.url LIKE '%$basename'

		");

		$result->execute();

		if ($result->rowCount()) {
			$row = $result->fetch();
			//return $row['groupid'].$row['name'].$row['url'];
			return 'Auth';
		} else {
			//header('Location: '.$this->systemEIPURL());
			header('Location: ' . $this->systemOtherLoginURL());
			exit;
		}
	}

	public function checkUserAuthTypeII($userid, $uri, $basename, $gid)
	{
		$resultTypeII = $this->pdo_connect()->prepare("
			
			SELECT a.groupid,b.name,b.url,SUBSTRING_INDEX(SUBSTRING_INDEX('$uri','/',-3),b.url,2) AS strchk
			FROM eip_hrlevel a
			LEFT JOIN eip_flowmenu b ON a.groupid = b.groupid
			WHERE a.USERID = '$userid'
		");

		$resultTypeII->execute();
		$rowTypeII = $resultTypeII->fetch();

		$chkUrl = $rowTypeII['strchk'];

		$resultTypeIICHKGID = $this->pdo_connect()->prepare("
			
			SELECT a.groupid
			FROM eip_hrlevel a
			LEFT JOIN eip_flowmenu b ON a.groupid = b.groupid
			WHERE a.USERID = '$userid'
			AND b.url = SUBSTRING_INDEX('$chkUrl','.',1);
			
		");

		$resultTypeIICHKGID->execute();
		$rowTypeIICHKGID = $resultTypeIICHKGID->fetch();

		if ($gid == $rowTypeIICHKGID['groupid']) {
			return 'Auth';
		} else {
			//return $uri.$rowTypeII['url'];
			header('Location: ' . $this->systemEIPURL());
			exit;
		}
	}

	public function checkUserGroup($userid)
	{
		$resultGroupData = $this->pdo_connect()->prepare("
			
			SELECT a.groupid,b.name,b.url
			FROM eip_hrlevel a
			LEFT JOIN eip_flowmenu b ON a.groupid = b.groupid
			WHERE a.USERID = '$userid'
		");

		$resultGroupData->execute();
		$rowGroupData = $resultGroupData->fetch();

		$getUserGroup = $rowGroupData['groupid'];

		return $getUserGroup;
	}

	public function checkSystemName($userid, $uri, $basename, $gid, $getlang)
	{
		$resultTypeII = $this->pdo_connect()->prepare("
			
			SELECT a.groupid,b.name,b.url,SUBSTRING_INDEX(SUBSTRING_INDEX('$uri','/',-3),b.url,2) AS strchk
			FROM eip_hrlevel a
			LEFT JOIN eip_flowmenu b ON a.groupid = b.groupid
			WHERE a.USERID = '$userid'
		");

		$resultTypeII->execute();
		$rowTypeII = $resultTypeII->fetch();

		$chkUrl = $rowTypeII['strchk'];

		$resultTypeIICHKGID = $this->pdo_connect()->prepare("
			
			SELECT b.*
			FROM eip_hrlevel a
			LEFT JOIN eip_flowmenu b ON a.groupid = b.groupid
			WHERE a.USERID = '$userid'
			AND b.url = SUBSTRING_INDEX('$chkUrl','.',1);
		");

		$resultTypeIICHKGID->execute();
		$rowTypeIICHKGID = $resultTypeIICHKGID->fetch();

		if ($getlang == '') {
			return $rowTypeIICHKGID['name'];
		} else if ($getlang == 'tw') {
			return $rowTypeIICHKGID['name'];
		} else if ($getlang == 'en') {
			return $rowTypeIICHKGID['en'];
		} else if ($getlang == 'vn') {
			return $rowTypeIICHKGID['vn'];
		}
	}

	public function getFirstDayOfWeek($year, $week)
	{
		$first_day = strtotime($year . "-01-01");
		$month = date('m', $first_day);
		$is_monday = date("w", $first_day) == 1;
		$week_one_start = !$is_monday ? strtotime("last monday", $first_day) : $first_day;

		if ($week == 1) {
			return date('y-M-d', mktime(0, 0, 0, + ($month), 1, $year));
		} else {
			return date('y-M-d', $week_one_start + (3600 * 24 * 7 * ($week - 1)));
		}
	}

	public function selectLang($getFileName)
	{
		/*<?php echo $_SERVER['HTTP_HOST'].'/modules/cms/sys/images/lang_tw.png';?>*/

	?>
		<div style="padding:5px 0;" align="left">
			<a href="<?php echo $getFileName . '.php?lang=tw'; ?>"><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_tw.png'; ?>" width="21" height="22"></a>
			<a href="<?php echo $getFileName . '.php?lang=en'; ?>"><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_en.png'; ?>" width="21" height="22"></a>
			<!--a href="<?php echo $getFileName . '.php?lang=vn'; ?>" ><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_vn.png'; ?>" width="21" height="22" ></a-->
		</div>
	<?php
	}

	public function selectLangType2($getFileName)
	{
	?>
		<div style="padding:5px 0;" align="left">
			<a href="<?php echo $getFileName . '?lang=tw'; ?>"><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_tw.png'; ?>" width="21" height="22"></a>
			<a href="<?php echo $getFileName . '?lang=en'; ?>"><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_en.png'; ?>" width="21" height="22"></a>
			<!--a href="<?php echo $getFileName . '?lang=vn'; ?>" ><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_vn.png'; ?>" width="21" height="22" ></a-->
		</div>
	<?php
	}

	public function selectLangType3($getFileName)
	{
	?>

		<a href="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . $getFileName . '?lang=tw'; ?>" class="easyui-linkbutton" width="28" height="28" style="margin-top: -10px;"><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_tw2.png'; ?>" width="28" height="28"></a>
		<a href="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . $getFileName . '?lang=en'; ?>" class="easyui-linkbutton" width="28" height="28" style="margin-top: -10px;"><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_en2.png'; ?>" width="28" height="28"></a>
		<!--a href="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . $getFileName . '?lang=vn'; ?>" class="easyui-linkbutton" width="28" height="28" style="margin-top: -10px;"><img src="<?php echo dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/cms/sys/images/lang_vn.png'; ?>" ></a-->

	<?php
	}

	public function getToday()
	{
		return date("Y-m-d H:i:s");
	}

	public function getTodayYMD($setpara)
	{
		$formatDate = new DateTime($setpara);
		return $formatDate->format('Y-m-d');
	}

	public function systemURL()
	{
		return dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/';
	}

	public function systemEIPURL()
	{

		//return 'http://'.$_SERVER['HTTP_HOST'].'/index.php/systemlogin'; /*原始登入joomla畫面*/
		return dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/modules/login/';
	}

	public function systemOtherLoginURL()
	{

		return dbCommand::$httpstypes . '://' . $_SERVER['HTTP_HOST'] . '/' . dbCommand::$websiteName . '/modules/login/';
	}

	public function eipDefaultPWD()
	{
		return dbCommand::$defaultPWD;
	}

	public function sendAgentEmail($agent)
	{

		$queryEmployeeEmail = "SELECT email FROM eip_hrlevel WHERE employee = '$agent' ";
		$rsQueryEmployeeEmail = $this->pdo_connect()->prepare($queryEmployeeEmail);
		$rsQueryEmployeeEmail->execute();
		$rowEmployeeEmail = $rsQueryEmployeeEmail->fetch();
		$getEmail = $rowEmployeeEmail['email'];
		return $getEmail;
	}

	public function sendEmailByDEPT($querySQL)
	{

		$queryEmployeeEmail = "SELECT email FROM eip_hrlevel WHERE deptcode = '$querySQL' ";
		$rsQueryEmployeeEmail = $this->pdo_connect()->prepare($queryEmployeeEmail);
		$rsQueryEmployeeEmail->execute();
		$rowEmployeeEmail = $rsQueryEmployeeEmail->fetch();
		$getEmail = $rowEmployeeEmail['email'];
		return $getEmail;
	}

	public function checkUserDirectManger($setUser, $type)
	{

		//查詢使用者部門階層
		$rsFlowChartHRtable = "SELECT * FROM eip_hrlevel WHERE employee = '$setUser' ";
		$rs = $this->pdo_connect()->prepare($rsFlowChartHRtable);
		$rs->execute();
		$rowFlowChartHRtable = $rs->fetch();
		$DirectManager = $rowFlowChartHRtable['directmanager'];
		$uesrdept = $rowFlowChartHRtable['dept'];
		if ($type == 'dm') {
			return $DirectManager;
		} else if ($type == 'dept') {
			return $uesrdept;
		}
	}

	public function getDept($name)
	{

	?>
		<select class="easyui-combobox" name=<?php echo $name; ?> labelPosition="top" style="width:120px;" data-options="required:false" id="dept">
			<option value="">Select..</option>
			<?php
			$queryDept = "SELECT dept FROM eip_hrlevel WHERE status = 'Y' GROUP BY dept ";
			$rsQueryDept = mysqli_query($queryDept);

			while ($rowDept = mysqli_fetch_array($rsQueryDept)) {
				echo '<option value="' . $rowDept['dept'] . '">' . $rowDept['dept'] . '</option>';
			}
			?>
		</select>
	<?php
	}

	public function getConnfid($flowidtype)
	{

	?>
		<select class="easyui-combobox" name="connfid" labelPosition="top" style="width:200px;" data-options="required:false">
			<option value="">Select..</option>
			<?php
			//指定由經辦人員 peter 結完建議單後才勾稽資訊設備採購單
			$queryConnFid = "SELECT MAX(id)as id,fid,connfid 
											  FROM eip_flowchartbomlist p
											  WHERE NOT EXISTS (
											  SELECT * 
											  FROM eip_flowchartbomlist od
											  WHERE p.fid = od.connfid
											  )
											  AND status = 'E' 
											  AND connflowid = '$flowidtype' 
											  AND connfid IS NULL
											  AND uid = 'peter'
											  GROUP BY fid,fid,connfid
											   ";


			$rsQueryConnFid = mysqli_query($queryConnFid);

			while ($rowConnFid = mysqli_fetch_array($rsQueryConnFid)) {
				echo '<option value="' . $rowConnFid['fid'] . '">' . $rowConnFid['fid'] . '</option>';
			}
			?>
		</select>
	<?php
	}

	//現場採購申請主選單
	public function pur02MainMenu()
	{

	?>
		<a target="" href="<?php echo $this->systemURL(); ?>cms/sys/form/purchase02.php?lang=vn"><button style="width:100%" type="button" class="button button-glow button-rounded button-raised button-primary">PHIẾU ĐỀ NGHỊ MUA VẬT TƯ</button></a>
		<p></p>
		<a target="" href="<?php echo $this->systemURL(); ?>cms/sys/jobsitehistory.php?lang=vn"><button style="width:100%" type="button" class="button button-glow button-rounded button-highlight">Danh sách kiểm tra đơn hàng</button></a>
		<p></p>
		<a target="" href="<?php echo $this->systemURL(); ?>cms/sys/jobsitesigned.php?lang=vn"><button style="width:100%" type="button" class="button button-glow button-rounded button-caution">Kiểm tra các đơn hàng đã ký</button></a>
		<p></p>
		<a target="" href="<?php echo $this->systemURL(); ?>cms/sys/jobsitecheck.php?lang=vn"><button style="width:100%" type="button" class="button button-glow button-rounded button-royal">Quản lý các đơn đã ký</button></a>
		<p></p>
		<!--a target="" href="checkcountersignaturesingle.php?lang=vn"><button type="button" class="btn btn-primary btn-block">Chức năng kiểm tra các đơn trình ký</button></a><p></p-->

		<a target="" href="<?php echo $this->systemURL(); ?>cms/sys/default.php"><button style="width:100%" type="button" class="button button-glow button-border button-rounded button-primary">
				<font style="font-family: 微軟正黑體;"><strong>回首頁</strong></font>
			</button></a>
		<p></p>
		<?php
	}

	public function chkEndAgentStatus($flowid)
	{

		//查詢系統設定是否開啟指定結單主管
		$endAgentStatusSQL = "SELECT endagentstatus,endagent FROM eip_flowchartdb WHERE flowid = '$flowid' ";
		$rsEndAgentStatusSQL = mysqli_query($endAgentStatusSQL);
		$rowEndAgentStatusSQL = mysqli_fetch_array($rsEndAgentStatusSQL);

		if ($rowEndAgentStatusSQL['endagentstatus'] == 'true') {
			//將指定結單主管資料用hidden寫入簽核流程
		?>
			<!--input type="hidden" name="endagent" value="<?php //echo $rowEndAgentStatusSQL['endagent'];
															?>" /-->
			<input type="submit" name="endagent" value="Submit送簽執總" style=" background-color:#F54BA0;border:1;color:#fff;border-radius:10px;cursor:pointer;width:150px;height:50px;">
<?php
		} else {
		}
	}

	public function sendEmailFunction($a, $content)
	{

		$to = $a; //收件者 -- 網站管理員
		$subject = "=?UTF-8?B?" . base64_encode("【系統通知信件】") . "?="; //信件標題
		$msg = $content; //信件內容

		$headers = "From: bm888@ms39.hinet.net"; //寄件者
		$headers .= "\nContent-Type:text/html;charset=utf-8";

		if (mail("$to", "$subject", "$msg", "$headers")) :
			echo "信件已經發送成功。"; //寄信成功就會顯示的提示訊息
		else :
			echo "信件發送失敗！"; //寄信失敗顯示的錯誤訊息
		endif;
		return $a;
	}

	//寄送直屬主管email
	public function otsysmail($m, $c)
	{


		$mail = new PHPMailer; // 先建立phpmailer實體
		$mail->isSMTP();
		$mail->SMTPAuth = true; //設定SMTP需要驗
		$mail->SMTPDebug = 0; //是否顯示client-server對話字串 0=off 1= show client 2 = show client and server
		$mail->Debugoutput = 'html'; //顯示對話串時的格式
		$mail->SMTPSecure = "ssl";
		$mail->Host = "smtp.gmail.com"; //電子郵件主機
		$mail->Port = 465; //設定通訊port
		$mail->Username = "";
		$mail->Password = "";
		$mail->CharSet = "utf-8";  //信件內容編碼
		$mail->Encoding = "base64"; //傳送編碼方式
		$mail->setFrom('', 'xxx'); //寄件者
		$mail->addReplyTo('', 'xxx'); //按回復郵件時的收件者

		$mail->AddAddress($m); //郵件收件者

		//收件者陣列								
		$maddress = explode(",", $m);
		for ($i = 0; $i < count($maddress); $i++) {
			$mail->AddAddress($maddress[$i]); //收件者
		}

		$mail->Subject = 'ERP系統通知信,System E-Mail'; //郵件主旨
		$mail->Body = $c; //郵件內容
		$mail->IsHTML(true); //設定郵件內容為HTML
		$mail->send(); //郵件發送
	}

	/*
	* //陣列POST
	* Form_Post ($form_array,$TargetPage)
	* 		參數1 : 要POST的陣列
	* 		參數2 : 目標位置, 為網址
	* 
	*/

	public function Form_Post($form_array, $TargetPage)
	{
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>POST</title>
			<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
			<script>
				$(function () {
					$("#BankForm").submit();
					return false;
				}) ;
			</script>
			</head>
			<body style="background: white;">';

		echo '<form id="BankForm" method="POST" action=' . $TargetPage . '>';

		$html_code = '';
		foreach ($form_array as $key => $val) {
			$html_code .= "<input type='hidden' name='" . $key . "' value='" . $val . "'><BR>";
		}
		echo $html_code;

		echo '</form></body></html>';
	}

	//方法：判斷是否爲空
	public function checkEmpty($username, $password)
	{
		if ($username == null || $password == null) {
			echo '<html><head><Script Language="JavaScript">alert("用戶名或密碼爲空");</Script></head></html>' . "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
		} else {
			//if($verifycode==null){
			/*  echo '<html><head><Script Language="JavaScript">alert("驗證碼爲空");</Script></head></html>' . "<meta http-equiv=\"refresh\" content=\"0;url=login.php\">";*/
			//}
			//else{
			return true;
			//}
		}
	}

	//方法：檢查驗證碼是否正確

	public function checkVerifycode($verifycode, $code)
	{
		if ($verifycode == $code) {
			return true;
		} else {
			echo '<html><head><Script Language="JavaScript">alert("驗證碼錯誤");</Script></head></html>' . "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
		}
	}

	//方法：查詢用戶是否在數據庫中
	public function checkUser($companyid, $companypw, $chktype)
	{

		$checkUserSQL = $this->pdo_connect()->prepare("
			
			SELECT *
			FROM web_company
			WHERE companyid = '$companyid'
			AND companypw = '$companypw'
			AND enable = '1'
			;
		");

		$checkUserSQL->execute();

		$rowCheckUser = $checkUserSQL->fetch();

		if ($chktype == 'auth') {

			if ($rowCheckUser['companyid'] == $companyid && $rowCheckUser['companypw'] == $companypw) {

				return true;
			} else {
				echo '<html><head><Script Language="JavaScript">alert("用戶不存在");</Script></head></html>' . "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
			}
		} else if ($chktype == 'un') {
			return $companyid;
		} else if ($chktype == 'id') {
			return $rowCheckUser['cid'];
		}
	}

	public function chkIPAddress()
	{
		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$ip = $_SERVER["REMOTE_ADDR"];
		}
		return $ip;
	}


	public function httpRequest($api, $data_string, $type, $url, $style)
	{
		/*
		*  $type = GET 取得API值
		*  $type = POST 傳遞欄位, 寫入目標網址資料
		*/

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);

		if ($type == "POST") {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); //POST欄位寫入API
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		$result = curl_exec($ch);
		curl_close($ch);

		if ($style == "ARR") {

			$dataResbonde = json_decode($result, true);

			foreach ($dataResbonde as $value) {

				return $value["price"];
			}
		} else if ($style == "JSON") {
			return $result;
		}
	}

	//方法：查詢用戶是否在數據庫中
	public function checkMember($username, $password, $chktype)
	{

		$checkMemberQuery = $this->pdo_connect()->prepare("
			
			SELECT *
			FROM web_member
			
			WHERE Member_Acc = '$username'
			AND realpwd = '$password'
			AND Member_Open = '1'
			;
		");

		$checkMemberQuery->execute();

		$rowCheckMember = $checkMemberQuery->fetch();

		if ($chktype == 'auth') {

			if ($rowCheckMember['Member_Acc'] == $username && $rowCheckMember['realpwd'] == $password) {

				return true;
			} else {
				echo '<html><head><Script Language="JavaScript">alert("用戶不存在");</Script></head></html>' . "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
			}
		} else if ($chktype == 'un') {

			return $username;
		} else if ($chktype == 'id') {

			return $rowCheckMember['Member_ID'];
		}
	}

	//方法：查詢用戶是否在數據庫中
	public function checkOrderMember($username, $password, $chktype)
	{

		$checkMemberQuery = $this->pdo_connect()->prepare("
			
			SELECT *
			FROM web_member
			
			WHERE Member_Acc = '$username'
			AND realpwd = '$password'
			AND Member_Open = '1'
			;
		");

		$checkMemberQuery->execute();

		$rowCheckMember = $checkMemberQuery->fetch();

		if ($chktype == 'auth') {

			if ($rowCheckMember['Member_Acc'] == $username && $rowCheckMember['realpwd'] == $password) {

				return true;
			} else {
				echo 2;
			}
		} else if ($chktype == 'un') {

			return $username;
		} else if ($chktype == 'id') {

			return $rowCheckMember['Member_ID'];
		}
	}

	public function getDatabase()
	{
		return $this->database;
	}

	public function checkPermission($pid, $pageName)
	{
		$result = $this->pdo_connect()->prepare("SELECT $pageName FROM web_permission
		WHERE pid = '$pid' AND $pageName= 1");

		$result->execute();

		if ($result->rowCount()) {
			$row = $result->fetch();
			return true;
		} else {
			echo ('<script>alert("無權限！");history.back();</script>');
			exit;
		}
	}

	public function checkPermissionEmployee($pid, $pageName)
	{
		$result = $this->pdo_connect()->prepare("SELECT $pageName FROM web_permission
		WHERE pid = '$pid' AND $pageName= 1");

		$result->execute();

		if ($result->rowCount()) {
			$row = $result->fetch();
			return true;
		} else {
			return false;
		}
	}
}

$DB = new dbCommand;

?>