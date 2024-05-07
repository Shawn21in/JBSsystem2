<?php 
require_once(__DIR__.'/include/web.config.php');

$Input 	= GDC( $_GET['c'] , 'emailauth');

$verify = $Input['v'];

$type = $Input['type'];

if(empty($verify)||empty($type)) {
	
	JSAH('操作異常' , 'login.php');
	exit;
}
switch($type){
	case "member":
		$db->Where = " WHERE  Member_Verify = '" .$db->val_check($verify). "'";
		$db->query_sql($member_db, '*');
		if( $row = $db->query_fetch() ){			
			$db->query_data( 'web_member' , array('Member_Open' => 1), 'UPDATE');
		}else{
			JSAH('操作異常' , 'login.php');
			exit;
		}
	break;
	case "company":
		$db->Where = " WHERE  Company_Verify = '" .$db->val_check($verify). "'";
		$db->query_sql($company_db, '*');
		if( $row = $db->query_fetch() ){			
			$db->query_data( 'web_company' , array('Company_Open' => 1), 'UPDATE');
		}else{
			JSAH('操作異常' , 'login.php');
			exit;
		}
	break;
	default:
		JSAH('操作異常' , 'login.php');
		exit;
	break;
}

$_Title 	= "註冊完成";
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
	<head>
		<?php require('head.php') ?>
		<link rel="stylesheet" type="text/css" href="stylesheets/layout.css?v=<?=$version?>" />
		<script type="text/javascript" src="js/hc-sticky.js"></script>
		<link rel="stylesheet" href="stylesheets/css/general.css">
		<link rel="stylesheet" href="stylesheets/css/signup.css">
		<link rel="stylesheet" href="stylesheets/css/queries.css">
	</head>



	<body class="signupBody">
		<?php require('mobile_aside.php') ?>
		<div class="Wrapper">
			<div class="Wrapper__mask"></div>
			<?php require('header.php') ?>
			<section class="sectionVerify">
				<div class="verifyTitleBox">
					<h1 class="verifyTitle">信箱驗證成功，請至登入頁面重新登入</h1>
				</div>
				<div class="verifyImgBox">
					<img src="images/img/ai2biRobot.png" alt="ai2bi的識別圖" class="verifyImg">
				</div>
			</section>
			<?php require('footer.php') ?>
			<?php /*$RC = new ReCaptcha(); echo $RC->Call_Init( "#recaptchaResponse" , "contact");*/ ?>
		</div>
		
	</body>
	<script>

		
		
		$(document).ready(function() {

			$( ".member_formcode img" ).click(function(){
		
				$( this ).attr('src' , 'formcode/formcode.php');
			});
			
			$('body').ajaddress({ selcity: '#mem_city', city: "<?=$_MemberData['Member_City']?>", county: "<?=$_MemberData['Member_County']?>",  selcounty: '#mem_county' });
		});
	 </script>
</html>