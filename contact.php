<?php 
require_once(__DIR__.'/include/web.config.php');
if( $_Login ) {
	switch($_state){
		case 'company':
			header("Location:com_index.php"); exit;
		break;
		case 'member':
			header("Location:cust_index.php"); exit;
		break;
	}
}
$_html 			= $CM->GET_WEB_SETTING( "Setting_Index01,Setting_Index02" );

//-----------------------------SEO-------------------------------------------
$_setting_['WO_Keywords'] 		.= $_html['SEO']['WO_Keywords'];
$_setting_['WO_Description'] 	.= $_html['SEO']['WO_Description'];

$_setting_['WO_Keywords'] 		.= $_ProductList['SEO']['WO_Keywords'];
$_setting_['WO_Description'] 	.= $_ProductList['SEO']['WO_Description'];

$_setting_['WO_Keywords'] 		.= $_BannerList['SEO']['WO_Keywords'];
$_setting_['WO_Description'] 	.= $_BannerList['SEO']['WO_Description'];
//---------------------------------------------------------------------------

$_Title 	= '首頁';
?>
<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
	<?php require('head.php') ?>
	<link rel="stylesheet" type="text/css" href="stylesheets/index.css?v=<?=$version?>" />
	<link rel="stylesheet" href="stylesheets/css/general.css">
	<link rel="stylesheet" href="stylesheets/css/signup.css">
	<link rel="stylesheet" href="stylesheets/css/style.css">
	<link rel="stylesheet" href="stylesheets/css/contact.css">
	<link rel="stylesheet" href="stylesheets/css/queries.css">
</head>


	

<body>
	<?php require('mobile_aside.php') ?>
		<div class="Wrapper">
			<div class="Wrapper__mask"></div>
			
			<?php require('header.php') ?>
			<section class="sectionContact">
				<div class="contactTitleBox">
					<h1 class="contactTitle">聯絡我們</h1>
					<p class="contactContent">親愛的貴賓您好，如果您有任何疑問與建議，歡迎線上填寫表單或撥打專線電話，收到訊息我們將盡快與您聯繫。</p>
				</div>
				<div class="contactContainer">
					<div class="mapBox">
						<div class="rwdmapBox">
							<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3349.315528502503!2d120.6165616579968!3d24.17811616451558!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x346915ff66187093%3A0x2ed3a8636afadba1!2z5bqr6bue5a2Q!5e0!3m2!1szh-TW!2stw!4v1706257543978!5m2!1szh-TW!2stw" width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
							<div class="contactinfoBox">
								<p class="contactinfo"><ion-icon name="call" class="contactIcon"></ion-icon>公司電話：04-23502490</p>
								<p class="contactinfo"><ion-icon name="location" class="contactIcon"></ion-icon>公司地址：台中市西屯區中工二路120號</p>
							</div>
						</div>
					</div>
					<div class="contactBox">
						<form class="ContactinputBox" id="form1" onsubmit="return false;">
							<div>
								<label class="label">聯絡人姓名</label>
								<input type="text" class="input" data-name="聯絡人姓名" name="con_name" placeholder="請輸入姓名" maxlength="20" required>
							</div>
							<div>
								<label class="label">聯絡電話</label>
								<input type="text" class="input" data-name="聯絡電話" name="tel" placeholder="請輸入電話" maxlength="20" required>
							</div>
							<div>
								<label class="label">聯絡信箱</label>
								<input type="email" class="input" data-name="聯絡信箱" name="email" placeholder="請輸入信箱" maxlength="68" required>
							</div>
							<div>
								<label class="label">訊息內容</label>
								<textarea class="input textarea" data-name="訊息內容" name="content" placeholder="請輸入訊息(限150字)" maxlength="150" required></textarea>
							</div>
							<div class="btnBox contactbtnBox">
								<button class="mainButton msgBtn" data-type="msg_save">確認送出</button>
							</div>
						</form>
					</div>
				</div>
			</section>
			<?php require('footer.php') ?>
		</div>
	</body>
</html>