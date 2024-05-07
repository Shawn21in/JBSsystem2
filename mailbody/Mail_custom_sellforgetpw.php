<?php 
require_once("../include/web.config.php");


$Input = GDC($_GET['c'] , 'forgetpw_sell');
$code = $Input['v'];

$WEB_Mail_URL = 'http://'.dirname(dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'])).'/';

$url = $WEB_Mail_URL.'dealer_forget_pw.php?pwdcode='.urlencode($code);
				
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<style>
		body{
			font-family: '微軟正黑體';
		}
		.mailbody{
			border: 3px solid #999;
			padding: 20px 30px;
			max-width: 700px;
			box-sizing: border-box;
		}
		.mailbody h2{
			text-align: center;
		}
		.mailbody p{
			font-size: 15px;
			line-height: 1.7;
		}

		.mailbody p.tip{
			color: #ff0000;
			font-weight: bold;
			font-size: 17px;
			margin-bottom: 20px;
		}
		.mailbody p.center{
			margin: 0;
			text-align: center;
			font-size: 14px;
		}

		.mailbody table{
			width: 100%;
			border: 2px solid #999;
			border-spacing: 0;
			margin: 30px 0;
		}
		.mailbody table tr td{
			padding: 10px;
			border-collapse: collapse;
			border: 1px solid #999;
			letter-spacing: 0.05em;
			font-size: 15px;
		}
		.mailbody span{
			margin: 50px 0;
			display: block;
		}
		.mailbody a{
			color: #4963ed;
			font-weight: bold;
			margin: 0 5px;
		}
	</style>
</head>
<body>
	<article class="mailbody">
		<h2>忘記密碼重設信</h2>
        
		<p>親愛的顧客，您好：<br><br>
      
        您在「<?=$_setting_['WO_Title']?>」申請重設密碼，請點選以下按鈕進行密碼重設。<br>
        ※如您的瀏覽器無法點擊[重設按鈕]，請複製[重設密碼連結]貼至您的瀏覽器網頁位置，在按下搜尋即可。<br>

		<table cellpadding="0"  border-spacing="0" border-collapse="0">
			<tr>
				<td width="40%" bgcolor="#ebeefc">重設按鈕</td>
				<td width="60%"><a href="<?=$url?>">點我前去重設密碼</a></td>
			</tr>
			<tr>
				<td width="40%" bgcolor="#ebeefc">重設密碼連結</td>
				<td width="60%"><?=$url?></td>
			</tr>
		</table>
		
		<span>祝 ! 使用愉快</span>

		<p class="tip">※本電子信箱為系統自動發送通知使用，請勿直接回覆， 如有任何疑問，歡迎來信以下客服專用信箱※</p>
		<p class="center"><?=$_setting_['WO_Name']?> </p>
		<p class="center">客服電話:<?=$_setting_['WO_Tel']?></p>
        <p class="center">客服信箱:<?=$_setting_['WO_Email']?></p>
	</article>
	
</body>
</html>