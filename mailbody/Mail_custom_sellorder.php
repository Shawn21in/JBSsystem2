<?php 
require_once("../include/web.config.php");

$Input = GDC($_GET['c'] , 'custom_sellorder');
$oid = $Input['v'];

$SC = new ShopCart();
$_OrderData = $SC->Get_SellOrder($oid);

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
		<h2>訂單成立通知信</h2>
        <p style ="color:#a52a2a">【防詐騙提醒】近日詐騙集團猖獗且手法囂張，常會竄改電信設備偽裝顯示號碼，若您接獲任何電話要您依照指示操作ATM，提供剩額、變更付款方式或更改分期設定等，請不要依電話指示至ATM操作，建議您可撥打165防詐騙專線或是聯絡本公司網站客服中心，謝謝您！</p><br>
        
		<p>親愛的顧客，您好：<br>
        ※本通知信僅代表已收到您的訂購訊息、並供您再次自行核對之用，不代表交易已經完成。</br>
		您於「<?=$_setting_['WO_Title']?>」成立了一筆訂單，以下為您的訂單資訊：</p>
		<table cellpadding="0"  border-spacing="0" border-collapse="0">
			<tr>
				<td width="40%" bgcolor="#ebeefc">商店訂單編號</td>
				<td width="60%"><?=$_OrderData['Orderm_ID']?></td>
			</tr>
			<tr>
				<td width="40%" bgcolor="#ebeefc">付款方式</td>
				<td width="60%"><?=$_OrderData['Orderm_Delivery']?></td>
			</tr>
            <tr>
				<td width="40%" bgcolor="#ebeefc">訂單日期</td>
				<td width="60%"><?=$_OrderData['Orderm_Sdate']?></td>
			</tr>
            
		</table>
		<p>您已訂購完成，我們確認後將會盡快安排出貨，
訂單狀態與資訊可至會員中心<a href="<?=WEB_Mail_URL.'member_orderin.php'?>">【訂單查詢】</a>查看！</p>

		<span>祝 ! 使用愉快</span>

		<p class="tip">※本電子信箱為系統自動發送通知使用，請勿直接回覆， 如有任何疑問，歡迎來信以下客服專用信箱※</p>
		<p class="center"><?=$_setting_['WO_Name']?> </p>
		<p class="center">客服電話:<?=$_setting_['WO_Tel']?></p>
        <p class="center">客服信箱:<?=$_setting_['WO_Email']?></p>
	</article>
	
</body>
</html>