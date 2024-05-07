<?php 
require_once(__DIR__.'/include/web.config.php');
if(!isset($_GET)||empty($_GET['i'])){
	JSAH('操作異常!' , 'login.php');
	exit;
}
$_Title 	= "忘記密碼申請";
?>
<!DOCTYPE html>
<html  lang="zh-Hant-TW">
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
			<section class="sectionMain sectionLogin sectionForget">
				<h1 class="forgetTitle">忘記密碼？</h1>
				<div class="loginInputContainer">
					<form class="inputContainer" name="forms" id="forms" onSubmit="return false;">
						<div class="inputBox">
							<div>
								<label class="label forgerLabel"><span class="impmark">*</span>輸入會員信箱，我們會將密碼發送至您的信箱</label>
								<input type="text" class="input" name="forgot_email" placeholder="請輸入會員信箱">
							</div>
						</div>
						<div class="btnBox btnBox2">
							<input type="hidden" class="input" name="target" value="<?=$_GET['i']?>">
							<button href="#" class="mainButton fsubmit" data-type="send_forget_pw">送出</button>
						</div>
					</form>
				</div>
			</section>
			<?php require('footer.php') ?>
			<?php /*$RC = new ReCaptcha(); echo $RC->Call_Init( "#recaptchaResponse" , "contact");*/ ?>
		</div>
		
	</body>
	<script>
	    "use strict";
	    var Sticky = new hcSticky('.aside__cat', {
	      stickTo: '.mainbody',
	      top: 70,
	      queries: {
	        980: {
	          disable: true
	        }
	      }
	    });
		
		$(document).ready(function() {
		
			$( ".forgot_formcode img" ).click(function(){
			
				$( this ).attr('src' , 'formcode/formcode.php');
			});
		});
	 </script>

</html>
