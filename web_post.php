<?php
require_once(__DIR__ . '/include/web.config.php');

if (!isset($_POST['_type'])) {

	header("Location:index.php");
	exit;
}

$json_array = array();

$_Type  = $_POST['_type']; //主執行case

$_POST = arr_filter($_POST); //簡易輸入過濾

$is_verify = $_MemberData['Company_Verify'] == $_POST['token'] ? true : false; //檢查token，是否從正常管道寄送資料

ob_start();

if (!empty($_Type)) {

	$db 	= new MySQL();

	$Upload = new Upload();

	$sdate 	= date('Y-m-d H:i:s');

	switch ($_Type) {

			//帳號註冊-公司
		case "regist_com":
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['account'] 		= trim($_POST['account']); //名字
			$Value['password'] 		= trim($_POST['password']); //電話
			$Value['repassword'] 	= trim($_POST['repassword']); //電話
			$Value['Email'] 		= trim($_POST['email']); //信箱
			$Value['company_name'] 	= trim($_POST['com_name']);
			$Value['com_id'] 		= trim($_POST['com_id']);
			$Value['company_tel'] 	= trim($_POST['com_tel']);
			$Value['com_address']   = trim($_POST['com_address']);
			$Value['Verify'] 	= 'CT' . strtoupper(dechex(time()));
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $Value['account'])) {
				array_push($_html_msg_array, '帳號請輸入6~12位的英文加數字，請重新確認！');
			}
			if ($Value['password'] != $Value['repassword']) {
				array_push($_html_msg_array, '密碼與確認密碼不相同，請重新確認！');
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['agent_tel'] 	= trim($_POST['agent_tel']);
				$Value['agent_name']   	= trim($_POST['agent_name']);
				$Value['Plan']			= trim($_POST['Plan']);
				$Value['Pay_type']		= trim($_POST['Pay_Type']);
				$Value['Invoice_Address'] = trim($_POST['Invoice_Address']);
				$Value['Invoice_Type']  = trim($_POST['Invoice_Type']);
				$acc = $Value['account'];
				$email = $Value['Email'];
				$com_id = $Value['com_id'];
				$pwd = md5(Turnencode(trim($Value['password']), 'password'));
				$db->Where = " WHERE  Company_Acc = '" . $db->val_check($acc) . "' OR Company_email = '" . $db->val_check($email) . "' OR Company_EDITORIAL = '" . $db->val_check($com_id) . "' ";
				$db->query_sql($company_db, '*');
				if ($row = $db->query_fetch()) {
					$_html_msg = '經檢測，已發現有相同的資料，請重新檢查帳號、信箱和統編是否已經申請過';
					break;
				} else {
					$SN = date('Ymd');
					$SN = 'C' . substr($SN, -6);
					$Company_ID = GET_NEW_ID($company_db, 'Company_ID', $SN, 4);
					if (empty($Company_ID)) {
						$_html_msg 	= '企業編號取得失敗，請重新操作!';
						break;
					}
					$Indepflag = true;
					while ($Indepflag) {
						$Indep_SN = GET_RAND_ID(1, 'upper') . GET_RAND_ID(2, 'num');
						$db->Where = " WHERE  Company_IndepID = '" . $Indep_SN . "'";
						$db->query_sql($company_db, '*');
						if ($row = $db->query_fetch()) {
							$Indepflag = true;
						} else {
							$Indepflag = false;
						}
					}

					$db_data = array(
						'Company_ID'				=> $Company_ID,
						'Company_Acc'				=> $acc,
						'Company_PW' 				=> $pwd,
						'Company_NAME' 				=> $Value['company_name'],
						'Company_EDITORIAL'			=> $com_id,
						'Company_CTEL'				=> $Value['company_tel'],
						'Company_ADDRESS'			=> $Value['com_address'],
						'Company_PER'				=> $Value['agent_name'],
						'Company_TEL' 				=> $Value['agent_tel'],
						'Company_EMAIL' 			=> $Value['Email'],
						'Company_IndepID'			=> $Indep_SN,
						'Company_Plan'				=> $Value['Plan'],
						'Company_Pay_Type'			=> $Value['Pay_type'],
						'Company_Invoice_Address'	=> $Value['Invoice_Address'],
						'Company_Invoice_Type'		=> $Value['Invoice_Type'],
						'Company_Verify'			=> $Value['Verify'],
						'Company_NDATE'				=> $sdate
					);

					$db->query_data('web_company', $db_data, 'INSERT');
					if (!empty($db->Error)) {

						$_html_msg 	= '註冊失敗，請重新整理後再試試';
					} else {
						$FromName = !empty($_setting_['WO_SendName']) ? $_setting_['WO_SendName'] : '本網站';

						//收件人姓名 收件人信箱
						$mailto = array(
							$Value['Name'] => $Value['Email']
						);

						$sbody = get_MailBody('emailauth', $Value['Verify'] . '&type=company');


						//寄給客戶
						$Send_TF = SendMail($_setting_, '您在 ' . $FromName . ' 有進行企業註冊, 此信件為系統發出信件，請勿直接回覆', $sbody, $mailto);

						$_html_msg 	= '註冊成功！請進行Email認證後開通';

						// if( !empty($_setting_['WO_Email']) ){

						// $mailto = array(
						// 	$FromName => $_setting_['WO_Email']
						// );
						// $sbody = get_MailBody('admin_contact',$Value['Verify']);

						// //寄給管理者
						// $Send_TF = SendMail($_setting_, '您有新的企業註冊, 此信件為系統發出信件，請勿直接回覆', $sbody, $mailto);
						// }

						$_html_href = 'register_finish.php?c=' . OEncrypt('v=' . $Company_ID, 'register_finish');
					}
				}
			}
			break;

			//帳號註冊-消費者
		case "regist_cust":
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['reg_account'] 		= trim($_POST['reg_account']); //名字
			$Value['reg_password'] 		= trim($_POST['reg_password']); //電話
			$Value['reg_repassword'] 	= trim($_POST['reg_repassword']); //電話
			$Value['reg_email'] 		= trim($_POST['reg_email']); //信箱
			$Value['reg_name'] 			= trim($_POST['reg_name']);
			$Value['reg_tel'] 			= trim($_POST['reg_tel']);
			$Value['reg_city'] 			= trim($_POST['reg_city']);
			$Value['reg_county'] 		= trim($_POST['reg_county']);
			$Value['reg_sex'] 			= trim($_POST['reg_sex']);
			$Value['Verify'] 			= 'CT' . strtoupper(dechex(time()));
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $Value['reg_account'])) {
				array_push($_html_msg_array, '帳號請輸入6~12位的英文加數字，請重新確認！');
			}
			if ($Value['reg_password'] != $Value['reg_repassword']) {
				array_push($_html_msg_array, '密碼與確認密碼不相同，請重新確認！');
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$acc = $Value['reg_account'];
				$email = $Value['reg_email'];
				$pwd = md5(Turnencode(trim($Value['reg_password']), 'password'));
				$db->Where = " WHERE  Member_Acc = '" . $db->val_check($acc) . "' OR Member_email = '" . $db->val_check($email) . "'";
				$db->query_sql($member_db, '*');
				if ($row = $db->query_fetch()) {
					$_html_msg = '經檢測，已發現有相同的資料，請重新檢查帳號是否已經申請過';
					break;
				} else {
					$SN = date('Ymd');
					$SN = 'M' . substr($SN, -6);
					$Member_ID = GET_NEW_ID($member_db, 'Member_ID', $SN, 4);
					if (empty($Member_ID)) {
						$_html_msg 	= '會員編號取得失敗，請重新操作!';
						break;
					}
					$_MemberData = array(
						'Member_ID'			=> $Member_ID,
						'Member_Acc'		=> $acc,
						'Member_Pwd' 		=> $pwd,
						'Member_Name' 		=> $Value['reg_name'],
						'Member_Email' 		=> $Value['reg_email'],
						'Member_Mobile'		=> $Value['reg_tel'],
						'Member_City'		=> $Value['reg_city'],
						'Member_County'		=> $Value['reg_county'],
						'Member_Sex'		=> $Value['reg_sex'],
						'Member_Open'		=> 0,
						'Member_Verify'		=> $Value['Verify'],
						'Member_Sdate'		=> date('Y-m-d H:i:s', time()), //$sdate
					);

					$db->query_data('web_member', $_MemberData, 'INSERT');
					if (!empty($db->Error)) {

						$_html_msg 	= '註冊失敗，請重新整理後再試試';
					} else {


						$FromName = !empty($_setting_['WO_SendName']) ? $_setting_['WO_SendName'] : '本網站';

						//收件人姓名 收件人信箱
						$mailto = array(
							$Value['Name'] => $Value['reg_email']
						);

						$sbody = get_MailBody('emailauth', $Value['Verify'] . '&type=member');


						//寄給客戶
						$Send_TF = SendMail($_setting_, '您在 ' . $FromName . ' 有進行會員註冊, 此信件為系統發出信件，請勿直接回覆', $sbody, $mailto);

						$_html_msg 	= '註冊成功！請進行Email認證後開通';

						// if( !empty($_setting_['WO_Email']) ){

						// 	$mailto = array(
						// 		$FromName => $_setting_['WO_Email']
						// 	);
						// 	$sbody = get_MailBody('admin_contact',$Value['Verify']);

						// 	//寄給管理者
						// 	$Send_TF = SendMail($_setting_, '您有新的會員註冊, 此信件為系統發出信件，請勿直接回覆', $sbody, $mailto);
						// }

						$_html_href = 'register_finish.php?c=' . OEncrypt('v=' . $Member_ID, 'register_finish');
					}
				}
			}
			break;

			//會員資料修改-公司
		case "mem_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['cono'] 	= trim($_POST['cono']);
			$Value['coname1'] 		= trim($_POST['coname1']);
			$Value['coname2'] 		= trim($_POST['coname2']);
			$Value['coper'] 	= trim($_POST['coper']);
			$Value['couno']   	= trim($_POST['couno']);
			$Value['cotel1'] 		= trim($_POST['cotel1']);
			$Value['cofax1']   	= trim($_POST['cofax1']);
			$Value['coaddr1']   	= trim($_POST['coaddr1']);
			$Value['coemail']   	= trim($_POST['coemail']);
			$Value['laobaono']   	= trim($_POST['laobaono']);
			$Value['jianbaono']   	= trim($_POST['jianbaono']);

			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			$Value['cowww']   	= trim($_POST['cowww']);
			$Value['comemo1']   	= trim($_POST['comemo1']);
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$comp_data = array(
					'cono' 				=> $Value['cono'],
					'coname1' 			=> $Value['coname1'],
					'coname2' 			=> $Value['coname2'],
					'coper' 			=> $Value['coper'],
					'couno' 			=> $Value['couno'],
					'cotel1' 			=> $Value['cotel1'],
					'cofax1' 			=> $Value['cofax1'],
					'coaddr1' 			=> $Value['coaddr1'],
					'coemail' 			=> $Value['coemail'],
					'laobaono' 			=> $Value['laobaono'],
					'jianbaono' 		=> $Value['jianbaono'],
				);
				$db->Where = " WHERE  1 = 1";
				$db->query_sql($comp_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($comp_db, $comp_data, 'UPDATE');
				} else {
					$db->query_data($comp_db, $comp_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '修改失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '修改成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "pw_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['oldPassword'] 		= md5(Turnencode(trim($_POST['oldPassword']), 'password'));
			$Value['newPassword'] 		= md5(Turnencode(trim($_POST['newPassword']), 'password'));
			$Value['conPassword'] 		= md5(Turnencode(trim($_POST['conPassword']), 'password'));
			if ($_MemberData['Company_PW'] != $Value['oldPassword']) {
				array_push($_html_msg_array, '舊密碼不正確，請重新確認！');
			}
			if ($Value['newPassword'] != $Value['conPassword']) {
				array_push($_html_msg_array, '新密碼與確認密碼不相同，請重新確認！');
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$pwd = $Value['newPassword'];
				$db->Where = " WHERE  1 = 1 ";
				$db->query_sql($company_db, '*');
				if ($row = $db->query_fetch()) {
					$db_data = array(
						'Company_PW' 				=> $pwd,
					);
					$db->query_data($company_db, $db_data, 'UPDATE');
					if (!empty($db->Error)) {
						$_html_msg 	= '修改失敗，請重新整理後再試試';
					} else {
						$_html_msg 	= '修改成功！';
						$_html_eval = 'Reload()';
					}
				}
			}
			break;
		case "bank_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['bankno'] 		= $_POST['bankno'];
			$Value['bankname'] 		= $_POST['bankname'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['bankid'] 		= $_POST['bankid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$bank_data = array(
					'bankno' 				=> $Value['bankno'],
					'bankname' 			=> $Value['bankname'],
				);
				$db->Where = " WHERE  bankno = '" . $Value['bankid'] . "'";
				$db->query_sql($bank_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($bank_db, $bank_data, 'UPDATE');
				} else {
					$db->query_data($bank_db, $bank_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試或檢查是否有一樣的編號存在';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_banklist.php';
				}
			}
			break;
		case "bank_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['bankno'] 		= GDC($_POST['bankno'], 'bank')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  bankno = '" . $Value['bankno'] . "'";
				$db->query_delete($bank_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "education_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['educationno'] 		= $_POST['educationno'];
			$Value['educationname'] 		= $_POST['educationname'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['educationid'] 		= $_POST['educationid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$education_data = array(
					'educationno' 				=> $Value['educationno'],
					'educationname' 			=> $Value['educationname'],
				);
				$db->Where = " WHERE  educationno = '" . $Value['educationid'] . "'";
				$db->query_sql($education_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($education_db, $education_data, 'UPDATE');
				} else {
					$db->query_data($education_db, $education_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試或檢查是否有一樣的編號存在';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_educationlist.php';
				}
			}
			break;
		case "education_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['educationno'] 		= GDC($_POST['educationno'], 'education')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  educationno = '" . $Value['educationno'] . "'";
				$db->query_delete($education_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "jobs_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['appno'] 		= $_POST['appno'];
			$Value['appname'] 		= $_POST['appname'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['appid'] 		= $_POST['appid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$jobs_data = array(
					'appno' 				=> $Value['appno'],
					'appname' 			=> $Value['appname'],
				);
				$db->Where = " WHERE  appno = '" . $Value['appid'] . "'";
				$db->query_sql($jobs_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($jobs_db, $jobs_data, 'UPDATE');
				} else {
					$db->query_data($jobs_db, $jobs_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試或檢查是否有一樣的編號存在';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_jobslist.php';
				}
			}
			break;
		case "jobs_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['appno'] 		= GDC($_POST['appno'], 'jobs')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  appno = '" . $Value['appno'] . "'";
				$db->query_delete($jobs_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "part_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['partno'] 		= $_POST['partno'];
			$Value['partname'] 		= $_POST['partname'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['partid'] 		= $_POST['partid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$part_data = array(
					'partno' 				=> $Value['partno'],
					'partname' 			=> $Value['partname'],
				);
				$db->Where = " WHERE  partno = '" . $Value['partid'] . "'";
				$db->query_sql($part_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($part_db, $part_data, 'UPDATE');
				} else {
					$db->query_data($part_db, $part_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試或檢查是否有一樣的編號存在';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_partlist.php';
				}
			}
			break;
		case "part_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['partno'] 		= GDC($_POST['partno'], 'part')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  partno = '" . $Value['partno'] . "'";
				$db->query_delete($part_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "family_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['relationno'] 		= $_POST['relationno'];
			$Value['relationship'] 		= $_POST['relationship'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['relationid'] 		= $_POST['relationid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$family_data = array(
					'relationno' 				=> $Value['relationno'],
					'relationship' 			=> $Value['relationship'],
				);
				$db->Where = " WHERE  relationno = '" . $Value['relationid'] . "'";
				$db->query_sql($family_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($family_db, $family_data, 'UPDATE');
				} else {
					$db->query_data($family_db, $family_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試或檢查是否有一樣的編號存在';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_familylist.php';
				}
			}
			break;
		case "family_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['relationno'] 		= GDC($_POST['relationno'], 'family')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  relationno = '" . $Value['relationno'] . "'";
				$db->query_delete($family_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "reason_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['reasonno'] 		= $_POST['reasonno'];
			$Value['reason'] 		= $_POST['reason'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['reasonid'] 		= $_POST['reasonid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$reason_data = array(
					'reasonno' 				=> $Value['reasonno'],
					'reason' 			=> $Value['reason'],
				);
				$db->Where = " WHERE  reasonno = '" . $Value['reasonid'] . "'";
				$db->query_sql($reason_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($reason_db, $reason_data, 'UPDATE');
				} else {
					$db->query_data($reason_db, $reason_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試或檢查是否有一樣的編號存在';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_reasonlist.php';
				}
			}
			break;
		case "reason_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['reasonno'] 		= GDC($_POST['reasonno'], 'reason')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  reasonno = '" . $Value['reasonno'] . "'";
				$db->query_delete($reason_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "deduction_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['deductionno'] 		= $_POST['deductionno'];
			$Value['deductionname'] 		= $_POST['deductionname'];
			$Value['dedmny'] 		= $_POST['dedmny'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['deductionid'] 		= $_POST['deductionid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$deduction_data = array(
					'deductionno' 				=> $Value['deductionno'],
					'deductionname' 			=> $Value['deductionname'],
					'dedmny' 			=> $Value['dedmny'],
				);
				$db->Where = " WHERE  deductionno = '" . $Value['deductionid'] . "'";
				$db->query_sql($deduction_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($deduction_db, $deduction_data, 'UPDATE');
				} else {
					$db->query_data($deduction_db, $deduction_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試或檢查是否有一樣的編號存在';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_deductionlist.php';
				}
			}
			break;
		case "deduction_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['deductionno'] 		= GDC($_POST['deductionno'], 'deduction')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  deductionno = '" . $Value['deductionno'] . "'";
				$db->query_delete($deduction_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "seclab_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['seclabMny'] 		= $_POST['seclabMny'];
			$Value['seclablMny'] 		= $_POST['seclablMny'];
			$Value['ForeignMny'] 		= $_POST['ForeignMny'];
			$Value['employerSeclablMny'] 		= $_POST['employerSeclablMny'];
			$Value['employerForeignMny'] 		= $_POST['employerForeignMny'];
			$len = count($Value['seclabMny']);
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  1 = 1";
				$db->query_delete($seclab1_db);
				for ($i = 0; $i < $len; $i++) {
					$seclab_data = array(
						'seclabNo'				=> (int)$i + 1,
						'seclabMny' 			=> $Value['seclabMny'][$i],
						'seclablMny' 			=> $Value['seclablMny'][$i],
						'ForeignMny' 			=> $Value['ForeignMny'][$i],
						'employerSeclablMny' 			=> $Value['employerSeclablMny'][$i],
						'employerForeignMny' 			=> $Value['employerForeignMny'][$i],
					);
					$db->query_data($seclab1_db, $seclab_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_seclab1.php';
				}
			}
			break;
		case "purchaser_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['purchaserhmny'] 		= $_POST['purchaserhmny'];
			$Value['purchasermny'] 		= $_POST['purchasermny'];
			$Value['employerPurchaserhmny'] 		= $_POST['employerPurchaserhmny'];
			$len = count($Value['purchaserhmny']);
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE  1 = 1";
				$db->query_delete($purchaser1_db);
				for ($i = 0; $i < $len; $i++) {
					$purchaser_data = array(
						'purchaserno'				=> (int)$i + 1,
						'purchaserhmny' 			=> $Value['purchaserhmny'][$i],
						'purchasermny' 			=> $Value['purchasermny'][$i],
						'employerPurchaserhmny' 			=> $Value['employerPurchaserhmny'][$i],
					);
					$db->query_data($purchaser1_db, $purchaser_data, 'INSERT');
				}
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = 'm_purchaser1.php';
				}
			}
			break;
		case "attendance_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['attendanceno'] 		= $_POST['attendanceno'];
			$Value['attendancename'] 		= $_POST['attendancename'];
			$Value['week'] 		= $_POST['week'];
			$Value['ontime'] 		= $_POST['ontime'];
			$Value['latetime'] 		= $_POST['latetime'];
			$Value['resttime1'] 		= $_POST['resttime1'];
			$Value['resttime2'] 		= $_POST['resttime2'];
			$Value['resttime3'] 		= $_POST['resttime3'];
			$Value['resttime4'] 		= $_POST['resttime4'];
			$Value['offtime'] 		= $_POST['offtime'];
			$Value['addontime'] 		= $_POST['addontime'];
			$Value['addofftime'] 		= $_POST['addofftime'];
			$Value['mealtime'] 		= $_POST['mealtime'];
			$Value['worktime'] 		= $_POST['worktime'];
			$Value['daytype'] 		= $_POST['daytype'];
			$len = count($_POST['week']);
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['attendanceid'] 		= $_POST['attendanceid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$Value['origin_attendanceno'] 		= $_POST['origin_attendanceno']; //若是編輯已有的資料，該欄位回傳不會是空值
				if ($Value['attendanceid'][0]) {
					if ($Value['origin_attendanceno'] != $Value['attendanceno']) {
						$db->Where = " WHERE attendanceno = '" . $Value['attendanceno'] . "'";
						$db->query_sql($attendance_db, '*');
						if ($row = $db->query_fetch()) {
							$_html_msg 	= '班別編號已存在，請更換一個後再儲存';
							break;
						}
					}
				} else {
					$db->Where = " WHERE attendanceno = '" . $Value['attendanceno'] . "'";
					$db->query_sql($attendance_db, '*');
					if ($row = $db->query_fetch()) {
						$_html_msg 	= '班別編號已存在，請更換一個後再儲存';
						break;
					}
				}
				for ($i = 0; $i < $len; $i++) {
					$attendance_data = array(
						'attendanceno'				=> $Value['attendanceno'],
						'attendancename' 			=> $Value['attendancename'],
						'week' 						=> $Value['week'][$i],
						'ontime' 					=> str_replace(":", "", $Value['ontime'][$i]),
						'latetime' 					=> str_replace(":", "", $Value['latetime'][$i]),
						'resttime1' 				=> str_replace(":", "", $Value['resttime1'][$i]),
						'resttime2' 				=> str_replace(":", "", $Value['resttime2'][$i]),
						'resttime3' 				=> str_replace(":", "", $Value['resttime3'][$i]),
						'resttime4' 				=> str_replace(":", "", $Value['resttime4'][$i]),
						'offtime' 					=> str_replace(":", "", $Value['offtime'][$i]),
						'addontime' 				=> str_replace(":", "", $Value['addontime'][$i]),
						'addofftime' 				=> str_replace(":", "", $Value['addofftime'][$i]),
						'mealtime' 					=> str_replace(":", "", $Value['mealtime'][$i]),
						'worktime' 					=> $Value['worktime'][$i],
						'type' 						=> $Value['daytype'][$i],
					);
					if ($Value['attendanceid'][$i]) {
						$db->Where = " WHERE attendanceid = '" . $Value['attendanceid'][$i] . "'";
						$db->query_data($attendance_db, $attendance_data, 'UPDATE');
					} else {
						$db->query_data($attendance_db, $attendance_data, 'INSERT');
					}
				}
				if ($Value['origin_attendanceno']) {
					if (!empty($db->Error)) {
						$_html_msg 	= '儲存失敗，請重新整理後再試試';
					} else {
						$_html_msg 	= '儲存成功！';
						$_html_href = "m_attendance.php?c=" . OEncrypt('v=' . $Value['attendanceno'], 'attendance');
					}
				} else {
					if (!empty($db->Error)) {
						$_html_msg 	= '新增失敗，請重新整理後再試試';
					} else {
						$_html_msg 	= '新增成功！';
						$_html_eval = 'Reload();';
					}
				}
			}
			break;
		case "attendance_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['attendanceno'] 		= GDC($_POST['attendanceno'], 'attendance')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE attendanceno = '" . $Value['attendanceno'] . "'";
				$db->query_delete($attendance_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "holidays_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['niandu'] 		= Intval($_POST['niandu']);
			$Value['holiday'] 		= $_POST['holiday'];
			$Value['holidayName'] 		= $_POST['holidayName'];
			$Value['AttendDay'] 		= $_POST['AttendDay'];
			$len = count($_POST['holiday']);
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$Value['holidayid'] 		= $_POST['holidayid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$Value['origin_niandu'] 		= $_POST['origin_niandu']; //若是編輯已有的資料，該欄位回傳不會是空值
				if ($Value['origin_niandu']) {
					if ($Value['origin_niandu'] != $Value['niandu']) {
						$db->Where = " WHERE niandu = '" . $Value['niandu'] . "'";
						$db->query_sql($holidays_db, '*');
						if ($row = $db->query_fetch()) {
							$_html_msg 	= '年度已存在，請更換一個後再儲存';
							break;
						}
					}
					$db->Where = " WHERE niandu = '" . $Value['niandu'] . "'";
					$db->query_delete($holidays_db);
				} else {
					$db->Where = " WHERE niandu = '" . $Value['niandu'] . "'";
					$db->query_sql($holidays_db, '*');
					if ($row = $db->query_fetch()) {
						$_html_msg 	= '年度已存在，請更換一個後再儲存';
						break;
					}
				}
				for ($i = 0; $i < $len; $i++) {
					$holidays_data = array(
						'niandu'					=> $Value['niandu'],
						'holiday' 					=> date("md", strtotime($_POST['holiday'][$i])),
						'holidayName' 				=> $Value['holidayName'][$i],
						'AttendDay' 					=> $Value['AttendDay'][$i],
					);
					$db->query_data($holidays_db, $holidays_data, 'INSERT');
				}
				if ($Value['origin_niandu']) {
					if (!empty($db->Error)) {
						$_html_msg 	= '儲存失敗，請重新整理後再試試';
					} else {
						$_html_msg 	= '儲存成功！';
						$_html_href = "m_holidays.php?c=" . OEncrypt('v=' . $Value['niandu'], 'holidays');
					}
				} else {
					if (!empty($db->Error)) {
						$_html_msg 	= '新增失敗，請重新整理後再試試';
					} else {
						$_html_msg 	= '新增成功！';
						$_html_eval = 'Reload();';
					}
				}
			}
			break;
		case "holidays_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['niandu'] 		= GDC($_POST['niandu'], 'holidays')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE niandu = '" . $Value['niandu'] . "'";
				$db->query_delete($holidays_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "employee_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['cono'] 		= $_POST['cono'];
			$Value['coname1'] 		= $_POST['coname1'];
			$Value['employeid'] 		= $_POST['employeid'];
			$Value['employename'] 		= $_POST['employename'];

			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				//從POST傳值
				$Value['eid'] 			= $_POST['eid']; //若是編輯已有的資料，該欄位回傳不會是空值
				$Value['EngName'] 		= $_POST['EngName'];
				$Value['sex'] 			= $_POST['sex'];
				$Value['marry'] 		= $_POST['marry'];
				$Value['blood'] 		= $_POST['blood'];
				$Value['nationality'] 	= $_POST['nationality'];
				$Value['born'] 			= $_POST['born'];
				$Value['bornday'] 		= $_POST['bornday'];
				$Value['email'] 		= $_POST['email'];
				$Value['address'] 		= $_POST['address'];
				$Value['tel'] 			= $_POST['tel'];
				$Value['fax'] 			= $_POST['fax'];
				$Value['no'] 		= $_POST['no'];
				$Value['id'] 		= $_POST['id'];
				$Value['mphone'] 		= $_POST['mphone'];
				$Value['partno'] 		= $_POST['partno'];
				$Value['partname'] 		= $_POST['partname'];
				$Value['appno'] 		= $_POST['appno'];
				$Value['appname'] 		= $_POST['appname'];
				$Value['presenttype'] 	= $_POST['presenttype'];
				$Value['presentname'] = $_POST['presentname'];
				$Value['workday'] 		= $_POST['workday'];
				$Value['expireday'] 	= $_POST['expireday'];
				$Value['contact'] 		= $_POST['contact'];
				$Value['contactrelation'] 		= $_POST['contactrelation'];
				$Value['contacttel1'] 	= $_POST['contacttel1'];
				$Value['contacttel2'] 	= $_POST['contacttel2'];
				$Value['contactadd'] 	= $_POST['contactadd'];
				$Value['pro'] 			= $_POST['pro'];
				$Value['add1'] 			= $_POST['add1'];
				$Value['add2'] 			= $_POST['add2'];
				//後端另外處理
				$Value['bornday2'] 		= tw2ad($Value['bornday']);
				$Value['workday2'] 		=  tw2ad($Value['workday']);
				$Value['expireday2'] 	=  tw2ad($Value['expireday']);
				$employee = $CM->get_employee_data($Value['eid']);
				if (!empty($employee)) {
					$o_employid = $employee['employeid'];
					$o_no = $employee['no'];
					$Value['buildday'] 		= $employee['buildday'];
					$Value['buildday2'] 	= $employee['buildday2'];
				} else {
					$o_employid = '';
					$o_no = '';
					$tw_year = date('Y') - 1911;
					$Value['buildday'] 		=  $tw_year . '-' . date('m-d');
					$Value['buildday2'] 	= date('Y-m-d');
				}
				if ($o_employid != $Value['employeid']) {
					$db->Where = " WHERE employeid = '" . $Value['employeid'] . "'";
					$db->query_sql($employee_db, '*');
					if ($row = $db->query_fetch()) {
						$_html_msg 	= '員工編號已存在，請更換一個後再儲存';
						break;
					}
				}
				if ($o_no != $Value['no'] && !empty($Value['no'])) {
					$db->Where = " WHERE no = '" . $Value['no'] . "'";
					$db->query_sql($employee_db, '*');
					if ($row = $db->query_fetch()) {
						$_html_msg 	= '卡片編號已存在，請更換一個後再儲存';
						break;
					}
				}
				$employee_data = array(
					'cono' 				=> $Value['cono'],
					'coname1' 			=> $Value['coname1'],
					'employeid' 			=> $Value['employeid'],
					'employename' 		=> $Value['employename'],
					'no' 				=> $Value['no'],
					'id' 				=> $Value['id'],
					'EngName' 			=> $Value['EngName'],
					'sex' 				=> $Value['sex'],
					'marry' 			=> $Value['marry'],
					'blood' 			=> $Value['blood'],
					'nationality' 		=> $Value['nationality'],
					'born' 				=> $Value['born'],
					'bornday' 			=> $Value['bornday'],
					'bornday2' 			=> $Value['bornday2'],
					'email' 			=> $Value['email'],
					'address' 			=> $Value['address'],
					'tel' 				=> $Value['tel'],
					'fax' 				=> $Value['fax'],
					'mphone' 			=> $Value['mphone'],
					'partno' 			=> $Value['partno'],
					'partname' 			=> $Value['partname'],
					'appno' 			=> $Value['appno'],
					'appname' 			=> $Value['appname'],
					'presenttype' 		=> $Value['presenttype'],
					'presentname' 		=> $Value['presentname'],
					'workday' 			=> $Value['workday'],
					'workday2' 			=> $Value['workday2'],
					'expireday' 		=> $Value['expireday'],
					'expireday2' 		=> $Value['expireday2'],
					'contact' 			=> $Value['contact'],
					'contactrelation' 	=> $Value['contactrelation'],
					'contacttel1' 		=> $Value['contacttel1'],
					'contacttel2' 		=> $Value['contacttel2'],
					'contactadd' 		=> $Value['contactadd'],
					'pro' 				=> $Value['pro'],
					'add1' 				=> $Value['add1'],
					'add2' 				=> $Value['add2'],
					'add1' 				=> $Value['add1'],
					'buildday'			=> $Value['buildday'],
					'buildday2'			=> $Value['buildday2'],
				);
				$db->Where = " WHERE  eid = '" . $Value['eid'] . "'";
				$db->query_sql($employee_db, '*');
				if ($row = $db->query_fetch()) {
					$db->query_data($employee_db, $employee_data, 'UPDATE');
				} else {
					$db->query_data($employee_db, $employee_data, 'INSERT');
				}
				if ($Value['eid']) {
					if (!empty($db->Error)) {
						$_html_msg 	= '儲存失敗，請重新整理後再試試';
					} else {
						$_html_msg 	= '儲存成功！';
						$_html_href = "m_employee.php?c=" . OEncrypt('v=' . $Value['employeid'], 'employee');
					}
				} else {
					if (!empty($db->Error)) {
						$_html_msg 	= '新增失敗，請重新整理後再試試';
					} else {
						$_html_msg 	= '新增成功！';
						$_html_eval = 'Reload();';
					}
				}
			}
			break;
		case "employee_del":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['eid'] 		= GDC($_POST['eid'], 'employee')['v'];
			foreach ($Value as $key => $val) {
				if (empty($val)) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$db->Where = " WHERE eid = '" . $Value['eid'] . "'";
				$db->query_delete($employee_db);
				if (!empty($db->Error)) {
					$_html_msg 	= '刪除失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '刪除成功！';
					$_html_eval = 'Reload()';
				}
			}
			break;
		case "salary_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['eid'] 			= intval($_POST['eid']);
			$employee = $CM->get_employee_data($Value['eid']);
			$Value['sandtype'] 		= $_POST['sandtype'];
			$Value['standardday'] 	= $_POST['standardday'];
			$Value['standardhour'] 	= $_POST['standardhour'];
			$Value['monthmny'] 		= $_POST['monthmny'];
			$Value['daymny'] 		= $_POST['daymny'];
			$Value['hourmny'] 		= $_POST['hourmny'];
			$Value['taxmny'] 		= $_POST['taxmny'];
			$Value['starttype'] 	= !empty($_POST['starttype']) ? '1' : '0';
			$Value['resttype'] 		= !empty($_POST['resttype']) ? '1' : '0';
			$Value['bankno'] 		= $_POST['bankno'];
			$Value['bankno2'] 		= $_POST['bankno2'];
			$Value['bankname'] 		= $_POST['bankname'];
			$Value['bankname2'] 	= $_POST['bankname2'];
			$Value['huming'] 		= $_POST['huming'];
			$Value['huming2'] 		= $_POST['huming2'];
			$Value['bankid'] 		= $_POST['bankid'];
			$Value['bankid2'] 		= $_POST['bankid2'];
			if (empty($employee)) { //判斷資料完整度
				$_html_msg = '請先產生員工資料後再進行';
				break;
			} else {
				$employee_data = array(
					'sandtype' 				=> $Value['sandtype'],
					'standardday' 			=> $Value['standardday'],
					'standardhour' 			=> $Value['standardhour'],
					'monthmny' 				=> $Value['monthmny'],
					'daymny' 				=> $Value['daymny'],
					'hourmny' 				=> $Value['hourmny'],
					'taxmny' 				=> $Value['taxmny'],
					'starttype' 			=> $Value['starttype'],
					'resttype' 				=> $Value['resttype'],
					'bankno' 				=> $Value['bankno'],
					'bankno2' 				=> $Value['bankno2'],
					'bankname' 				=> $Value['bankname'],
					'bankname2' 			=> $Value['bankname2'],
					'huming' 				=> $Value['huming'],
					'huming2' 				=> $Value['huming2'],
					'bankid' 				=> $Value['bankid'],
					'bankid2' 				=> $Value['bankid2'],
				);
				$db->Where = " WHERE  eid = '" . $Value['eid'] . "'";
				$db->query_data($employee_db, $employee_data, 'UPDATE');
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = "m_employee.php?c=" . OEncrypt('v=' . $employee['employeid'], 'employee');
				}
			}
			break;
		case "overtime_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['eid'] 			= intval($_POST['eid']);
			$employee = $CM->get_employee_data($Value['eid']);
			$Value['overtimetype'] 				= !empty($_POST['overtimetype']) ? '1' : '0';
			$Value['overtimemnytype'] 			= $_POST['overtimemnytype'];
			$Value['normalovertimemny'] 		= $_POST['normalovertimemny'];
			$Value['normalovertimerate'] 		= $_POST['normalovertimerate'];
			$Value['extendovertimemny'] 		= $_POST['extendovertimemny'];
			$Value['extendovertimerate'] 		= $_POST['extendovertimerate'];
			$Value['holidayovertimemny'] 		= $_POST['holidayovertimemny'];
			$Value['holidayovertimerate'] 		= $_POST['holidayovertimerate'];
			$Value['publicholidayovertimemny'] 	= $_POST['publicholidayovertimemny'];
			$Value['publicholidayovertimerate'] = $_POST['publicholidayovertimerate'];
			$Value['restovertimemny1'] 			= $_POST['restovertimemny1'];
			$Value['restovertimemny2'] 			= $_POST['restovertimemny2'];
			$Value['restovertimemny3'] 			= $_POST['restovertimemny3'];
			$Value['resthourrate1'] 		= $_POST['resthourrate1'];
			$Value['resthourrate2'] 		= $_POST['resthourrate2'];
			$Value['resthourrate3'] 		= $_POST['resthourrate3'];
			$Value['otway'] 					= $_POST['otway'];
			$Value['jiabanbudadan'] 			= !empty($_POST['jiabanbudadan']) ? '1' : '0';
			$Value['overtime'] 					= $_POST['overtime'];
			$Value['jiabanbudashi'] 			= !empty($_POST['jiabanbudashi']) && $_POST['overtime'] == '1' ? $_POST['jiabanbudashi'] : '0';
			$Value['mealflag'] 					= !empty($_POST['mealflag']) ? '1' : '0';
			$Value['mealmny'] 					= !empty($_POST['mealflag']) && !empty($_POST['mealmny']) ? $_POST['mealmny'] : '0';
			if (empty($employee)) { //判斷資料完整度
				$_html_msg = '請先產生員工資料後再進行';
				break;
			} else {
				$employee_data = array(
					'overtimetype' 				=> $Value['overtimetype'],
					'overtimemnytype' 			=> $Value['overtimemnytype'],
					'normalovertimemny' 		=> $Value['normalovertimemny'],
					'normalovertimerate' 		=> $Value['normalovertimerate'],
					'extendovertimemny' 		=> $Value['extendovertimemny'],
					'extendovertimerate' 		=> $Value['extendovertimerate'],
					'holidayovertimemny' 		=> $Value['holidayovertimemny'],
					'holidayovertimerate' 		=> $Value['holidayovertimerate'],
					'publicholidayovertimemny' 	=> $Value['publicholidayovertimemny'],
					'publicholidayovertimerate' => $Value['publicholidayovertimerate'],
					'restovertimemny1' 			=> $Value['restovertimemny1'],
					'restovertimemny2' 			=> $Value['restovertimemny2'],
					'restovertimemny3' 			=> $Value['restovertimemny3'],
					'resthourrate1' 			=> $Value['resthourrate1'],
					'resthourrate2' 			=> $Value['resthourrate2'],
					'resthourrate3' 			=> $Value['resthourrate3'],
					'otway' 					=> $Value['otway'],
					'jiabanbudadan' 			=> $Value['jiabanbudadan'],
					'overtime' 					=> $Value['overtime'],
					'jiabanbudashi' 			=> $Value['jiabanbudashi'],
					'mealflag' 					=> $Value['mealflag'],
					'mealmny' 					=> $Value['mealmny'],
				);
				$db->Where = " WHERE  eid = '" . $Value['eid'] . "'";
				$db->query_data($employee_db, $employee_data, 'UPDATE');
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = "m_employee.php?c=" . OEncrypt('v=' . $employee['employeid'], 'employee');
				}
			}
			break;
		case "insurance_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$Value['eid'] 			= intval($_POST['eid']);
			$employee = $CM->get_employee_data($Value['eid']);
			$Value['insuredperson'] 			= $_POST['insuredperson'];
			$Value['seclabno'] 					= $_POST['seclabno'];
			$Value['seclabtno'] 				= $_POST['seclabtno'];
			$Value['purchaserno'] 				= $_POST['purchaserno'];
			$Value['lmoney'] 					= $_POST['lmoney'];
			$Value['tmoney'] 					= $_POST['tmoney'];
			$Value['hmoney'] 					= $_POST['hmoney'];
			$Value['selflmoney'] 				= $_POST['selflmoney'];
			$Value['selftmoney'] 				= $_POST['selftmoney'];
			$Value['selfhmoney'] 				= $_POST['selfhmoney'];
			$Value['selftrate'] 				= $_POST['selftrate'];
			$Value['insuredsum'] 				= $_POST['insuredsum'];
			$Value['insuredmny'] 				= $_POST['insuredmny'];
			$Value['tuixiuselfmny'] 			= $_POST['tuixiuselfmny'];
			$Value['tuixiugerenmny'] 			= $_POST['tuixiugerenmny'];
			$Value['employerlmny'] 				= $_POST['employerlmny'];
			$Value['employerhmny'] 				= $_POST['employerhmny'];
			if (empty($employee)) { //判斷資料完整度
				$_html_msg = '請先產生員工資料後再進行';
				break;
			} else {
				$employee_data = array(
					'insuredperson' 				=> $Value['insuredperson'],
					'seclabno' 			=> $Value['seclabno'],
					'seclabtno' 		=> $Value['seclabtno'],
					'purchaserno' 		=> $Value['purchaserno'],
					'lmoney' 		=> $Value['lmoney'],
					'tmoney' 		=> $Value['tmoney'],
					'hmoney' 		=> $Value['hmoney'],
					'selflmoney' 		=> $Value['selflmoney'],
					'selftmoney' 	=> $Value['selftmoney'],
					'selfhmoney' => $Value['selfhmoney'],
					'selftrate' 			=> $Value['selftrate'],
					'insuredsum' 			=> $Value['insuredsum'],
					'insuredmny' 			=> $Value['insuredmny'],
					'tuixiuselfmny' 			=> $Value['tuixiuselfmny'],
					'tuixiugerenmny' 			=> $Value['tuixiugerenmny'],
					'employerlmny' 			=> $Value['employerlmny'],
					'employerhmny' 					=> $Value['employerhmny'],
				);
				$db->Where = " WHERE  eid = '" . $Value['eid'] . "'";
				$db->query_data($employee_db, $employee_data, 'UPDATE');
				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = "m_employee.php?c=" . OEncrypt('v=' . $employee['employeid'], 'employee');
				}
			}
			break;
		case "ededuction_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$value['eid'] 			= intval($_POST['eid']);
			$employee = $CM->get_employee_data($value['eid']);
			$value['dotype'] 			= $_POST['dotype'];
			$value['deductionno'] 		= $_POST['deductionno'];
			$value['deductionname'] 	= $_POST['deductionname'];
			$value['deductionmny'] 			= $_POST['deductionmny'];
			$value['jstype'] 			= $_POST['jstype'];
			$len = count($value['deductionno']);
			if (empty($employee)) { //判斷資料完整度
				$_html_msg = '請先產生員工資料後再進行';
				break;
			} else {

				$db->Where = " WHERE  employeid = '" . $value['eid'] . "'";
				$db->query_delete($ed_db);
				for ($i = 0; $i < $len; $i++) {
					$ed_data = array(
						'employeid' 		=> $value['eid'],
						'dotype' 			=> !empty($value['dotype'][$i]) ? $value['dotype'][$i] : '0',
						'deductionno' 		=> $value['deductionno'][$i],
						'deductionname' 	=> $value['deductionname'][$i],
						'deductionmny' 		=> $value['deductionmny'][$i],
						'jstype' 			=> $value['jstype'][$i],
					);
					$db->query_data($ed_db, $ed_data, 'INSERT');
					if (!empty($db->Error)) {
						$_html_msg 	= '儲存失敗，請重新整理後再試試';
						break;
					}
				}
				if (empty($db->Error)) {
					$_html_msg 	= '儲存成功！';
					$_html_href = "m_employee.php?c=" . OEncrypt('v=' . $employee['employeid'], 'employee');
				}
			}
			break;
		case "cardset_edit":
			if (!$_Login || !$is_verify) {
				$_html_msg = '請先登入帳號！';
				$_html_href = "index.php";
				break;
			}
			$_html_msg = '';
			$_html_msg_array = array();
			$value = array();
			$value['years'] 				= $_POST['years'];
			$value['yeare'] 				= $_POST['yeare'];
			$value['yeartype'] 				= $_POST['yeartype'];
			$value['months'] 				= $_POST['months'];
			$value['monthe'] 				= $_POST['monthe'];
			$value['days'] 					= $_POST['days'];
			$value['daye'] 					= $_POST['daye'];
			$value['hours'] 				= $_POST['hours'];
			$value['houre'] 				= $_POST['houre'];
			$value['minutes'] 				= $_POST['minutes'];
			$value['minutee'] 				= $_POST['minutee'];
			$value['employees'] 			= $_POST['employees'];
			$value['employeee'] 			= $_POST['employeee'];
			$value['employeetype'] 			= $_POST['employeetype'];
			$value['discerns'] 				= $_POST['discerns'];
			$value['discerne'] 				= $_POST['discerne'];
			foreach ($value as $key => $val) {
				if ($val == null) {
					array_push($_html_msg_array, '資料填寫不完整');
					break;
				}
			}
			if (!empty($_html_msg_array)) { //判斷資料完整度
				foreach ($_html_msg_array as $hma) {
					$_html_msg = $hma;
					break;
				}
			} else {
				$value['ontimed'] 				= $_POST['ontimed'];
				$value['offtimed'] 				= $_POST['offtimed'];
				$value['restime1d'] 				= $_POST['restime1d'];
				$value['restime2d'] 				= $_POST['restime2d'];
				$value['addontimed'] 				= $_POST['addontimed'];
				$value['addofftimed'] 				= $_POST['addofftimed'];
				$cardset_data = array(
					'years' 			=> $value['years'],
					'yeare' 			=> $value['yeare'],
					'yeartype' 			=> $value['yeartype'],
					'months' 			=> $value['months'],
					'monthe' 			=> $value['monthe'],
					'days' 				=> $value['days'],
					'daye' 				=> $value['daye'],
					'hours' 			=> $value['hours'],
					'houre' 			=> $value['houre'],
					'minutes' 			=> $value['minutes'],
					'minutee' 			=> $value['minutee'],
					'employees' 		=> $value['employees'],
					'employeee' 		=> $value['employeee'],
					'employeetype' 		=> $value['employeetype'],
					'discerns' 			=> $value['discerns'],
					'discerne' 			=> $value['discerne'],
					'ontimed' 			=> $value['ontimed'],
					'offtimed' 			=> $value['offtimed'],
					'restime1d' 		=> $value['restime1d'],
					'restime2d' 		=> $value['restime2d'],
					'addontimed' 		=> $value['addontimed'],
					'addofftimed' 		=> $value['addofftimed'],
				);
				$db2 = new Mysql();
				$db2->Where = " WHERE  1=1";
				$db2->query_sql($cardset_db, '*', 0, 1);
				if ($row = $db2->query_fetch()) {
					$db2->query_data($cardset_db, $cardset_data, 'UPDATE');
				} else {
					$db2->query_data($cardset_db, $cardset_data, 'INSERT');
				}

				if (!empty($db->Error)) {
					$_html_msg 	= '儲存失敗，請重新整理後再試試';
				} else {
					$_html_msg 	= '儲存成功！';
					$_html_href = "m_daka.php";
				}
			}
			break;
		case "mlogin":


			$acc 		= $_POST['acc'];

			$pwd = md5(Turnencode(trim($_POST['pwd']), 'password'));
			//-----------------------CAPTCHA ------------------------------------
			$capcha = trim($_POST['capcha']);

			$formsigncode 	= trim($_POST['formsigncode']);

			if ($formsigncode != $_SESSION['website']['formsigncode'] || empty($formsigncode)) {

				$_html_msg 	= '驗證錯誤, 請重新操作';
				break;
			}

			if (empty($acc) || empty($pwd)) {

				$_html_msg 	= '帳號和密碼請勿空白, 請重新操作';
				break;
			}

			if ($capcha != $_SESSION['string']) {

				$_html_msg 	= '驗證碼錯誤, 請重新輸入';
				break;
			}
			//-----------------------recaptcha------------------------------------

			// if( 0 && !empty($_POST['recaptchaResponse']) ) {

			// 	$RC = new ReCaptcha();
			// 	$RC->Verify( $_POST['recaptchaResponse'] );

			// 	if( $RC->rs != "success" ) {

			// 		$_html_msg 	= '驗證碼連接失敗';
			// 		break;
			// 	}
			// }
			//-------------------------------------------------------------------

			ob_start();

			if (empty($_html_msg)) {

				$db->Where = " WHERE (BINARY Company_Acc = '" . $db->val_check($acc) . "' OR BINARY Company_Email = '" . $db->val_check($acc) . "') AND (BINARY Company_PW = '" . $db->val_check($pwd) . "' OR BINARY Company_RePwd = '" . $db->val_check($pwd) . "')";
				$db->query_sql($company_db, 'Company_Open, Company_ID, Company_Name');
				if ($row = $db->query_fetch()) {

					if ($row['Company_Open'] != 1) {

						$_html_msg 	= '帳號未啟用';
					} else {
						$token = bin2hex(random_bytes(16));
						$db->query_data($company_db, array('Company_PW' => $pwd, 'Company_RePwd' => '', 'Company_Is_RePwd' => 0, 'Company_Verify' => $token), 'UPDATE');
						$_html_msg 	= '歡迎 ' . $row['Company_Name'] . ' 回來';
						$_SESSION[$_Website]['website']['company_id']    = $row['Company_ID'];
						$_SESSION[$_Website]['website']['type']    = 'company';
						$_html_href = 'member.php';
					}
				} else {

					$_html_msg 	= '帳號或密碼輸入錯誤';
					$_html_eval = 'Reload()';
				}
			}


			ob_end_clean();


			break;

		case "fblogin":

			$id				= trim($_POST['id']); //FB的id
			$acc			= trim($_POST['email']);
			$email			= trim($_POST['email']);
			$name			= trim($_POST['name']);


			//--------------------------------------
			$_html_msg 		= '';
			$member_db 		= 'web_member';

			if (empty($id) || empty($acc)) {

				$_html_msg 	= 'FB登入失敗';
				break;
			}

			if ($acc == 'undefined') {

				$_html_msg 	= '請勾選提供電子郵件資訊， 如無法做修改， 請到facebook將應用程式移除， 再次登入。';
				break;
			}

			if (empty($_html_msg)) {

				$db = new MySQL();
				$db->Where = " WHERE Member_Acc = '" . $db->val_check($id) . "' AND Member_FBID = '" . $db->val_check($id) . "'";
				$db->query_sql($member_db, '*');

				//直接登入
				if ($row = $db->query_fetch()) {

					if ($row['Member_Open'] != 1) {

						$_html_msg 	= '帳號未啟用';
						break;
					} else {

						$_html_msg 	= '歡迎 ' . $row['Member_Name'] . ' 回來';
						$_SESSION[$_Website]['website']['member_id']    = $row['Member_ID'];
						$_SESSION[$_Website]['website']['member_name']  = $row['Member_Name'];

						$_html_href = 'index.php';
					}

					//第一次登入	
				} else {


					$SN = 'M' . substr(date('Ymd'), -6);
					$Member_ID = GET_NEW_ID($member_db, 'Member_ID', $SN, 4);

					if (empty($Member_ID)) {

						$_html_msg 	= '編號產生失敗，請重新操作。';
						break;
					}

					if (empty($_html_msg)) {

						$db = new MySQL();

						$db_data = array(
							'Member_ID'			=> $Member_ID,
							'Member_Acc'		=> $id,
							'Member_Pwd' 		=> $pwd,
							'Member_Name' 		=> $name,
							'Member_Email' 		=> $acc,
							'Member_Open'		=> 1,
							'Member_Sdate'		=> $sdate,
							'Member_FBID'		=> $id,
						);

						$db->query_data($member_db, $db_data, 'INSERT');

						if (!empty($db->Error)) {

							$_html_msg 	= '加入失敗, 請重新操作';
							break;
						} else {

							$_html_msg 	= '歡迎 ' . $name . ' 回來';
							$_SESSION[$_Website]['website']['member_id']    	= $Member_ID;
							$_html_href = 'index.php';
							break;
						}
					}
				}

				//撈購物車ID
				$ord_db = new MySQL();
				$ord_db->Where = "Where Ord_UID='" . $_SESSION[$_Website]['website']['member_id'] . "'";
				$ord_db->query_sql('web_ordert_sn', 'Ord_Sn');

				$Cust_Order_ID = $_SESSION[$_Website]['website']['order_Sn'];

				if ($ord_sn = $ord_db->query_fetch()) {

					$Old_Order_ID = $ord_sn['Ord_Sn'];

					//登入前購物車已有物品
					if (!empty($Cust_Order_ID)) {

						$db = new MySQL();
						$SC	= new ShopCart();
						//遊客ORDER
						$db->Where = " WHERE Ordertm_Sn = '" . $Cust_Order_ID . "'";
						$db->query_sql($SC->Tabletd, 'Product_ID,Ordertd_Count');

						while ($row = $db->query_fetch()) {

							$Pro_ID 	= $row['Product_ID'];
							$Pro_Count 	= $row['Ordertd_Count'];
							$SC->Check_OrderPro($Old_Order_ID, $Pro_ID, $Pro_Count, 'login'); //將遊客購物車存進原有購物車
						}
					}

					$_SESSION[$_Website]['website']['order_Sn'] = $Old_Order_ID;

					//會員無歷史購物車
				} else if (!empty($Cust_Order_ID)) {

					$db->Where = "Where Ord_Sn = '" . $Cust_Order_ID . "'";
					$db->query_data("web_ordert_sn", array('Ord_UID' => $_SESSION[$_Website]['website']['member_id']), 'UPDATE');
				}
			}
			break;


		case "mlogout":

			unset($_SESSION[$_Website]['website']);

			$_html_msg 	= '成功登出，歡迎再次光臨。';
			$_html_href = 'index.php';

			break;

			//---會員資料修改
		case "send_forget_pw":

			global $member_db, $_setting_;

			$Value 				= array();
			$_html_msg 			= '';

			//$Value['forgot_formcode'] 	= trim($_POST['forgot_formcode']);
			$Value['forgot_email'] 		= trim($_POST['forgot_email']);
			$Value['target']			= trim($_POST['target']);
			//$formscode 	= $_SESSION[$_Website]['website']['formcode'];//驗證碼	

			//-----------------------recaptcha------------------------------------

			if (!empty($_POST['recaptchaResponse'])) {

				$RC = new ReCaptcha();
				$RC->Verify($_POST['recaptchaResponse']);

				if ($RC->rs != "success") {

					$_html_msg 	= '驗證碼連接失敗';
					break;
				}
			}
			//-------------------------------------------------------------------


			//判斷空值
			foreach ($Value as $key => $val) {

				if (empty($val)) {

					$_html_msg 	= '資料填寫不完全';
					break;
				}
			}

			if (!empty($_html_msg)) break;
			/*
			if( strtolower($formscode) != strtolower($Value['forgot_formcode']) || empty($formscode) || empty($Value['forgot_formcode']) ){
				
				$_html_msg 	= '驗證碼錯誤';
				break;
			}*/
			switch ($Value['target']) {
				case "com":
					$db 				= new MySQL();
					$db->Where = "Where Company_Email ='" . $db->val_check($Value['forgot_email']) . "'";
					$db->query_sql($company_db, 'Company_ID,Company_Name');

					if ($row = $db->query_fetch()) {
						$re_pwd	     = rand(10000000, 99999999);
						$re_pwd_save = md5(Turnencode(trim($re_pwd), 'password'));
						$FromName = !empty($_setting_['WO_SendName']) ? $_setting_['WO_SendName'] : '本網站';

						//收件人姓名 收件人信箱
						$mailto = array(
							$row['Company_Name'] => $Value['forgot_email']
						);

						$sbody = get_MailBody('forgetpw_member_resend', $re_pwd);

						//寄給客戶
						$Send_TF = SendMail($_setting_, '【' . $FromName . ' 】 忘記密碼申請通知信。', $sbody, $mailto);

						if ($Send_TF) {

							$_html_msg 	= '申請成功，請至信箱查看。';

							$_html_href = 'login.php';
						} else {

							$_html_msg 	= '申請信寄送失敗，請確認EMAIL輸入正確。';
							$_html_href = 'forgot.php?i=' . $Value['target'];
						}
					} else {

						$_html_msg 	= '查無此信箱，請確認後再行輸入。';
						$_html_href = 'forgot.php?i=' . $Value['target'];
					}
					if ($Send_TF) {
						$db->Where = "Where Company_Email ='" . $db->val_check($Value['forgot_email']) . "'";
						$db->query_data($company_db, array('Company_RePwd' => $re_pwd_save, 'Company_Is_RePwd' => 1), 'UPDATE');
					}
					break;
				case "cust":
					$db 				= new MySQL();
					$db->Where = "Where Member_Email ='" . $db->val_check($Value['forgot_email']) . "'";
					$db->query_sql($member_db, 'Member_ID,Member_Name');

					if ($row = $db->query_fetch()) {
						$re_pwd	     = rand(10000000, 99999999);
						$re_pwd_save = md5(Turnencode(trim($re_pwd), 'password'));
						$FromName = !empty($_setting_['WO_SendName']) ? $_setting_['WO_SendName'] : '本網站';

						//收件人姓名 收件人信箱
						$mailto = array(
							$row['Member_Name'] => $Value['forgot_email']
						);

						$sbody = get_MailBody('forgetpw_member_resend', $re_pwd);

						//寄給客戶
						$Send_TF = SendMail($_setting_, '【' . $FromName . ' 】 忘記密碼申請通知信。', $sbody, $mailto);

						if ($Send_TF) {

							$_html_msg 	= '申請成功，請至信箱查看。';

							$_html_href = 'login.php';
						} else {

							$_html_msg 	= '申請信寄送失敗，請確認EMAIL輸入正確。';
							$_html_href = 'forgot.php?i=' . $Value['target'];
						}
					} else {

						$_html_msg 	= '查無此信箱，請確認後再行輸入。';
						$_html_href = 'forgot.php?i=' . $Value['target'];
					}
					if ($Send_TF) {
						$db->Where = "Where Member_Email ='" . $db->val_check($Value['forgot_email']) . "'";
						$db->query_data($member_db, array('Member_RePwd' => $re_pwd_save, 'Member_Is_RePwd' => 1), 'UPDATE');
					}
					break;
				default:
					$_html_msg 	= '資料異常，請確認後再行輸入';
					$_html_href = 'forgot.php?i=' . $Value['target'];
					break;
			}


			break;

		case "forget_pwedit":

			global $member_db;

			$Value = array();

			$Value['forgot_pwd'] 		= md5(Turnencode(trim($_POST['forgot_pwd']), 'password'));
			$Value['forgot_repwd'] 		= md5(Turnencode(trim($_POST['forgot_repwd']), 'password'));
			$Value['memID'] 			= trim($_POST['mem_id']);

			if ($Value['pwd'] != $Value['repwd']) {

				$_html_msg 	= '輸入之密碼不相同';
				break;
			}

			//判斷空值
			foreach ($Value as $key => $val) {

				if (empty($val)) {

					$_html_msg 	= '資料填寫不完全';
					break;
				}
			}

			$_html_msg 			= '';
			$db 				= new MySQL();

			if (empty($_html_msg)) {

				$db->Where = "Where Member_ID = '" . $Value['memID'] . "'";
				$db->query_data($member_db, array('Member_Pwd' => $Value['forgot_pwd']), 'UPDATE');

				if (!empty($db->Error)) {

					$_html_msg 	= '修改失敗, 請重新操作';
				} else {

					$_html_msg 	= '修改成功，請使用新密碼登入';
					$_html_href = 'login.php';
				}
			}

			break;

		case "hidden": //24hr不顯示

			$tomorrow = date('Y-m-d', strtotime('+1 days'));
			$sub = strtotime($tomorrow) - time(); //距離明天時間
			setcookie("hidden", "1", time() + $sub, '/');

			break;
	}
}

if ($_Type != 'mailauth') {

	$json_array['html_msg']     = $_html_msg ? $_html_msg : ''; //訊息
	$json_array['html_href']    = $_html_href ? $_html_href : ''; //連結
	$json_array['html_eval']    = $_html_eval ? $_html_eval : ''; //確定後要執行的JS
	$json_array['html_content'] = $_html_content ? $_html_content : ''; //輸出內容

	echo json_encode($json_array);
}
