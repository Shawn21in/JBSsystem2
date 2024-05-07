<?php 
require_once(__DIR__.'/include/web.config.php');

$Input 	= GDC( $_GET['c'] , 'forgetpw_member');

$Member_ID = $Input['v'];

$_Time = $Input['time'];

$_Now = time();

if( empty($Member_ID) ) {
	
	JSAH('操作異常' , 'login.php');
	exit;
}

if( ( (int)$_Now - (int)$_Time ) >= 3600 ) {
	
	JSAH('超出操作時間，請重新申請' , 'login.php');
	exit;
}

$_Title 	= "密碼重新設定";
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
	<head>
		<?php require('head.php') ?>
		<link rel="stylesheet" type="text/css" href="stylesheets/layout.css?v=<?=$version?>" />
		<script type="text/javascript" src="js/hc-sticky.js"></script>
	</head>



	<body>
		<?php require('mobile_aside.php') ?>
		<div class="Wrapper">
			<div class="Wrapper__mask"></div>
			<?php require('header.php') ?>
			<article class="mainbody container">
				


				<section class="main">
					<div class="bread">
						<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
						  <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						    <!-- Method 1 (preferred) -->
						    <a itemprop="item" href="index.php">
						        <span itemprop="name">首頁</span></a>
						    <meta itemprop="position" content="1" />
						  </li>
						  > 
						  <li itemprop="itemListElement" itemscope
						      itemtype="https://schema.org/ListItem">
						    <!-- Method 2 -->
						    <a itemscope itemtype="https://schema.org/WebPage"
						       itemprop="item" itemid="https://example.com/books/sciencefiction">
						      <h1 itemprop="name">密碼重新設定</h1></a>
						    <meta itemprop="position" content="2" />
						  </li>
						</ol>
					
					</div>
					
					<form name="forms1" id="forms" onSubmit="return false;">
					
						<div class="memberform">
						<br>
							<div class="memberformbox">
								<div class="title nobd"><p>設定密碼</p></div>
								<div class="content">
									<input type="password" class="style02" name="forgot_pwd" id="forgot_pwd" input-type="repassword" input-re-id="forgot_repwd" placeholder="請輸入新密碼">
									<input type="hidden" name="mem_id" value="<?=$Member_ID?>">
								</div>
							</div>
							<div class="memberformbox">
								<div class="title nobd"><p>密碼確認</p></div>
								<div class="content">
									<input type="password" class="style02" name="forgot_repwd" id="forgot_repwd" placeholder="請再次輸入新密碼">
								</div>
							</div>
							<!--
							<div class="memberformbox">
								<div class="title nobd"><p>驗證碼</p></div>
								<div class="content code member_formcode">
									<input type="text" class="style02" name="formcodes" msg="請輸入驗證碼">
									<img src="formcode/formcode.php" alt="請輸入驗證碼">
								</div>
								<br>
							</div>
							-->
							<div class="memberformbox">
								<div class="title nobd"><p></p></div>
								<input type="hidden" id="recaptchaResponse" name="recaptchaResponse">
								<div class="content"><button class="btnstyle01  btnstyle01--black fsubmit" data-type="forget_pwedit">確認送出</button></div>
							</div>
							
						</div>
						<br><br><br><br><br><br>
							<br>
					
					</form>

				</section>




			</article>

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