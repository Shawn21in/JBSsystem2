<?php 
require_once(__DIR__.'/include/web.config.php');

$Input 	= GDC( $_GET['c'] , 'register_finish');

$Member_ID = $Input['v'];

$_Time = $Input['time'];

$_Now = time();

if( empty($Member_ID) ) {
	
	JSAH('操作異常' , 'login.php');
	exit;
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



	<body class="signupBody" >
		<?php require('mobile_aside.php') ?>
		<div class="Wrapper">
			<div class="Wrapper__mask"></div>
			<?php require('header.php') ?>
			<section class="sectionVerify">
				<div class="verifyTitleBox">
					<h1 class="verifyTitle">請至信箱驗證...</h1>
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