<?php 
require_once("../include/web.config.php");

$oid = $_GET['v'];

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
        
		您的「<?=$_setting_['WO_Title']?>」網站成立了一筆訂單，請盡快前往處理：</p>
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
		
		<span>祝 ! 使用愉快</span>

		<p class="tip">※本電子信箱為系統自動發送通知使用，請勿直接回覆※</p>
		
	</article>
	
</body>
</html>